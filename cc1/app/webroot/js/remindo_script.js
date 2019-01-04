function profilepicbox(){

	$('profilePicBox').style.display = 'block';
	centerPos($('profilePicBox'), 1);

}

function paginatePrfPic(action,maxDiv){
	var currVal = $('prfPicPagCnt').value;
	var newVal = '';
	
	var profileEle = $$('div.profilePicDivClass');
	var prfCount = profileEle.length;
	
	for(i=0;i<prfCount;i++){
		profileEle[i].hide();
	}
			
	if(action == 'P'){
		newVal = parseInt(currVal) - 1; 		
		$('pcNavigation'+newVal).style.display = 'block';
		$('prfPicPagCnt').value = newVal;
	}else if(action == 'N'){
		newVal = parseInt(currVal) + 1;		
		$('pcNavigation'+newVal).style.display = 'block';
		$('prfPicPagCnt').value = newVal;
	}
	
	if(newVal == 1){
		$('prfPicPrevious').hide();
	}else{
		$('prfPicPrevious').show();
	}
	
	if(newVal == maxDiv){
		$('prfPicNext').hide();
	}else{
		$('prfPicNext').show();
	}
	
}

function showMilstoneComment(id,taskListid){    	
    	var url    = '/groups/tasks/getMilcomment.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);		
		var pars   = "sessUID="+userID+"&milid="+id+"&rand="+rand;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
											if(transport.responseText.strip() == "deleted")
											{
												showUtlPopup('Milestone','view',1);
											}
											else
											{
												centerPos($('milestoneCommentPopup'), 1);
												commentMiles.popUp = true;
												$('milestoneCommentPopup').show();											
												$('milestoneCommentPopupDatadiv').innerHTML = transport.responseText;
												$('commentMilTitle').innerHTML = $('taskListTitle'+taskListid).innerHTML;
												var strreadElems = 'div#milestoneCommentPopupDatadiv div.read';
												var readElems = $$(strreadElems);
												var elemLen = readElems.length;
												for(var i=0;i<elemLen; i++)
												{
													if(readElems[i])
														readElems[i].style.marginRight = '0px';
												}
											}	
										}
						});
}

function showtasklistComment(id,tasklistArr){    	
    	var url    = '/groups/milestones/getTasklistcomment.json';		
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);		
		var pars   = "sessUID="+userID+"&milid="+id+"&rand="+rand+"&tasklistArr="+tasklistArr;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
											
											if(transport.responseText.strip() == "deleted")
											{
												showUtlPopup('task','viewTask',1);
											}
											else
											{
												centerPos($('tasklistCommentPopup'), 1);
												commentTask.popUp = true;
												
												
												$('tasklistCommentPopup').show();											
												$('tasklistCommentPopupDatadiv').innerHTML = transport.responseText;
												$('commentTasklistTitle').innerHTML = $('milestoneTitle'+id).innerHTML;
											}	
										}
						});
}


function showCommentRHS(id,type){
		//alert(id+"===="+type);    	
		if($('commonCommentPopup'))$('commonCommentPopup').hide();
    	var url    = '/groups/groups/getCommentsrhs.json';		
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);		
		var pars   = "sessUID="+userID+"&id="+id+"&rand="+rand+"&type="+type;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
											
											var res = transport.responseText.split("^^^div$$$$");
											
											if(res.length >1)											
											{
												centerPos($('commonCommentPopup'), 1);
												$('commonCommentPopup').style.zIndex = '700';
												
												
												$('commonCommentPopupDatadiv').innerHTML = res[0];
												
												if(res[1] == '0'){
													eval(res[2]);
													if($('closeButtonPopup'))$('closeButtonPopup').hide();
												}
												
												var strreadElems = 'div#commentBoxInner div.read';
												var readElems = $$(strreadElems);
												var elemLen = readElems.length;
												for(var i=0;i<elemLen; i++)
												{
													if(readElems[i])
														readElems[i].style.marginRight = '0px';
												}
												if(type == "M" || type == "T"){
													dimCookie = document.cookie.split(';');
			  	
												  	//var cookieCnt = dimCookie.count(); 
												  	for(var k=0;k<10;k++){		  		
												  		if(dimCookie[k].search('winDimension')>-1){		  		
												  			var dCookie = dimCookie[k];
												  			break;
												  		}
												  	}		  	
												  	dimCookie1 = dCookie.split('=');		  
												  	dimCookie2 = dimCookie1[1].split('x');
												  	
												  	var maxHeight = dimCookie2[0].strip() - 150;
												  	$('commonCommentPopup').show();
												
												  	var top = $('commonCommentPopup').style.top;
												  	var upperdist = 60;
												  	maxHeight = maxHeight - 2*upperdist;
												  	
												  	$('commonCommentPopup').style.top = '5000px';
												  	if($("commonCommentPopup").offsetHeight > maxHeight){
												  		$('commonCommentPopup').style.top = getScrollHeight() + upperdist + 'px';	
												  	}
												  	else {
												  		$('commonCommentPopup').style.top = top;
												  	}
												   	
												}
												else if(type == "MCal" || type == "TCal"){
													commentTask.popUp = true;
													commentMiles.popUp = true;
													
													var leftCoordinate = ($("calendarLayer").offsetWidth - 725 - 24)/2;
													var maxHeight = $("calendarLayer").offsetHeight - (0.1*$("calendarLayer").offsetHeight) - 180;
													var topCoordinate = $("calendarLayer").offsetTop + (0.05*$("calendarLayer").offsetHeight);
													
													$('commonCommentPopup').style.left=leftCoordinate+"px";
													$('commonCommentPopup').style.top=topCoordinate+"px";
										
													$('commonCommentPopup').style.height = maxHeight + 170 +"px";
													$('commonCommentPopup').show();
												
												}
												
												$('commentBoxInner').style.maxHeight =maxHeight+"px";
												$('commentBoxInner').style.minHeight ="100px";
												$('commentBoxInner').style.overflow = 'auto';
													
												var commentsDiv = $$('div#commentBoxInner div.comments');
												if(commentsDiv[0])
													commentsDiv[0].style.marginTop = '0px';
																					
											}
											else
											{
												var evt = "Milestone";
												//var data = transport.responseText;
												if(type == "T" || type == "TCal")
												{
													evt = "Task";
												}
												showUtlPopup(evt,'comment',1);
											}		
										}
						});
						
}


/*new script to detect browser by dinesh */
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
 
 	
	var tmpURL = 'http://www.remindo.com/check.php';
	var RHSdata = '';
	var startColor = "#FFF799";
	var endColor = "#FFFFFF";
	var profileInterval = "";
	var windowHeight;
	var windowWidth;
      
	function breakLongWords(str) {
		var num = 65;
		var dataArr = str.split(' ');
		var newDataArr = new Array();
		for(var i=0; i<dataArr.length; i++) {
			if(dataArr[i].length > num) {
				//newDataArr[i] = dataArr[i].replace(RegExp("(\\w{" + num + "})(\\w)", "g"), function(all,text,char){ return text + " " + char; });
				newDataArr[i] = dataArr[i].wordWrap(65, " ", 2);
			} else newDataArr[i] = dataArr[i];
		}
		str = newDataArr.join(' ');
		return str; 
	}
	String.prototype.wordWrap = function(m, b, c){
	    var i, j, l, s, r;
	    if(m < 1)
	        return this;
	    for(i = -1, l = (r = this.split("\n")).length; ++i < l; r[i] += s)
	        for(s = r[i], r[i] = ""; s.length > m; r[i] += s.slice(0, j) + ((s = s.slice(j)).length ? b : ""))
	            j = c == 2 || (j = s.slice(0, m + 1).match(/\S*(\s)?$/))[1] ? m : j.input.length - j[0].length
	            || c == 1 && m || j.input.length + (j = s.slice(m).match(/^\S*/)).input.length;
	    return r.join("\n");
	};
	
	function wrapTitle(data, state) {
		data = breakLongWords(data);
		if(!state) data = anchor(data);
		if(data.length > 100)
			data = data.slice(0,100) + '...';
			
		return data;
	}
	
	function wrapDesc(data) {
		data = anchor(data);
//		data = breakLongWords(data);
		var ogData = data;
		var reg = new RegExp( "\\n", "g" );
		var l = 0;
		
		try{
			var nl = data.match(reg);
			l = nl.length;
		} catch(err) {
			//alert(err);	
		}
		
		if(l > 4) {
			var t = 0;
			for(var i=0; i<5; i++)
				t = data.indexOf("\n", t+1);

			data = data.slice(0, t) + '...';
		}
		
		tmpData = data.stripTags();

		//if(data.length > 200)
		if(tmpData.length > 200)
			data = data.slice(0,200) + '...';

		//if(l > 4 || data.length > 200) {
		if(l > 4 || tmpData.length > 200) {
			data = data.replace(reg, "<br/>");
			ogData = ogData.replace(reg, "<br/>");
			data = data + '&nbsp;<a href="javascript:void(0)" onclick="showHideReadMore(this)">Read more</a>';
			return '<span style="display:block">'+data+'</span><span style="display:none">'+ogData+'</span>';
			
		} else if(l != 0){
			data = data.replace(reg, "<br/>");
			return data;
			
		} else {
			return data;
		}
	}
	
	function showHideReadMore(self){
		var nextSibling = self.parentNode.nextSibling;
		while(nextSibling && nextSibling.nodeType != 1)
    		nextSibling = nextSibling.nextSibling

		self.parentNode.style.display = 'none';
		nextSibling.style.display = 'block';
	}
	
	function htmlspecialchars(p_string) {
		//p_string = p_string.replace(/&/g, '&amp;');
		p_string = p_string.replace(/</g, '&lt;');
		p_string = p_string.replace(/>/g, '&gt;');
		//p_string = p_string.replace(/"/g, '&quot;');
		//p_string = p_string.replace(/'/g, '&#039;');
		return p_string;
	}
	
	function htmlspecialchars_decode(p_string) {
		p_string = p_string.replace(/&amp;/g, '&');
		p_string = p_string.replace(/&lt;/g, '<');
		p_string = p_string.replace(/&gt;/g, '>');
		p_string = p_string.replace(/&quot;/g, '"');
		return p_string;
	}	

	function in_array(what, where) {
		var a=false;
		for(var i=0;i<where.length;i++){
			if(what == where[i]){
				a=true;
				break;
			}
		}
		return a;
	}

	// Array Remove - By John Resig (MIT Licensed)
	Array.prototype.remove = function(from, to) {
	  var rest = this.slice((to || from) + 1 || this.length);
	  this.length = from < 0 ? this.length + from : from;
	  return this.push.apply(this, rest);
	};

	/* This is supported by all browsers	*/
	getElementsByClassName1 = function(className, parentElement) {
	  if (Prototype.BrowserFeatures.XPath) {
	    var q = ".//*[contains(concat(' ', @class, ' '), ' " + className + " ')]";
	    return document._getElementsByXPath(q, parentElement);
	  } else {
	    var children = ($(parentElement) || document.body).getElementsByTagName('div');
	    var elements = [], child;
	    for (var i = 0, length = children.length; i < length; i++) {
	      child = children[i];
	      if (Element.hasClassName(child, className))
	        elements.push(Element.extend(child));
	    }
	    return elements;
	  }
	};
	
	/*Get window dimension and set it in global variable*/
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
		/*Set the values in cookie*/
		document.cookie = "winDimension="+winDim.height+"x"+winDim.width+"; path=/";
	}
	
	function readCookie(name){
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
		
	///document onclick function
	
	document.onclick=function(e){			
		var drodowns = "shoutBox,0,1,2,3,5,dropdownCal,7,invProc,inviteTab,cm,todo";
		drodowns = drodowns.split(',');			
		for (var j = 0; j < drodowns.length; j++){        	
    		
    		if(drodowns[j] == 'todo'){
    			var commonDropDOwn = 'todo_dropdown';
    			var innerDiv = 'todo_dropdown_content';
    		}
    		else {
    			var commonDropDOwn = 'commonDropDOwn'+drodowns[j];
    			var selectedVal = 'selectedVal'+drodowns[j];
    			var innerDiv = 'innerDiv'+drodowns[j];
    		}    		
    		
            if(document.getElementById(commonDropDOwn) && document.getElementById(commonDropDOwn).style.display != 'none')
            {
				if(drodowns[j] == 'todo')
					var arr = new Array(commonDropDOwn,innerDiv);
				else 	
					var arr = new Array(commonDropDOwn,selectedVal,innerDiv);
					
				e = (e) ? e : window.event;
				var targ = (e.target) ? e.target : e.srcElement;
				if(targ != null){
	        		if(targ.nodeType == 3)
	            		targ = targ.parentNode;
	    		}
	    		var cnt = 0;
	    		
	    		while((targ) && (targ.offsetParent) && targ.id == ""){
	    				targ = targ.offsetParent;
	    		}
	    		
				var flag = false;

				if(targ){
	    			var arrLen = arr.length;
	    			for(i=0;i<arrLen;i++){		    				
		    			if(targ.id == arr[i]){			    			   			    			  	
		    				flag = true;
		    				break;			    			  
		    			}
		    		}
		    		if(!flag){		    			
		    			if(targ.type != "checkbox"){
			    			document.getElementById(commonDropDOwn).style.display = 'none';
			    			break;
		    			}	    					    			
		    		}
	    		}    		
			}
		}
		
		/*dont show the blurb if the user navigates anywhere from dashboard*/
		if($('currGrpId') && ($('currGrpId').value != "ovr" || $('currPageCat').value != "ovr"))
		{
			$('profile_blurb').hide();
			$('mynews_blurb').hide();
			$('calender_blurb').hide();
			$('talk_blurb').hide();
			$('share_blurb').hide();
		}			
	};
	
	document.onmouseover = function(){ //alert('on mouseover');
		/*dont show the blurb if the user navigates anywhere from dashboard*/
		if($('currGrpId') && ($('currGrpId').value != "ovr" || $('currPageCat').value != "ovr"))
		{
			$('profile_blurb').hide();
			$('mynews_blurb').hide();
			$('calender_blurb').hide();
		}
	};

	function dashboardTMrhs(id,userid){			
		var url    = '/groups/groups/dashboardLoadRhs.json';
		var rand   = Math.random(99999);
		var pars   = "rand="+rand+"&grpId="+id;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onComplete: function(transport)
		{						
			if(transport.responseText == "expired"){      
	        	window.location = MainUrl + "main.php?u=signout";return;
	        }else{
	        	$('dashboardTMrhs').innerHTML = transport.responseText;
	        	if(transport.responseText.strip() == ''){
	        		dashboardTMrhs(id,userid);
	        	}
			}
		}
		});
	}
	
	/*	Storage Code	*/
	function closeFrontend(fid){
		var id = $('id').value;	
		$('coupon_code_frontend_display').hide();
		new Ajax.Request("/groups/groups/hideFrontend/"+id+"/"+fid, {method:'post'});
	}
	/*	end	*/

	function showhide(obj){
		if (obj.style.display == 'block'){
			obj.style.display = 'none';
		}else{
			obj.style.display = 'block';
		}
	}
	
	function anchor(text){
	    var spaces = text.split(' ');
	    for(i=0;i<spaces.length;i++) {
	    
	    	var reg = new RegExp( "\\n", "g" );
	    	if(spaces[i].match(reg)) {
	    	
	    		var newline = spaces[i].split('\n');
	    		for(z=0;z<newline.length;z++) {
	    			newline[z] = anchorExtended(newline[z]);
	    		}
	    		spaces[i] = newline.join('\n');
	    		
	    	} else spaces[i] = anchorExtended(spaces[i]);
	    		
	    }
	    var extra = spaces.join(' ');
 	   	return extra.replace(/<br\/>/g, '&nbsp;<br/>');
	}

	function anchorExtended(text) {
		
		var emailPattern=/^([a-zA-Z0-9_.-]+)+@([a-zA-Z0-9_-]+.[a-zA-Z0-9]+)+(\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$)/;
    	var urlPattern=/^(http|https|ftp):\/\/([a-zA-Z0-9_-])$/;
  
        if(text.match(/@/)) {
	        if(emailPattern.test(text)) {
    	        var strreplace = " <a href='mailto:"+text+"' target='_blank'>"+breakLongWords(text)+"</a>";
				text=text.replace(text,strreplace);
					//break;
				}
        } else if(urlPattern.test(text)) {
            var strreplace = " <a href='"+text+"' target='_blank'>"+breakLongWords(text)+"</a> mail";
			text=text.replace(text,strreplace);
			//break;
		}//eg http://google
    	else if(text.match(/\./))
    	{
    		text = /^((http|https|ftp):\/\/)?(([a-zA-Z0-9_-]+.[a-zA-Z0-9]+)+)(\.(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|asia|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cat|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jobs|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tel|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|travel|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw))(\/.*)?$/.test(text)?(/((http:\/\/)|(https:\/\/)|(ftp:\/\/)).*/.test(text)?"<a href='" + text + "' target='_blank'>" + breakLongWords(text) + "</a>":"<a href='http://" + text + "' target='_blank'>" + breakLongWords(text) + "</a>"):breakLongWords(text);
    	} else
    	{
    		text = breakLongWords(text);
    	}
    	return text;
	}

	function showhidecomments(obj){		
		var frmName = 'commentFrm'+obj;
		ldDispCmt.commentEdit(obj);
		var obj = 'comment'+obj;
		if ($(obj).style.display != 'none')
		$('slideupFix').style.display = 'block';
		Effect.toggle(obj,'blind',{duration: 0.5});
		setTimeout("if($('slideupFix').style.display != 'none'){$('slideupFix').style.display = 'none';}",1000);		
		$(frmName).reset();
	}
		
	function showhideAddTodo(obj){			
		if ($(obj).style.display != 'none'){
			$('slideupFix').style.display = 'block';
			Effect.toggle(obj,'slide',{duration: 0.5});			
			setTimeout("if($('slideupFix').style.display != 'none'){$('slideupFix').style.display = 'none';}",600);			
		}else{
			Effect.SlideDown(obj,{duration: 0.5});			
		}
		var divid = new Array();
		divid = obj.split("_");
		var formName = "frm_"+divid[1];
		$(formName).reset();
	}
	
	function hide(obj){
		obj.style.display = 'none';
	}
	
	function showTag(obj){			
		if(obj.id == "invitehome"){
			$('invitehome').style.display = "block";			
			if($('interMedDiv').style.display != 'none')
			$('interMedDiv').style.display = 'none';			
			Effect.toggle('interMedDiv','slide', { duration: 0.5});						
		}else{
        	obj.style.display = 'block';
        }       
	}
	
	function hideTag(obj){
		if(obj.id == "status_popup" || (obj.id=='allHeadPopup' && document.getElementById('status_popup').style.display!='none')){ 
			if(document.getElementById('status_popup').style.display!='none'){				
				document.getElementById('statusCntOpen').style.display='none';
				document.getElementById('statusCnt').style.display='none';
				document.getElementById('statusCnt').innerHTML='';
				document.getElementById('statusCntClose').style.display='none';
			}
		}	
		obj.style.display = 'none';		
	}
	
	function Addmore(){
		var ni = document.getElementById('added');
		var newdiv = document.createElement('div');
		var divIdName = 'my'+1;
		newdiv.setAttribute('id',divIdName);
		newdiv.className = "addedtoDo";
		newdiv.innerHTML = "<p><strong>" + document.getElementById('Todo_lebel').value + " : " + document.getElementById('responsible').value + "</strong></p>";
		document.getElementById('Todo_lebel').value = "";
		ni.appendChild(newdiv);
	}
	
	/*
	function showProfile(){
		document.getElementById('profile').style.display = "block";
	}
	*/
	function steps(hide,show){
		document.getElementById(show).style.display = 'block';
		document.getElementById(hide).style.display = 'none';
		if (hide == show){
			document.getElementById('inviteProcess').style.display = 'none';
			document.getElementById('bg').className = 'hidewrap';
		}
	}

	var currOption = ""
	function showAlerts(obj){
		if (currOption != ""){
			document.getElementById(currOption.id).className = "";
			document.getElementById(currOption.id + '_popup').style.display = 'none';
			currOption.parentNode.className = "";
		}
		
		document.getElementById('allHeadPopup').style.display = 'block';
		document.getElementById('popupLinks').className = "popupInfoActive";
		
		document.getElementById(obj.id).className = "";
		document.getElementById(obj.id + '_popup').style.display = 'block';
		obj.parentNode.className = "curr";
	
		currOption = obj
	}

	function changeClass(obj,nm){
		obj.className = nm;
	}

	function hideSpans(ttlValue, openUpdateBrace, closeUpdateBrace, tagUpdateValue, openAltBrace, closeAltBrace, tagAltValue, openMsgBrace, closeMsgBrace, tagMsgValue){
		var allValues = document.getElementById(ttlValue).innerHTML.split(':');
		allValues[1] = allValues[1].replace(/\"/g, '');
		allValues[1] = allValues[1].replace(/}/g, '');
		var arrayVal = allValues[1].split(',');		
		
		document.getElementById(tagUpdateValue).innerHTML = arrayVal['0'];
		document.getElementById(tagAltValue).innerHTML = arrayVal['2'];
		document.getElementById(tagMsgValue).innerHTML = arrayVal['1'];
		
		if(arrayVal['0'] > 0){
			document.getElementById(openUpdateBrace).style.display = '';
			document.getElementById(closeUpdateBrace).style.display = '';
			document.getElementById(tagUpdateValue).style.display = '';
		}else{
			document.getElementById(openUpdateBrace).style.display = 'none';
			document.getElementById(closeUpdateBrace).style.display = 'none';
			document.getElementById(tagUpdateValue).style.display = 'none';
		}
		
		if(arrayVal['1'] > 0){
			document.getElementById(openMsgBrace).style.display = '';
			document.getElementById(closeMsgBrace).style.display = '';
			document.getElementById(tagMsgValue).style.display = '';
		}else{
			document.getElementById(openMsgBrace).style.display = 'none';
			document.getElementById(closeMsgBrace).style.display = 'none';
			document.getElementById(tagMsgValue).style.display = 'none';
		}
		
		if(arrayVal['2'] > 0){
			document.getElementById(openAltBrace).style.display = '';
			document.getElementById(closeAltBrace).style.display = '';
			document.getElementById(tagAltValue).style.display = '';
		}else{
			document.getElementById(openAltBrace).style.display = 'none';
			document.getElementById(closeAltBrace).style.display = 'none';
			document.getElementById(tagAltValue).style.display = 'none';
		}
	}
	
	function chgCaption(tagId, captSecond, captFirst){
		if(document.getElementById(tagId).innerHTML == captFirst){
			document.getElementById(tagId).innerHTML = captSecond;
		}else{
			document.getElementById(tagId).innerHTML = captFirst;
		}
	}

    function setDiv(divId){        
      var wh = this.windowHeight; // Window <strong class="highlight">Height</strong>
      var d = document.getElementById(divId) // Get <strong class="highlight">div</strong> element
      var dh = d.offsetHeight // <strong class="highlight">div</strong> <strong class="highlight">height</strong>
      
      if(dh > wh-200)
        d.style.height = wh-200 + 'px'; // <strong class="highlight">Set</strong> <strong class="highlight">div</strong> <strong class="highlight">height</strong> to window <strong class="highlight">height</strong>
      else
          d.style.height = "";
    }
    
    function hideFirstTimerLink(userID,type,obj){
		var url    = '/groups/groups/firstTimer.json';
		var encUserID = $('SessStrUserID').value;
		var rand   = Math.random(99999);
		var pars   = "rand="+rand+"&uID="+userID+"&type="+type+"&sessUID="+encUserID;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onComplete: function(transport)
		{			
			if(transport.responseText == "expired"){      
	        	window.location = MainUrl + "main.php?u=signout";return;
	        }else{
	        	Effect.SlideUp(obj, { duration: 1.5 });
				//setTimeout('positionFooter();',50);
			}
		}
		});
	}
	
	/*When user hits enter button inside a text box , then the form shld nt be submitted*/
	function barFormSubmit(evt){
		var k = evt.keyCode||evt.which;
		return (k!=13);
	}
	
	function sendTaskListMails(todolistId,group_id,type){
		var rand   = Math.random(99999); 
		var url    = '/groups/tasks/sendMail4ChangeTodoStatus.json';						
		var pars   = 'rand=' + rand + '&tdID=' + todolistId + '&groupid=' + group_id + '&type='+type;
		var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars} );	
	}
	
	/*** Position Elements to center of the page ***/
	function centerPos(element, level){
		  var deltaX;
		  var deltaY;
		  var options = Object.extend({
		      zIndex: 990,
		      update: false
		  }, arguments[1] || {});
		
		  element = $(element);
		
		  if(!element._centered){
		      Element.setStyle(element, {position: 'absolute', zIndex: options.zIndex });
		      element._centered = true;
		  }
		
		  var dims = Element.getDimensions(element);
		  		  	  
		  Position.prepare();
					 
		  var winWidth,winHeight;
		  
		  if(this.windowWidth == undefined || this.windowHeight == undefined || this.windowWidth < 10  || this.windowHeight < 10){
		  			  	
		  	dimCookie = document.cookie.split(';');
		  	
		  	//var cookieCnt = dimCookie.count(); 
		  	for(var k=0;k<10;k++){		  		
		  		if(dimCookie[k].search('winDimension')>-1){		  		
		  		var dCookie = dimCookie[k];
		  		break;
		  		}
		  	}		  	
		  	dimCookie1 = dCookie.split('=');		  
		  	dimCookie2 = dimCookie1[1].split('x');
		  	
		  	winWidth = dimCookie2[1].strip();
		  	winHeight = dimCookie2[0].strip();
		  }else{		  
		  	winWidth = this.windowWidth;
		  	winHeight = this.windowHeight;
		  }
		  
		  var paddingPopupTop = 0;
		  
		  if(document.all){
		 	switch(level){
		 		case 0:	deltaX = document.body.scrollLeft;
				    	deltaY = document.body.scrollTop;
				    	break;
		 		case 1:	deltaX = document.documentElement.scrollLeft;
				    	deltaY = document.documentElement.scrollTop;
				    	paddingPopupTop = 0.05;
				    	break;
				case 2: deltaX = parent.window.document.documentElement.scrollLeft;
				    	deltaY = parent.window.document.documentElement.scrollTop-95;
				    	paddingPopupTop = (parent.window.document.documentElement.scrollTop>95)?0.05:0.18;	
				    	break;
				default:deltaX = 0;
				    	deltaY = 0;
				    	break;
			}
		  }else{
		 	switch(level){
		 		case 0:	deltaX = Position.deltaX;
				    	deltaY = Position.deltaY;
				    	break;
				case 1: deltaX = Position.deltaX;
				    	deltaY = Position.deltaY;
				    	paddingPopupTop = 0.05;
				    	break;
				case 2: deltaX = parent.window.pageXOffset - parent.window.document.getElementById('iprofile').offsetLeft;
				    	deltaY = parent.window.pageYOffset - parent.window.document.getElementById('iprofile').offsetTop;
				    	paddingPopupTop = (parent.window.pageYOffset>95)?0.05:0.18;			    	
				    	break;
				default:deltaX = 0;
				    	deltaY = 0;
				    	break;
			}
		  }
		 
		 var offLeft = ( deltaX + Math.floor((winWidth-dims.width)/2));
		 var offTop = ( deltaY + Math.floor((winHeight-dims.height)/2));
		 var adjustRatio = winHeight*paddingPopupTop;
		 element.style.top = (dims.height >= winHeight)? ((deltaY)? (adjustRatio + deltaY + "px") : adjustRatio + "px"):((offTop != null && offTop > 0) ? offTop : '0')+ 'px';
		 element.style.left = ((offLeft != null && offLeft > 0) ? offLeft :'0') + 'px';
		 
		 var tempTop = element.style.top;
		 var tempIntTop = tempTop.slice(0,element.style.top.length-2);
		 if(tempIntTop < 0)
		 {
		 	element.style.top = '0px';
		 }
	}
	
	function showGroupInfo(obj,grpId,level){
		talk_file_cnt = 0;
		showLoader();		
		$('currPageCat').value = 'ovr';
		if(level == "1"){$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Employees</a>';}else{$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Members</a>';}
		if($('moreTabLink')) setHeaderMore('moreTabLink',0);
		this.setHeader(obj,grpId,level);
		this.setVars(grpId,level);
		$('homeNavigation').className = 'sel';
		chk4NewTab();
		
		this.paginateData('/groups/groups/view.json',1);
		messagesObject.initialize(); //re-initialize message object	
		//$('LoadingProgress').show();
	}
	
	function showGroupInfoNewly(obj,grpId,level){
		showLoader();
		$('currPageCat').value = 'ovr';		
		if(level == "1"){$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Employees</a>';}else{$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Members</a>';}
		if($('moreTabLink')) setHeaderMore('moreTabLink',0);		
		this.setHeader(obj,grpId,level);
		this.setVars(grpId,level);		
		$('homeNavigation').className = 'sel';
				
		this.paginateData('/groups/groups/view.json',1);		
		messagesObject.initialize(); //re-initialize message object	
		//$('LoadingProgress').show();		
	}
		
	function chk4NewTab(){
		milestonesObject.saveRemQuickTab('0');
	}
	
	function showLoader()
	{
		$('maincontent').innerHTML = '<div class="mainContent1" style="height:800px"><img id="alertsLoadingProgress" src="/images/loading.gif" class="loadingImgLeft" border="0"/></div>';
	}

	function paginateData(url,pageNum){
		if($('dashboardTMrhs')){
			//$('dashboardTMrhs').innerHTML = '<br><br><img src="/images/loading.gif" class="loadingImgLeft" border="0"/>';
			RHSdata = $('dashboardTMrhs').innerHTML;
		}
		grpId = $('currGrpId').value;
		showDiv = this.afterSuccess.bind(this);
		var rand   = Math.random(99999);
		new Ajax.Updater('maincontent', url, {
  			parameters: { groupid: grpId,pageNum: pageNum,rand:rand},
  			evalScripts:true,
  			onSuccess: showDiv
		});
	}
	
	function paginateDataMilestone(url,pageNum,type){
		if($('dashboardTMrhs')){
			//$('dashboardTMrhs').innerHTML = '<br><br><img src="/images/loading.gif" class="loadingImgLeft" border="0"/>';
			RHSdata = $('dashboardTMrhs').innerHTML;
		}
		grpId = $('currGrpId').value;
		showDiv = this.afterSuccess.bind(this);
		var rand   = Math.random(99999);
		new Ajax.Updater('maincontent', url, {
  			parameters: { groupid: grpId,pageNum: pageNum,type: type, rand:rand},
  			evalScripts:true,
  			onSuccess: showDiv
		});
	}
	
	function paginateDataAfterNewRec(url,pageNum,newRecId){
		grpId = $('currGrpId').value;
		showDiv = this.afterSuccess.bind(this);
		new Ajax.Updater('maincontent', url, {
  			parameters: { groupid: grpId,pageNum: pageNum},
  			onSuccess: showDiv
		});
		setTimeout("highlightAfterAdd('"+newRecId+"');",2000);		
	}
	
	function highlightAfterAdd(newRecId){	
		new Effect.Highlight(newRecId, { startcolor: startColor,endcolor: endColor ,duration: 2});	
	}
		
	function afterSuccess(){
		$('maincontent').show();
		scroll(0,0);
	}
	
	function setVars(grpId,level){
		$('currGrpId').value = grpId;
		$('talkGrpId').value = grpId;
		$('currLevel').value = level;
	}
	
	function setHeaderMore(id,para){
		$('moreTabDesc').innerHTML = 'All your groups are listed here. To navigate to a group, click on its name.';	//proj2Gr	
		if(para == 1){
			$(id).className = 'selMore';
		}else{		
			$(id).className = '';
			$('navigate_groups').style.display = 'none';
		}
	}
	
	function setHeader(obj,grpId,level){
		/*Highlight the selected group tab*/		
		var headerElements = $$('li.sel');
		var elemCount = headerElements.length;
		
		for(i=0;i<elemCount;i++){
			headerElements[i].className='';
		}
		obj.parentNode.className = 'sel';
		//if(level != 'ovr') {
			var url    = '/groups/groups/showSettingstab.json';
			var userID = $('SessStrUserID').value;	
			var rand   = Math.random(9999);
	
			var pars   = "sessUID="+userID+"&groupid="+grpId+"&rand="+rand;
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess:function(transport)
							{
								if(transport.responseText == "expired")
								{
									window.location = MainUrl + "main.php?u=signout";return;
								}
								else
								{	
									var r = transport.responseText.strip();
									var retDataArr = r.split('!^-sep!^-');
									var retData = retDataArr[0];
									var cpTab = retDataArr[1];
									var invTab = retDataArr[2];
									if(retData == "YES" || retData == "YESDM"){
										$('sepratorTwo').style.display = 'block';
										$('generalSettingsLink').style.display = 'block';										
										if(retData == "YES"){									
											$('settingLinkSpan').innerHTML =  '<a href="javascript:void(0)" onClick="setSecondHeader(\'generalSettingsLink\');loadGeneralSettings()">Settings</a>';
										}
										if(retData == "YESDM"){
											$('settingLinkSpan').innerHTML =  '<a href="javascript:void(0)" onClick="setSecondHeader(\'generalSettingsLink\');showManageThisDomainViewAdmin()">Settings</a>';
										}	
									} else {
										$('sepratorTwo').hide();
										$('generalSettingsLink').hide();
									}
									
									// create project shud not be shown when the user is not a part of this n/w
									if(cpTab == "YES") {
										$('txtCreateGroup').show();
									} else {
										$('txtCreateGroup').hide();
									}
									
									// shud not be show when the user is not a moderator or admin of this group
									if(invTab == "YES") {
										$('sepratorOne').show();
										$('txtInvite').show();
									} else {
										$('sepratorOne').hide();
										$('txtInvite').hide();
									}
									
									afterShowSetting(obj, grpId, level, true,retDataArr[3],retDataArr[4]);
									
								}
							}
			});
		/*} else {
		
			afterShowSetting(obj, grpId, level, false);
		}*/
	
			
	}

	function afterShowSetting(obj, grpId, level, state,freeUsr,grpName) {
	$('currGrpName').value = grpName; //alert(grpName);
		/*
		//Hide Messages,Files,Milestones,Tasks tab for overview section
		
		if(grpId == "ovr"){
			$('activityTab').hide();
			if(!state) {
				$('generalSettingsLinkPipe').style.display = 'none';
				$('generalSettingsLink').style.display = 'none';
			}
			
		}else{
			$('activityTab').show();
			if(!state) {
				$('generalSettingsLinkPipe').style.display = 'block';
				$('generalSettingsLink').style.display = 'block';
			}
			$('homeNavigation').className = 'sel';			
		}
		//Handle 'Create Group' section
			
		if(level == "3" || level == "prof")
			$('crtGroup').hide();
		else
			$('crtGroup').show();

		if(level == "2")
			$('txtCreateGroup').innerHTML = "Create sub-group";
		else
			$('txtCreateGroup').innerHTML = "Create group";
			*/

		if(grpId == "ovr" && level == "ovr"){// dashboard
			$('activityTab').hide();
			$('profileActivityTab').hide();
			$('otherTabs').show();
			$('TempTabs').hide();
			if(freeUsr == 'YES'){
				$('TempTabs').show();
			}
			$('txtCreateGroup').innerHTML = "Create group";//proj2Gr
			$('txtInvite').innerHTML = "Invite people";
			$('inviteHeader').innerHTML = "Your company intranet is a secure and exclusive space for your team, co-workers and clients to communicate and work together. Choose who you want to invite into it.";
			if(!state) {
				$('sepratorOne').show();
				$('txtCreateGroup').show();
				$('txtInvite').show();
				$('sepratorTwo').style.display = 'none';
				$('generalSettingsLink').style.display = 'none';
			}
		}
		
		if(grpId == "ovr" && (level == "profile" || level == "eprofile" || level == "public" || level == "settings")){ //profile page
			$('activityTab').hide();
			$('profileActivityTab').show();
			$('otherTabs').hide();
			$('TempTabs').hide();
		}
		
		if(grpId == "ovr" && level == "mtd"){ //manage this intranet
			$('activityTab').hide();
			$('profileActivityTab').hide();
			$('otherTabs').hide();
			$('TempTabs').show();
		}
		
		if(grpId != "ovr" && (level == "1" || level == "2" || level == "3")){
			$('activityTab').show();
			$('profileActivityTab').hide();
			$('otherTabs').show();
			$('TempTabs').hide();

			if(!state) {			
				$('sepratorTwo').style.display = 'none';
				$('generalSettingsLink').style.display = 'none';
				$('sepratorOne').style.display = 'block';
			}
			
			if(level == "2"){
				$('txtCreateGroup').innerHTML = "Create sub-group";//proj2Gr
				$('txtInvite').innerHTML = "Invite to this group";//proj2Gr
				$('inviteHeader').innerHTML = "This group is a secure and private place for you to collaborate with your team and clients. Only people that are invited into it will see this group. Choose who to invite.";//proj2Gr
			}else if(level == "1"){
				$('txtCreateGroup').innerHTML = "Create group";//proj2Gr
				$('txtInvite').innerHTML = "Invite co-workers to this Intranet";
				$('inviteHeader').innerHTML = "Your company intranet is a secure and exclusive space for your team, co-workers and clients to communicate and work together. Choose who you want to invite into it.";
			}else{
				$('txtCreateGroup').innerHTML = "";
				$('sepratorOne').style.display = 'none';
				$('txtInvite').innerHTML = "Invite to this sub-group";//proj2Gr
				$('inviteHeader').innerHTML = "This group is a secure and private place for you to collaborate with your team and clients. Only people that are invited into it will see this group. Choose who to invite.";//proj2Gr
			}
			
			//$('homeNavigation').className = 'sel';

		}
	}
	
	function showOvrCommentBox(objId,module,type){
		if(module == "M"){
			if(type=="2")
				$('plnMsgCommentBox'+objId).hide();
			else
			{
				//$('msgComentBox'+objId).hide();
				Element.immediateDescendants($('msgComentBox'+objId))['0'].hide();
			}	
				
			$('actMsgCommentBox'+objId).show();
		}else if(module == "F"){
			if(type=="2")
				$('plnFlCommentBox'+objId).hide();
			else
			{
				//$('flComentBox'+objId).hide();
				//Element.immediateDescendants($('flComentBox'+objId))['0'].hide();
			}	
				
			$('actFlCommentBox'+objId).show();
		}else if(module == "T"){
			if(type=="2")
				$('plnTalkCommentBox'+objId).hide();
			else
			{
				//$('tlkComentBox'+objId).hide();
				//Element.immediateDescendants($('tlkComentBox'+objId))['0'].hide();
			}	
				
			$('actTalkCommentBox'+objId).show();
		}
	}
	
	function toggleFile(objId,grpId) {
		var t = $('actFlCommentBox'+objId);
		
		if($('brwCounter_'+objId))$('brwCounter_'+objId).value = '0';
		if($('filesCommentBox_'+objId))$('filesCommentBox_'+objId).innerHTML = '';
		
		if(t.style.display == 'none') {
			$('flsCommMsg'+objId).hide();
			$('txtFileCommentBox'+objId).value = '';
			FileBrowseAdd(objId,grpId);	
		}

		if($('plnFlCommentBox'+objId)) {
			var p = $('plnFlCommentBox'+objId);
			if(t.style.display == 'none') {
				p.hide();
				$('txtFileCommentBox'+objId).rows = '2';
				t.show();
				$('txtFileCommentBox'+objId).focus();
			} else {
				p.show();
				t.hide();
			}
			 
		} else Effect.toggle(t, 'slide', { duration: 0.5});
	}
	
	
	function FileBrowseAdd(msgId,grpId){	
		var id = messagesObject.getUniqueID();
		var group_id = group = grpId;		
		var brwCnt = $('brwCounter_'+msgId).value;
		var category_id = $('fileCategory_'+msgId).value;
		brwCnt = parseInt(brwCnt) + 1;
		$('brwCounter_'+msgId).value = brwCnt;
		var filecnt = brwCnt;
		
        var divid = "filesCommentBox_"+msgId+'_'+filecnt;
        var formid = "fileCommentFileUpload_"+msgId+"_"+filecnt;
        var newform = document.createElement("form");
        newform.id = formid;
        newform.name = "fileCommentFileUpload_"+msgId;
        newform.method = 'post';
        newform.style.display = 'block';
		newform.style.paddingBottom = "10px";
        newform.enctype = 'multipart/form-data';
        document.getElementById("filesCommentBox_"+msgId).appendChild(newform);
        document.getElementById(formid).innerHTML = '<input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="'+id+'"/>'
                                                	+ '<input name="groupid" type="hidden"  value="'+group_id+'" '
	                                                + '<input type="hidden" name="msgParentId" value="'+msgId+'" />'
                                                    + '<input type="hidden" name="childno" id="childno" value="'+filecnt+'"/>'
                                                    + '<input type="hidden" name="lastid" id="lastid" value="" />'
                                                    + '<input type="hidden" name="last" id="last" value="0" />'
										        	+ '<input type="hidden" name="vx" id="vx" value="tt"/>'
										        	+ '<input type="hidden" name="group" id="group" value="'+group+'"/>'
	                                                + '<input type="hidden" name="data[groupfile][groupfilecategory_id]" value="'+category_id+'" />'
                                                    + '<input type="file" name="name" id="'+divid+'" onchange=\'if(this.value) FileBrowseAdd("'+msgId+'","'+grpId+'")\'/>&nbsp;&nbsp;'
							+ '<span id="removeGroupNewLinkSpan_'+msgId+'_'+filecnt+'" class="dummy_filesRemoveLink"></span>'
							+ '<span id="addGroupNewLinkSpan_'+msgId+'_'+filecnt+'"></span>'
							//+'<span id="status_'+id+'" style="display:none"> #{complete}/#{total} (#{percent}%)</span>'
                            +'&nbsp;<span id="element_'+id+'" style="display:none">'
                                    +'<img class="percentImage" style="margin: 0pt; padding: 0pt; width: 120px; height: 12px; background-position: -120px 50%; background-image: url(/images/bramus/percentImage_back.png);" alt="" src="/images/bramus/percentImage.png" id="element_'+id+'_percentImage" title=""/>'
                            +'</span>'
                            +'&nbsp;<span id="element_'+id+'_percentText" style="display:none"></span>'
                            +'</div>';
		if(filecnt > 1) {	
			var linkid = filecnt-1;
			var linkformid = 'fileCommentFileUpload_'+msgId+'_'+linkid;
			var remlinkid = 'removeGroupNewLinkSpan_'+msgId+'_'+linkid;
			messagesObject.getRemoveLink(linkformid, remlinkid);
			// Cross-browser complaint
			$("filesCommentBox_"+msgId+"_"+linkid).onchange = null;
			$("addGroupNewLinkSpan_"+msgId+"_"+linkid).remove();
		}
	}
	
	function FileBrowseAddComObj(msgid) { // FOR COMMENTS ON files  PAGE (message) 
	        var filepara = { form : "fileCommentForm_"+msgid,
	                         url_action : "/groups/files/saveFileComments",
	                         id_element : "flsCommMsg"+msgid,
	                         html_show_loading : "",
	                         html_error_http : ""
	                       };
	        return filepara;
	}
	var filesObj = '';
	var filesGlobal = false;
	function FileBrowseAddObj(msgid,grpId) { // FOR COMMENTS ON FIles PAGE (file upload)			
	        var filepara = { form : "fileCommentFileUpload_"+msgid,
	        				 groupid : grpId,
	        				 msgid : msgid,
	        				 catid : "",
	        				 objName : 'filesObj',
	                         url_action : "/groups/files/uploadFile/"+grpId,
	                         id_element : "flsCommMsg"+msgid,
	                         area : "txtFileCommentBox"+msgid,
	                         html_show_loading : "loading"+msgid,
	                         html_error_http : "Error",
	                         error_message : "Please type a comment",
	                         global : "filesGlobal = false",
	                         get_comment : "filesObj = new uploadMF('FileBrowseAddObj("+msgid+", "+grpId+")'); filesObj.submitform();",
				 			 add_file : "FileBrowseAdd("+msgid+","+grpId+")",
	                         comment_edit : "",
	                         submit_comment : "FileBrowseAddComObj("+msgid+")",
							 before_submit : "",
							 after_submit : "",
							 lastid : "fileLastid_"+msgid,
							 removelinkclass : "dummy_filesRemoveLink",
							 gct : "",
							 got_gct : false,
							 after_gct : "",
							 after_upload : "",
							 clean_up : "$('brwCounter_"+msgid+"').value = '0'; $('fileCommentForm_"+msgid+"').reset(); $('filesCommentBox_"+msgid+"').innerHTML='';pullComments("+msgid+","+grpId+");$('saveFileComm"+msgid+"').removeClassName('butDisabled');"
	                       };
	        return filepara;
	}
	
	function pullComments(parent,grpId){
		var lastInsertId = $('fileLastid_'+parent).value;		
		var url    = '/groups/files/pullComment.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var myAjax = new Ajax.Request(url, {method: 'post', 
							parameters: {sessUID:userID, lastId:lastInsertId, rand:rand}, 
							onSuccess:function(transport) {
							
							if(transport.responseText == "expired")
							{
								window.location = MainUrl + "main.php?u=signout";return;
							}
							else
							{	
								//alert(transport.responseText);
								//$('dinesh').innerHTML = transport.responseText;
								
								var whichComment = transport.responseText.split("^$@yz^");
								
								if(whichComment[1] > 1){
									//multi comments
									//elem = Element.immediateDescendants($('fileComments'+parent))['0'];
									//elemDesc = Element.immediateDescendants(elem);
									//elemDescLenght = elemDesc.length;
									//reqElem = elemDesc[elemDescLenght-1].id;

						                    
						            $('plnFlCommentBox'+parent).insert ({
										'before'  : whichComment[0]
									} );
									
									$('brwCounter_'+parent).value = '0';
								
									var arrCntComment = $('cntFileComments'+parent).innerHTML;
									cntComment = parseInt(arrCntComment.split(' ')['0'])+1;
									$('cntFileComments'+parent).innerHTML = cntComment+" Comments";									
									$('filesCommentBox_'+parent).innerHTML = '';
									$('actFlCommentBox'+parent).hide();
									$('plnFlCommentBox'+parent).show(); 
								}else{
								//first comment
								
									html = 	'<div class="commentsTotalNo gainlayout">'+
							                      '<div class="rightFloat"><img src="/images/minimize.gif" id="cntFileCommentsImg'+parent+'" style="cursor:pointer" onclick="showHideComments('+parent+',\'F\',this)"/></div>'+
							                      '<a href="javascript:void(0)" onclick="showHideComments('+parent+',\'F\',this)" id="cntFileComments'+parent+'">1 Comment</a>'+
							                    '</div>'+
							                    '<div id="fileComments'+parent+'">'+
							                    	'<div>'+
							          			whichComment[0]	
							          		+'<div id="plnFlCommentBox'+parent+'" class="commentsCont gainlayout">'+
							          			'<div onclick="toggleFile('+parent+','+grpId+')" style="border: 1px solid #b5def9; padding: 8px; background: #FAFBFC; width: 90%; font-size: 12pt; font-weight: bold; color:#CBCBCB; ">Write more comments...</div>'+
							                '</div>'+                						                            						                            						                            
							               '</div>'+
							              '</div>';
							           
							           $('fileDataComm'+parent).insert ({
											'top'  : html
									   } );
									   
									   $('fileComments'+parent).appendChild($('actFlCommentBox'+parent));
									   
									   Element.remove($('flComentBox'+parent));
									   //Element.remove($('actFlCommentBox'+parent));
									   
									   $('actFlCommentBox'+parent).hide();
								}
								
								$('hdnFileCommCount'+parent).value = parseInt($('hdnFileCommCount'+parent).value) + 1;
								
							}
						}
					});
					
					
					url = '/groups/files/afterSaveFileComments.json';
								pars = "sessUID="+userID+"&groupid="+grpId+"&parent="+parent+"&lastid="+lastInsertId;
								new Ajax.Request(url, {method: 'post', parameters: pars,
											onSuccess:function(transport)
											{
												if(transport.responseText == "expired"){
													window.location = MainUrl + "main.php?u=signout";return;
												}
											}
								});
	}
	
	function toggleTalk(objId) {
		var t = $('actTalkCommentBox'+objId);
		if(t.style.display == 'none') {
			$('tlkCommMsg'+objId).hide();
			$('txtTalkCommentBox'+objId).value = '';
		}

		if($('plnTalkCommentBox'+objId)) {
			var p = $('plnTalkCommentBox'+objId);
			if(t.style.display == 'none') {
				p.hide();
				t.show();
				$('txtTalkCommentBox'+objId).focus();
			} else {
				p.show();
				t.hide();
			}

		} else Effect.toggle(t, 'slide', { duration: 0.5});
	}
	
	function closeTalkComment(objId) {
	
		toggleTalk(objId);
	
		/*$('tlkCommMsg'+objId).hide();
		$('actTalkCommentBox'+objId).hide();
		$('txtTalkCommentBox'+objId).value = '';
		if($('plnTalkCommentBox'+objId))
				$('plnTalkCommentBox'+objId).show();
			else
				$('tlkComentBox'+objId).show();*/
	}
	
	function showFullComments(type,obj,objId){
		var anchorText = "";
		if(type=='M'){
			anchorText = 'midMsgText'+objId;
			Effect.toggle('midMsgComment'+objId,'slide', { duration: 0.5});
		}else if(type=='F'){
			anchorText = 'midFileText'+objId;
			Effect.toggle('midFileComment'+objId,'slide', { duration: 0.5});
		}else if(type=='T'){
			anchorText = 'midTalkText'+objId;
			Effect.toggle('midTalkComment'+objId,'slide', { duration: 0.5});
		}
		
		var ctext = $(anchorText).innerHTML;
		if(ctext == "Show")
			$(anchorText).innerHTML = "Hide";
		else
			$(anchorText).innerHTML = "Show";		
	}
	
	function showHideComments(objId,type,self)
	{
		if(self.getAttribute('src') != null)
		{
			if(self.src.match('minimize.gif'))
				self.src = '/images/maximize.gif';
			else
				self.src = '/images/minimize.gif'
		} else {
			if(type=='M'){
				typeName = 'Message';
			}else if(type=='F'){
				typeName = 'File';
			}else if(type=='T'){
				typeName = 'Talk';
			}
		
			if($('cnt'+typeName+'CommentsImg'+objId).src.match('minimize.gif'))
				$('cnt'+typeName+'CommentsImg'+objId).src = '/images/maximize.gif';
			else
				$('cnt'+typeName+'CommentsImg'+objId).src = '/images/minimize.gif';
		}
				
		if(type=='M'){
			Effect.toggle('msgComments'+objId,'slide', { duration: 0.5});
		}else if(type=='F'){
			Effect.toggle('fileComments'+objId,'slide', { duration: 0.5});
		}else if(type=='T'){
			Effect.toggle('talkComments'+objId,'slide', { duration: 0.5});
		}
	}
	
	function showHideTabs(type,self){
		if(self.src.match('colapse.gif'))
			self.src = '/images/expand.gif';
		else
			self.src = '/images/colapse.gif'
			
		if(type=='MMT'){
			
			if($('milestoneGroupScrollOverviewMy').style.display != 'none'){
			
			Effect.toggle('milestoneGroupScrollOverviewMy','slide', { duration: 0.2});
			$('milestoneGroupScrollOverviewMy').className = 'milestoneGroupScrollOverviewNoFlow';
			
			setTimeout("$('myMilestonesTasks').style.display = 'none';",200)
			}else{
			$('myMilestonesTasks').style.display = 'block';
			$('milestoneGroupScrollOverviewMy').className = 'milestoneGroupScrollOverview';
			$('milestoneGroupScrollOverviewMy').style.overflowY = '';								
			Effect.toggle('milestoneGroupScrollOverviewMy','slide', { duration: 0.2});
			
			//setTimeout("$('milestoneGroupScrollOverviewMy').className = 'milestoneGroupScrollOverview';",1)
			}
									
		}else if(type=='MAMT'){
			//Effect.toggle('myAssignedMilestonesTasks','slide', { duration: 0.4});
			if($('milestoneGroupScrollOverviewMyA').style.display != 'none'){
			Effect.toggle('milestoneGroupScrollOverviewMyA','slide', { duration: 0.2});
			$('milestoneGroupScrollOverviewMyA').className = 'milestoneGroupScrollOverviewNoFlow';
			setTimeout("$('myAssignedMilestonesTasks').style.display = 'none';",200)
			}else{
			$('myAssignedMilestonesTasks').style.display = 'block';
			$('milestoneGroupScrollOverviewMyA').className = 'milestoneGroupScrollOverview';
			$('milestoneGroupScrollOverviewMyA').style.overflowY = '';								
			Effect.toggle('milestoneGroupScrollOverviewMyA','slide', { duration: 0.2});
			
			//setTimeout("$('milestoneGroupScrollOverviewMy').className = 'milestoneGroupScrollOverview';",1)
			}
		}else if(type=='MEM'){
			if($('membersScrollOverview').style.display != 'none'){	
				Effect.toggle('membersScrollOverview','slide', { duration: 0.2});
				$('membersScrollOverview').className = 'milestoneGroupScrollOverviewNoFlow';
				setTimeout("$('membersTab').style.display = 'none';",200)
			}
			else{
				$('membersTab').style.display = 'block';
				$('membersScrollOverview').className = 'milestoneGroupScrollOverview';
				$('membersScrollOverview').style.overflowY = '';								
				Effect.toggle('membersScrollOverview','slide', { duration: 0.2});
			}
		}else if(type=='CAT'){
			if($('category_content').style.display != 'none'){	
				Effect.toggle('category_content','slide', { duration: 0.2});
				$('category_content').className = 'imNetworkMembers milestoneGroupScrollOverviewNoFlow';
				setTimeout("$('categoriesTab').style.display = 'none';",200)
			}
			else{
				$('categoriesTab').style.display = 'block';
				$('category_content').className = 'imNetworkMembers milestoneGroupScrollOverview';
				$('category_content').style.overflowY = '';								
				Effect.toggle('category_content','slide', { duration: 0.2});
			}
		}else if(type =='QL'){			
			if($('myQuickLinks').style.display != 'none'){				
				Effect.toggle('myQuickLinks','slide', { duration: 0.2});				
			}else{										
				Effect.toggle('myQuickLinks','slide', { duration: 0.2});
			}
		}else if(type =='PP'){			
			if($('profilePics').style.display != 'none'){				
				Effect.toggle('profilePics','slide', { duration: 0.2});				
			}else{										
				Effect.toggle('profilePics','slide', { duration: 0.2});
			}
		}		
	}
	
	function TALK_A() { // FOR COMMENTS ON GROUPMESSAGE PAGE (file upload)
			var category_id = 0;
			var groupid = group = $('selectedIDsshoutBox').value;
	        var filepara = { form : "frmTalkUpload",
	        				 groupid : groupid,
	        				 catid : category_id,
	        				 objName : 'talkUploadObject',
	                         url_action : "/groups/files/uploadFile/"+groupid,
	                         id_element : "talkStatus",
	                         area : "talkTxtArea",
	                         html_show_loading : "talkLoading",
	                         html_error_http : "Error",
	                         error_message : "Please type a comment",
	                         global : "talkSubmitted=false;",
	                         get_comment : "talkUploadObject = new uploadMF('TALK_A()'); talkUploadObject.submitform();",
				 			 add_file : "TALK_A_AddFile()",
	                         comment_edit : "",
	                         submit_comment : "TALK_A_Message()",
							 before_submit : "",
							 after_submit : "",
							 lastid : "talklastid",
							 removelinkclass : "dummy_talk_RemoveLink",
							 gct : "/groups/messages/GCT.json",
							 got_gct : false,
							 //after_gct : "MF_B_AfterGCT('data', msgid, parent, level)",
							 //after_upload : "MF_B_AfterUpload("+msgid+", lastid)",
							 clean_up : "getTalkData(); talkHideNormalBox();"
	                       };
	        return filepara;
	}
	
	function TALK_A_Message() { // TALK MESSAGE
	        var filepara = { form : "talkForm",
	                         url_action : "/groups/groups/saveTalkMessage",
	                         id_element : "talkStatus",
	                         html_show_loading : "",
	                         html_error_http : ""
	                       };
	        return filepara;
	}
	
	var talk_file_cnt = 0;
	var talkSubmitted = false;
	var talkUploadObject;
	
	function TALK_A_AddFile() {
		var id = messagesObject.getUniqueID();
		var group_id = group = $('selectedIDsshoutBox').value;
		talk_file_cnt += 1;
		var filecnt = talk_file_cnt;
		
        var divid = "talkFile_"+filecnt;
        var formid = "frmTalkUpload_"+filecnt;
        var newform = document.createElement("form");
        newform.style.styleFloat = "left";
        newform.id = formid;
        newform.name = "frmTalkUpload";
        newform.method = 'post';
        newform.style.display = 'block';
		newform.style.paddingBottom = "10px";
        newform.enctype = 'multipart/form-data';
        document.getElementById("talk_file_para").appendChild(newform);
        document.getElementById(formid).innerHTML = '<div class="leftFloat"><input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="'+id+'"/>'
        											+ '<input type="hidden" name="noevents" />'
                                                	+ '<input type="hidden" name="groupid" value="'+group_id+'"/>'
                                                    + '<input type="hidden" name="childno" id="childno" value="'+filecnt+'"/>'
                                                    + '<input type="hidden" name="lastid" id="talklastid" value="" />'
                                                    + '<input type="hidden" name="last" id="last" value="0" />'
										        	+ '<input type="hidden" name="vx" id="vx" value="tt"/>'
										        	+ '<input type="hidden" name="group" id="group" value="'+group+'"/>'
                                                    + '<input type="file" name="name" id="'+divid+'" onchange=\'if(this.value) TALK_A_AddFile()\'/>&nbsp;&nbsp;'
							+ '<span class="dummy_talk_RemoveLink" id="removeTalkLinkSpan_'+filecnt+'"></span>'
							+ '<span id="addTalkLinkSpan_'+filecnt+'"></span>'
                            +'&nbsp;<span id="element_'+id+'" style="display:none">'
                                    +'<img class="percentImage" style="margin: 0pt; padding: 0pt; width: 120px; height: 16px; background-position: -120px 50%; background-image: url(/images/bramus/percentImage_back.png);" alt="" src="/images/bramus/percentImage.png" id="element_'+id+'_percentImage" title=""/>'
                            +'</span>'
                            +'&nbsp;<span id="element_'+id+'_percentText" style="display:none"></span></div>'
                            +'</div>';
        
        //var clearLeft = '<div class="clearLeft">&nbsp;</div>';                    
        //$("formDivsComments_"+msgId).insert ( { 'bottom'  : clearLeft } );
		if(filecnt > 1) {	
			var linkid = filecnt-1;
			var linkformid = 'frmTalkUpload_'+linkid;
			var remlinkid = 'removeTalkLinkSpan_'+linkid;
			messagesObject.getRemoveLink(linkformid, remlinkid);
			// Cross-browser complaint
			$("talkFile_"+linkid).onchange = null;
			$("addTalkLinkSpan_"+linkid).remove();
		}
	}
	
	function saveTalkMsg(event,para) {
		if((!$('talkTxtArea').value.strip().blank()) && ($('talkTxtArea').value!='Share a thought... Share a link...')) {
			
			if(!talkSubmitted) {
				
				var groupid = messagesObject.getElementsByName('groupid', 'input');
				if(groupid.length > 0)
					for(var i in groupid)
						groupid[i].value = $('selectedIDsshoutBox').value;
				
				talkSubmitted = true; 
				var vFBU = new checkFBU( 'TALK_A()' );
			
				if(document.getElementById('talkStatus').style.display=='none')
					vFBU.showUploaderOnSubmit(); 
				
				if( vFBU.validatefiles() )  {
					//talkSubmitted = false;
				}
			}
			//hide the talk blurb after the talk message is saved
			//$('talk_blurb').style.display = 'none';
			$('talk_blurb').hide();
			
		} else {
			talkShowNormalBox();
			
			if(para)
			{
				//if($('talk_blurb').style.display == 'none')
				{
					showBlurb('talk',$('selectedVal'+para),event);
				}
			}
		}
		
	}
	
	function getTalkData() {
		
		if(talkSubmitted) talkSubmitted = false;
		else return false; 
		
		var url    = '/groups/groups/getTalkData.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var grpId = $('selectedIDsshoutBox').value;
		var talklastid = $('talklastid').value;
		var msg = $('talkTxtArea').value;
		
 		//alert(talklastid +' :: '+ $('talkTxtArea').value +' :: '+ $('selectedIDsshoutBox').value);
 		
		var myAjax = new Ajax.Request(url, {method: 'post', 
						parameters: {sessUID:userID, groupid:grpId, talklastid:talklastid, msg:msg, rand:rand}, 
						onSuccess:function(transport)
						{
							if(transport.responseText == "expired")
							{
								window.location = MainUrl + "main.php?u=signout";return;
							}
							else
							{
								edata = (eval('(' + transport.responseText + ')'));
								var m = new messages();
								m.initialize();
								
								var attachment = '';
								if(edata.data.length > 0) attachment = m.getAttachment(edata) + '<div class="clearLeft">Â </div>';
								
								var tlkId = edata.other.parent;
								var tlkMsg = $('talkTxtArea').value; 
								var currPage = $('currPage').value;
								$('divTalkSuccessMsg').hide();
								if(currPage != "1" || (($('currGrpId').value != "ovr") && (grpId != $('currGrpId').value)))
								{
									/*
									grpId = $('talkGrpId').value;
									showDiv = this.afterSuccess.bind(this);
									new Ajax.Updater('maincontent', '/groups/groups/view.json', {
							  			parameters: { grpid: grpId,pageNum: 1,rand:rand},
							  			onSuccess: showDiv
									});
									*/
									$('divTalkSuccessMsg').show();
									$('divTalkSuccessMsg').innerHTML = "Message posted successfully.";
									//new Effect.Highlight($('divTalkSuccessMsg'), { startcolor: startColor,endcolor: endColor ,duration: 5 });
									setTimeout("$('divTalkSuccessMsg').fade();",3000);
								} else {
									
									var np = "network";
									grplevel = edata.other.level;
									
									if(grplevel == "2")
										np = "group";//proj2Gr
									else if(grplevel == "3")
										np = "subgroup";//proj2Gr	
									
									nName = edata.other.name;
									tlkMsg = edata.other.message;
									
									var top = '<div class="postGroupTop" id="TL_'+tlkId+'" onmouseout="javascript:$(\'sidebar\').hide()" onmouseover="javascript:ovrToggle(this,true,\''+tlkId+'\',\''+nName+'\',\''+np+'\',\'Talk\',\''+grpId+'\',\'\',\'1\',\'\')">';
									var html = '<div class="read">'+
						                        '<div class="postTitleSm"> <img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /> </div>'+
						                        '<div class="postDataSm">'+
						                          '<div class="postDataDescSm"><span><a href="javascript:void(0);" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\'];showProfilePage(obj,\'public\',\''+$('SessStrUserID').value+'\');">'+$('username').value+'</a></span> : '+wrapDesc(htmlspecialchars(tlkMsg)).replace(/\n/g, "<br>")+'</div>'+
						                          attachment +
						                          '<div id="talkData'+tlkId+'">'+
						                          	'<div id="talkbutComment_'+tlkId+'" class="butCommentDiv" style="display:block">'+
	                           							'<a class="butComment" href="javascript:void(0);" onclick="commentTalk.openComment(\''+tlkId+'\',\''+grpId+'\')">Comment</a>'+
	                        					  	'</div>';
						             
						             html +=  commentTalk.CommentBoxHtml(tlkId,grpId);         
						             
						             /*html +='<div class="comments">'+
							             		'<div class="commentsCont" id="actTalkCommentBox'+tlkId+'" style="display:none">'+
							             		'<div class="closeGrey rightFloat closeRelative"><a href="javascript:void(0)" onclick="closeTalkComment('+tlkId+');">X</a></div>'+	                                  			
					                              '<div class="postTitleSm"><img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /></div>'+
					                              '<div class="postDataSm">'+
					                                '<div class="field">'+
					                                  '<textarea style="width: 90%;" id="txtTalkCommentBox'+tlkId+'" onkeyup="ta_autoresize(this);" rows="2" cols="65"></textarea>'+
					                                '</div>'+
					                                '<div style="display:none;" id="tlkCommMsg'+tlkId+'" class="err">Please type a comment</div>'+
					                                '<div class="butBlueDiv"><a class="butBlue" href="javascript:void(0);" onClick="saveTalkComments('+tlkId+','+grpId+')" linkindex="17"><span>Comment</span></a></div>'+
					                              '</div>'+
					                            '</div><input type="hidden" id="hdnTlk'+tlkId+'" value="0">'+
					                      	'</div>'+
					                      	*/
					                      	
									html +=		'<input type="hidden" id="hdnTlk'+tlkId+'" value="0"></div>'+
						                	'</div>'+
						             	'</div>';
						             
						             var bottom = '</div>'; 
	                            
						                    
									/*var latestDate = Element.immediateDescendants($('overviewBody'))['0'].immediateDescendants()['0'].innerHTML;
									if(latestDate.strip().toLowerCase() == "today"){
						            	Element.immediateDescendants($('overviewBody'))['1'].className = "postGroup";
						            	Element.immediateDescendants($('overviewBody'))['0'].remove();            	
						            }
						            
						            html = '<div class="threadDate"><span class="threadDateDisplay">Today</span></div>'+html;
						            
						        	$('overviewBody').insert ({
						 				'top'  : html
						  			} );*/   
						  			
						  				

						  			var ov = $('overviewBody').childElements();
									var ovFirst = ov[0].childElements();
									var p = ovFirst[0].innerHTML.strip();
									if(p.toLowerCase() == 'today')
									{
										var obj;
										if(ov[1].firstChild)
										{
											obj = ov[1].firstChild;
											var ovSec = ov[1].childElements();										
											ovSec[0].className = "postGroup";
										}	
										else
											obj = ov[1];										
															
										var newDiv = Builder.node('div', {id:'TL_'+tlkId,onmouseout: 'javascript:$("sidebar").hide()', onmouseover : 'javascript:ovrToggle(this,true,\''+tlkId+'\',\''+nName+'\',\''+np+'\',\'Talk\',\''+grpId+'\',\'\',\'1\')'}).addClassName("postGroupTop").update( html );
										Element.insert( obj, {'before' : newDiv} );
									}
									else
									{
										var newDiv = Builder.node('div', {className : 'threadGroup'} ).update( top + html + bottom );
										Element.insert( $('overviewBody'), {'top' : newDiv} );
										
										var newDivToday = Builder.node('div', {className : 'threadDate'} ).update( '<span class="threadDateDisplay">Today</span>' );
										Element.insert( $('overviewBody'), {'top' : newDivToday} );
									}
									if($('blank'))
						  				$('blank').hide();
						  				
						  			$('overviewBody').show();
						  			new Effect.Highlight(newDiv, { startcolor: startColor,endcolor: endColor ,duration: 1 });
						  			                     						                            
								}
							}
							
							url = '/groups/groups/afterSaveTalkMsg.json';							
							pars = "sessUID="+userID+"&groupid="+grpId+"&parent=0&lastid="+tlkId;							
							new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
										}
									});
						}
		});
				
		//Reset the text area
		$('talkTxtArea').value = "";
		$('talkTxtArea').rows = "2";
	}
		
	
	function saveTalkMsgTEMP(){ // DO NOT USE
		var url    = '/groups/groups/saveTalkMessage.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var grpId = $('selectedIDsshoutBox').value;

		var tlkMsg = $('talkTxtArea').value; 
		//var pars   = "sessUID="+userID+"&groupid="+grpId+"&parent=0&tlkMsg="+tlkMsg+"&rand="+rand;
		var myAjax = new Ajax.Request(url, {method: 'post', 
						parameters: {sessUID:userID, groupid:grpId, parent:0, tlkMsg:tlkMsg, rand:rand}, 
						onSuccess:function(transport)
						{
							if(transport.responseText == "expired")
							{
								window.location = MainUrl + "main.php?u=signout";return;
							}
							else
							{
								edata = (eval('(' + transport.responseText + ')'));
								var tlkId = edata.lastId;
								var currPage = $('currPage').value;
								$('divTalkSuccessMsg').hide();
								if(currPage != "1" || (($('currGrpId').value != "ovr") && (grpId != $('currGrpId').value)))
								{
									/*
									grpId = $('talkGrpId').value;
									showDiv = this.afterSuccess.bind(this);
									new Ajax.Updater('maincontent', '/groups/groups/view.json', {
							  			parameters: { grpid: grpId,pageNum: 1,rand:rand},
							  			onSuccess: showDiv
									});
									*/
									$('divTalkSuccessMsg').show();
									$('divTalkSuccessMsg').innerHTML = "Message posted successfully.";
									//new Effect.Highlight($('divTalkSuccessMsg'), { startcolor: startColor,endcolor: endColor ,duration: 5 });
									setTimeout("$('divTalkSuccessMsg').fade();",3000);
								}else{
									
									var np = "network";
									grplevel = edata.level
									
									if(grplevel == "2")
										np = "group";//proj2Gr
									else if(grplevel == "3")
										np = "subgroup";//proj2Gr
									
									nName = edata.name;
									
									var top = '<div class="postGroupTop" id="TL_'+tlkId+'" onmouseout="javascript:$(\'sidebar\').hide()" onmouseover="javascript:ovrToggle(this,true,\''+tlkId+'\',\''+nName+'\',\''+np+'\',\'Talk\',\''+grpId+'\',\'\',\'1\')">';
									var html = '<div class="read">'+
						                        '<div class="postTitleSm"> <img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /> </div>'+
						                        '<div class="postDataSm">'+
						                          '<div class="postDataDescSm"><span><a href="javascript:void(0);" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\'];showProfilePage(obj,\'public\',\''+$('SessStrUserID').value+'\');">'+$('username').value+'</a></span> : '+wrapDesc(htmlspecialchars(tlkMsg)).replace(/\n/g, "<br>")+'</div>'+
						                          '<div id="talkData'+tlkId+'">'+
						                          '<div id="tlkComentBox'+tlkId+'" style="padding-bottom:15px" class="butCommentDiv"><a href="javascript:void(0);" onClick="toggleTalk('+tlkId+')" class="butComment">Comment</a></div>';
						                        
						                    
						                    
						             html +='<div class="comments">'+
							             		'<div class="commentsCont" id="actTalkCommentBox'+tlkId+'" style="display:none">'+
							             		'<div class="minGrey rightFloat closeRelative"><a href="javascript:void(0)" onclick="closeTalkComment('+tlkId+');">-</a></div>'+	                                  			
					                              '<div class="postTitleSm"><img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /></div>'+
					                              '<div class="postDataSm">'+
					                                '<div class="field">'+
					                                  '<textarea style="width: 90%;" id="txtTalkCommentBox'+tlkId+'" onkeyup="ta_autoresize(this);" rows="2" cols="65"></textarea>'+
					                                '</div>'+
					                                '<div style="display:none;" id="tlkCommMsg'+tlkId+'" class="err">Please type a comment</div>'+
					                                '<div class="butBlueDiv"><a class="butBlue" href="javascript:void(0);" onClick="saveTalkComments('+tlkId+','+grpId+',\'TM\')" linkindex="17"><span>Comment</span></a></div>'+
					                              '</div>'+
					                            '</div><input type="hidden" id="hdnTlk'+tlkId+'" value="0">'+
					                      	'</div>'+
										  '</div>'+
						                '</div>'+
						             '</div>';
						             
						             var bottom = '</div>'; 
						             
						             				                            
						                    
									/*var latestDate = Element.immediateDescendants($('overviewBody'))['0'].immediateDescendants()['0'].innerHTML;
									if(latestDate.strip().toLowerCase() == "today"){
						            	Element.immediateDescendants($('overviewBody'))['1'].className = "postGroup";
						            	Element.immediateDescendants($('overviewBody'))['0'].remove();            	
						            }
						            
						            html = '<div class="threadDate"><span class="threadDateDisplay">Today</span></div>'+html;
						            
						        	$('overviewBody').insert ({
						 				'top'  : html
						  			} );*/   
						  			
						  				

						  			var ov = $('overviewBody').childElements();
									var ovFirst = ov[0].childElements();
									var p = ovFirst[0].innerHTML.strip();
									if(p.toLowerCase() == 'today')
									{
										var obj;
										if(ov[1].firstChild)
										{
											obj = ov[1].firstChild;
											var ovSec = ov[1].childElements();										
											ovSec[0].className = "postGroup";
										}	
										else
											obj = ov[1];										
															
										var newDiv = Builder.node('div', {id:'TL_'+tlkId,onmouseout: 'javascript:$("sidebar").hide()', onmouseover : 'javascript:ovrToggle(this,true,\''+tlkId+'\',\''+nName+'\',\''+np+'\',\'Talk\',\''+grpId+'\',\'\',\'1\')'}).addClassName("postGroupTop").update( html );
										Element.insert( obj, {'before' : newDiv} );
									}
									else
									{
										var newDiv = Builder.node('div', {className : 'threadGroup'} ).update( top + html + bottom );
										Element.insert( $('overviewBody'), {'top' : newDiv} );
										
										var newDivToday = Builder.node('div', {className : 'threadDate'} ).update( '<span class="threadDateDisplay">Today</span>' );
										Element.insert( $('overviewBody'), {'top' : newDivToday} );
									}
									if($('blank'))
						  				$('blank').hide();
						  				
						  			$('overviewBody').show();
						  			new Effect.Highlight(newDiv, { startcolor: startColor,endcolor: endColor ,duration: 1 });
						  			                     						                            
								}
							}
							
							url = '/groups/groups/afterSaveTalkMsg.json';							
							pars = "sessUID="+userID+"&groupid="+grpId+"&parent=0&lastid="+tlkId;							
							new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
										}
									});
						}
		});
				
		//Reset the text area
		$('talkTxtArea').value = "";
		$('talkTxtArea').rows = "2";
	}
	
	function saveTalkComments(parent,grpId,strType){
		var url    = '/groups/groups/saveTalkMessage.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var grpId = grpId;
		var tlkMsg = $('txtTalkCommentBox'+parent).value,
		tlkBoxRow = $('txtTalkCommentBox'+parent).rows;
 		var html;
 		
		if(tlkMsg.strip().blank())
		{
			$('tlkCommMsg'+parent).show();
			$('tlkCommMsg'+parent).innerHTML = "Please type a comment";
			return false;
		}
		else
			$('tlkCommMsg'+parent).hide();
		 
		//var pars   = "sessUID="+userID+"&groupid="+grpId+"&parent="+parent+"&tlkMsg="+tlkMsg+"&rand="+rand;
		var myAjax = new Ajax.Request(url, {method: 'post', 
						parameters: {sessUID:userID, groupid:grpId, parent:parent, tlkMsg:tlkMsg, rand:rand, cat:strType}, 
						onSuccess:function(transport)
						{
							if(transport.responseText == "expired")
							{
								window.location = MainUrl + "main.php?u=signout";return;
							}
							else
							{
								edata = (eval('(' + transport.responseText + ')'));
								strdate = edata.date;
								newid = edata.lastId;
								strCat = edata.cat;
								
								if(edata.isDelete)
								{
									$('tlkCommMsg'+parent).show();
									$('tlkCommMsg'+parent).innerHTML = "Your comment was not posted because this tweet was deleted by its author.";
									$('txtTalkCommentBox'+parent).value = tlkMsg;
									$('txtTalkCommentBox'+parent).rows = tlkBoxRow;
									
									var errDiv = 'tlkCommMsg'+parent;
									setTimeout("$('"+errDiv+"').hide();",5000);
									return false;
								}
								
								
								strFunc = "ovrDelComments(\'"+newid+"\',\'TL\',\'"+parent+"\')";
								strDelElem = '<span id="tkc_'+newid+'" class="butComment" style="float:right;display:none" onmouseover="javascript:this.show();" onmouseout="javascript:this.hide();" onClick="javascript:beforeDelete(this,event,\'TL\',\'1\',\''+ escape(strFunc) + '\')">Delete</span>';																								
								if($('talkComments'+parent))
								{			
									elem = Element.immediateDescendants($('talkComments'+parent))['0'];
									elemDesc = Element.immediateDescendants(elem);
									elemDescLenght = elemDesc.length;
									reqElem = elemDesc[elemDescLenght-1].id;
																		
									
									html = '<div class="commentsCont read gainlayout" id="tlkCmt_'+newid+'" onmouseover="javascript:$(\'tkc_'+newid+'\').show();" onmouseout="javascript:$(\'tkc_'+newid+'\').hide();">'+strDelElem+
						                     '<div class="postTitleSm"><img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /></div>'+
						                      '<div class="postDataSm">'+
						                        '<div class="commentsBy"><a href="javascript:void(0)" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\'];showProfilePage(obj,\'public\',\''+$('SessStrUserID').value+'\');">'+$('username').value+'</a> <span>'+strdate+'</span></div>'+
						                        '<div class="commentsDesc">'+wrapDesc(htmlspecialchars(tlkMsg)).replace(/\n/g, "<br>")+'</div>'+
						                      '</div>'+
						                    '</div>';
						                   
						            $(reqElem).insert ({
										'before'  : html
									} );
									
									arrCntComment = $('cntTalkComments'+parent).innerHTML;
									cntComment = parseInt(arrCntComment.split(' ')['0'])+1;
									$('cntTalkComments'+parent).innerHTML = cntComment+" Comments";
									$('actTalkCommentBox'+parent).hide();
									$('plnTalkCommentBox'+parent).show();
								}
								else
								{			
									html = '<div class="comments"><div class="commentsTotalNo gainlayout">'+
						                      '<div class="rightFloat"><img style="cursor: pointer;" onclick="showHideComments('+parent+',\'T\',this)" id="cntTalkCommentsImg'+parent+'" src="/images/minimize.gif"/></div>'+
						                      '<a href="javascript:void(0)" onclick="showHideComments('+parent+',\'T\',this)" id="cntTalkComments'+parent+'">1 Comment</a>'+
						                   '</div>'+                    
						                   '<div id="talkComments'+parent+'">'+
						                   	'<div>'+
						          				'<div class="commentsCont gainlayout" id="tlkCmt_'+newid+'" onmouseover="javascript:$(\'tkc_'+newid+'\').show();" onmouseout="javascript:$(\'tkc_'+newid+'\').hide();">'+strDelElem+
						                          '<div class="postTitleSm"><img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /></div>'+
						                          '<div class="postDataSm">'+
						                            '<div class="commentsBy"><a href="javascript:void(0)" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\'];showProfilePage(obj,\'public\',\''+$('SessStrUserID').value+'\');">'+$('username').value+'</a> <span>'+strdate+'</span></div>'+
						                            '<div class="commentsDesc">'+wrapDesc(htmlspecialchars(tlkMsg)).replace(/\n/g, "<br>")+'</div>'+
						                          '</div>'+
						                        '</div>'+
						          				'<div id="plnTalkCommentBox'+parent+'" class="commentsCont gainlayout">'+
						          					'<div onclick="toggleTalk('+parent+')" style="border: 1px solid #b5def9; padding: 8px; background: #FAFBFC; width: 90%; font-size: 12pt; font-weight: bold;color:#CBCBCB;  ">Write more comments...</div>'+						                  
						                		'</div>'+                						                            						                            						                                           						                            						                            						                            
						               		'</div>'+
						               		'</div>'+
							              '</div>'+
							           '</div>';
							       
						          
						           $('talkData'+parent).insert ({
										'top'  : html
								   } );
								   
								  // Element.remove($('tlkComentBox'+parent));
								   var c = $('talkData'+parent).childElements();
								
								 /*  var childToAppend = c[1].innerHTML;
								   	$('talkComments'+parent).insert ({
								   		'bottom' : childToAppend
								   	});*/
								   
								   Element.remove(c[1]);
								   
								   $('actTalkCommentBox'+parent).hide();
								   
								   if($('tlkComentBox'+parent))
								   	$('tlkComentBox'+parent).hide();
								   	
								   if($('actTalkCommentBox'+parent))	
								   	$('actTalkCommentBox'+parent).hide();
								}																																																
								
								
								url = '/groups/groups/afterSaveTalkMsg.json';
								pars = "sessUID="+userID+"&groupid="+grpId+"&parent="+parent+"&lastid="+newid+"&cat="+strCat;
								new Ajax.Request(url, {method: 'post', parameters: pars,
											onSuccess:function(transport)
											{
												if(transport.responseText == "expired"){
													window.location = MainUrl + "main.php?u=signout";return;
												}
											}
										});
							}
						}
			});
		
		$('txtTalkCommentBox'+parent).value = "";
		$('txtTalkCommentBox'+parent).rows = "2";
		$('txtTalkCommentBox'+parent).blur();	   
		
		/*making the talk, comments read if they are unread*/
		if($('hdnTlk'+parent).value == 1)
		{
			$('hdnTlk'+parent).value = 0;
			if($('tlkDiv'+parent).className == 'unRead')
			{
				$('tlkDiv'+parent).className = 'read';
			}
			else
			{
				if($('talkComments'+parent)) 
				{
					var e = $('talkComments'+parent).childElements();
					var e1 = e[0].childElements();
					//var e2 = e1[1].childElements();
					var elemCount = e1.length;
					for(i=0;i<elemCount;i++) {
						e1[i].className = e1[i].className.replace('unRead','read');
					}
				}
			}
		}
		
	}
	
	function closeFileComment(parent,grpId) {

		if($('actFlCommentBox'+parent).style.display != 'none'){			
			$('filesCommentBox_'+parent).innerHTML = '';
			$('brwCounter_'+parent).value = '0';
		}
		
		toggleFile(parent);

		/*$('flsCommMsg'+parent).hide();
		if($('flComentBox'+parent)) {
			$('flComentBox'+parent).show();
			$('actFlCommentBox'+parent).hide();
		} else {
			$('plnFlCommentBox'+parent).show();
			$('actFlCommentBox'+parent).hide();
		}*/
	}
	
	function saveOvrFileComments(parent,grpId){
		if(!filesGlobal){
			filesGlobal = true;
			$('saveFileComm'+parent).addClassName('butDisabled');
			var vFBU = new checkFBU( 'FileBrowseAddObj('+parent+','+grpId+')' ); vFBU.validatefiles();
		}

		return false;
	}
	
	function ovrMarkAllAsRead(){
		arrUnRead = $$('div.unRead');
		
		var elemCount = arrUnRead.length;
		for(i=0;i<elemCount;i++){
			arrUnRead[i].className = arrUnRead[i].className.replace('unRead','read'); 
		}
		
		var url    = '/groups/alerts/ovrMarkAll.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var grpId = $('currGrpId').value;		
		var pars   = "sessUID="+userID+"&groupid="+grpId+"&rand="+rand;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
										}
									});

		arrHiddenVar = $$('input.clsHdnVar');
		arrLength = arrHiddenVar.length;
		for(i=0;i<arrLength;i++)
		{ 
			arrHiddenVar[i].value = '0';
		}
	}
	
	function ovrMarkAsRead(type,obid,obj,grpId,hdnelem){
		//reqElem = Element.immediateDescendants($(obj))['0'];
		//reqElem.className = reqElem.className.replace('unRead','read');

		if(hdnelem) $(hdnelem).value = "0";
		if($('hdnFl'+obid)) $('hdnFl'+obid).value = 0;
		
		$('sidebar').hide();
		var value = "";
		var parentThread = "";
		if(type == "Message")
		{
			value = "M";
			parentThread = "div#"+obj+" div.unRead";
		}	
		else if(type == "File")
		{
			value = "F";
			parentThread = "div#"+obj+" div.unRead";
		}	
		else if(type == "Talk")
		{
			value = "TL";
			parentThread = "div#TL_"+obid+" div.unRead";
		}	
		else if(type == "Milestone")
		{
			value = "ML";
			parentThread = "div#mil"+obid+" div.unRead";
		}	
		else if(type == "Todo")
		{
			value = "TD";
			parentThread = "div#taskThread_"+obid+" div.unRead";
		}	
		parentThread = "div#"+obj+" div.unRead";	
		if(value != "" )
		{
			arrChildElems = $$(parentThread);
			//alert(arrChildElems.length+"---"+cmntValue+obid);
			if(arrChildElems.length && arrChildElems.length >0)
			{
				arrLenght = arrChildElems.length
				for(i=0;i<arrLenght;i++)
				{
					arrChildElems[i].className = arrChildElems[i].className.replace('unRead','read');
				}
			}
		}	
		var url    = '/groups/alerts/markAsRead.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);		
		var pars   = "sessUID="+userID+"&groupid="+grpId+"&rand="+rand+"&eventId="+obid+"&val="+value;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
										}
									});

		if(type == "Message")									
			messagesObject.markCommentRead(obid);
	}
	
	function markAsRead(type,oid,grpId)
	{
		var strHdnVar,strObjDiv;
		
		if(type == "tlk")
		{
			strHdnVar = "hdnTlk"+oid;
			strObjDiv = "tlkDiv"+oid;
			
			var url    = '/groups/alerts/markAsRead.json';
			var userID = $('SessStrUserID').value;	
			var rand   = Math.random(9999);		
			var pars   = "sessUID="+userID+"&groupid="+grpId+"&rand="+rand+"&eventId="+oid+"&val=TL";
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
						onSuccess:function(transport)
						{
							if(transport.responseText == "expired"){
								window.location = MainUrl + "main.php?u=signout";return;
							}
						}
					});		
		}
		
		if($(strHdnVar))
		{
			$(strHdnVar).value = "0";
		}
		
		if($(strObjDiv))
		{
			$(strObjDiv).className = $(strObjDiv).className.replace('unRead','read');
		}
	}
	function ovrToggle(self,state,oid,grpname,grplevel,type,grpId,catId,showDel,postedby){
			//self.id = Math.random(9999);						
			if(state){
				var f = findPosition(self);
				/*
				intRight = 	(windowWidth/2)-365;
				if(windowWidth<1280)
					intRight = 295;
				*/
				
				intRight = 	((this.windowWidth - 1280)/2)+275;
				
				if(BrowserDetect.browser == 'Explorer' && BrowserDetect.OS == 'Windows')
				f[1] = f[1] - 200;
				
				if((BrowserDetect.browser == 'Chrome' ||  BrowserDetect.browser == 'Opera') && windowWidth >= 1280){
					intRight = intRight - 7;
				}
				if(windowWidth<=1100)
					intRight = 205;
				else if(windowWidth<=1280)
					intRight = 295;				
				
				var marginTop = f[1]+7 ;
				if(BrowserDetect.browser == 'Explorer'){
					if($('sc4') && $('sc4').style.display != 'none')
						marginTop = marginTop - $('sc4').offsetHeight -20;
					if($('sc2') && $('sc2').style.display != 'none')
						marginTop = marginTop - $('sc2').offsetHeight -20;	
					
					marginTop = marginTop  + 4;			
				}
				
				$('sidebar').setStyle({
					marginTop : marginTop + 'px',
					right: intRight +'px'
				});		
				
				$('groupname').innerHTML = grpname+" "+grplevel;
				
				$('postedby').hide();
				
				var hdnelem = "";
				if(type == "Message")
					hdnelem = "hdnMsg"+oid;
				else if(type == "File")
					hdnelem = "hdnFl"+oid;
				else if(type == "Talk")
					hdnelem = "hdnTlk"+oid;
				else if(type == "Milestone")
				{
					$('postedby').show();
					$('postedby').innerHTML = "by <span class='fontBold'>"+postedby+"</span>";
					hdnelem = "hdnMl"+oid;
				}
				else if(type == "Todo")
				{
					$('postedby').show();
					$('postedby').innerHTML = "by <span class='fontBold'>"+postedby+"</span>";
					hdnelem = "hdnTd"+oid;
				}	
				
				if($(hdnelem).value == "1"){				 					
					$('markAsRead').show();
					$('markAsRead').innerHTML = '<a onclick="javascript:ovrMarkAsRead(\''+type+'\',\''+oid+'\',\''+self.id+'\',\''+grpId+'\',\''+hdnelem+'\');" href="javascript:void(0)">Mark as read</a>';
				}else{								
					$('markAsRead').hide();
				}								
			
				$('sidebar').show();
				
				$('postedGrp').show();
				//For BDAY/Ann popout will be shown only in case of unread thread
				if(catId == "ATM" || catId == "BTM") 
				{
					$('postedGrp').hide();
					if($(hdnelem).value != "1")
						$('sidebar').hide();
				}	
			}else{				
				$('sidebar').hide();
			}
			
			$('sbPipe').hide();
			
			if(!$('sidebarDel').className.match('paddingLeft10'))
				$('sidebarDel').className = $('sidebarDel').className+' paddingLeft10';
				
			if(showDel == "1")
			{
				if($(hdnelem).value == "1")
					$('sbPipe').show();
				else
					$('sidebarDel').className = $('sidebarDel').className.replace('paddingLeft10','');	
					
				$('sidebarDel').show();
				
				var delType = 'TL';
				
				if(type == "Message")
					delType = "DC";
				else if(type == "File")
					delType = "FL";
				else if(type == "Milestone")
					delType = "ML";
				else if(type == "Todo")
				{
					type = "Task";
					delType = "TK";
				}	
				
				strFunc = "ovrDelete(\'"+type+"\',\'"+oid+"\',\'"+self.id+"\')";
				//strFunc = 'ovrDelete('+type+','+oid+','+self.id+')';
					
				$('sidebarDel').innerHTML = '<a class="" href="javascript:void(0);" onclick="javascript:beforeDelete(this,event,\''+delType+'\',\'0\',\''+escape(strFunc)+'\')">Delete</a>';
			}	
			else
				$('sidebarDel').hide();
										
			 	
	}
		
	function ovrDelete(type,oid,elem,milVersion)
	{
		var rand = Math.random(9999);
		var url,hdnDelFlag;
		if(type == "Message")
		{
			url = "/groups/delete/delDiscussion.json";
			hdnDelFlag = 'tlkFlag';
		}	
		else if(type == "File")
		{
			url = "/groups/delete/delFiles.json";
			hdnDelFlag = 'tlkFlag';
		}	
		else if(type == "Talk")
		{
			url = "/groups/delete/delTalk.json";
			hdnDelFlag = 'tlkFlag';
		}	
		else if(type == "Milestone" || type == "MilestoneCal" || type == "MilestoneRHS")
		{
			url = "/groups/delete/delMilestone.json";
			hdnDelFlag = 'milestoneFlag';
		}	
		else if(type == "Task" || type == "TaskCal" || type == "TaskRHS")
		{
			url = "/groups/delete/delTask.json";
			hdnDelFlag = 'taskFlag';
		}
		else if(type == "TaskList" || type == "TaskListCal" || type == "TaskListRHS")
		{
			url = "/groups/delete/delTaskLists.json";
			hdnDelFlag = 'taskFlag';
		}	
		
		var delFlag = "0";	
		if($('chkDel').checked)
		{
			delFlag = "1";
			$(hdnDelFlag).value = "0";
		}
		
		var ovrOnlyElem = false;
		/*** Remove the object dynamically **/
		
		if($('currPageCat') && $('currPageCat').value == 'ovr')
		{
			/*
			if(type == "Task" || type == "Milestone")
			{
				dashboardTMrhs($('selectedGrpId').value,$('~sec^Log@Ses~').value);
			}
			*/ 
			/*Show Blank state if the deleted element was the last one onthe page*/
			var strClsFormat = 'div#overviewBody div.postGroupTop';
			var strClsFormat1 = 'div#overviewBody div.postGroup';
			var divElems = $$(strClsFormat);
			var divElems1 = $$(strClsFormat1);
			
			if(divElems.length == 1 && divElems1.length == 0)
			{
				
				
				ovrOnlyElem = true;
			}
		}
		
		if(type == "MilestoneCal"){
			$('milestoneNTodos').hide();
			parent.intCalendarEdit = "1";
		}
		else if(type == "TaskListCal"){
			if($('todolistDiv'+oid).className.match('postGroupTop')){
				var succElem = Element.next($('todolistDiv'+oid));
				if(succElem)
					succElem.className = succElem.className.replace('postGroup','postGroupTop');
			}
			
			Element.remove($('todolistDiv'+oid));
			parent.intCalendarEdit = "1";
		}
		else if(type == "TaskCal"){
			parent.intCalendarEdit = "1";
			
			var todos = $$("div#"+ $('todoRow'+oid).parentNode.id + " div.mGDetailRow");
			var todosNum = todos.length;
			
			if(todosNum == 1){
				var pid = $('todoRow'+oid).parentNode.id.substring(8,$('todoRow'+oid).parentNode.id.length);
				if($('todolistDiv'+pid).className.match('postGroupTop')){
					var succElem = Element.next($('todolistDiv'+pid));
					if(succElem)
						succElem.className = succElem.className.replace('postGroup','postGroupTop');
				}
			
				Element.remove($('todolistDiv'+pid));
			}
			else {
				if($('todoRow'+oid).next() && $('todoRow'+oid).next().id == ''){
					Element.remove($('todoRow'+oid).next());
				}
				else if($('todoRow'+oid).previous() && $('todoRow'+oid).previous().id == ''){
					Element.remove($('todoRow'+oid).previous());
				}
			}
			
		}else if(type == "MilestoneRHS"){
			var succElem = Element.next($('RHSMilId'+oid)); //task list div
			if(succElem && Element.childElements(succElem).length == 0){ //if there is no task then remove parent node
				Element.remove($('RHSMilId'+oid).parentNode);
			}
			else{
				Element.remove($('RHSMilId'+oid));
			}
		}
		else if(type == "TaskRHS"){
			
			if($('RHSTask'+oid).next() && $('RHSTask'+oid).next().id == ''){
				Element.remove($('RHSTask'+oid).next());
			}
			else if($('RHSTask'+oid).previous() && $('RHSTask'+oid).previous().id == ''){
				Element.remove($('RHSTask'+oid).previous());
			}
			Element.remove($('RHSTask'+oid));
			
		}
		else if(type == "TaskListRHS"){
			if($('myATodolist'+oid)){
				if($('myATodolist'+oid).className.match('milestoneGroup')){
					var succElem = Element.next($('myATodolist'+oid));
					if(succElem)
						succElem.className = succElem.className.replace('milestoneGroup1','milestoneGroup');
			 		
			 	}
			 	Element.remove($('myATodolist'+oid));	
			}
			
			if($('myTodolist'+oid)){
				if($('myTodolist'+oid).className.match('milestoneGroup')){
					var succElem = Element.next($('myTodolist'+oid));
					if(succElem)
						succElem.className = succElem.className.replace('milestoneGroup1','milestoneGroup');
			 		
			 	}
			 	Element.remove($('myTodolist'+oid));	
			}
		}
		
		if(type == "MilestoneRHS" || type == "TaskRHS"){ //delete from LHS dashboard
			var elemId = '';
			if(type == "MilestoneRHS")
				elemId = 'mil'+milVersion;
			else
				elemId = 'taskThread_'+oid;
					
			if($(elemId) && $(elemId).className.match('postGroupTop'))
			{
				/*First element of the thread group is being deleted and hence the classname of succeeding element has to be changed */
				succElem = Element.next($(elemId));
				if(succElem)
					succElem.className = succElem.className.replace('postGroup','postGroupTop');
				else
				{
					/* Remove the Date if its a only element */				
					ancestor = Element.ancestors($(elemId))['0'];
					ancestorSibling = Element.previousSiblings(ancestor)['0'];	
					Element.remove(ancestor);
					Element.remove(ancestorSibling);	
				}
			}
			if($(elemId)){ 
				Element.remove($(elemId));
			}
			
		}
		
    	if($(elem) && $(elem).className.match('postGroupTop'))
		{
			
			/*First element of the thread group is being deleted and hence the classname of succeeding element has to be changed */
			succElem = Element.next($(elem));
			if(succElem)
				succElem.className = succElem.className.replace('postGroup','postGroupTop');
			else
			{
				/* Remove the Date if its a only element */				
				ancestor = Element.ancestors($(elem))['0'];
				if(type == "Message" && $('currPageCat').value == 'msg')
				{
					Element.remove(ancestor.parentNode);
				}
				else if(type == "Task" && $('currPageCat').value == 'td'){
					//no idea now
				}
				else 
				{
					ancestorSibling = Element.previousSiblings(ancestor)['0'];	
					Element.remove(ancestor);
					Element.remove(ancestorSibling);
				}	
			}
		}
	
		if($('sidebar'))$('sidebar').hide();
		if($(elem)){ 
			Element.remove($(elem));
		}
		//setTimeout("if($('"+elem+"'))Element.remove($('"+elem+"'));",1000);
							
		
		var myAjax = new Ajax.Request( url, {method: "post", parameters: {rand:rand,oid:oid,delFlag:delFlag},onSuccess:
								function(transport)
								{
									if(transport.responseText == "expired")
							        {      
							        	window.location = MainUrl + "main.php?u=signout";return;
							        }
							        else
							        {
							        	if(transport.responseText != "")
							        	{
							        		if($('currPageCat') && $('currPageCat').value == 'ovr')
											{
												if(type == "Task" || type == "Milestone")
												{
													dashboardTMrhs($('selectedGrpId').value,$('~sec^Log@Ses~').value);
												}
											}
												
							        		if(type == "Milestone" || type == "MilestoneCal" || type == "MilestoneRHS")
							        		{
							        			var data = transport.responseText.split('#%#');
							        			getNewCCD(data['0'].strip(), '', data['1']);
							        			if(window.milestoneInfoPopupStatus)
							        				window.milestoneInfoPopupStatus = 0;	
							        		}
							        		else if(type == "TaskListRHS" || type == "TaskList" || type == "TaskListCal"){ 
							        			var data = transport.responseText.split('#%#');
							        			
							        			if(data.length > 1){ //remove TaskIcon from calendar
 							        				refreshTaskIconCalendar(data[1],data[2],data[3],'remove');
							        			}
							        			
							        			if(type == "TaskListRHS"){ //delete todos from LHS dashboard if a task list is deleted
								        			var data = data[0].split(',');
								        			var todosLen = data.length;
								        			for(var i=0;i< todosLen; i++){
								        				if(data[i] != ''){
								        					var elemId = 'taskThread_'+data[i].strip();
								        					if($(elemId) && $(elemId).className.match('postGroupTop'))
															{
																/*First element of the thread group is being deleted and hence the classname of succeeding element has to be changed */
																succElem = Element.next($(elemId));
																if(succElem)
																	succElem.className = succElem.className.replace('postGroup','postGroupTop');
																else
																{
																	/* Remove the Date if its a only element */				
																	ancestor = Element.ancestors($(elemId))['0'];
																	ancestorSibling = Element.previousSiblings(ancestor)['0'];	
																	Element.remove(ancestor);
																	Element.remove(ancestorSibling);	
																}
															}
															if($(elemId)){ 
																Element.remove($(elemId));
															}
								        				
								        				}
							        				}
							        			}
							        		}
							        	}
							        	
							        	if(ovrOnlyElem)
							        	{
							        		paginateData('/groups/groups/view.json','1');
											showLoader();
											$('currPageCat').value = 'ovr';
							        	}
							        	
							        	if(type == "File" && $('fileItems') && $('currPageCat').value == 'fls')
										{
											if(Element.childElements($('fileItems')).length == 0)
											{
												loadFiles('F',0,0,1);	
								    		}
										}
										
										if(type == "Message" && $('messageItems') && $('currPageCat').value == 'msg')
										{
											if($$('div.threadDate').length == 0)
											{
												setSecondHeader('messNavigation');
												messagesObject.callMessages();
											}
										}
										
										if((type == "Task" || type == "TaskList") && $('currPageCat').value == 'td'){
											tasksObject.loadRHS();
											if($$('div.taskTitle').length == 1 && $$('div.postGroupTop').length == 0){
												setSecondHeader('tksNavigation');
												tasksObject.callTasks()
											}
										}
										
										if(type == "Milestone" && $('currPageCat').value == 'mlt'){
											tasksObject.loadRHS();
											if($$('div.threadDate').length == 0)
											{
												loadMilestoneSec($('milPageType').value);
											}
										}
							        }
								}
										
						} );			
	}
	
	function delMsgComment(oid,parent,grpId){
		var rand = Math.random(9999);
		var url = "/groups/delete/delDiscussion.json";
		
		var delFlag = "0";	
		if($('chkDel').checked)
		{
			delFlag = "1";
			$('tlkFlag').value = "0";
		}	
		
		var myAjax = new Ajax.Request( url, {method: "post", parameters: {rand:rand,oid:oid,parent:parent,delFlag:delFlag},onSuccess:
				function(transport)
				{
					if(transport.responseText == "expired")
					{      
						window.location = MainUrl + "main.php?u=signout";return;
					}
					else //Remove element
					{	
						var num = $('hdnMsgCommCount'+parent).value;
						if(num > 1){
							num--;
							$('hdnMsgCommCount'+parent).value = num;
							var str = "comment";
							if(num > 1) str = "comments";
							$('commentCount'+parent).innerHTML = num + " " + str;
							if($('showMoreComment_'+parent) && ($('Msgcomment_'+oid).parentNode.id == 'showMoreComment_'+parent)){
								var hiddElems = Element.immediateDescendants($('showMoreComment_'+parent)).length;
								if(hiddElems > 1){
									hiddElems--;
									str = "comment";
									if(hiddElems > 1) str = "comments";
									$('midMsgComments'+parent).innerHTML = " " +  hiddElems + " more " + str;
								}
								else{
									var previousSibling = Element.previousSiblings($('showMoreComment_'+parent))['0'];
									Element.remove(previousSibling);
									Element.remove($('showMoreComment_'+parent));
								}
								
							}
							
						}
						else {
							var html;
							
							if($('currPageCat').value == 'ovr')
							{
								html = '<div class="butCommentDiv" id="butMessageComment_'+parent+'">'
	                            		+'<a onclick="messagesObject.openOverMessageComment('+parent+', '+$('category').value+','+grpId+')" href="javascript:void(0);" class="butComment">Comment</a>'
	                           		 + '</div>';
							}
							else
							{
								html = '<div class="butCommentDiv" id="butMessageComment_'+parent+'">'
	                            		+'<a onclick="messagesObject.openMessageComment('+parent+', '+$('category').value+')" href="javascript:void(0);" class="butComment">Comment</a>'
	                           		 + '</div>';		
							}
							
							$('hdnMsgCommCount'+parent).value = 0;
							var previousSibling = Element.previousSiblings($('allComment_'+parent))['0'];
							Element.remove(previousSibling);
							Element.insert( $('comment_'+parent), {'before' : html} );
							$('TA1_'+parent).hide();
							$('comment_'+parent).hide();		  	 	        	
						}
							 
						if($('Msgcomment_'+oid)){
							Element.remove($('Msgcomment_'+oid));
						}	
										 
					}
				}								
		} );
					
	}
	
	function delFileComment(oid,parent){
		var rand = Math.random(9999);
		var url = "/groups/delete/delFiles.json";
		
		var delFlag = "0";	
		if($('chkDel').checked)
		{
			delFlag = "1";
			$('tlkFlag').value = "0";
		}
		
		var myAjax = new Ajax.Request( url, {method: "post", parameters: {rand:rand,oid:oid,parent:parent,delFlag:delFlag},onSuccess:
				function(transport)
				{
					if(transport.responseText == "expired")
					{      
						window.location = MainUrl + "main.php?u=signout";return;
					}
					else //Remove element
					{	
						var num = $('hdnFileCommCount'+parent).value;
						if(num > 1)
						{
							num--;
							$('hdnFileCommCount'+parent).value = num;
							var str = "comment";
							if(num > 1) str = "comments";
							$('cntFileComments'+parent).innerHTML = num + " " + str;
							
							if($('midFileComment'+parent) && ($('comment_'+oid).parentNode.id == 'midFileComment'+parent)){
								var hiddElems = Element.immediateDescendants($('midFileComment'+parent)).length;
								if(hiddElems > 1){
									hiddElems--;
									str = "comment";
									if(hiddElems > 1) str = "comments";
									$('midNumComm'+parent).innerHTML = " " +  hiddElems + " more " + str;
								}
								else{
									var previousSibling = Element.previousSiblings($('midFileComment'+parent))['0'];
									Element.remove(previousSibling);
									Element.remove($('midFileComment'+parent));
								}
								
							}
							
							if($('comment_'+oid))
								Element.remove($('comment_'+oid));
								
						}	
						else 
						{
							        		
							  var commentHTML = $('actFlCommentBox'+parent).innerHTML;
							        		
							  var html = '<div id="flComentBox'+parent+'" class="butCommentDiv"><a class="butComment" href="javascript:void(0)" onclick="toggleFile('+parent+')">Comment</a>'
										+ '</div>'
										+ '<input type="hidden" id="hdnFl'+parent+'" value="0"/>'
										+ '<input type="hidden" id="hdnFileCommCount'+parent+'" value="0"/>'
										+ '<div class="comments" id="fileDataComm'+parent+'">'
										+	 '<div class="commentsCont" id="actFlCommentBox'+parent+'" style="display: none;">'
													 
							        	+     commentHTML
							        	+   '</div>'
							        	+ '</div>';
							       	
							 /* var html = '<div id="flComentBox'+parent+'" style="padding-bottom:15px" class="butCommentDiv"><a class="butComment" href="javascript:void(0)" onclick="toggleFile('+parent+')">Comment</a>'
										+ '</div>'
										+ '<input type="hidden" id="hdnFl'+parent+'" value="0"/>'
										+ '<input type="hidden" id="hdnFileCommCount'+parent+'" value="0"/>'
										+	 '<div class="commentsCont" id="actFlCommentBox'+parent+'" style="display: none;">'
													 
							        	+     commentHTML
							        	+ '</div>'; 
							   */     	     	
							  $('fileData'+parent).innerHTML = html;		 
							        	
					   }		 
					}
				}								
		} );
	
	}
	
	function ovrDelComments(oid,type,par)
	{
		
		var rand = Math.random(9999);
		var url,hdnDelFlag;
		if(type == "DC")
		{
			url = "/groups/delete/delDiscussion.json";
			hdnDelFlag = 'tlkFlag';
		}	
		else if(type == "FL")
		{
			url = "/groups/delete/delFiles.json";
			hdnDelFlag = 'tlkFlag';
		}	
		else if(type == "TL")
		{
			url = "/groups/delete/delTalk.json";
			hdnDelFlag = 'tlkFlag';
		}	
		else if(type == "Milestone")
		{
			url = "/groups/delete/delMilestone.json";
			hdnDelFlag = 'milestoneFlag';
		}	
		else if(type == "Todo")
		{
			url = "/groups/delete/delTask.json";
			hdnDelFlag = 'taskFlag';
		}	
		
		var delFlag = "0";	
		if($('chkDel').checked)
		{
			delFlag = "1";
			$(hdnDelFlag).value = "0";
		}			
		
		var myAjax = new Ajax.Request( url, {method: "post", parameters: {rand:rand,oid:oid,delFlag:delFlag,parent:par},onSuccess:
								function(transport)
								{
									if(transport.responseText == "expired")
							        {      
							        	window.location = MainUrl + "main.php?u=signout";return;
							        }
							        else //Remove element
							        {
							        	
							        	
							        	
							        	var objElem,objCommentCnt,objCommentBox,objCommentButton,objMidCommentBox,objMidCommentCnt,objMidCommentStrip,objActCommentBox,strFunc;
										if(type == "TL")
										{
											objElem = "tlkCmt_" + oid;
											objCommentCnt = "cntTalkComments" + par;
											objCommentBox = "talkData" + par;
											objCommentButton = "tlkComentBox" + par;
											objMidCommentBox = "midTalkComment" + par;
											objMidCommentCnt = "midTalkCmtCnt" + par;
											objMidCommentStrip = "midTalkStrip" + par;
											objActCommentBox = "actTalkCommentBox" + par;
											strFunc = "toggleTalk";
										}
										else if(type == "FL")
										{
											objElem = "flsCmt_" + oid;
											objCommentCnt = "cntFileComments" + par;
											objCommentBox = "fileData" + par;
											objCommentButton = "flComentBox" + par;
											objMidCommentBox = "midFileComment" + par;
											objMidCommentCnt = "midFileCmtCnt" + par;
											objMidCommentStrip = "midFileStrip" + par;
											objActCommentBox = "actFlCommentBox" + par;
											strFunc = "toggleFile";
										}
										else if(type == "DC")
										{
											objElem = "dCmt_" + oid;
											objCommentCnt = "commentCount" + par;
											objCommentBox = "msgCommentThread" + par;
											objCommentButton = "butMessageComment_" + par;
											objMidCommentBox = "showMoreComment_" + par;
											objMidCommentCnt = "midMsgCmtCnt" + par;
											objMidCommentStrip = "moreComment_" + par;
										}	
										
										var arrCntComment = $(objCommentCnt).innerHTML;
										var cntComment = parseInt(arrCntComment.split(' ')['0'])-1;
										
										if(cntComment == 0)
										{	
											plnBox = $(objActCommentBox).innerHTML;
																															
											var cmtBox = Element.immediateDescendants($(objCommentBox));
											Element.remove(cmtBox['0']);
											/*
											html = '<div style="display:none" class="butCommentDiv" id="'+objCommentButton+'"><a class="butComment" href="javascript:void(0);" onClick="'+strFunc+'('+par+');">Comment</a></div>'+
				                            '<div class="comments">'+
				                              '<div class="commentsCont" id="'+objActCommentBox+'" style="display:none">'+
				                               plnBox+
				                              '</div>'+
				                            '</div>';
				                            */
				                            html = '<div style="display:none;padding-bottom:15px;" class="butCommentDiv" id="'+objCommentButton+'"><a class="butComment" href="javascript:void(0);" onClick="'+strFunc+'('+par+');">Comment</a></div>'+
				                              '<div class="commentsCont" id="'+objActCommentBox+'" style="display:none;">'+
				                               plnBox+
				                            '</div>';
				                            
											$(objCommentBox).insert({
												'top' : html
											});
												
											$(objCommentButton).show();
										}
										else if(cntComment == 1)
										{
											$(objCommentCnt).innerHTML = "1 Comment";
										}
										else
										{
											$(objCommentCnt).innerHTML = cntComment + " Comments";
										}
												
										if($(objElem))
											Element.remove($(objElem));
										
										if($(objMidCommentBox))
										{
											var midComments = $(objMidCommentBox).childElements().length;
											if(midComments == 0)
											{
												$(objMidCommentStrip).hide();
											}
											else
											{
												$(objMidCommentCnt).innerHTML = midComments;
											}
										}
							        	
							        }
								}
										
		} );
						
	}
	
	function beforeDeleteCal(obj,event,type,isComment,func)
	{
		var delMsg = "All comments and files in this thread will also be deleted. Are you sure you want to delete this?";
		func = unescape(func);
		var strHdnFlag;
		
		if(type == "ML")//milestone from calendar
		{
			strHdnFlag = parent.window.frames["mainframe"].document.getElementById('milestoneFlag').value;
			delMsg = "Are you sure you want to delete this? All the associated task lists would lose their linkage with this milestone.All comments and files in this thread will also be deleted.";
		}	
		else if(type == "TK")
			strHdnFlag = parent.window.frames["mainframe"].document.getElementById('taskFlag').value;
		else if(type == "TKL")
		{
			strHdnFlag = parent.window.frames["mainframe"].document.getElementById('taskFlag').value;
			delMsg = "Are you sure you want to delete this? All the associated tasks would also get deleted.";
		}
		
		if(strHdnFlag == "0")
		{
			eval(func);
		}
		else
		{	
			$('chkDelType').value = type;	
			
			if(isComment == "1")
				delMsg = "Are you sure you want to delete this?";
				
			$('spnDelMsg').innerHTML = delMsg;	
			//get the popup center positioned
			centerPos($('deletePopup'),1);
			$('deletePopup').style.display='block';
			$('delFn').value = func;
		}	
	
	}
	
	function beforeDelete(obj,event,type,isComment,func)
	{
		var delMsg = "All comments and files in this thread will also be deleted. Are you sure you want to delete this?";
		func = unescape(func);
		var strHdnFlag;
		if(type == "TL" || type == "FL" || type == "DC")
			strHdnFlag = $('tlkFlag').value;
		else if(type == "ML")
		{
			strHdnFlag = $('milestoneFlag').value;
			delMsg = "Are you sure you want to delete this? All the associated task lists would lose their linkage with this milestone.All comments and files in this thread will also be deleted.";
		}	
		else if(type == "TK")
			strHdnFlag = $('taskFlag').value;
		else if(type == "TKL")
		{
			strHdnFlag = $('taskFlag').value;
			delMsg = "Are you sure you want to delete this? All the associated tasks would also get deleted.";
		}		
								
	
		if(strHdnFlag == "0")
		{
			eval(func);
		}
		else
		{
			$('chkDelType').value = type;	
				
			if(isComment == "1")
				delMsg = "Are you sure you want to delete this?";
				
			$('spnDelMsg').innerHTML = delMsg;
					
			//get the popup center positioned
			centerPos($('deletePopup'),1);
			
			$('deletePopup').show();
			$('delFn').value = func;
		}	
	}
	
	function paginationToggle(type)
	{
		if($('pgnFirst'))
		{
			obj = Element.immediateDescendants($('pgnFirst'))['0'];

			//if(obj.src.match('/images/spacer.gif'))
			if(type == 'S')
			{
				obj.src = "/images/paginationFirst.gif";
			}
			else
			{
				obj.src = "/images/spacer.gif";
			}
											
		}
		
		if($('pgnLast'))
		{
			obj = Element.immediateDescendants($('pgnLast'))['0'];
			//if(obj.src.match('/images/spacer.gif'))
			if(type == 'S')
			{
				obj.src = "/images/paginationLast.gif";
			}
			else
			{
				obj.src = "/images/spacer.gif";
			}
						
		}
	}
	var vcardFlag = 0;
	function showVcardslowly(userid){
		
		arrVcard = $$('div.vcardDummyClass');
		arrlen = arrVcard.length;
		for(i=0;i<arrlen;i++)
		{
			if(arrVcard[i].style.display != 'none')//if any vcard is already open
			{
				arrVcard[i].hide();//hide that vcard
			}
		}
		if(vcardFlag == '0'){
		$('vcard'+userid).show();		
		}
		
	}
	function showVCard(userid,self,event)
	{		
		vcardFlag = 0;
		/*
		arrUnRead = $$('div.clsVCard');
	
		var elemCount = arrUnRead.length;
		for(i=0;i<elemCount;i++)
		{
			arrUnRead[i].hide(); 
		}
		*/
		arrVcard = $$('div.vcardDummyClass');
		arrlen = arrVcard.length;
		for(i=0;i<arrlen;i++)
		{
			if(arrVcard[i].style.display != 'none')//if any vcard is already open
			{
				arrVcard[i].hide();//hide that vcard
			}
		}		
		var intLeft = 0;
		var intTop = 0;
		var f = 0;
		var flag = 0;
		timeoutId = setTimeout("",1000);
		if($('vcard'+userid))
		{
			//$('vcard'+userid).show();
			var f = findPosVcard(self, event);			
			intLeft = parseInt(f[0]);
			intTop = parseInt(f[1]);				
			$('vcard'+userid).setStyle({
				top : intTop + 'px',
				left : intLeft + 'px'
			});
			//$('vcard'+userid).show();
			timeoutId = setTimeout("showVcardslowly('"+userid+"');",1000);
		}
		else
		{		
			var url    = '/groups/groups/getVCard.json';
			var userID = $('SessStrUserID').value;	
			var rand   = Math.random(9999);
			
			var pars   = "sessUID="+userID+"&rand="+rand+"&userid="+userid;
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
											onSuccess:function(transport)
											{
												if(transport.responseText == "expired")
												{
													window.location = MainUrl + "main.php?u=signout";return;
												}
												else
												{
													$('header').insert ({
						         						'top'  : transport.responseText
						  							} );
						  							
						  							//while(flag == 0)
													//{
														var f = findPosVcard(self, event);			
														//if(f[0] != '')
														//{
															flag = 1;
														//}																	
													//}
						  							if(flag == 1)
						  							{
						  								intLeft = parseInt(f[0]);
														intTop = parseInt(f[1]);				
														$('vcard'+userid).setStyle({
															top : intTop + 'px',
															left : intLeft + 'px'
														});
														
														//setTimeout("showVcardslowly('"+userid+"');",1000);
														//$('vcard'+userid).show();
														//showVCard(userid,self,event);
														if(BrowserDetect.browser != "Explorer"){
															clearTimeout(timeoutId);
															timeoutId = setTimeout("showVcardslowly('"+userid+"');",1500);
														}
														
													}
												}
											}
										});
		}									
	}
	
	function hideVCard(userid)
	{
		vcardFlag = 1;
		clearTimeout(timeoutId);
		arrVcard = $$('div.vcardDummyClass');
		arrlen = arrVcard.length;
		for(i=0;i<arrlen;i++)
		{
			if(arrVcard[i].style.display != 'none')//if any vcard is already open
			{
				arrVcard[i].hide();//hide that vcard
			}
		}
		if($('vcard'+userid))
			$('vcard'+userid).hide();
	}
	
	function loadFiles(section,catId,order,pageNum){
		$('currPageCat').value = "fls";
		showLoader();
		var url    = '/groups/files/viewGroups.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var grpId = $('currGrpId').value;		
	
		/*new Ajax.Request('/groups/files/test.json',{method:'post'});*/
			
		new Ajax.Updater('maincontent', url, 
		{
  			parameters: {sessUID:userID,groupid:grpId,rand:rand,section:section,catId:catId,order:order,pageNum:pageNum},
  			onComplete:function(transport)
			{
				if(transport.responseText == "expired"){
					window.location = MainUrl + "main.php?u=signout"; return;
				} else {
					/*
					if(typeof uF == 'object') {
						for(var i=0; i<uF.fileIDS.length; i++) {
							new Effect.Highlight($('file_'+ uF.fileIDS[i]), { startcolor: startColor,endcolor: endColor ,duration: 1 });}
					}
					*/ 
				}
			}  			
		});
		
	}
	
	function loadMsgFiles(section,catId,order,pageNum){
		$('currPageCat').value = "flsmsg";
		var url    = '/groups/files/getMsgFiles.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var grpId = $('currGrpId').value;		
		
		new Ajax.Updater('maincontent', url, 
		{
  			parameters: {sessUID:userID,groupid:grpId,rand:rand,section:section,catId:catId,order:order,pageNum:pageNum},
  			onSuccess:function(transport)
			{
				if(transport.responseText == "expired"){
					window.location = MainUrl + "main.php?u=signout";return;
				}
			}  			
		});
	}
	
	function flsMarkAllAsRead(){				
		var url    = '/groups/alerts/flsMarkAll.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);
		var grpId = $('currGrpId').value;		
		var pars   = "sessUID="+userID+"&groupid="+grpId+"&rand="+rand;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired"){
												window.location = MainUrl + "main.php?u=signout";return;
											}
											
											var arrHiddenVar = $$('input.clsHdnVar');
											var arrLength = arrHiddenVar.length;
											for(i=0;i<arrLength;i++)
											{ 
												arrHiddenVar[i].value = '0';
											}
											
											arrUnRead = $$('div.unRead');
		
											var elemCount = arrUnRead.length;
											for(i=0;i<elemCount;i++){
												arrUnRead[i].className = arrUnRead[i].className.replace('unRead','read'); 
											}
										}
									});
	}
	
	function showFlsCommentBox(objId,type){		
		if(type=="2")
			$('plnFlCommentBox'+objId).hide();
		/*else
			$('flComentBox'+objId).hide();*/
			
		$('actFlCommentBox'+objId).show();
	}
	
	function flsToggle(obj,state,flId,showDel){
			if(state){
				readUnread = $('hdnFl'+flId).value;
				if(readUnread == "1" || showDel == "1"){
					//obj.id = Math.random(9999);
					grpId = $('currGrpId').value;
					var f = findPosition(obj);
					/*
					intRight = 	(windowWidth/2)-365;
					if(windowWidth<1280)
						intRight = 295;
					*/
					if(BrowserDetect.browser == 'Explorer' && BrowserDetect.OS == 'Windows')
					f[1] = f[1] - 200;
					
					intRight = 	((windowWidth - 1280)/2)+275;
					if((BrowserDetect.browser == 'Chrome' ||  BrowserDetect.browser == 'Opera') && windowWidth > 1280){
						intRight = intRight - 7;
					}
					if(windowWidth<=1100)
						intRight = 205;
					else if(windowWidth <= 1280)
						intRight = 295;						
					$('sidebar').setStyle({
						marginTop : f[1] + 'px',
						right: intRight + 'px'
					});
					$('sidebarDel').hide();
					$('markAsRead').hide();
				}
				if(readUnread == "1")
				{	
					$('markAsRead').innerHTML = '<a onclick="javascript:ovrMarkAsRead(\'File\',\''+flId+'\',\''+obj.id+'\',\''+grpId+'\');" href="javascript:void(0)">Mark as read</a>';																				
					$('markAsRead').show();
				}
				$('sbPipe').hide();
				if(showDel == "1")
				{	
					var elemId = 'parFile_'+flId;
					var func = escape("ovrDelete(\'File\',\'"+flId+"\',\'"+elemId+"\')");
					$('sidebarDel').innerHTML = '<a href="javascript:void(0)" onclick="beforeDelete(this,event,\'FL\',\'0\',\''+func+'\');">Delete</a>';
					$('sidebarDel').show();
					
					if(readUnread == "1")
						$('sbPipe').show();
				}
				if(readUnread == "1" || showDel == "1")
					$('sidebar').show();
				}
			else
				$('sidebarDel').hide();	
	}
	
	function showIndividualFile(fileId){
		showLoader();
		$('currPageCat').value = "flsdld";
		var userID = $('SessStrUserID').value;
		new Ajax.Updater('maincontent', '/groups/files/getIndividualFiles.json', {
  			parameters: { fileId:fileId,sessUID:userID,groupid:$('currGrpId').value}  		
		});
	}
	
	function loadFilesSec(obj,type,catID)
	{
		if(type=="M")
		{
			loadMsgFiles('M',catID,0,1);
		}
		else
		{
			loadFiles('F',catID,0,1);
		}
				
		elems = $$('li.curr');
		elemLength = elems.length
		for(i=0;i<elemLength;i++)
		{
			elems[i].className = "";
		}
		if(Element.ancestors(obj)['0'])
		Element.ancestors(obj)['0'].className="curr";
	}		
	
	function loadMilestoneSec(type)
	{
		$('currMileTab_O').className = '';
		$('currMileTab_U').className = '';
		$('currMileTab_C').className = '';
					
		var id = "currMileTab_" + type;
		$(id).className = 'curr';
		milestonesObject.callMilestones(type,'0');
	}		
	
	var prjType1;
	var parentId1;
	var parentLevel1;
	function createGroup()
	{
		var prjType = 'G';
		var parentId,parentLevel;
		if($('currGrpId').value == "ovr")
		{
			parentId = $('networkId').value;
			parentLevel = '1';
		}
		else
		{
			parentId = $('currGrpId').value;
			parentLevel = $('currLevel').value;
		}
		
		if($('currLevel').value == "2")
			prjType = 'SG';
		
		if($('currLevel').value == "ovr")
		{
			$('groupMenu').hide();
			if($('projectSelect'))
			{
				$('projectSelect').show();
				$('projectSelectFieldSpacer').show();
			}
		}
		else //Create sub-project
		{
			$('groupMenu').hide();
			if($('projectSelect')){
				$('projectSelect').hide();
				$('projectSelectFieldSpacer').hide();
			}
		}

		try{
			group = new groupInfo();
			group.initializeAll(prjType,parentId,parentLevel,$('addGrpUserCount').value,$('otherGrpUserCount').value);
			group.createSubGroupStepOne();
			group.getClients();
		}
		catch(err){
			
			prjType1 = prjType;
			parentId1 = parentId;
			parentLevel1 = parentLevel;
					
			LazyLoad.load([
			  '/groups/js/groups.js',
			], function() {
				//alert('this is only a test mode alert .. will be removed in the production. Ignore it'); 
				group = new groupInfo();
				group.initializeAll(prjType1, parentId1, parentLevel1, $('addGrpUserCount').value, $('otherGrpUserCount').value);
				group.createSubGroupStepOne();
				group.getClients();
			});
		}
	}
	
	function showProfilePage(obj,type,userid)
	{
		showLoader();
		var url = "/groups/groups/profile.json";
		if(userid != "")
			url = "/groups/groups/profile/"+userid+"/inner";
		$('currPageCat').value = "prf";
		$('profileLink').className = '';    		
    	$('navProfile').style.display = 'none';
    	var obj = $('profileTab');
		this.setHeader(obj,'ovr',type)
		
		if(type == 'profile' || type == 'eprofile')setSecondHeader('EPNavigation');
		if(type == 'public')setSecondHeader('PPNavigation');
		if(type == 'settings')setSecondHeader('MPNavigation');
		
		new Ajax.Updater('maincontent', url, {
  			parameters: { page: type},
  			onSuccess: function(){$('maincontent').show();}
		});
	}
	
	function selectParentGroup(obj,name,selIds,selVals,mainDiv)
	{
	
		var parentId = obj;
		var networkId = $('networkId').value;
		var type,parentGroupLevel;
		if(parentId == networkId)
		{
			type='G';
			parentGroupLevel='1';
		}
		else
		{
			type='SG';
			parentGroupLevel='2'
		}
		document.getElementById(selIds).value = obj;
		document.getElementById(selVals).value = name;
		document.getElementById(mainDiv).style.display = 'none';
		
		group.initializeForm(type, parentId, parentGroupLevel);
		group.getDataFromDB();
	}
	
	function selectTalkGroup(obj,name,selIds,selVals,mainDiv)
	{
		objPeople.hideError();
		document.getElementById('talkGrpId').value = obj;
		document.getElementById(selVals).value = name;
		document.getElementById(selIds).value = obj;
		document.getElementById(mainDiv).style.display = 'none';

		objPeople.checkPeople(obj);

		if($('createCategory').style.display != 'none') {
			$('createCategory').hide();
			$('strCategory').value = '';
		}

		if(obj != $('networkId').value) {
			$('inviteUsers').show();
			$('otherGroupserDiv').show();
			$('invite_email').hide();
		} else {
			$('inviteUsers').hide();
			$('otherGroupserDiv').hide();
			$('invite_email').show();
		}

		Effect.toggle('inviteFooterInfo', 'blind',{duration: 0.0});
		/*if(selIds == 'selectedIDsinviteTab') {
			objPeople.hideError();
			objPeople.showAuthErr();
		}*/
	}	
		
	function resizeIframe()
	{
		if($('iprofile') && $('iprofile').contentWindow.document.body){			
			$('iprofile').style.height = $('iprofile').contentWindow.document.body.scrollHeight + 'px';			
		}		
	}
	
	function changeClass(obj,nm)
	{
		obj.className = nm;
	}
	
	function showTag(obj)
	{		
		if(obj.id == "invitehome")
		{

			$('invitehome').style.display = "block";
			
			if($('interMedDiv').style.display != 'none')
			$('interMedDiv').style.display = 'none';
			
			Effect.toggle('interMedDiv','slide', { duration: 0.5});
						
		}
		else
		{
        	obj.style.display = 'block';
        }	
        
	}
	
	function hideTag(obj)
	{
		if(obj.id == "status_popup" || (obj.id=='allHeadPopup' && document.getElementById('status_popup').style.display!='none')) 
		{ 
			if(document.getElementById('status_popup').style.display!='none') 
			{			
				document.getElementById('statusCntOpen').style.display='none';
				document.getElementById('statusCnt').style.display='none';
				document.getElementById('statusCnt').innerHTML='';
				document.getElementById('statusCntClose').style.display='none';
			}
		}
	
		obj.style.display = 'none';
			
	}
		
	
	function changeEditLink(mystatus)
	{	
		$('imgArrow').innerHTML = '<img width="12" height="20" class="callout" src="/images/callout_textarea1.gif"/>';
		$('setStatus').className = 'inpBigEdit';
		$('setStatus').focus();
		
		if($('setStatus').value == 'Enter your status here') $('setStatus').value = ''; 
		
		var saveLink = '<a href="javascript:;" onclick="javascript:saveUserStatus(document.getElementById(\'setStatus\').value);">save</a>';
		$('divSaveLink').innerHTML = saveLink;
	}
	
	function saveUserStatus(status)
	{
		var status = status;
		
		$('setStatus').disabled = 'true';		
		var editLink = '<a href="javascript:;" onclick="javascript:$(\'setStatus\').disabled=false;changeEditLink();">edit</a> | <a href="javascript:;" onclick="javascript:saveUserStatus(\'\');">clear</a>';
		$('divSaveLink').innerHTML = editLink;	
		
		alertStatus.saveStatus(status);
	
		if(status == '') $('setStatus').value = 'Enter your status here';
	
		$('setStatus').className = 'inpBig';
		$('imgArrow').innerHTML = '<img width="12" height="20" class="callout" src="/images/callout_textarea1Edit.gif"/>';
	}
	
	function findPosition(obj) {
		var curleft = curtop=curwidth = curheight=0;
		if(typeof obj != "object")
		var obj = document.getElementById(obj);
		if(BrowserDetect.browser == "Explorer"){
		   	var obj1 = obj.getBoundingClientRect();
		   	curleft = parseInt(obj1.left);
			curtop = parseInt(obj1.top + document.documentElement.scrollTop);
			
		} else {
			curleft = parseInt(obj.offsetLeft);
			curtop = parseInt(obj.offsetTop);
			
		}
		
		curwidth=parseInt(obj.offsetWidth);
		curheight=parseInt(obj.offsetHeight);
		return [curleft,curtop,curwidth,curheight];
	}	
	
	function findPositionTalk(obj) {
		var curleft = curtop=curwidth = curheight=0;
		if(typeof obj != "object")
		var obj = document.getElementById(obj);
		if(BrowserDetect.browser == "Explorer"){
		   	curleft = parseInt(obj.offsetLeft);
			curtop = parseInt(obj.offsetTop);
			
		} else {
			curleft = parseInt(obj.offsetLeft);
			curtop = parseInt(obj.offsetTop);
			
		}
		
		curwidth=parseInt(obj.offsetWidth);
		curheight=parseInt(obj.offsetHeight);
		return [curleft,curtop,curwidth,curheight];
	}
	
	function findPosVcard(obj, e)
	{
  		if(!e) e = window.event;
  		
		var curleft = 0;
		var curtop = 0;
		
  		if(BrowserDetect.browser != "Explorer"){
			document.captureEvents(Event.MOUSEMOVE);
		}
		
    	curleft = (e.pageX) ? e.pageX : e.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
		curtop = (e.pageY) ? e.pageY : e.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
			
		//alert('Left : '+curleft+'||Top : '+curtop);
		return [curleft,curtop];
	}

	function sz2(t){
        var lines = t.value.split('\n');
        lines = parseInt(lines.length);
      
       	var maxCharPerLine = 70;
       	var noofChar = t.value.length;
       	noofChar = parseInt(noofChar)+((parseInt(lines)-1)*parseInt(maxCharPerLine));
       	//alert(noofChar);
       	var tempr = parseInt(lines)*parseInt(maxCharPerLine);
	
   		if(lines == "7" && noofChar > tempr){
   			lines  = Math.round(noofChar/parseInt(maxCharPerLine))+1;
   		}else if(lines == "6" && noofChar > tempr){
   			lines  = Math.round(noofChar/parseInt(maxCharPerLine))+1;
   		}else if(lines == "5" && noofChar > tempr){
   			lines  = Math.round(noofChar/parseInt(maxCharPerLine))+1;
   		}else if(lines == "4" && noofChar > tempr){
   			lines  = Math.round(noofChar/parseInt(maxCharPerLine))+1;
   		}else if(lines == "3" && noofChar > tempr){
   			lines  = Math.round(noofChar/parseInt(maxCharPerLine))+1;
   		}else if(lines == "2" && noofChar > tempr){
   			lines  = Math.round(noofChar/parseInt(maxCharPerLine))+1;
   		}else if(lines == "1" && noofChar > tempr){
   			lines  = Math.round(noofChar/parseInt(maxCharPerLine))+1;
   		}else{
   			lines = lines;
   		}
   		
   		//alert(noofChar+'-'+lines);
   		if(lines >= 8  && t.rows != "20"){
   			//alert('expand');
   			t.rows = 20;
   			//positionFooter();	
        }  
        
        	if(lines < 8){
   		
    		if(t.rows == "20"){
    		//alert('reduce');
    			t.rows = 7;lines=1;/*positionFooter();*/}else{}
       		}
	}
	
	function findPosCreateGr(obj,positionDiv) {
	
		var obj = positionDiv;	
		var curleft = curtop = 0;
		var obj = document.getElementById(obj);
	
		if(BrowserDetect.browser == "Explorer"){
			if(BrowserDetect.version == 8){
				curleft = parseInt(obj.offsetLeft)+parseInt(14);
				curtop = parseInt(obj.offsetTop)+parseInt(22);
			}
			else {
				curleft = parseInt(obj.offsetLeft)+parseInt(14)+parseInt(34);
				curtop = parseInt(obj.offsetTop)+parseInt(37)+parseInt(170);
			}
		}else{
			curleft = parseInt(obj.offsetLeft)+parseInt(14);
			curtop = parseInt(obj.offsetTop)+parseInt(37);
		}
		curheight = obj.offsetHeight;
					
		return [curleft,curtop,curheight];
	}
	
	function findPosTask(obj,positionDiv) {
		var obj = positionDiv;	
		var curleft = curtop = 0;
		var obj = document.getElementById(obj);
	
		if(BrowserDetect.browser == "Explorer"){
			if(BrowserDetect.version == 8){
				curleft = parseInt(obj.offsetLeft)+parseInt(14);
				curtop = parseInt(obj.offsetTop)+parseInt(37);
			}
			else {
				curleft = parseInt(obj.offsetLeft)+parseInt(14)+parseInt(34);
				curtop = parseInt(obj.offsetTop)+parseInt(37)+parseInt(104);
			}
		}else{
			curleft = parseInt(obj.offsetLeft)+parseInt(14);
			curtop = parseInt(obj.offsetTop)+parseInt(37);
		}
		curheight = obj.offsetHeight;
					
		return [curleft,curtop,curheight];
	}
	/************ COMMENTS ***************/
	
	
	
	var comments = Class.create({
	
		initialize : function(par) {
			this.filesGlobal = false;
			this.filesObj='';
			this.par = par;
			this.url = '/groups/groups/uploadFile/';
			this.commentUrl = '/groups/groups/saveComments';
			this.type= ''; //for beforeDelete function
			this.popUp= false; //for beforeDelete function
			
			if(par=='task'){
				this.objpara = 'commentTask';
				this.type = 'TK';	
			}
			else if(par=='miles'){
				this.objpara = 'commentMiles';
				this.type = 'ML';
			}
			else if(par=='talk'){
				this.objpara = 'commentTalk';
				this.type = 'TL';
			}
			else if(par=='taskRHS'){
				this.objpara = 'commentTaskRHS';
				this.type = 'TK';
				this.popUp= true;
			}
			else if(par=='milesRHS'){
				this.objpara = 'commentMilesRHS';
				this.type = 'ML';
				this.popUp= true;
			}
		},
		
		openComment : function(ID,grpId) {
			
			$(this.par+"commentArea" + ID).removeClassName('inpErr');
			$("err"+this.par+"Comment"+ID).hide();
			
			var c = $(this.par + 'comment_' + ID);
			
			if(c.style.display == 'none') {	
				$(this.par+'formDivsComments_' +ID).innerHTML = '';
				$(this.par+'brwCounter_'+ID).value = '0';
				$(this.par+'lastid_'+ID).value = '0';
				$(this.par+'commentArea' +ID).value = '';
				$(this.par+'commErrMsg'+ID).hide();
				$(this.par + 'TA1_' + ID).hide();
				$(this.par + 'TA2_' + ID).show();
				Effect.toggle(c,'slide', { duration: 0.5});
				this.BrowseAdd(ID,grpId);
			} else {
				Effect.toggle(c,'slide', { duration: 0.5});
			}
		},
		
			
		saveComments : function(parent,grpId){
		
			if(!this.filesGlobal){
				var comment = $(this.par+"commentArea"+parent).value.strip();
				var vFBU = new checkFBU( this.objpara+'.BrowseAddObj('+parent+','+grpId+')' ); 
				if(comment != ''){
					this.filesGlobal = true;
					vFBU.showUploaderOnSubmit();
					$(this.par+"commentBut"+parent).addClassName("butDisabled");
					/*if(this.popUp)
						$(this.par+"commentBut"+parent).className= "butDisabled";
					else
						$(this.par+"commentBut"+parent).className= "butBlueDisabled";
					*/	 	
				}
				vFBU.validatefiles();
			}
		
			return false;	
		},
		
		pullComment : function(parent,grpId){
			var lastInsertId = $(this.par+'lastid_'+parent).value;
			var commentCountVar = '';
			var url    = '/groups/groups/pullComment.json';
			var userID = $('SessStrUserID').value;	
			var rand   = Math.random(9999);
			var par = this.par;
			var myAjax = new Ajax.Request(url, {method: 'post', 
								parameters: {sessUID:userID, lastId:lastInsertId, rand:rand,par: this.par}, 
								onSuccess:function(transport) {
								
								if(transport.responseText == "expired")
								{
									window.location = MainUrl + "main.php?u=signout";return;
								}
								else
								{	
									//alert(transport.responseText);
									//$('dinesh').innerHTML = transport.responseText;
									
									var whichComment = transport.responseText.split("^$@yz^");
									
									whichComment[1] = parseInt($(par+'hdnCommCount'+parent).value) + 1;
									
									/*Mark thread as read if not in calendar*/
									if($('currPageCat').value != 'cal'){
										var elem = "";
										if(par == "talk")
											elem = "TL_"+parent;
										else if(par == "miles" || par == "milesRHS"){
											if($('currPageCat').value == 'ovr')
												elem = "mil"+parent; //Version for overview page
											else	
												elem = "mil"+whichComment[3]; //id for inner page
											if($('hdnMl'+whichComment[3]))	
												$('hdnMl'+whichComment[3]).value = "0";
										}	
										else if(par == "task" || par == "taskRHS")
										{
											elem = "taskThread_"+parent;
											if($('hdnTd'+parent))
												$('hdnTd'+parent).value = "0";
										}
											
										changeBlueBg(elem);
									}
									else {
										intCalendarEditCal = 1;		
									}
									
									
									commentCountVar = '1&nbsp;Comment';
									if(whichComment[1] > 1){
										//multi comments
										$(par+'TA1_'+parent).insert ({
											'before'  : whichComment[0]
										} );
										
										commentCountVar = whichComment[1] +"&nbsp;Comments";
	                                	$(par+'hdnCommCount'+parent).value = whichComment[1];
										$(par+'commentCount'+parent).innerHTML = commentCountVar;
										
										
									}else{
									//first comment
										
										$(par+'comment_'+parent).insert ({
											'top'  : whichComment[2]
										} );
										
										$(par+'TA1_'+parent).insert ({
											'before'  : whichComment[0]
										} );
										
										$(par+'comment_'+parent).show();
										if($(par+'butComment_'+parent)){
											$(par+'butComment_'+parent).hide();
											var innerEle = Element.descendants($(par+'butComment_'+parent).parentNode);
											var innerEleLen = innerEle.length;
											if(innerEleLen == '1'){
												$(par+'butComment_'+parent).parentNode.style.display = 'none';
											}else{
												if($('milesPipe'+parent))$('milesPipe'+parent).hide();
												
											}
										}
											
										$(par+'hdnCommCount'+parent).value = 1;

										var cngFn = Element.descendants($(par+'TA2_' +parent))[0];
										cngFn.innerHTML= cngFn.innerHTML.replace('openComment','toggleCommentBox');
										
										
									}

									$(par+'brwCounter_'+parent).value = '0';
									$(par+'lastid_'+parent).value = '0';	
									$(par+'TA1_' +parent).show();
									$(par+'TA2_' +parent).hide();
									$(par+'formDivsComments_' +parent).innerHTML = '';
									$(par+'commentArea' +parent).value = '';
									//alert(this.par+'==='+par+'===='+commentCountVar);
									/** For Dashboard RHS **/
									if($('commentLinkMilRHS'+parent)){
										$('commentLinkMilRHS'+parent).innerHTML = '['+commentCountVar+']';
										if($(par+'img_'+parent)) $(par+'img_'+parent).remove();														
									}
									
									if($('commentLinkTaskRHS'+parent)){
										$('commentLinkTaskRHS'+parent).innerHTML = '['+commentCountVar+']';
										if($(par+'img_'+parent)) $(par+'img_'+parent).remove();																													
									}
									
									/** For Calendar **/
										
									if($('milestoneCommNum') && par == 'miles'){
										$('milestoneCommNum').innerHTML = commentCountVar;
										if($(par+'img_'+parent)) $(par+'img_'+parent).remove();													
									}
									
									if($('todoCommNum'+parent) && par == 'task'){
										$('todoCommNum'+parent).innerHTML = commentCountVar;
										if($(par+'img_'+parent)) $(par+'img_'+parent).remove();																													
									}
								}								
							}
						});
						
				url    = '/groups/groups/afterSaveComments.json';
				rand   = Math.random(9999);
				var myAjax = new Ajax.Request(url, {method: 'post', 
							parameters: {lastid:lastInsertId, rand:rand,par: this.par,grpId: grpId,parent: parent}, 
							onSuccess:function(transport) {
								
								if(transport.responseText == "expired")
								{
									window.location = MainUrl + "main.php?u=signout";return;
								}
								var id = transport.responseText;
								
								url    = '/groups/delete/commentMailers.json';
								rand   = Math.random(9999);
								var myAjax = new Ajax.Request(url, {method: 'post', 
											parameters: {type:par,id:id,commentId:lastInsertId,rand:rand,grpId:grpId}
									
								});
							}		
						});
						
				if(par == 'milesRHS' && $('mil'+parent)){
					commentMiles.pullCommentLHS(parent,grpId,lastInsertId);
				}else if(par == 'taskRHS' && $('taskThread_'+parent)){
					commentTask.pullCommentLHS(parent,grpId,lastInsertId);										
				}
				
				$(this.par+"commentBut"+parent).removeClassName('butDisabled');
				/*if(this.popUp)
					$(this.par+"commentBut"+parent).className= "but";
				else 
					$(this.par+"commentBut"+parent).className= "butBlue";	
				*/
		},
		
		pullCommentLHS : function(parent,grpId,lastInsertId){
			var url    = '/groups/groups/pullComment.json';
			var userID = $('SessStrUserID').value;	
			var rand   = Math.random(9999);
			var par = this.par;
			var myAjax = new Ajax.Request(url, {method: 'post', 
								parameters: {sessUID:userID, lastId:lastInsertId, rand:rand,par: this.par}, 
								onSuccess:function(transport) {
								
								if(transport.responseText == "expired")
								{
									window.location = MainUrl + "main.php?u=signout";return;
								}
								else
								{	

									var whichComment = transport.responseText.split("^$@yz^");
									whichComment[1] = parseInt($(par+'hdnCommCount'+parent).value) + 1;
									if(whichComment[1] > 1){
										//multi comments
									
										$(par+'TA1_'+parent).insert ({
											'before'  : whichComment[0]
										} );
										
										
	                                	$(par+'hdnCommCount'+parent).value = whichComment[1];
										$(par+'commentCount'+parent).innerHTML = whichComment[1]+"&nbsp;Comments";	
										
									}else{
									//first comment
										
										$(par+'comment_'+parent).insert ({
											'top'  : whichComment[2]
										} );
										
										$(par+'TA1_'+parent).insert ({
											'before'  : whichComment[0]
										} );
										$(par+'comment_'+parent).show();
										$(par+'butComment_'+parent).hide();
										$(par+'hdnCommCount'+parent).value = 1;
									
									}
									
									$(par+'brwCounter_'+parent).value = '0';
									$(par+'lastid_'+parent).value = '0';								
									$(par+'TA1_' +parent).show(); 
									$(par+'TA2_' +parent).hide();
									$(par+'formDivsComments_' +parent).innerHTML = '';	
									$(par+'commentArea' +parent).value = '';
									
								}
							}
						});
			
		},
		delComment : function(oid,parent){
			var rand = Math.random(9999);
			var delUrl = "";
			var strHdnFlag;
			var commentCountVar = '';
			if(this.par=='task' || this.par == "taskRHS" ){
				delUrl = "/groups/delete/delTask.json";
				strHdnFlag = "taskFlag";
			}
			else if(this.par=='miles' || this.par == "milesRHS"){
				delUrl = "/groups/delete/delMilestone.json";
				strHdnFlag = "milestoneFlag";
			}
			else if(this.par=='talk'){
				delUrl = "/groups/delete/delTalk.json";
				strHdnFlag = "talkFlag";
			}
			
			var delFlag = "0";	
			if($('chkDel').checked)
			{
				$(strHdnFlag).value = "0";
				delFlag = "1";
			}	
			var par = this.par;
			var objpara = this.objpara;
			var myAjax = new Ajax.Request( delUrl, {method: "post", parameters: {rand:rand,oid:oid,parent:parent,delFlag:delFlag},onSuccess:
					function(transport)
					{
						if(transport.responseText == "expired")
						{      
							window.location = MainUrl + "main.php?u=signout";return;
						}
						else //Remove element
						{	
							var num = $(par+'hdnCommCount'+parent).value;
							commentCountVar = 'Comment';
							if(num > 1){
								num--;
								$(par+'hdnCommCount'+parent).value = num;
								var str = "Comment";
								if(num > 1) str = "Comments";
								
								commentCountVar = num + "&nbsp;" + str;
								$(par+'commentCount'+parent).innerHTML = commentCountVar;
								
								if($(par+'showMoreComment_'+parent) && ($(par+'Msgcomment_'+oid).parentNode.id == par+'showMoreComment_'+parent)){
									var hiddElems = Element.immediateDescendants($(par+'showMoreComment_'+parent)).length;
									if(hiddElems > 1){
										hiddElems--;
										str = "Comment";
										if(hiddElems > 1) str = "Comments";
										$(par+'midComments'+parent).innerHTML = " " +  hiddElems + " more " + str;
									}
									else{
										var previousSibling = Element.previousSiblings($(par+'showMoreComment_'+parent))['0'];
										Element.remove(previousSibling);
										Element.remove($(par+'showMoreComment_'+parent));
									}
									
								}
								
							}
							else {
								$(par+'hdnCommCount'+parent).value = 0;
								$(par+'TA1_'+parent).hide();
								$(par+'comment_'+parent).hide();
								var previousSibling = Element.previousSiblings($(par+'allComment_'+parent))['0'];
								Element.remove(previousSibling);
								
								if(par == "milesRHS" || par == "taskRHS" || $('currPageCat').value == 'cal'){
									eval(objpara+".openComment('"+parent+"','"+$(par+'commentFrm'+parent).groupid.value+"')");
								}else{
									if($(par+'butComment_'+parent)){
										$(par+'butComment_'+parent).show();
										if($(par+'butComment_'+parent).parentNode && $(par+'butComment_'+parent).parentNode.id == '' && $(par+'butComment_'+parent).parentNode.style.display == 'none'){
											$(par+'butComment_'+parent).parentNode.show();
										}
									}
									if($(par+'Pipe'+parent))$(par+'Pipe'+parent).show();
								}
										  	 	        	
							}
								
							if($(par+'Msgcomment_'+oid)){
								Element.remove($(par+'Msgcomment_'+oid));
							}
							
							/** For Dashboard RHS **/
							if($('commentLinkMilRHS'+parent)){
								$('commentLinkMilRHS'+parent).innerHTML = '['+commentCountVar+']';														
							}
							
							if($('commentLinkTaskRHS'+parent)){
								$('commentLinkTaskRHS'+parent).innerHTML = '['+commentCountVar+']';																													
							}
							
							/** For Calendar **/
										
							if($('milestoneCommNum') && par == 'miles'){
								$('milestoneCommNum').innerHTML = commentCountVar;													
							}
							
							if($('todoCommNum'+parent) && par == 'task'){
								$('todoCommNum'+parent).innerHTML = commentCountVar;																													
							}
							
							if($('currPageCat').value == 'cal'){
								intCalendarEditCal = "1";
							}	
											 
						}
					}								
			} );
				
			if(this.par == 'milesRHS' && $('mil'+parent))		
				commentMiles.delCommentLHS(oid,parent);
			else if(this.par == 'taskRHS' && $('taskThread_'+parent))
				commentTask.delCommentLHS(oid,parent);		
		},
		
		delCommentLHS : function(oid,parent){
			var par = this.par;
			
			var num = $(par+'hdnCommCount'+parent).value;
			if(num > 1){
				num--;
				$(par+'hdnCommCount'+parent).value = num;
				var str = "comment";
				if(num > 1) str = "comments";
				$(par+'commentCount'+parent).innerHTML = num + " " + str;
				if($(par+'showMoreComment_'+parent) && ($(par+'Msgcomment_'+oid).parentNode.id == par+'showMoreComment_'+parent)){
					var hiddElems = Element.immediateDescendants($(par+'showMoreComment_'+parent)).length;
					if(hiddElems > 1){
						hiddElems--;
						str = "comment";
						if(hiddElems > 1) str = "comments";
						$(par+'midComments'+parent).innerHTML = " " +  hiddElems + " more " + str;
					}
					else{
						var previousSibling = Element.previousSiblings($(par+'showMoreComment_'+parent))['0'];
						Element.remove(previousSibling);
						Element.remove($(par+'showMoreComment_'+parent));
					}
					
				}
				
			}
			else {
				
				$(par+'hdnCommCount'+parent).value = 0;
				var previousSibling = Element.previousSiblings($(par+'allComment_'+parent))['0'];
				Element.remove(previousSibling);
				$(par+'butComment_'+parent).show();
				$(par+'TA1_'+parent).hide();
				$(par+'comment_'+parent).hide();		  	 	        	
			}
				 
			if($(par+'Msgcomment_'+oid)){
				Element.remove($(par+'Msgcomment_'+oid));
			}	
						
		},
		
		hideCommentBut : function(id,status){
			if($(this.par+'hdnCommCount'+id))
			{
				var comm = parseInt($(this.par+'hdnCommCount'+id).value);
					
				if(comm > 0){
					if(status == 'completed'){
						$(this.par+'TA1_'+id).hide();
						$(this.par+'TA2_'+id).hide();
					}
					else {
						$(this.par+'TA1_'+id).show();
					}
				}
				else {
					if(status == 'completed'){
						$(this.par+'butComment_'+id).hide();
					}
					else {
						$(this.par+'butComment_'+id).show();
					}
					$(this.par+'TA1_'+id).hide();
					$(this.par+'TA2_'+id).hide();
				}
			}
		},
		
		BrowseAdd : function(msgId,grpId){
	
			var id = messagesObject.getUniqueID();
			var group_id = group = grpId;
			
			var brwCnt = $(this.par+'brwCounter_'+msgId).value;
			var category_id = $(this.par+'fileCategory_'+msgId).value;
			brwCnt = parseInt(brwCnt) + 1;
			$(this.par+'brwCounter_'+msgId).value = brwCnt;
			var filecnt = brwCnt;
		
        	var divid = this.par + "msgFile_"+msgId+'_'+filecnt;
       	 	var formid = this.par + "frmGroupFileUpload_"+msgId+"_"+filecnt;
       	 	var newform = document.createElement("form");
        	newform.style.styleFloat = "left";
        	newform.id = formid;
        	newform.name = this.par + "frmGroupFileUpload_"+msgId;
        	newform.method = 'post';
        	newform.style.display = 'block';
			newform.style.paddingBottom = "10px";
        	newform.enctype = 'multipart/form-data';
        	document.getElementById(this.par + "formDivsComments_"+msgId).appendChild(newform);
        	document.getElementById(formid).innerHTML = '<div class="leftFloat"><input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="'+id+'"/>'
                                                	+ '<input name="groupid" type="hidden"  value="'+group_id+'" '
	                                                + '<input type="hidden" name="msgParentId" value="'+msgId+'" />'
                                                    + '<input type="hidden" name="childno" id="childno" value="'+filecnt+'"/>'
                                                    + '<input type="hidden" name="lastid" id="lastid" value="" />'
                                                    + '<input type="hidden" name="last" id="last" value="0" />'
										        	+ '<input type="hidden" name="vx" id="vx" value="tt"/>'
										        	+ '<input type="hidden" name="group" id="group" value="'+group+'"/>'
	                                                + '<input type="hidden" name="groupmessagecategory_id" value="'+category_id+'" />'
                                                    + '<input type="file" name="name" id="'+divid+'" onchange=\'if(this.value) '+this.objpara+'.BrowseAdd("'+msgId+'","'+grpId+'")\'/>&nbsp;&nbsp;'
										+ '<span class="dummy_msgCmt_'+msgId+'_RemoveLink" id="'+this.par+'removeGroupNewLinkSpan_'+msgId+'_'+filecnt+'"></span>'
							+ '<span id="'+this.par+'addGroupNewLinkSpan_'+msgId+'_'+filecnt+'"></span>'
							//+'<span id="status_'+id+'" style="display:none"> #{complete}/#{total} (#{percent}%)</span>'
                            +'&nbsp;<span id="element_'+id+'" style="display:none">'
                                    +'<img class="percentImage" style="margin: 0pt; padding: 0pt; width: 120px; height: 16px; background-position: -120px 50%; background-image: url(/images/bramus/percentImage_back.png);" alt="" src="/images/bramus/percentImage.png" id="element_'+id+'_percentImage" title=""/>'
                            +'</span>'
                            +'&nbsp;<span id="element_'+id+'_percentText" style="display:none"></span></div>'
                            +'</div>';
        
        	var clearLeft = '<div class="clearLeft">&nbsp;</div>';                    
        	$(this.par+"formDivsComments_"+msgId).insert ( { 'bottom'  : clearLeft } ); 

			if(filecnt > 1) {
	
				var linkid = filecnt-1;
				var linkformid = this.par + 'frmGroupFileUpload_'+msgId+'_'+linkid;
				var remlinkid = this.par + 'removeGroupNewLinkSpan_'+msgId+'_'+linkid;
				messagesObject.getRemoveLink(linkformid, remlinkid);
				// Cross-browser complaint
				$(this.par+"msgFile_"+msgId+"_"+linkid).onchange = null;
				$(this.par+ "addGroupNewLinkSpan_"+msgId+"_"+linkid).remove();
			}
		},
	
		
		BrowseAddObj : function(msgid,grpId) { // FOR COMMENTS ON PAGE			
	        var filepara = { form : this.par + "frmGroupFileUpload_"+msgid,
	        				 groupid : grpId,
	        				 msgid : msgid,
	        				 catid : "",
	        				 objName : this.objpara+".filesObj",
	                         url_action : this.url+grpId+"/"+this.par,
	                         id_element : this.par+"commErrMsg"+msgid,
	                         area_desc : this.par+"commentArea"+msgid,
							 errDiscText : "err"+this.par+"Comment"+msgid,
	                         html_show_loading : this.par+"loading"+msgid,
	                         html_error_http : "Error",
	                         error_message : "Please type a comment",
	                         global : this.objpara+".filesGlobal = false",
	                         get_comment : this.objpara+".filesObj = new uploadMF('"+this.objpara+".BrowseAddObj("+msgid+", "+grpId+")'); "+this.objpara+".filesObj.submitform();",
				 			 add_file : this.objpara+".BrowseAdd("+msgid+","+grpId+")",
	                         comment_edit : "",
	                         submit_comment : this.objpara+".BrowseAddComObj("+msgid+")",
							 before_submit : "",
							 after_submit : "",
							 lastid : this.par+"lastid_"+msgid,
							 removelinkclass : "dummy_msgCmt_"+msgid+"_RemoveLink",
							 gct : "",
							 got_gct : false,
							 after_gct : "",
							 after_upload : "",
							 clean_up : "$('"+this.par+"brwCounter_"+msgid+"').value = '0'; $('"+this.par+"commentFrm"+msgid+"').reset(); $('"+this.par+"formDivsComments_"+msgid+"').innerHTML=''; "+this.objpara+".pullComment("+msgid+","+grpId+")"
	                       };
	        return filepara;
		},
		
		BrowseAddComObj : function(msgid) {  
	        var filepara = { form : this.par+"commentFrm"+msgid,
	                         url_action : this.commentUrl,
	                         id_element : this.par+"commErrMsg"+msgid,
	                         html_show_loading : "",
	                         html_error_http : ""
	                       };
	        return filepara;
		},
		
		openCommentSec : function(self, ID) {			
			new Effect.toggle(this.par+'allComment_' + ID,'slide', { duration: 0.5});
			
			if(self){
				if(self.src.match('minimize.gif')) self.src = '/images/maximize.gif';
				else self.src = '/images/minimize.gif';
			}	
		},
		
		moreCommentToggle : function(ID) {
			if($(this.par+'showMoreComment_' + ID).style.display == 'none') {
				$(this.par+'midText'+ID).innerHTML = 'Hide';				
				Effect.toggle(this.par+'showMoreComment_' + ID,'slide', { duration: 0.5});
 
			} else {
				$(this.par+'midText'+ID).innerHTML = 'Show';
				Effect.toggle(this.par+'showMoreComment_' + ID,'slide', { duration: 0.5});
			}
		},
		
		toggleCommentBox : function(ID, grpID) {
			$(this.par+"commentArea" + ID).removeClassName('inpErr');
			$("err"+this.par+"Comment"+ID).hide();
			$(this.par+'formDivsComments_' + ID).innerHTML = '';
			$(this.par+'brwCounter_' + ID).value = '0';
			
			if($(this.par+'TA1_' + ID).style.display != 'none') { 				
				$(this.par+'TA1_' + ID).hide();
				$(this.par+'commentArea' + ID).rows = '2'; 
				$(this.par+'TA2_' + ID).show();
				$(this.par+'commentArea' + ID).focus();
				
				this.BrowseAdd(ID,grpID);				 
			} else { 
				
				$(this.par+'TA1_' + ID).show(); 
				$(this.par+'TA2_' + ID).hide();
			}
		},
		
		CommentBoxHtml : function(ID,grpId){
			var butClass = 'but';
			if(!this.popUp)
				butClass = 'butBlue';
			var Html = 
			 					/*'<div id="'+this.par+'butComment_'+ID +'" class="butCommentDiv">'
	                         	+   '<a class="butComment" href="javascript:void(0);" onclick="'+this.objpara+'.openComment('+ID+','+grpId+')">Comment</a>'
	                         	+'</div>'*/
		                        '<div id="'+this.par+'comment_'+ID +'" class="comments" style="display:none">'
                            	+'<div id="'+this.par+'allComment_'+ID +'" style="display:block">'
                            	
                            		+'<div id="'+this.par+'TA1_'+ID +'" class="commentsCont gainlayout">'
	                            	+	'<div onclick="'+this.objpara+'.toggleCommentBox('+ID+','+grpId+')"; style="border: 1px solid #b5def9; padding: 8px; background: #FAFBFC; width: 90%; font-size: 12pt; font-weight: bold;color:#CBCBCB;">Write more comments...</div>'	                            		
	                            	+'</div>'
	                            	+'<div id="'+this.par+'TA2_'+ID +'"  class="commentsCont" style="display:none">'
	                            	   + '<div class="minGrey rightFloat closeRelative"><a href="javascript:void(0)" onclick="'+this.objpara+'.openComment('+ID+','+grpId+')";>-</a></div>'
	                            		
	                            		+'<div class="postTitleSm"><img onMouseOver="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" onMouseOut="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" src="'+$('userpic').value+'" /></div>'
	                            		+'<div class="postDataSm">'
		                            		+'<form method="post" id="'+this.par+'commentFrm'+ID +'" >'		                            			
		                                		+'<div id="commentDisplay'+ID+'" class="field">'
		                                  			+'<textarea name="commentArea" id="'+this.par+'commentArea'+ID +'" style="width:90%" onkeyup="ta_autoresize(this);" rows="2" cols="65"></textarea>'
		                                			+'<div class="inlineErr" id="err'+this.par+'Comment'+ID+'" style="display:none">Please type a comment</div>'
		                                		+'</div>'
		                                		+'<input name="par" type="hidden" value="'+this.par+'" />'
                                                +'<input name="groupid" type="hidden" value="'+grpId+'" />'
                                                +'<input name="data[groupfile][groupfilecategory_id]" id="'+this.par+'fileCategory_'+ID +'" type="hidden" value="0" />'
                                               	+'<input name="parent" type="hidden" value="'+ID+'" />'
		                                	+'</form>'
                            +'<div class="field">'
		                                		+'<div id="'+this.par+'formDivsComments_'+ID+'">'
		                                  			
                             +' </div>'
                            +'</div>'
		                                	+'<div id="commentStatus'+ID+'" style="display:none"></div>'
		                                	+'<div class="err" id="'+this.par+'commErrMsg'+ID+'" style="display:none">Please type a comment</div>'
		                                	+'<div class="butBlueDiv leftFloat"><a id="'+this.par+'commentBut'+ID+'" href="javascript:void(0);" class="'+butClass+'"><span onclick="'+this.objpara+'.saveComments(\''+ID+'\',\''+grpId+'\')";>Comment</span></a></div>'
		                                	+'<div valign="top" id="'+this.par+'loading'+ID+'" style="display:none" class="loadingImgDiv"><img src="/groups/img/loading.gif" class="loadingImgLeft"></img></div>'
                              				+'<div class="clearLeft1"></div>'
                              +'</div>'
	                                	+'<input id="'+this.par+'lastid_'+ID+'" type="hidden" value="0" />'
	                                	+'<input id="'+this.par+'hdnCommCount'+ID+'" type="hidden" value="0" />'
	                              		+'<input id="'+this.par+'brwCounter_'+ID+'" type="hidden" value="0" />'
	                           
	                              	+'</div>'
	                          	+'</div>'

                           	+'</div>';
                           	
                 return  Html ;      	
		} 
	
	});
	
	
	/************	MESSAGES	***************/
	var messages = Class.create({

		initialize : function() {
			this.fileCnt = 0;
			//this.encgroup = '';
			this.groupid = '';
			this.msgid = '';
			this.tmp = '';
			this.etc = ''; 
			this.uMF = '';
			this.cmt = new Array();
			this.unreadmessages = '';
			this.messageSubmitted = false;
			this.popup = 'create_message';
			this.errortitle = 'errorMsgTitle';
			this.successtitle = 'msgAddSuccess';			
		},
		
		reloadCategory : function(para)
		{
			var userID = $('selectedIDs'+para).value;
			this.setGroupID();
			var showCategory = this.showCategory.bind(this);

			new Ajax.Request("/groups/groups/getMessages.json", { 
				'method':'post', 
				'parameters':{ id :userID},
				onSuccess:showCategory  
			});
		},
	
		showCategory : function(originalRequest)
		{
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			var data = originalRequest.responseText.split('##~~##');
			$('category').update(data[0]);
			/*var groupEnc = $('enc'+this.groupid+'cm').value;
			var f = this.getElementsByName('frmFileUpload', 'form');*/

			for(var i=0; i<f.length; i++) {
				f[i].group.value = this.groupid;
			}
		},
		
		callMessages : function(catID, highlightLast) {
		
			showLoader();
			this.initialize();
			this.setGroupID();
					
			$('currPageCat').value = "msg";
			this.cmt = new Array();
			var e = this.messagesVar.bind(this);
			new Ajax.Request('/groups/messages/getMessages.json', {
	  			parameters: { groupid: this.groupid, catID:catID, highlightLast:highlightLast },
	  			onSuccess: e
			});
		},
 
 		callMessagesDetailed : function(msgid,grpId) {
 			showLoader();
 			this.initialize();
			this.groupid = grpId;
			//this.encgroup = ($('encGroup')) ? $('encGroup').value : $('enc'+ this.groupid +'cm').value; 
			//alert(this.encgroup);
			
			this.cmt = new Array();
			$('currPageCat').value = 'msgdld';
			
			//if(this.in_array(msgid, this.unreadmessages))
			//	this.markMessageRead(msgid, false);
						
			var e = this.messagesVar.bind(this);
			new Ajax.Request('/groups/messages/getMessagesDetailed.json', {
	  			parameters: { groupid: this.groupid, msg_id:msgid },
	  			evalScripts : true,
	  			onSuccess: e
			});
		},
 
 		messagesVar : function(resp) {
 			if(resp.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout"; return;
			}

 			//var respScripts = resp.responseText.extractScripts();
 			//var respScripts = resp.responseText.evalScripts();
			$('maincontent').innerHTML = resp.responseText;
			
			/*var myReturnedValues = respScripts.map(function(script) {
  				return eval(script);
			});*/
			 
			var s = $('messageIDArr').value.split('-');
			
			for(var i=0; i<s.length; i++) {	
				var v = s[i];
				this.cmt[v] = '1'; 
			}

			if($('messHighlightLast').value > 0) 
				if($('message_'+ s[0])) 
					new Effect.Highlight($('message_'+ s[0]), { startcolor: startColor,endcolor: endColor ,duration: 1 });
			
			this.unreadmessages = $('unReadMessages').value.split('-');
			scroll(0,0);
			//alert(this.unreadmessages.length);
			//alert(typeof this.cmt);
 		},
 
		openMessageComment : function(msgID, catID) {
			this.initializeComment(msgID, catID);
			var c = $('comment_' + msgID);
			if(c.style.display == 'none') {
				$('TA1_' + msgID).hide();
				$('TA2_' + msgID).show();
				//$('commentArea' + msgID).focus();
				Effect.toggle(c,'slide', { duration: 0.5});
			} else {
				Effect.toggle(c,'slide', { duration: 0.5});
			}
		},
		
		openOverMessageComment : function(msgID, catID, grpID) {
			this.beforeInitializeComment(msgID, grpID);
			this.initializeComment(msgID, catID);

			var c = $('comment_' + msgID);
			if(c.style.display == 'none') {
				$('TA1_' + msgID).hide();
				$('TA2_' + msgID).show();
				//$('commentArea' + msgID).focus(); giving problem in IE
				Effect.toggle(c,'slide', { duration: 0.5});
			} else {
				Effect.toggle(c,'slide', { duration: 0.5});
			}
		}, 

		closeTextarea : function (msgID) {
			var ch = $('allComment_'+msgID).childElements();
			if(ch[0].identify() == 'TA1_'+msgID) {
				//$('comment_'+msgID).hide();
				Effect.toggle('comment_' + msgID,'slide', { duration: 0.5});
			} else {
				//Effect.toggle('TA1_' + msgID,'slide', { duration: 0.5});
				//Effect.toggle('TA2_' + msgID,'slide', { duration: 0.5});
				$('TA1_'+msgID).show();
				$('TA2_'+msgID).hide();
				
			}
		},

		openFilledMessageComment : function(self, msgID) {
			Effect.toggle('allComment_' + msgID,'slide', { duration: 0.5});
			
			if(self.src.match('minimize.gif')) self.src = '/images/maximize.gif';
			else self.src = '/images/minimize.gif'
		},

		beforeInitializeComment : function(msgID, grpID) {
			this.groupid = grpID;
			this.msgid = msgID;
		},

		initializeComment : function(msgID, catID) {
			if(typeof this.cmt[msgID] != 'object')  
				this.cmt[msgID] = new setComment('MF_B('+msgID+', '+catID+')'); 

			var cmtObject = this.cmt[msgID];
			eval( cmtObject.filepara.clean_up );
			eval( cmtObject.filepara.global );
			eval( cmtObject.filepara.add_file ); 
		},

		messagesToggle : function(self, state, mid, showDel) {
			this.msgid = mid;
			if(state) {
				var f = findPosition(self);
				
				var readUnread = "1";
				if(!this.in_array(mid, this.unreadmessages) &&  $('hdnMsg'+mid).value != "1") {
					readUnread = "0";
				}
				if(readUnread == "1" || showDel == "1"){
					/*
					intRight = 	(windowWidth/2)-365;
					if(windowWidth<1280)
						intRight = 295;
					*/
					//if(BrowserDetect.browser == 'Explorer' && BrowserDetect.OS == 'Windows')
					//f[1] = f[1] - 200;
					
					intRight = 	((parent.windowWidth - 1280)/2)+275;
					if((BrowserDetect.browser == 'Chrome' ||  BrowserDetect.browser == 'Opera') && windowWidth > 1280){
						intRight = intRight - 7;
					}
					if(parent.windowWidth<=1100)
						intRight = 205;
					else if(parent.windowWidth<=1280)
						intRight = 295;	
					if(BrowserDetect.browser == 'Explorer'){$('sidebar').setStyle({ top : f[1] + 'px' ,right: intRight+'px'});}
					else {$('sidebar').setStyle({ marginTop : f[1] + 'px',right: intRight+'px' });}				
					//$('sidebar').show();
					$('sidebarDel').hide();
					$('sidebar_markasread').hide();
				}
				if(readUnread == "1")
				{	
					$('sidebar_markasread').show();
				}
				
				$('sbPipe').hide();
				if(showDel == "1")
				{	
					var elemId = 'parMsg_'+mid;
					//alert(elemId);
					var func = escape("ovrDelete(\'Message\',\'"+mid+"\',\'"+elemId+"\')");
					$('sidebarDel').innerHTML = '<a href="javascript:void(0);" onclick="beforeDelete(this,event,\'DC\',\'0\',\''+func+'\');">Delete</a>';
					$('sidebarDel').show();
					
					if(readUnread == "1")
						$('sbPipe').show();
				}
				if(readUnread == "1" || showDel == "1")
					$('sidebar').show();
			}
		},

		moreCommentToggle : function(msgID) {
			if($('showMoreComment_' + msgID).style.display == 'none') {
				$('midMsgText'+msgID).innerHTML = 'Hide';				
				Effect.toggle('showMoreComment_' + msgID,'slide', { duration: 0.5});
 
			} else {
				$('midMsgText'+msgID).innerHTML = 'Show';
				Effect.toggle('showMoreComment_' + msgID,'slide', { duration: 0.5});
			}
		},

		textAreaToggle : function(msgID, catID, state, grpID) {
		
			if(grpID)
				this.beforeInitializeComment(msgID, grpID);
		
			this.initializeComment(msgID, catID);
			if(state) { //alert(typeof(this.cmt[msgID]) +' - '+ msgID);
				$('TA1_' + msgID).hide();
				$('commentArea' + msgID).rows = '2'; 
				$('TA2_' + msgID).show();
				$('commentArea' + msgID).focus();
				 
			} else { //alert(typeof(this.cmt[msgID]) +' - '+ msgID);
				$('comment_' + msgID).hide();
				$('TA1_' + msgID).show(); 
				$('TA2_' + msgID).hide();
			}
		},
	
		markMessageRead : function(m, state) {
			if(!m) m = this.msgid;
			//var mExt = this.markMessageReadExt.bind(this);
			var url = '/groups/alerts/markAsRead.json';
			//new Ajax.Request(url, {method:'post', parameters:{'groupid':this.groupid, 'eventId':this.msgid, val:'M'}, onSuccess:mExt });
			new Ajax.Request(url, {method:'post', parameters:{'groupid':this.groupid, 'eventId':this.msgid, val:'M'} });
			if(state)
			{ 
				this.markMessageReadExt(m);
				if($('hdnMsg'+m))
					$('hdnMsg'+m).value = "0";
			}
		},
		
		markMessageReadExt : function(m) {
			$('message_'+m).className = $('message_'+m).className.replace('unRead', 'read');
			if($('hdnMsg'+m)) $('hdnMsg'+m).value = "0";
			this.markCommentRead(m);
			
			for(var s=0; s<this.unreadmessages.length; s++) {
				if(Number(this.unreadmessages[s]) == Number(this.msgid))
					this.unreadmessages.splice(s, 1);
			}

			$('unReadMessages').value = this.unreadmessages.join('-');
		},
		
		markAllMessageRead : function() {
			this.tmp = $('unReadMessages');
			var mExt = this.markAllMessageReadExt.bind(this); 
			var url = '/groups/alerts/markAllMessagesAsRead.json';
			new Ajax.Request(url, {method:'post', parameters:{'grpId':this.groupid, val:'M'}, onSuccess:mExt });
		},
		
		markAllMessageReadExt : function(t) {
			if(t.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			if(this.tmp.value != '') {
				var x = this.tmp.value.split('-');
				for(var i=0; i<x.length; i++) {
					$('message_' + x[i]).className = 'read';
					this.markCommentRead(x[i]);
				}
			}
			
			var arrHiddenVar = $$('input.clsHdnVar');
			var arrLength = arrHiddenVar.length;
			for(i=0;i<arrLength;i++)
			{ 
				arrHiddenVar[i].value = '0';
			}
			this.unreadmessages = '';
			$('unReadMessages').value = '';
		},
		
		markCommentRead : function(m) {
			var t = $('allComment_' + m).getElementsByClassName('unRead');
			
			if(BrowserDetect.browser == 'Opera') {
				for(var s=0; s<t.length;)
					t[s].removeClassName('unRead');
					
			} else if(BrowserDetect.browser != 'Explorer') {
				if(BrowserDetect.browser == 'Firefox' && BrowserDetect.version < 3) {
					for(var s=0; s<t.length; s++)
						t[s].removeClassName('unRead');
				} else {
					for(var s=0; s<t.length;)
						t[s].removeClassName('unRead');
				}
			} else {
				for(var s=0; s<t.length; s++) {
					var d = t[s].className;
					var dd = d.replace('unRead', '');
					t[s].className = dd;
				}
			}
		},
		
		getElementsByName: function(name, element) {

			var temp = document.getElementsByTagName(element);
			var elem = '';
			var matches = [];
			for(var i=0;i<temp.length;i++){
					if(typeof(temp[i].name) != 'undefined'){
			        if(typeof(temp[i].name) == "object")
		        	        elem = temp[i].id;
			        else elem = temp[i].name;
		
		        	if(elem.match(name) != null && (matches.indexOf(temp[i]) == -1)) 
		                	matches.push(temp[i]);
		            }    	
			} 
			return matches;
		},
		
		removeFile : function(file) {
	        if(BrowserDetect.browser == 'Explorer') $(file).removeNode(true);
	        else $(file).remove();
		},
		
		getRemoveLink : function(linkformid, remlinkid) {
			if($(remlinkid))
				$(remlinkid).innerHTML = ' <a href="javascript:void(0)" onclick="messagesObject.removeFile(\''+linkformid+'\')">Remove</a>';
		},
		
		removeIframe : function() {
			var iframe = this.getElementsByName("micox-temp", "iframe");
			var length = iframe.length;
			for(var i=0; i<length; i++) {
				if(BrowserDetect.browser == 'Explorer') iframe[i].removeNode(true);
		        else iframe[i].remove();
			}
		},
		
		getUniqueID : function() {
			var temp = '';
			var length = 20;
			//var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'; // NOT WORKING IN IE 7
			var chars = new Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0');
			
			for(var i=0; i<length; i++)
				temp += chars[Math.floor(Math.random() * 62)]; 

			return temp;
		},
		
		openMessagePopup : function() {
			if($('formDivs').innerHTML == '') {
				this.setGroupID();
				MF_A_AddFile();
			}
			
			if($('createCategory')) $('createCategory').hide();
			$(this.popup).show();
			$('messageCreate').reset();

			if($('category').options[0].text.toLowerCase().match(/create new category/g)) var selID = 1;
			else  var selID = 0;

			$('category').selectedIndex = selID;
			centerPos($(this.popup), 1);
		},
		
		closeMessagePopup : function() {
			$('errDiscSubject').hide();
			$('msgTitle').removeClassName('inpErr');
			$('errDiscText').hide();
			$('msgDesc').removeClassName('inpErr');
			
			$(this.popup).hide();
			var tp = MF_A();
			eval( tp.clean_up );
			eval( tp.add_file );
			eval( tp.global );
			$(tp.id_element).innerHTML = '';
			$(tp.id_element).hide();
			$(tp.html_show_loading).hide();
		},
		
		createCategory: function(){
			$("errorMsgTitle").hide();		
			if($("category").value == 0) {
				$("createCategory").show();
				$("strCategory").focus();
			} else {
				if($("strCategory")) $("strCategory").value = "";
				$("errorMsgTitle").innerHTML = "&nbsp;";
				if($("createCategory")) $("createCategory").hide();
			}
		},
		
		addMessageCategory: function(){
			var strCategory = $("strCategory").value;
			
			this.setGroupID();
			
			if(strCategory.blank())
			{
				$("errorMsgTitle").style.display = 'block';
				$("errorMsgTitle").innerHTML = "Please enter category";
				$("strCategory").focus();
				return;
			}
			
			if(strCategory.indexOf(',') != "-1")
			{
				$("errorMsgTitle").style.display = 'block';
				$("errorMsgTitle").innerHTML = "<font color='red'>You can enter only one category at a time.</font>";
				$("strCategory").focus();
				return;
			}
			
			if(strCategory.length > 25)
			{
				$("errorMsgTitle").style.display = 'block';
				$("errorMsgTitle").innerHTML = "<font color='red'>Cannot exceed 25 characters.</font>";
				$("strCategory").focus();
				return;
			}
		
			var addMessageCategoryResponse = this.addMessageCategoryResponse.bind(this);
			var url    = "/groups/messages/addMessageCategory.json";
			//var pars   = "groupid="+this.groupid+"&txtCategory="+strCategory+"&from=messages";
			var myAjax = new Ajax.Request( url, {method: "post", 
							parameters: {groupid:this.groupid, txtCategory:strCategory, from:'addCategory'}, 
							onComplete: addMessageCategoryResponse} );		
		},
	
		addMessageCategoryResponse: function(originalRequest){
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			
			if(originalRequest.responseText == "exists")
			{
				$("errorMsgTitle").style.display = 'block';
				$("errorMsgTitle").innerHTML = "Category exists";
				$("strCategory").value = "";
				$("strCategory").focus();
			}
			else if(originalRequest.responseText == '0') 
			{
				$("errorMsgTitle").style.display = 'block';
				$("errorMsgTitle").innerHTML = "You are not authorized to create a category";
				$("strCategory").value = "";
				$('createCategory').hide();
			}
			else
			{
				$("errorMsgTitle").innerHTML = '&nbsp;';
				$("errorMsgTitle").style.display = 'none';
				$("strCategory").value = "";
				$('createCategory').hide();
				$("loadCategories").show();
				var data = originalRequest.responseText;
				data = eval( "("+ data +")" );
				var newData = data.split('##~~##');
				$('category').update(newData[0]);
				
				if($('category_content')) $('category_content').update(newData[1]);
			}
		},
		
		/* THE UPLOAD FUNCTION */
		micoxUpload : function(form, url_action, id_element, html_show_loading, html_error_http, objName) {

			form = typeof(form)=="string"?$m(form):form;
		
			//creating the iframe
			var iframe = document.createElement("iframe");
			iframe.setAttribute("id","micox-temp");
			iframe.setAttribute("name","micox-temp");
			iframe.setAttribute("width","0");
			iframe.setAttribute("height","0");
			iframe.setAttribute("border","0");
			iframe.setAttribute("style","width: 0; height: 0; border: none;");
		 
			//add to document
			form.parentNode.appendChild(iframe);
			window.frames['micox-temp'].name="micox-temp"; //ie sucks

			//attaching objName to the form
			if(objName != '') {
				var inpHidden = document.createElement("input");
				inpHidden.name = "objName";
				inpHidden.type = "hidden";
				inpHidden.value = objName;
			 
				form.appendChild(inpHidden);
			}

			//properties of form
			form.setAttribute("target","micox-temp");
			form.setAttribute("action",url_action);
			form.setAttribute("method","post");
			form.setAttribute("enctype","multipart/form-data");
			form.setAttribute("encoding","multipart/form-data");
			
			//alert('submiting form in micox '+ objName);
			
			//submit
			form.submit();

			return iframe; 
		},
		
		getAttachment : function(data) {
			var attachment = '';
			if(data['data'].length > 0) {
				messagesObject.etc = '';
				var obj = data['data'];
				var other = data['other'];
				var header = '';
				
				if(other['totalCount'] > 1){
					header += '<div class="attachmentTitle">'+ other['totalCount'] +' Attachments';
					if(other['imagesCount'] > 1){
						header += ' - <a href="' + other['url'] +'" target="_blank">View all images</a>';
					}
					header += '</div>';
				}
				
				if(other['imagesCount'] > 1) var a_top = header + '<div class="attachments">';
				else var a_top = '<div class="attachments">';

				var a_data = '';

				for(var i=0; i<obj.length; i++) {
					a_data += '<div class="attachmentsBlock">'
		                          +'<div>'
		                            +'<div class="block">';
		                
		                            if(obj[i]['gv']['view']) {
		                            
		                            	a_data += '<img src="'+ obj[i]['gv']['nameExt'] +'" style="cursor:pointer" onclick="javascript:window.open(\''+ obj[i]['gv']['view'] +'\', \'_blank\', \'toolbar=no,location=no,width=650,height=500,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\');return false;"/>';
		                            	
		                            } else {
		                            
		                            	a_data += '<a href="'+ obj[i]['gv']['download'] +'"><img src="'+ obj[i]['gv']['nameExt'] +'" /></a>';
		                            }
		                            
						a_data += '</div>'
		                            +'<div class="fileNameSm">'+ obj[i]['gv']['name'] + ' [ '+ obj[i]['gv']['size'] + ' ]</div>'
		                            +'<div class="smallerFont">';
		                            
						if(obj[i]['gv']['view']) {
							a_data += '<a target="_blank" href="javascript:void(0);" onclick="javascript:window.open(\''+ obj[i]['gv']['view'] +'\', \'_blank\', \'toolbar=no,location=no,width=650,height=500,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\');return false;">View</a>'
										+'<span class="pipe">|</span>';
						}
									
							a_data += '<a href="'+ obj[i]['gv']['download'] +'">Download</a></span></p><br class="clearLeft" />'
		                            +'</div>'
		                          +'</div>'
		                       +'</div>';
				}
				
				var a_bottom = '<div class="clearLeft">&nbsp;</div></div>';
				attachment = a_top + a_data + a_bottom;
			}
			return attachment;
		},
		
		in_array : function( what, where ) {
 			var a=false;
 			var len = where.length;
			for(var i=0;i<len;i++){
				if(what == where[i]){
					a=true;
					break;
				}
			}
			return a;
		},
		
		paginateData : function (pageNum, catID) {
			var e = this.messagesVar.bind(this);
			new Ajax.Request('/groups/messages/getMessages.json', {
	  			parameters: { groupid: this.groupid, pageNum: pageNum, catID:catID },
	  			onSuccess: e
			});
		},
		
		getTotPageMsg : function(catID,crit,cntFld,order)
		{
			var rand   = Math.random(99999);
			new Ajax.Request('/groups/messages/getTotPageMsg.json', {
	  			parameters: { crit:crit, cntFld:cntFld, order:order, rand:rand },
	  			onSuccess: function(transport)
	  				{
	  					if(transport.responseText == "expired")
				        {      
				        	window.location = MainUrl + "main.php?u=signout";return;
				        }
				        else
				        {
				        	data = (eval('(' + transport.responseText + ')'));
				        	totPage = data.msgCnt;
	  						$('hdnCurrPageNo').value=totPage;
	  						messagesObject.paginateData(totPage, catID);
				        }	
	  				}
			});
		},
		
		setGroupID : function() {
			if($('selectedIDscm')) {
				this.groupid = $('selectedIDscm').value;
				//this.encgroup = $('enc'+ this.groupid +'cm').value;
			} else {
				this.groupid = $('currGrpId').value;
				//this.encgroup = $('encGroup').value;
			}
			//alert(this.groupid);
		}
	});
	
	/*	COMMENT CLASS	*/
	
	var setComment = Class.create({
	
		initialize : function(objectname) {
			this.objectname = objectname;
			if(objectname != '') {
				this.filepara = eval(objectname);
				this.fileMsgCnt = 0;
				//this.encgroup = $('encGroup').value;
				this.groupid = this.filepara.groupid;
				this.msgid = this.filepara.msgid;
				this.catid = this.filepara.catid;
				this.comment = ''; 
				this.uMF = '';
				this.commentSubmitted = false;
				this.cmtAttachment = '';
			}
		},
		
		submitComment : function() {
			if (!this.commentSubmitted) {
				this.comment = $(this.filepara.area).value; 
				this.commentSubmitted = true;
				var vFBU = new checkFBU( 'MF_B('+this.msgid+','+this.catid+')' );
				if(this.comment != ''){
					$('saveMsgComm'+this.msgid).addClassName('butDisabled');
					vFBU.showUploaderOnSubmit();
				}	
				if( vFBU.validatefiles() )  { this.commentSubmitted = false; }		
			}
		}
	
	});
	
	
	/*	GET SIZE OF THE FILE BEFORE STARTING UPLOAD	*/

	var checkFBU = Class.create({
	
		initialize : function(objectname) {
		
			this.objectname = objectname;
			this.valid = '';
			this.invalid = '';
			this.iframe = '';
			this.totalfile = 0;
			this.currentForm = '';
			this.diskspacefull = false;
			this.returnfalse = false;
			this.totaluploadsize = 0;
			this.tmpurl = '/groups/messages/tmpUpload';
			this.returnfalse = false;

			if(objectname != '') {
				this.filepara = eval(objectname);
				this.form = messagesObject.getElementsByName(this.filepara.form, "form");
				this.length = this.form.length;
	
				if(!this.checktextarea())
					this.returnfalse = true;
	
				if(!this.returnfalse)
					if(!this.clearempty())
						this.returnfalse = true;
	
			}
		},
		
		showUploaderOnSubmit : function (){
			if(this.form[0].name.value != '') {
					var oldId = this.form[0].APC_UPLOAD_PROGRESS.value;
					$('element_' + oldId).style.display='';
					$('element_' + oldId + '_percentImage').style.display='';
					$('element_' + oldId + '_percentText').innerHTML = '0%';
					$('element_' + oldId + '_percentText').style.display='';
			}
	
		},
	
		validatefiles : function() { 
			if(this.returnfalse) {
				eval ( this.filepara.global  );
				return false;
			} else {
				this.removeLink(false);
				//$(this.filepara.id_element).hide();  
				//$(this.filepara.id_element).innerHTML = '';
	 			//$(this.filepara.id_element).className = '';
	 			$(this.filepara.html_show_loading).show(); // show loader
			}
			//alert(this.form[0].name.value);
			if(this.form[0].name.value != '') {
	                for(var z=0; z<this.length; z++) {
                        	if(this.form[z].vx.value=='tt' && typeof(this.form[z]) != "undefined") {
                        			//alert('child : ' + z);        
                                	this.form[z].vx.value = 'ff';
	                                this.iframe = messagesObject.micoxUpload(this.form[z], this.tmpurl, this.filepara.id_element, this.filepara.html_show_loading, this.filepara.html_error_http, '');
                                	this.totalfile = this.totalfile + 1;
                                	this.currentForm = this.form[z];
                                	this.checkStatus();
	                                return;
        	                }
                	}
	         } else {
        	        this.totalfile = this.totalfile + 1;
	        }
	       // alert('wow');
        	this.displaystatus();
		},
	
		checkStatus : function() {
	
			var invform = this.currentForm;
			var userID = $('SessStrUserID').value;
			var filterOut = this.filterOut.bind(this);
			
			new Ajax.Request("/groups/messages/getUploadFileStatus.json", { 
				'method':'post', 
				'parameters':{'id':invform.APC_UPLOAD_PROGRESS.value, 'groupid':invform.group.value, 'totaluploadsize':this.totaluploadsize, 'sessUID':userID},
				onSuccess:filterOut  
			});
		},
	
		filterOut : function(resp) {
			var data = eval( "("+ resp.responseText +")" );
			
	        if(data.id != "4") {
	        
	        	if(resp.responseText == "expired") {
	        	      
		        		window.location = MainUrl + "main.php?u=signout";return;
		        		
		        } else {
	
					this.iframe.src = tmpURL;
	                
	                if(data.id == "1") this.invalid += data.file+", ";
	                else if(data.id == "2") this.diskspacefull = true;
	                else this.valid += data.file+", ";
	                
	                this.totaluploadsize = this.totaluploadsize + parseFloat(data.size);
					this.validatefiles();
				}
	
	        } else { 
					this.checkStatus();	
			}
		},
	
		displaystatus : function() {
		
			//alert(this.length+"-"+this.totalfile);
			if(this.length == this.totalfile) {
				//alert("valid : "+this.valid+"\r\n"+"invalid : "+this.invalid); 
	
				this.changevx();
				
				if(!this.diskspacefull) {
					if(this.invalid != '') {
						
						this.showerror("<b>"+this.invalid+"</b> exceeds maximum file limit");
						eval( this.filepara.add_file ); //add new file
	
					} else {

						eval( this.filepara.get_comment );
						
						if( this.filepara.comment_edit != '' )
							eval( this.filepara.comment_edit );
							
					}
				} else {
						
						this.showerror("<b>Disk space full</b>");
						eval( this.filepara.add_file ); //add new file

				}
			}
		},
	
		changevx : function() {
	
			for(var z=0; z<this.length; z++) {
	
				var oldId = this.form[z].APC_UPLOAD_PROGRESS.value;
				var id = messagesObject.getUniqueID();
				this.form[z].vx.value = 'tt';
				
				// progress bar
				$('element_' + oldId).id = 'element_' + id; 
				$('element_' + oldId + '_percentImage').id = 'element_' + id + '_percentImage';
				$('element_' + oldId + '_percentText').id = 'element_' + id + '_percentText';
				
				this.form[z].APC_UPLOAD_PROGRESS.value = id ;
			}
		
			this.totalfile = 0; 
			this.valid = '';
			this.invalid = '';
			this.objectname = '';
			this.totaluploadsize = 0;
			this.diskspacefull = false;
		},
	
		clearempty : function() {
		
	        var limit = 0;
			var length = this.length;
			
	        if(length > 1){
		        for(z=length-1; z>limit; z--){ 
	        	        if(this.form[z].name.value == '') {
	                	        if(BrowserDetect.browser == 'Explorer') this.form[z].removeNode(true);
	                            else this.form[z].remove();
								this.length--;
		                } else {
	        	                limit = -1;
	                	}
	            }
	    	}

			
	        if(this.form[0].name.value == '') 
				this.form.length = 1;

			//reinitialize form element
			this.form = messagesObject.getElementsByName(this.filepara.form, "form");

			//set the last file upload
			this.form[this.length-1].last.value = 1;
			
	    	return true;
		},
	
		checktextarea : function() {
		
			var errOccured;	
			
			if(this.filepara.area != "")	{
			
				if(this.filepara.errDiscSubject && $(this.filepara.area).value.strip() == '') {
					//this.showerror(this.filepara.error_message);
					$(this.filepara.errDiscSubject).show();
					$(this.filepara.area).addClassName('inpErr');
				 	//return false;
				 	errOccured = 1;
				} else {
					if(this.filepara.errDiscSubject)
					{
						$(this.filepara.errDiscSubject).hide();
						$(this.filepara.area).removeClassName('inpErr');
					}
				} 
				
				if($(this.filepara.cat_dd) && $(this.filepara.cat_dd).value.strip() == '0') {
					this.showerror(this.filepara.error_cat);
				 	//return false;
				 	errOccured = 1;
				} else {
					//return true;
				} 
				
				if($(this.filepara.area_desc) && $(this.filepara.area_desc).value.strip() == '') {
					//this.showerror(this.filepara.error_desc);
					$(this.filepara.errDiscText).show();
					$(this.filepara.area_desc).addClassName('inpErr');
					//return false;
					errOccured = 1;
				} else {
					if(this.filepara.area_desc)
					{
						$(this.filepara.errDiscText).hide();
						$(this.filepara.area_desc).removeClassName('inpErr');
					}
				}
				
				if(errOccured == 1) return false; else return true; 
			}
		},

		removeLink : function(state) {
 			//var rm_lk = getElementsByClassName1(this.filepara.removelinkclass);//alert(this.filepara.removelinkclass +' :: '+rm_lk.length);
            var rm_lk = $$('span.'+this.filepara.removelinkclass);
            var len = rm_lk.length;
            for(var i=0; i<len; i++) {
            	if(state) rm_lk[i].show();
                else rm_lk[i].hide();
           	}
        },
	
		showerror : function(message) { 
			this.removeLink(true);
			$(this.filepara.id_element).show();
			$(this.filepara.id_element).className = 'err';
			$(this.filepara.id_element).innerHTML = message;
			$(this.filepara.html_show_loading).hide();
			eval ( this.filepara.global  );
			messagesObject.removeIframe();
			
		}
	});

	var uploadMF = Class.create({
	
		initialize : function(objname) {
			
			this.objname = objname;
			this.filepara = eval( this.objname );
	        this.form = messagesObject.getElementsByName(this.filepara.form, "form");
	        this.length = this.form.length;
	        if($('SessStrUserID')) this.userID = $('SessStrUserID').value;
	        if($('category')) this.catid = $('category').value; // only used for popup
	        this.lastid = "";
	        this.timeoutID = null;
	        this.timeoutAPCID = "";
	        this.timeoutPeriod = 1000;
	        //this.status = "";
	        //this.statusTemplate = "";
	
		},
	
		submitform : function() { //alert('submitform : ' + this.filepara.submit_comment);
		
			if(this.filepara.before_submit != "")
				eval( this.filepara.before_submit );
				
			var s = eval( this.filepara.submit_comment );
			messagesObject.micoxUpload(s.form, s.url_action, s.id_element, s.html_show_loading, s.html_error_http, this.filepara.objName);

			if(this.filepara.after_submit != "")
				eval( this.filepara.after_submit );
		},
		
		upload : function(lastid) {
		
			if(lastid == '') this.lastid = $(this.filepara.lastid).value;
			else { $(this.filepara.lastid).value = lastid; this.lastid = lastid; }
		
			//alert('last inserted ID : '+ this.lastid +' - '+this.filepara.objName);
		
			for(var z=0; z<this.length; z++) { //this.form[z].name.value != '' && 
	 			if(this.form[z].style.display != 'none' && this.form[z].vx.value=='tt' && typeof(this.form[z]) != "undefined") { 
                    this.form[z].lastid.value = this.lastid;
	                messagesObject.micoxUpload(this.form[z], this.filepara.url_action, this.filepara.id_element, this.filepara.html_show_loading, this.filepara.html_error_http, this.filepara.objName);
	                
	                if(this.form[z].name.value.strip().length > 0) {
		                this.timeoutAPCID = this.form[z].APC_UPLOAD_PROGRESS.value;
		                /*this.status = $('status_'+this.timeoutAPCID);
		                this.statusTemplate = new Template(this.status.innerHTML); 
		                this.status.update();*/
		                
		                execFunctionVar = this.filepara.objName + '.monitorStatus()';
						this.timeoutID = setInterval( "execFunction()", this.timeoutPeriod );
						
		                startProgressBar(this.timeoutAPCID);
					}
	                
        	        return;
	        	}
			}
			
			var m = this.setGCT.bind(this);
			if(this.length == z) { //alert(this.length +' :: '+ z +' :: '+ this.filepara.got_gct);
				if(!this.filepara.got_gct) {
					this.filepara.got_gct = true;
		        	var url = this.filepara.gct;
					var pars = 'groupid='+this.filepara.groupid+'&msgID='+this.lastid;
					new Ajax.Request(url, { method:'post', parameters:pars , onSuccess: m });
				
					this.showHideLoader(false);
	            	this.cleanup();
				}
	        }
		},

		monitorStatus : function() {
		
			var onMonitorStatus = this.onMonitorStatus.bind(this);
			var url = '/groups/messages/monitorFiles.json';
			new Ajax.Request(url, { method:'post', parameters:{ 'id':this.timeoutAPCID }, onSuccess: onMonitorStatus });
		
		},
		
		onMonitorStatus : function(req) {
			var data = eval( "(" + req.responseText + ")" );
			if(data.error != '1') {
				if(data.finished == '1') {
					clearInterval(this.timeoutID);
					this.timeoutID = null;
				}
				//this.status.show();
				//this.status.update(this.statusTemplate.evaluate(data));
				progressBarObject.move(data.percent, data.complete, data.total);
				
			} else {
				//clearInterval(this.timeoutID);
				//alert('error');
			}
		},

		pauseForASecond : function() {
			if(this.timeoutID) {
				execFunctionVar = this.filepara.objName + '.pauseForASecond()';
				setTimeout( "execFunction()", 1000);
			} else {
				this.upload('');
			}
		},

		cleanup : function() {
		
			eval( this.filepara.clean_up );
			eval( this.filepara.global );
			eval( this.filepara.add_file );
		},
		
		setGCT : function(resp) {
	
			if(resp.responseText == "expired") {      
				window.location = MainUrl + "main.php?u=signout";return;
			}

			var data = eval( "("+ resp.responseText +")" );

			if( this.filepara.after_gct ) {
				
				var d = this.filepara.after_gct.replace('data', data['other']['cd']);
				d = d.replace('msgid', this.lastid);
				d = d.replace('parent', data['other']['parent']);
				d = d.replace('level', data['other']['level']);
				if(data['other']['parent'] != '0') messagesObject.cmt[data['other']['parent']].cmtAttachment = data;
				else messagesObject.etc = data;

				eval( d );
			}

			var t = this.filepara.after_upload.replace('lastid', this.lastid);
			eval( t );
		},
		
		removeChild : function(z) {	//alert('removechild : '+z);
	
			var f = $(this.filepara.form +"_"+ z);
	        f.vx.value = 'ff';
	        
	        if(this.filepara.objName) execFunctionVar = this.filepara.objName + '.pauseForASecond()';
	        else execFunctionVar = 'uF.pauseForASecond()';
	        
	        setTimeout( "execFunction()", 1000);
			//this.upload('');
		},
		
		showError : function(error,type) {		
		    $(this.filepara.id_element).style.display = '';
		    $(this.filepara.id_element).className = 'err';
		    $(this.filepara.id_element).innerHTML = error;
		    
		    if(type && type=="M")
		    {
		    	var errDiv = this.filepara.id_element;
		    	setTimeout("$('"+errDiv+"').hide()",5000);
		    }
		    
		    this.showHideLoader(false);
		    eval(this.filepara.global);
		    //eval( this.filepara.add_file ); //add new file
		},
		
		showHideLoader : function(n) {
		
			if(n) $(this.filepara.html_show_loading).show();
			else $(this.filepara.html_show_loading).hide(); 
		}
	
	});

	
	/*	related to uploader	*/
	
	var execFunctionVar = null;
	function execFunction() { eval(execFunctionVar); }
	
	var progressBarObject = null;
	var progressBar = Class.create({

		initialize : function(el) {
			this.ratio = 1.2;
			this.rate = 10;

			this.element = 'element_' + el;
			this.elementText = 'element_'+ el +'_percentText';
			this.elementImage = 'element_'+ el +'_percentImage';

			$(this.element).show();
			$(this.elementText).show().update('0%');

		},
	

		move : function(n, com, tot) {
                this.t = Math.round(120 - ( this.ratio * n ));
                this.t = -this.t + 'px 50%';
                $(this.elementImage).setStyle({ backgroundPosition:this.t });
                $(this.elementImage).alt = com +' / '+ tot;
                $(this.elementImage).title = com +' / '+ tot;
                $(this.elementText).update(n + '%');
        }

	});

	function startProgressBar(el) {
		progressBarObject = new progressBar(el);
	}
	
	function MF_A() { // FOR POPUP (file upload)
			var groupid = group = messagesObject.groupid;
			//var group = messagesObject.encgroup;
	        var filepara = { form : "frmFileUpload",
	        				 groupid : groupid,
	        				 objName : 'messagesObject.uMF',
	                         url_action : "/groups/messages/uploadFile/"+groupid,
	                         id_element : "errSubject",
							 area : "msgTitle",
							 area_desc : "msgDesc",
							 cat_dd : "category",
							 errDiscSubject : "errDiscSubject",
							 errDiscText : "errDiscText",
	                         html_show_loading : "upload_1",
	                         html_error_http : "Error", 
							 error_message : "Please enter subject",
							 error_desc : "Please enter text",
							 error_cat : "Please select a category",
							 global : "messagesObject.messageSubmitted=false;",
							 get_comment : "messagesObject.uMF = new uploadMF('MF_A()'); messagesObject.uMF.submitform();",
							 add_file : "MF_A_AddFile();",
							 edit_comment : "",
							 submit_comment : "MF_A_Message();",
							 before_submit : "var c = $('category').value; var arr = document.getElementsByName('groupmessagecategory_id'); for(var i=0; i<arr.length; i++) arr[i].value = c;",
							 after_submit : "",
							 lastid : "getlastid",
							 removelinkclass : "dummy_msgRemoveLink",
							 gct : "/groups/messages/GCT.json",
							 got_gct : false,
							 after_gct : "MF_A_AfterGCT('data', msgid, parent, level)",
							 after_upload : "MF_A_AfterUpload("+groupid+", lastid);",
							 clean_up : "messagesObject.fileCnt = 0; $('getlastid').value = '0'; $('formDivs').innerHTML = '';$('postDiscussion').removeClassName('butDisabled');"
	                       };
	        return filepara;
	}
	
	function MF_A_Message() { // FOR POPUP (message)
	        var filepara = { form : "messageCreate",
	                         url_action : "/groups/messages/SMA/",
	                         id_element : "errorMsgTitle",
	                         html_show_loading : "",
	                         html_error_http : ""
	                       };
	        return filepara;
	}
	
	function MF_A_AddFile() {

        messagesObject.fileCnt = messagesObject.fileCnt + 1;	
		id = messagesObject.getUniqueID();
        var divid = "msgFile"+messagesObject.fileCnt;
        var formid = "frmFileUpload_"+messagesObject.fileCnt;
        var newform = document.createElement("form");
        newform.id = formid;
        newform.name = "frmFileUpload";
        newform.method = 'post';
        newform.style.display = 'block';
		newform.style.paddingBottom = "10px";
        newform.enctype = 'multipart/form-data';
        document.getElementById("formDivs").appendChild(newform);
        document.getElementById(formid).innerHTML = '<input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="'+id+'"/>'
                                                        +'<input type="hidden" name="childno" id="childno" value="'+messagesObject.fileCnt+'"/>'
                                                        +'<input type="hidden" name="lastid" id="lastid" value="0" />'
                                                        +'<input type="hidden" name="last" id="last" value="0" />'
							+'<input type="hidden" name="vx" id="vx" value="tt"/>'
							+'<input type="hidden" name="group" id="group" value="'+messagesObject.groupid+'" />'
							+'<input id="groupmessagecategory_id" type="hidden" name="groupmessagecategory_id"/>'
							+'<div id="filePara">'
							+'<input type="file" name="name" id="'+divid+'" onchange=\'if(this.value) MF_A_AddFile();\'/>&nbsp;&nbsp;'
							+'<span id="removeLinkSpan_'+messagesObject.fileCnt+'" class="dummy_msgRemoveLink"></span>'
							+'<span id="addLinkSpan_'+messagesObject.fileCnt+'"></span>'
							//+'<span id="status_'+id+'" style="display:none"> #{complete}/#{total} (#{percent}%)</span>'
                            +'&nbsp;<span id="element_'+id+'" style="display:none">'
                                    +'<img class="percentImage" style="margin: 0pt; padding: 0pt; width: 120px; height: 16px; background-position: -120px 50%; background-image: url(/images/bramus/percentImage_back.png);" alt="" src="/images/bramus/percentImage.png" id="element_'+id+'_percentImage" title=""/>'
                            +'</span>'
                            +'&nbsp;<span id="element_'+id+'_percentText" style="display:none"></span>'
                            +'</div>';

		if(messagesObject.fileCnt > 1) {
			var link = messagesObject.fileCnt-1;
			var linkid = "frmFileUpload_"+link;
			var remlinkid = "removeLinkSpan_"+link;
			$("msgFile"+link).removeAttribute("onchange");
			$("addLinkSpan_"+link).remove();
			messagesObject.getRemoveLink(linkid, remlinkid);
		}
	    // positionFooter(); //This line throws an error in IE6
	}
	
	function MF_A_AfterUpload(groupid, lastid) {

		if($('selectedIDs0')) $('selectedIDs0').value = groupid;
		//messagesObject.removeIframe(); NOT WORKING IN IE
		$(messagesObject.errortitle).hide();

		if($(messagesObject.successtitle)){
			$(messagesObject.successtitle).className = 'msgCreated';
			$(messagesObject.successtitle).innerHTML = 'Message saved successfully';
			
			setTimeout("$(messagesObject.popup).hide();$(messagesObject.successtitle).innerHTML='';$(messagesObject.successtitle).className='';", 1000);
			
			if(comet.page=='overview') 
				comet.connect('overview',1);
		} else {
			$(messagesObject.popup).hide();
		}
	}
	
	function MF_A_AfterGCT(d, lastid, parent, level) {
		
		if($('messCatID')) {
			if($('messCatID').value > 0) {
				messagesObject.callMessages(0, 1);
				return false;
			}
		}
		
		if($('currPageCat').value == 'msgdld') {
			messagesObject.callMessages();
			return false;
		}
		
		if($('noMessageDiv')) {
			$('noMessageDiv').hide();
			//Element.remove($('noMessageDiv'));
			$('innerContent').show();
		}
		
		var attachment = '';
		/*	if attachments are present	*/
		if(messagesObject.etc != '') {
			attachment = messagesObject.getAttachment(messagesObject.etc);  
		}
		var select = $('category');
		var top = '<div class="threadDate"><span class="threadDateDisplay">Today</span></div>'
                  +'<div class="threadGroup">'
                  	+'<!--<div id="parMsg_'+ lastid +'" class="postGroupTop">-->';
		
		var data = '<div id="parMsg_'+ lastid +'" class="postGroupTop"><div id="message_'+ lastid +'" class="read" onmouseover="messagesObject.messagesToggle(this, true, '+ lastid +',1);" onmouseout="messagesObject.messagesToggle(this, false, '+ lastid +',1);">'                    
                        +'<div>'
                         +'<div class="title3"><a href="javascript:void(0)" onclick="messagesObject.callMessagesDetailed('+lastid+','+messagesObject.groupid+');">'+ wrapTitle(htmlspecialchars($('msgTitle').value), true) +'</a></div>'
                         +'<div class="postTitleBy1">posted by <span>Me</span> on '+ d +' in '+select.options[select.selectedIndex].text+'</div>'
                        +'<div class="postDataDesc">'+ wrapDesc(htmlspecialchars($('msgDesc').value)) +'</div>'
                        + attachment                        
                   		+'<div id="butMessageComment_'+lastid+'" class="butCommentDiv">'
                            +'<a class="butComment" href="javascript:void(0);" onclick="messagesObject.openMessageComment('+lastid+', '+select.value+')">Comment</a>'
                        +'</div>'
	                    +'<div id="comment_'+lastid+'" class="comments" style="display:none">'
	                    +'<div id="allComment_'+lastid+'">'
                            +'<div id="TA1_'+lastid+'" class="commentsCont gainlayout">'
                            		+'<div style="border: 1px solid rgb(181, 222, 249); padding: 8px; background: rgb(250, 251, 252) none repeat scroll 0% 0%; width: 90%; font-size: 12pt; font-weight: bold; color: rgb(203, 203, 203);" onclick="messagesObject.textAreaToggle('+lastid+', '+select.value+', true)">Write more comments...</div>'
                            +'</div>'
                            +'<div id="TA2_'+lastid+'" class="commentsCont" style="display:none">'
                            	+'<div class="minGrey rightFloat closeRelative"><a onclick="messagesObject.closeTextarea('+lastid+');" href="javascript:void(0)">-</a></div>'                            	
                            	+'<div class="postTitleSm"><img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /></div>'
                        		+'<div class="postDataSm">'
	                        		+'<form method="post" id="commentFrm'+lastid+'" >'

	                        			+'<div id="commentDisplay'+lastid+'" class="field">'
	                              			+'<textarea name="data[Groupmessage][description]" id="commentArea'+lastid+'" style="width:90%" onkeyup="ta_autoresize(this);" rows="2" cols="65"></textarea>'
	                            			+'<span id="errCommentArea'+lastid+'" class="inlineErr" style="display : none;">Please enter comment</span>'
	                            		+'</div>'
										+'<input name="data[Groupmessage][group_id]" id="groupid" type="hidden" value="'+messagesObject.groupid+'" /><input name="data[Groupmessage][groupmessagecategory_id]" type="hidden" value="'+select.value+'" /><input name="data[Groupmessage][parent]" id="msgParentId" type="hidden" value="'+lastid+'" />'
	                            	+'</form>'
	                            	+'<div class="field">'
	                            		+'<div id="formDivsComments_'+lastid+'"></div>'
	                            	+'</div>'
	                            	+'<div id="commentStatus'+lastid+'" style="display:none"></div>'
	                            	+'<div class="butBlueDiv leftFloat"><a id="saveMsgComm'+lastid+'" href="javascript:void(0)" class="butBlue"><span onclick="messagesObject.cmt['+lastid+'].submitComment()";>Comment</span></a></div>'
	                            	+'<div valign="top" id="loading'+lastid+'" style="display:none" class="loadingImgDiv"><img src="/groups/img/loading.gif" class="loadingImgLeft"></img></div>'
	                          		+'<div class="clearLeft1"></div>'
	                          	+'</div>'
	                          +'</div>'
	                          +'<input type="hidden" id="lastid_'+lastid+'" value="0" />'
	                          +'<input type="hidden" id="hdnMsgCommCount'+lastid+'" value="0" />'
	                          +'<input type="hidden" class="clsHdnMsg clsHdnVar" id="hdnMsg'+lastid+'" value="0">'
	                    +'</div>'
	                    +'</div>'
	                    +'</div>'	                   
                    +'</div>'
                    +'<div class="clearBoth"></div>' 
                    +'</div>';
        

       if($('overviewBody')){
                    
        //different textdata for overview section            
        var dataOvr="";
        var k; 
        if(level == 1) k = 'network';
		else if(level == 2) k = 'group';//proj2Gr 
		else if(level == 3) k = 'subgroup';//proj2Gr
                    
        dataOvr = '<div onmouseout="javascript:$(\'sidebar\').hide();" id="parMsg_'+lastid+'" onmouseover="ovrToggle(this, true, '+lastid+', \''+$("selectedValcm").value+'\', \''+ k +'\', \'Message\', '+messagesObject.groupid+',\'\',\'1\',\'\')" class="postGroupTop">';

		dataOvr+='<div class="read" id="message_'+lastid+'">'
					                        +'<div title="General" class="postCategory oneLineLimitSmall"><span>'+select.options[select.selectedIndex].text+'</span></div>'
					                        +'<div class="postData">'                          
					                          +'<div class="postTitleBy">posted by <span>Me</span></div>'
					                          +'<div class="postTitleCont">'+ wrapTitle(htmlspecialchars($("msgTitle").value), true) +'</div>'
					                          +'<div class="postDataDesc">'+ wrapDesc(htmlspecialchars($("msgDesc").value)) +'</div>';

					                if(attachment)
					                {
					                	dataOvr+=attachment;
					                }
				                            dataOvr+='<input type="hidden" value="0" id="hdnMsg'+lastid+'"/>'				                         
				                            		+'<div class="butCommentDiv" id="butMessageComment_'+lastid+'"><a onclick="messagesObject.openOverMessageComment('+lastid+', '+select.value+', '+messagesObject.groupid+')" href="javascript:void(0);" class="butComment">Comment</a></div>'
					                        		+'<div style="display: none;" class="comments" id="comment_'+lastid+'">'
			                            			+'<div style="display: block;" id="allComment_'+lastid+'">'
					                            		+'<div class="commentsCont gainlayout" id="TA1_'+lastid+'">'
				                            				+'<div style="border: 1px solid rgb(181, 222, 249); padding: 8px; background: rgb(250, 251, 252) none repeat scroll 0% 0%; width: 90%; font-size: 12pt; font-weight: bold; color: rgb(203, 203, 203);" onclick="messagesObject.textAreaToggle('+lastid+', '+select.value+', true, '+messagesObject.groupid+')">Write more comments...</div>';

				                            dataOvr+='</div>'
				                            	+'<div style="display: none;" class="commentsCont" id="TA2_'+lastid+'">'
				                            		+'<div class="minGrey rightFloat closeRelative"><a onclick="messagesObject.closeTextarea('+lastid+');" href="javascript:void(0)">-</a></div>'				                            		
				                            		+'<div class="postTitleSm"><img src="'+$('userpic').value+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /></div>'
				                            		+'<div class="postDataSm">'
					                            		+'<form id="commentFrm'+lastid+'" method="post">'
					                            			+'<div class="field" id="commentDisplay'+lastid+'">'
					                                  			+'<textarea name="data[Groupmessage][description]" id="commentArea'+lastid+'" style="width:90%" onkeyup="ta_autoresize(this);" rows="2" cols="65"></textarea>'
					                                			+'<span id="errCommentArea'+lastid+'" class="inlineErr" style="display : none;">Please enter comment</span>'
					                                		+'</div>'
															+'<input type="hidden" value='+messagesObject.groupid+' id="groupId" name="data[Groupmessage][group_id]"/>'+'<input type="hidden" value='+select.value+' name="data[Groupmessage][groupmessagecategory_id]"/>'+'<input type="hidden" value='+lastid+' id="msgParentId" name="data[Groupmessage][parent]"/>'
					                                	+'</form>';

											dataOvr+='<div class="field">'
					                                	+'<div id="formDivsComments_'+lastid+'">'
					                               		+'</div>'
					                               	+'</div>'
					                               	+'<div style="display: none;" id="commentStatus'+lastid+'"/></div>'
					                               	+'<div class="butBlueDiv leftFloat"><a id="saveMsgComm'+lastid+'" class="butBlue" href="javascript:void(0);"><span onclick="messagesObject.cmt['+lastid+'].submitComment()">Comment</span></a></div>'
					                               	+'<div class="loadingImgDiv" style="display: none;" id="loading'+lastid+'" valign="top"><img class="loadingImgLeft" src="/groups/img/loading.gif"/></div>'
					                            	+'<div class="clearLeft1"></div>'
					                            +'</div>'
				                                +'<input type="hidden" value="0" id="lastid_'+lastid+'"/>'
			                              	+'</div>'
										+'</div>'
	                           		+'</div>'
				         		+'</div>'
				               +'</div>'
				         		+'<div class="clearBoth"></div>'
				              +'</div>';

        }
                    
		var bottom = '</div>'
					+'<!--</div>-->';
		
		var strObjNewDiv;
		if($('overviewBody'))
		{
			strObjNewDiv = "parMsg_"+ lastid;			
			var ov = $('overviewBody').childElements();
			var ovFirst = ov[0].childElements();
			var p = ovFirst[0].innerHTML.strip();
			if(p.toLowerCase() == 'today')
			{
				var ovSec = ov[1].childElements();
				if(ovSec.length > 0) ovSec[0].className = "postGroup";
				//var newDiv = Builder.node('div',{id:"parMsg_"+lastid,onmouseover:"ovrToggle(this,true,'"+lastid+"','"+$("selectedValcm").value+"','"+k+"','Message','"+messagesObject.groupid+"','','1')"}).addClassName("postGroupTop").update( dataOvr );
				var newDiv = dataOvr;
				if(ov[1].firstChild) {
					Element.insert( ov[1].firstChild, {'before' : newDiv} );
				} else {
					$('overviewBody').show();
					Element.insert( ov[1], {'before' : newDiv} );
					$('blank').hide();
				}
			}
			else
			{
				//var newDiv = Builder.node('div', {id : 'today'} ).update( top + dataOvr + bottom );
				var newDiv = top + dataOvr + bottom
				Element.insert( $('overviewBody'), {'top' : newDiv} );	
			}
		}
		else
		{
			strObjNewDiv = "message_"+ lastid;
			if($('TODAY')) {
				var t = $('TODAY').childElements();
				var tt = t[1].childElements();
				tt[0].className = 'postGroup';
				var newDiv = Builder.node('div',{id: 'parMsg_'+lastid}).addClassName("postGroupTop").update( data );
				Element.insert( t[1].firstChild, {'before' : newDiv} );
			} 
			else 
			{
				if($('noFilesCat')) $('noFilesCat').hide();
				var newDiv = Builder.node('div', {id : 'TODAY'} ).update( top + data + bottom );
				Element.insert( $('sidebar'), {'after' : newDiv} );
			}

		}
		if(messagesObject.cmt[lastid])
			messagesObject.cmt[lastid] = '1';
			
		new Effect.Highlight($(strObjNewDiv), { startcolor: startColor,endcolor: endColor ,duration: 1 });
	}
	
	function MF_B(msgid, category_id) { // FOR COMMENTS ON GROUPMESSAGE PAGE (file upload)
			var groupid = group = messagesObject.groupid;
			//var messagesObject.encgroup;
	        var filepara = { form : "frmGroupFileUpload_"+msgid,
	        				 groupid : groupid,
	        				 msgid : msgid,
	        				 catid : category_id,
	        				 objName : 'messagesObject.cmt['+msgid+'].uMF',
	                         url_action : "/groups/messages/uploadFile/"+groupid,
	                         id_element : "commentStatus"+msgid,
	                         area : "commentArea"+msgid,
	                         errDiscSubject : "errCommentArea"+msgid,
	                         html_show_loading : "loading"+msgid,
	                         html_error_http : "Error",
	                         error_message : "Please type a comment",
	                         global : "messagesObject.cmt["+msgid+"].commentSubmitted=false;",
	                         get_comment : "messagesObject.cmt["+msgid+"].uMF = new uploadMF('MF_B("+msgid+", "+category_id+")'); messagesObject.cmt["+msgid+"].uMF.submitform();",
				 			 add_file : "MF_B_AddFile("+msgid+")",
	                         comment_edit : "",
	                         submit_comment : "MF_B_Message("+msgid+")",
							 before_submit : "",
							 after_submit : "",
							 lastid : "lastid_"+msgid,
							 removelinkclass : "dummy_msgCmt_"+msgid+"_RemoveLink",
							 gct : "/groups/messages/GCT.json",
							 got_gct : false,
							 after_gct : "MF_B_AfterGCT('data', msgid, parent, level)",
							 after_upload : "MF_B_AfterUpload("+msgid+", lastid)",
							 clean_up : "messagesObject.cmt["+msgid+"].fileMsgCnt = 0; $('commentStatus"+msgid+"').hide(); $('commentArea"+msgid+"').value = ''; $('loading"+msgid+"').hide(); $('formDivsComments_"+msgid+"').innerHTML = ''; $('saveMsgComm"+msgid+"').removeClassName('butDisabled');"
	                       };
	        return filepara;
	}
	
	function MF_B_Message(msgid) { // FOR COMMENTS ON GROUPMESSAGE PAGE (message) 
	        var filepara = { form : "commentFrm"+msgid,
	                         url_action : "/groups/messages/SMB",
	                         id_element : "commentStatus"+msgid,
	                         html_show_loading : "",
	                         html_error_http : ""
	                       };
	        return filepara;
	}
	
	function MF_B_AddFile(msgId){
	
		var id = messagesObject.getUniqueID();
		var group_id = group = messagesObject.groupid;
		//var messagesObject.cmt[msgId].encgroup;
		var category_id = messagesObject.cmt[msgId].catid;
		messagesObject.cmt[msgId].fileMsgCnt += 1;
		var filecnt = messagesObject.cmt[msgId].fileMsgCnt;
		
        var divid = "msgFile_"+msgId+'_'+filecnt;
        var formid = "frmGroupFileUpload_"+msgId+"_"+filecnt;
        var newform = document.createElement("form");
        newform.style.styleFloat = "left";
        newform.id = formid;
        newform.name = "frmGroupFileUpload_"+msgId;
        newform.method = 'post';
        newform.style.display = 'block';
		newform.style.paddingBottom = "10px";
        newform.enctype = 'multipart/form-data';
        document.getElementById("formDivsComments_"+msgId).appendChild(newform);
        document.getElementById(formid).innerHTML = '<div class="leftFloat"><input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="'+id+'"/>'
                                                	+ '<input name="groupid" type="hidden"  value="'+group_id+'" '
	                                                + '<input type="hidden" name="msgParentId" value="'+msgId+'" />'
                                                    + '<input type="hidden" name="childno" id="childno" value="'+filecnt+'"/>'
                                                    + '<input type="hidden" name="lastid" id="lastid" value="" />'
                                                    + '<input type="hidden" name="last" id="last" value="0" />'
										        	+ '<input type="hidden" name="vx" id="vx" value="tt"/>'
										        	+ '<input type="hidden" name="group" id="group" value="'+group+'"/>'
	                                                + '<input type="hidden" name="groupmessagecategory_id" value="'+category_id+'" />'
                                                    + '<input type="file" name="name" id="'+divid+'" onchange=\'if(this.value) MF_B_AddFile("'+msgId+'")\'/>&nbsp;&nbsp;'
							+ '<span class="dummy_msgCmt_'+msgId+'_RemoveLink" id="removeGroupNewLinkSpan_'+msgId+'_'+filecnt+'"></span>'
							+ '<span id="addGroupNewLinkSpan_'+msgId+'_'+filecnt+'"></span>'
							//+'<span id="status_'+id+'" style="display:none"> #{complete}/#{total} (#{percent}%)</span>'
                            +'&nbsp;<span id="element_'+id+'" style="display:none">'
                                    +'<img class="percentImage" style="margin: 0pt; padding: 0pt; width: 120px; height: 16px; background-position: -120px 50%; background-image: url(/images/bramus/percentImage_back.png);" alt="" src="/images/bramus/percentImage.png" id="element_'+id+'_percentImage" title=""/>'
                            +'</span>'
                            +'&nbsp;<span id="element_'+id+'_percentText" style="display:none"></span></div>'
                            +'</div>';
        
        var clearLeft = '<div class="clearLeft">&nbsp;</div>';                    
        $("formDivsComments_"+msgId).insert ( { 'bottom'  : clearLeft } ); 

		if(filecnt > 1) {
	
			var linkid = filecnt-1;
			var linkformid = 'frmGroupFileUpload_'+msgId+'_'+linkid;
			var remlinkid = 'removeGroupNewLinkSpan_'+msgId+'_'+linkid;
			messagesObject.getRemoveLink(linkformid, remlinkid);
			// Cross-browser complaint
			$("msgFile_"+msgId+"_"+linkid).onchange = null;
			$("addGroupNewLinkSpan_"+msgId+"_"+linkid).remove();
		}
	}
	
	function MF_B_AfterGCT(d, lastid, parent, level) {
		
		//var catid = messagesObject.cmt[parent].catid;
		var comment = messagesObject.cmt[parent].comment;
		var commentTime = d;
		var username = $('username').value;
		var userpic = $('userpic').value;
		var attachment = '';

		if(messagesObject.cmt[parent].cmtAttachment != '') {
			attachment = messagesObject.getAttachment(messagesObject.cmt[parent].cmtAttachment);
		}

		var header = '<div class="rightFloat"><img onclick="messagesObject.openFilledMessageComment(this, '+parent+')" id="img_'+parent+'" style="cursor:pointer" src="/images/minimize.gif"/></div>'
                	+'<a onclick="messagesObject.openFilledMessageComment($(\'img_'+parent+'\'), '+parent+')" href="javascript:void(0);"><span id="commentCount'+parent+'">0  comments</span></a>';

        var func = escape("delMsgComment(\'"+lastid+"\',\'"+parent+"\')");         	
        var mouseOver = 'javascript:$("delButMsg_'+lastid+'").show();';
		var mouseOut = 'javascript:$("delButMsg_'+lastid+'").hide();';
		var delBut = '<span class="butComment rightFloat" id="delButMsg_' + lastid +'" style="display:none" onclick="beforeDelete(this,event,\'DC\',\'1\',\''+func+'\');">Delete</span>';
		if($('currPageCat').value == 'msgdld'){
			delBut = '';
			mouseOver = '';
			mouseOut = '';
		}         	
		var data = '<div class="postTitleSm"><img src="'+userpic+'" onmouseout="hideVCard(\''+$('~sec^Log@Ses~').value+'\');" onmouseover="showVCard(\''+$('~sec^Log@Ses~').value+'\',this,event);" /></div>'
                      +'<div class="postDataSm">'
                      	+ delBut                            
                        +'<div class="commentsBy"><a href="javascript:void(0)" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\'];showProfilePage(obj,\'public\',\''+$('SessStrUserID').value+'\');">'+$('username').value+'</a><span> '+commentTime+'</span></div>'
                        +'<div class="commentsDesc">'+ wrapDesc(htmlspecialchars(comment)) +'</div>'
                        + attachment
                    +'</div>';
                    

		var comment = $("comment_"+parent).childElements();
		var allcomment = $("allComment_"+parent).childElements();
		var message = $("message_"+parent).childElements();
		//var messageChild = message[0].childElements();
			
		/* if no comment are present
		if(comment[0].classNames() != 'commentsTotalNo gainlayout') {
			var newDiv = Builder.node('div').addClassName("commentsTotalNo gainlayout").update( header );
			Element.insert( comment[0], {'before' : newDiv} );
		}*/

		if(allcomment[0].identify() == 'TA1_'+parent) {
			var newDiv = Builder.node('div').addClassName("commentsTotalNo gainlayout").update( header );
			Element.insert( $("allComment_"+parent), {'before' : newDiv} );
			
			var newDivcomment = Builder.node('div', {id : 'Msgcomment_'+lastid, onmouseover : mouseOver , onmouseout : mouseOut}).addClassName("commentsCont read gainlayout").update( data );
			Element.insert( allcomment[0], {'before' : newDivcomment} );

			//delete the comment link
			messagesObject.removeFile('butMessageComment_'+parent);
			
		} else {
			m = $("TA1_"+parent);
			var newDivcomment = Builder.node('DIV', {id : 'Msgcomment_'+lastid, onmouseover : mouseOver , onmouseout : mouseOut}).addClassName("commentsCont read gainlayout").update( data );
			Element.insert( m, {'before' : newDivcomment} );
		}
		
		if($('hdnMsgCommCount'+parent)){
			var num = $('hdnMsgCommCount'+parent).value;
			num++;
			$('hdnMsgCommCount'+parent).value =  num;
		}

		$('comment_'+parent).show();			
		$('TA1_'+parent).show();
		$('TA2_'+parent).blur();
		//Element.blur($('TA2_'+parent));
		$('TA2_'+parent).hide();
	}
	
	function MF_B_AfterUpload(parent, lastid) { 

		var d = $('commentCount'+parent);
		var num_comment = parseInt(d.innerHTML) + 1;
		if(num_comment > 1)
		{
			d.innerHTML = Number(num_comment)+' Comments';
		}
		else
		{
			d.innerHTML = Number(num_comment)+' Comment';
		}
		messagesObject.markMessageRead(parent, true);
		messagesObject.removeIframe();
		//$('effPara').value='1';
		
		return false;
	}
	
	function MF_D() { // FOR POPUP (index page)
		
		messagesObject.setGroupID();
		$('msgGroupId').value = messagesObject.groupid;
		
		return MF_A();
	}

	/************	MESSAGES ENDS		***************/


	/************	FILES	*******************/
	
	var uF = "";
	var uploadFileSubmitted = "";
	function uploadFile_Para() {
		var groupid = $('currGrpId').value
	    var filepara = { form : "frmFileUpload",
	                     url_action : "/groups/files/uploadFile/"+groupid,
	                     element : "errorMsgTitle",
	                     id_element : "errorMsgTitle_temp",
	                     html_show_loading : "<img src=/groups/img/loading.gif class=loadingImgLeft>",
	                     html_error_http : "Error",
	                     id : groupid,
	                     loader : "loading_img",
	                     main_div : "file_upload"
	                   };
	    return filepara;
	}
	
	var uploadFile = Class.create({
	
		initialize : function(objname) {
			this.filepara = eval( objname );
			this.form = '';
			this.formelem = '';
			this.length = '';
			this.iframe = '';
			this.tmpURL = '/groups/messages/tmpUpload';
			this.checkURL = '/groups/messages/getUploadFileStatus.json';
			this.fileUploadFirstId = '';
			this.continueFileUpload = false;
			if($('SessStrUserID')) this.userID = $('SessStrUserID').value;
			this.strCat = '';
			this.loader = '';
			this.crtcat = '';
			this.lodCat = '';;
			this.catDrop = '';
			this.count = '';
			this.recreateHTML = new Array();
			this.fileIDS = new Array();
			this.recreateHTMLDesp = '';
			this.recreateHTMLCat = '';
			this.recreateHTMLCatId = '';
			this.timeoutID = null;
	        this.timeoutAPCID = "";
	        this.timeoutPeriod = 1000;
	        this.APC_UPLOAD_PROGRESS = '';
		},
	
		openFilePopup : function() {
			uploadFileSubmitted = false;
			this.showProfile();
		},
		
		showProfile : function() {
	        $(this.filepara.main_div).style.display = "block";
	        $(this.filepara.form + 0).style.display = "block";
	        
	        if($('category').options[0].text.toLowerCase().match(/create new category/g)) var selID = 1;
			else  var selID = 0;

			$('category').selectedIndex = selID;
	        centerPos($(this.filepara.main_div), 1);
		},
	
		hidePopup : function() {
			$("fileUploadCount").value = 0;
			this.cleanup();
		    $(this.filepara.form + '0').reset();
		    $(this.filepara.main_div).style.display = "none";
		},
		
		removeFilePopup : function(id) {
			$(id).hide();
		},
		
		hidestatus : function() {
			$(this.filepara.element).innerHTML = '';
			$(this.filepara.element).className = '';
		},
		
		removeLink : function(state) {
			var count = $("fileUploadCount").value;
			for(var i=0; i<count; i++) {
				if($('removeLinkSpan_'+i)) { 
					if(state) $('removeLinkSpan_'+i).show();
					else $('removeLinkSpan_'+i).hide();
				}
			}
		},
		
		showUploaderOnSubmit : function (){
			if(this.form[0].name.value != '') {
					var oldId = this.APC_UPLOAD_PROGRESS = this.form[0].APC_UPLOAD_PROGRESS.value;
					$('element_' + oldId).style.display='';
					$('element_' + oldId + '_percentImage').style.display='';
					$('element_' + oldId + '_percentText').innerHTML = '0%';
					$('element_' + oldId + '_percentText').style.display='';
			}
	
		},
		
		hideUploaderOnSubmit : function (){
       		if(this.APC_UPLOAD_PROGRESS) {
            		var oldId = this.APC_UPLOAD_PROGRESS;
            		if($('element_' + oldId)) {
                    	$('element_' + oldId).style.display='none';
                    	$('element_' + oldId + '_percentImage').style.display='none';
                    	$('element_' + oldId + '_percentText').style.display='none';
            		}
			}
	
		},
		
		upload : function() {
			if(this.form == '') {
				this.form = messagesObject.getElementsByName(this.filepara.form, "form");
				this.length = this.form.length;
				this.showUploaderOnSubmit();
				$('postFilesBut').addClassName('butDisabled');
			}
	
			this.showHideLoader(true);
				
	    	for(var z=0; z<this.length; z++) {  //alert('child : ' + z + ' l : ' + this.length + ' t : ' +  this.form[z].vx.value);
	            if(this.form[z].style.display != 'none' && this.form[z].vx.value != 'ff' && typeof(this.form[z]) != "undefined") {
	            	this.formelem = this.form[z];
	            
	            	if(!this.checkIfEmpty())
						return false;

					this.removeLink(false);	             	
		            if(this.continueFileUpload) {
		            	var s = this.form[z].elements["data[groupfile][groupfilecategory_id]"];
		            	this.recreateHTMLCat = s.options[s.selectedIndex].text;
		            	this.recreateHTMLCatId = s.options[s.selectedIndex].value;
		            	this.recreateHTMLDesp = this.form[z].elements["data[groupfile][description]"].value;
		    	        this.iframe = messagesObject.micoxUpload(this.form[z], this.tmpURL, this.filepara.id_element, this.filepara.html_show_loading, this.filepara.html_error_http);
						this.checkstatus();
						return;
	
					} else {
						this.form[z].vx.value = 'ff';
	                }
				}
	       	}
			//alert(this.length +' - '+ parseInt(z));
	        if(this.length == parseInt(z)) {
				this.afterUpload();
	        }
		},
	
		checkstatus : function() {
			var d = this.decide.bind(this);
			new Ajax.Request(this.checkURL, { 
				'method':'post', 
				'parameters':{'id':this.formelem.APC_UPLOAD_PROGRESS.value, 'groupid':this.formelem.group.value, 'totaluploadsize':0},
				onSuccess:d	});
			return true;
		},
		
		decide : function(resp) {
			if(resp.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			var data = eval( "("+ resp.responseText +")" );
			
			if(data.id != "4") {
				this.iframe.src = tmpURL;
				
				if(data.id == "1") {
					this.showError("<b>"+data.file+"</b> exceeds maximum limit");
					this.formelem.APC_UPLOAD_PROGRESS.value = messagesObject.getUniqueID();
					this.removeLink(true);
					messagesObject.removeIframe();
					
				} else if(data.id == "2") {
					this.showError("<b>Disk space full</b>");
					this.formelem.APC_UPLOAD_PROGRESS.value = messagesObject.getUniqueID();
					this.removeLink(true);
					messagesObject.removeIframe();
					
				} else {
					//alert('upload');
					messagesObject.micoxUpload(this.formelem, this.filepara.url_action, this.filepara.id_element, this.filepara.html_show_loading, this.filepara.html_error_http);
					
					this.timeoutAPCID = this.formelem.APC_UPLOAD_PROGRESS.value
	                execFunctionVar = 'uF.monitorStatus()';
	                this.timeoutID = setInterval( "execFunction()", this.timeoutPeriod );
	                
	                startProgressBar(this.timeoutAPCID);
	                
				}
			} else {
				this.checkstatus();
			}
		},

		monitorStatus : function() {
			var onMonitorStatus = this.onMonitorStatus.bind(this);
			var url = '/groups/messages/monitorFiles.json';
			new Ajax.Request(url, { method:'post', parameters:{ 'id':this.timeoutAPCID }, onSuccess: onMonitorStatus });
		},
		
		onMonitorStatus : function(req) {
			var data = eval( "(" + req.responseText + ")" );
			if(data.error != '1') {
				if(data.finished == '1') {
					clearInterval(this.timeoutID);
					this.timeoutID = null;
				}
				progressBarObject.move(data.percent, data.complete, data.total);

			} else {
				//clearInterval(this.timeoutID);
				//alert('error');
				
			}
		
		},

		pauseForASecond : function() {
			if(this.timeoutID) {
				execFunctionVar = 'uF.pauseForASecond()';
				setTimeout( "execFunction()", 1000);
			
			} else {
				this.upload('');
			}
		},
		
		removeChild : function(z, lastInsertId, fileSize, fileURL, fileName, fileExt, dateTime, fileType, downloadURL) { //alert(z +' - '+ lastInsertId +' - '+ fileURL);
			this.recreateHTML[z] = new Array();
			this.recreateHTML[z]['id'] = lastInsertId;
			this.recreateHTML[z]['category'] = this.recreateHTMLCat;
			this.recreateHTML[z]['categoryId'] = this.recreateHTMLCatId;
			this.recreateHTML[z]['description'] = htmlspecialchars(this.recreateHTMLDesp);
			this.recreateHTML[z]['fileSize'] = fileSize;
			this.recreateHTML[z]['fileURL'] = fileURL;
			this.recreateHTML[z]['fileName'] = fileName;
			this.recreateHTML[z]['fileExt'] = fileExt;
			this.recreateHTML[z]['dateTime'] = dateTime;
			this.recreateHTML[z]['fileType'] = fileType;
			this.recreateHTML[z]['downloadURL'] = downloadURL;

			$(this.filepara.element).innerHTML = '';
			if(this.form[0].childno.value == z)
				this.fileUploadFirstId = lastInsertId;
		
			var f = $(this.filepara.form + z);
			f.vx.value = 'ff';
			
			this.pauseForASecond();
			//this.upload();
		},
		
		afterUpload : function() {
			var flID = comma = '';
			var len = this.recreateHTML.length;//alert('recreateHTML : ' + len);
			for(var i=0; i<len; i++) {
				comma = (i==len-1) ? '': ',';
				if(this.recreateHTML[i]) {
					this.fileIDS[i] = this.recreateHTML[i]['id'];
					flID += this.recreateHTML[i]['id'] + comma;
				}
			}
			var url    = '/groups/files/SMTGU.json';
			var userID = $('SessStrUserID').value;		
			var pars   = 'flID=' + flID + '&grpID=' + this.filepara.id;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars} ); 
			
			messagesObject.removeIframe();
			this.showHideLoader(false);
			this.cleanup();
			$(this.filepara.main_div).hide();
			if($('postFilesBut'))$('postFilesBut').removeClassName('butDisabled');
			this.afterGCT();
		},
		
		clearFirstPopup : function() {

			var id = $('progress_key').value;
			var newID = messagesObject.getUniqueID();

			$('element_'+id).update('<img title="" id="element_'+newID+'_percentImage" src="/images/bramus/percentImage.png" alt="" style="margin: 0pt; padding: 0pt; width: 120px; height: 16px; background-position: -120px 50%; background-image: url(/images/bramus/percentImage_back.png);" class="percentImage"/>').hide();
			$('element_'+id+'_percentText').update('').hide();

			$('element_'+id).id = 'element_'+newID;
			$('element_'+id+'_percentText').id = 'element_'+newID+'_percentText';
				
			$('progress_key').value = newID;
			$('vx').value = 'tt';
			$('frmFileUpload0').reset();
			$("removeLinkSpan_0").show().innerHTML = '';
		},
		
		cleanup : function() {
			if(this.form == '') {
				this.form = messagesObject.getElementsByName(this.filepara.form, "form");
				this.length = this.form.length;
			}
			$("fileUploadCount").value = 0;
			this.showHideLoader(false);
			this.hidestatus();
			this.clearFirstPopup();
			for(var z=1; z<this.length; z++) { 
				if(BrowserDetect.browser == 'Explorer') this.form[z].removeNode(true);
				else if(this.form[z]) this.form[z].remove();
			}
		},
	
		checkIfEmpty : function() {
			var tmp = this.formelem.getElementsByTagName('select');
			if(this.formelem.style.display != 'none' && this.formelem.vx.value == 'tt' && typeof(this.formelem) != "undefined") {
				if(this.formelem.groupfile.value == '') {
					this.continueFileUpload = false;
					this.showError('Please select a file to upload');
					this.hideUploaderOnSubmit();
					return false;
				} else if(tmp[0].value == '0') {
					this.continueFileUpload = false;
			 		this.showError('Please select a category');
			 		this.hideUploaderOnSubmit();
					return false;
				} else {
					this.continueFileUpload = true;
					return true;
				}
			}
		},
		
		showHideCategories : function(value, count) {
		
			this.catArr = this.getExistingCategories();
			this.strCat = $("strCategory"+count);
			this.loader = $("loadImageCat"+count);
			this.crtcat = $("createCategory"+count);
			this.lodCat = $("loadCategories"+count);
			this.catDrop = $("category"+count);
		
			if(this.crtcat) {
				if(value == 0) {
					this.crtcat.style.display = 'block';
				} else {
					if(this.crtcat.style.display == 'block')
						this.crtcat.style.display = 'none';
				}
			}
		},
		
		afterGCT : function() {
		
			if($('fileCatID').value > 0) {
				loadFiles('F',0,0,1)
				return false;
			}
			
			if($('currPageCat').value == 'flsdld' || $('currPageCat').value == "flsmsg") {
				loadFiles('F',0,0,1)
				return false;
			}
		
			if($('noFiles')) {
				$('noFiles').hide();
				$('innerContent').show();
			}
		
			for(var i=0; i<this.recreateHTML.length; i++) {
				//alert(this.recreateHTML[i]['id'] +' -- '+ this.recreateHTML[i]['category'] +' -- '+ this.recreateHTML[i]['description'] +' -- '+ this.recreateHTML[i]['fileSize'] +' -- '+ this.recreateHTML[i]['fileURL']+'--'+this.recreateHTML[i]['fileName']);
				
				if(!this.recreateHTML[i]) continue;
				
				var con = "test";
				var id = this.recreateHTML[i]['id'];
				var cat = this.recreateHTML[i]['category'];
				var catId = this.recreateHTML[i]['categoryId'];
				var desc = this.recreateHTML[i]['description'];
				var imgSize = this.recreateHTML[i]['fileSize'];
				var imgURL = this.recreateHTML[i]['fileURL'];
				var imgName = this.recreateHTML[i]['fileName'];
				var fileExt = this.recreateHTML[i]['fileExt'];
				var dateTime = this.recreateHTML[i]['dateTime'];
				var fileType = this.recreateHTML[i]['fileType'];
				var downloadURL = this.recreateHTML[i]['downloadURL'];
				//var networkName_org = document.getElementById('networkName').value;

				//make the first character caps
				//networkName_org += '';
    			//var f = networkName_org.charAt(0).toUpperCase();alert(f);
    			//networkName =  f + networkName_org.substr(1);alert(networkName);				

				con  = "<div id='parFile_"+ id +"' onmouseover='javascript:flsToggle(this,true,"+id+",1)' class='postGroupTop' onmouseout='javascript:$(\"sidebar\").hide();'>"
		                      +"<div id='file_"+ id +"' class='read'>"
		                          +"<div>"
		                            +"<div class='fileImage'>";
		                            
		                            if(fileType.match(/image/gi)) {
		                            
		                            	con += '<a href="/groups/download/file/group/'+ this.filepara.id +'/'+ id +'.'+ fileExt +'/v/" target="_blank"><img src="'+ imgURL +'" /></a>';
		                            	
		                            } else {
		                            
		                            	con += '<a href="'+ downloadURL +'"><img src="'+ imgURL +'" /></a>';
		                            }

		                     con += "</div>"
		                            +"<div>"
		                              +"<div class='fileName'><a onclick='showIndividualFile("+id+")' href='javascript:void(0)'>"+fileNameBreak(imgName,33)+"["+imgSize+"]</a></div>"
		                              +"<div class='block'>";
		                        
		                        if(fileType.match(/image/gi)) con += "<a target='_blank' href='/groups/download/file/group/"+this.filepara.id+"/"+id+"."+fileExt+"/v/'>View</a><span class='pipe'>|</span>";
								
								/*
		                        con += "<a target='_blank' href='"+ downloadURL + "'>Download</a>"
		                              +"</div>"
		                              +"<div class='postTitleBy1 block'>Uploaded by Me <span/> on "+dateTime+" in "+cat+"</div>"
		                              +"<div class='postDataDesc'>"+ wrapDesc(htmlspecialchars(desc)) +"</div>"
		                            +"</div>"
		                          +"</div>"
		                          +"<div class='clearLeft'> </div>"
		                          +"<div id='fileData"+id+"'>"		                          
		     		                	+"<div class='butCommentDiv' style='padding-bottom:15px' id='flComentBox"+id+"'><a onclick='toggleFile("+id+")' href='javascript:void(0)' class='butComment'>Comment</a></div>"
		     		     						+"<input type='hidden' value='0' id='hdnFl"+id+"'/>"
		     		     						+"<input type='hidden' value='0' id='hdnFileCommCount"+id+"'/>"
		     		     			+"<!--<div id='fileDataComm"+id+"' class='comments'>-->"
		     						+"<div style='display: none;' id='actFlCommentBox"+id+"' class='commentsCont'>"
		     						  +"<div class='closeGrey rightFloat closeRelative'><a href='javascript:void(0)' onClick='closeFileComment("+ id +","+this.formelem.group.value+")'>X</a></div>"
		                              +"<div class='postTitleSm'><img src='"+$('userpic').value+"' onmouseout='hideVCard(\""+$('~sec^Log@Ses~').value+"\");' onmouseover='showVCard(\""+$('~sec^Log@Ses~').value+"\",this,event);' /></div>"
		                              +"<div class='postDataSm'>"
		                                +"<div class='field'>"
		                                  +"<textarea id='txtFileCommentBox"+id+"' style='width: 90%;' onkeyup='ta_autoresize(this);' rows='2' cols='65'></textarea>"
		                                +"</div>"
		                                +"<div class='err' id='flsCommMsg"+ id +"' style='display: none;'>Please type a comment</div>"
		                                +"<div class='butBlueDiv'><a id='saveFileComm"+id+"' linkindex='17' onclick=\'saveOvrFileComments("+id+", "+this.formelem.group.value+")' href='javascript:void(0);' class='butBlue'><span>Comment</span></a></div>"
		                              +"</div>"
		                            +"</div>"		                            
		                           +"<!--</div>-->"
		                           +"</div>"
		                      +"</div>";                            
		           	
		           			*/
		           			
		           			con += "<a target='_blank' href='"+ downloadURL +"'>Download</a>"
		                              +"</div>"
		                              +"<div class='postTitleBy1 block'>Uploaded by Me <span/> on "+dateTime+" in "+cat+"</div>"
		                              +"<div class='postDataDesc'>"+ wrapDesc(htmlspecialchars(desc)) +"</div>"
		                            +"</div>"
		                          +"</div>"
		                          +"<div class='clearLeft'> </div>"
		                          +"<div id='fileData"+id+"'>"		                          
		     		                	+"<div class='butCommentDiv'  style='padding-bottom:15px' id='flComentBox"+id+"'><a onclick='toggleFile("+id+","+this.filepara.id+")' href='javascript:void(0)' class='butComment'>Comment</a></div>"
		     		     						+"<input type='hidden' value='0' id='hdnFl"+id+"'/>"
		     		     						+"<input type='hidden' value='0' id='hdnFileCommCount"+id+"'/>"
		     		     			+"<div id='fileDataComm"+id+"' class='comments'>"
		     						+"<div style='display: none;' id='actFlCommentBox"+id+"' class='commentsCont'>"
		     						  +"<div class='minGrey rightFloat closeRelative'><a href='javascript:void(0)' onClick='closeFileComment("+ id +","+this.formelem.group.value+")'>-</a></div>"				                      
		                              +"<div class='postTitleSm'><img src='"+$('userpic').value+"' onmouseout='hideVCard(\""+$('~sec^Log@Ses~').value+"\");' onmouseover='showVCard(\""+$('~sec^Log@Ses~').value+"\",this,event);' /></div>"
		                              +"<div class='postDataSm'>"
		                              +"<form name='fileCommentForm_"+id+"' id='fileCommentForm_"+id+"' method='POST'>"
		                                +"<div class='field'>"
		                                  +"<textarea id='txtFileCommentBox"+id+"' name='fileMsg' style='width: 90%;' onkeyup='ta_autoresize(this);' rows='2' cols='65'></textarea>"
		                                +"</div>"		                                
		                                +"<input type='hidden' name='groupid' id='fileGrpId_"+id+"' value='"+this.formelem.group.value+"' />"
				                        +"<input type='hidden' name='parent' id='fileRecId_"+id+"' value='"+id+"' />"
				                        +"</form>"
				                        +"<div id='filesCommentBox_"+id+"'></div>"
		                                +"<input type='hidden' name='brwCounter_"+id+"' id='brwCounter_"+id+"' value='0' />"
		                                +"<input type='hidden' name='fileCategory_"+id+"' id='fileCategory_"+id+"' value='"+catId+"' />"
		                                +"<input type='hidden' name='fileLastid_"+id+"' id='fileLastid_"+id+"' value='' />"
										+"<div class='err' id='flsCommMsg"+ id +"' style='display: none;'>Please type a comment</div>"				                                
		                                +"<div class='butBlueDiv leftFloat'><a id='saveFileComm"+id+"' linkindex='17' onclick=\'saveOvrFileComments("+id+", "+this.formelem.group.value+")' href='javascript:void(0);' class='butBlue'><span>Comment</span></a></div><div id='loading"+id+"' style='display:none'><img src='/groups/img/loading.gif' class='loadingImgLeft'></div>"
		                              	+"<div class='clearLeft1'></div>"
		                              +"</div>"
		                            +"</div>"		                            
		                           +"</div>"
		                           +"</div>"
		                      +"</div>";            					           			
		           	if(!$('fileDateToday'))
		           	{
		            	//else create date element
		            	var dateElement = "<div id='fileDateToday' class = 'threadDate'><span class='threadDateDisplay'>Today</span></div>";
	            		
		            	//create group element		            			            	
		            	var groupElement = "<div id='fileGroupDataToday' class = 'threadGroup'>";
		            	
		            	//if blank data is there, replace it
		            	var fi = $('fileItems').childElements();
		            	if(fi.length > 0) {
							if(fi[0].id == 'noFiles' || fi[0].id == 'noFilesCat') {
								$('fileItems').innerHTML = '';
							}
						}

		            	//insert the elements
		            	$('fileItems').insert ( { 'top'  : groupElement } );
		            	$('fileItems').insert ( { 'top'  : dateElement } );
		            	
		            	//then insert the message element		            	
		            	$('fileGroupDataToday').insert ( { 'top'  : con } );
		            }
		            else
		            {	            	
		            	//  alert(con);	
		           		//if date element exists, insert the message element
		            	//var beforeElement = document.getElementById('first');
		            	//beforeElement.id = "";
		            	//to find out first element here
		            	//beforeElement.className = "postGroup";
		            	Element.insert($('fileGroupDataToday'), {'top' : con});
		            	/*First element of the thread group is being deleted and hence the classname of succeeding element has to be changed */
						succElem = Element.next($('parFile_'+ id));
						if(succElem)
							succElem.className = succElem.className.replace('postGroupTop','postGroup');
		            	//alert($('fileGroupDataToday').innerHTML);
		            }
		            new Effect.Highlight($('parFile_'+ id), {startcolor: startColor,endcolor: endColor ,duration: 1});
			}
		},
		
		//function to convert the first character capital
		lcfirst : function(str) { 
    		str += '';
    		var f = str.charAt(0).toLowerCase();
    		return f + str.substr(1);
		},
		
		getExistingCategories: function() {
			var catArr = new Hash();
			var forms = messagesObject.getElementsByName('frmFileUpload', 'form');
	
			for(i=0; i<forms.length; i++){
				catArr.set(forms[i].elements["data[groupfile][groupfilecategory_id]"].id, forms[i].elements["data[groupfile][groupfilecategory_id]"].value);
				//alert(forms[i].elements["data[groupfile][groupfilecategory_id]"].id);
			}
			return catArr;
		},
	
		addFileCategory: function(count){
			this.count = count;
			if(this.strCat.value == '')
			{
				$("errorMsgTitle").innerHTML = "<div class='err'>Please enter category.</div>";
				this.strCat.focus();
				return;
			}
			
			if(this.strCat.value.indexOf(',') != "-1")
			{
				$("errorMsgTitle").innerHTML = "<div class='err'>You can enter only one category at a time.</div>";
				this.strCat.focus();
				return;
			}
			
			if(this.strCat.value.length > 25)
			{
				$("errorMsgTitle").innerHTML = "<div class='err'>Cannot exceed 25 characters.</div>";
				this.strCat.focus();
				return;
			}
		
			$(this.loader).innerHTML = '<img src="/groups/img/loading.gif" class="loadingImgLeft">';
			var response = this.addFileCategoryResponse.bind(this);
			var url    = "/groups/messages/addMessageCategory.json";
			new Ajax.Request( url, {method: "post", parameters: { groupid:this.filepara.id, txtCategory:this.strCat.value, from:'files' }, 
						onComplete:response	} );		
		},
		
		addFileCategoryResponse : function(originalRequest){
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
	        if(originalRequest.responseText == "exists") {
                $("errorMsgTitle").innerHTML = "<div class='err'>Category exists.</div>";
                this.loader.innerHTML = '';
    	        this.strCat.value = "";                                               
	        	this.strCat.focus();
	        	
	        } else if(originalRequest.responseText == '0') { 
				$("errorMsgTitle").style.display = 'block';
				$("errorMsgTitle").innerHTML = "You are not authorized to create a category";
				$("strCategory").value = "";
				$('createCategory').hide();
	        	
            } else {
            	var response = eval( "("+ originalRequest.responseText +")" );
            	var response = response.split('##~~##'); 
                $("errorMsgTitle").innerHTML = "";
    	        this.loader.innerHTML = '';
                this.crtcat.style.display = 'none';
                
                if($('category_content')) $('category_content').innerHTML = response[1];
                
                var data = '<select name="data[groupfile][groupfilecategory_id]" id="category' + this.count + '" onchange="javascript:uF.showHideCategories(this.value, '+ this.count +');" class="fieldWidth50" style="width:54.5%">'+ response[0] +'</select>';
				this.lodCat.innerHTML = data;
				this.catArr.each(function(pair){
					if(pair.value != 0) {
						$(pair.key).update(response[0]);
						$(pair.key).value = pair.value;
					}
				});
    		}
		},
	
		showError : function(error) {
			this.form = "";
			$(this.filepara.element).className = 'err';
			$(this.filepara.element).innerHTML = error;
			uploadFileSubmitted = false;
			this.showHideLoader(false);
		},
		
		showHideLoader : function(n) {
			if(n) $(this.filepara.loader).innerHTML = this.filepara.html_show_loading; 
			else $(this.filepara.loader).innerHTML = '';
		}
	});
	
	function addFile(group) {
		var uniqueid = messagesObject.getUniqueID();
	    var count = parseInt($('fileUploadCount').value) + 1;
	    var id = "frmFileUpload";
	    var newform = document.createElement("form");
	    newform.id = id + count;
	    newform.name = id;
	    newform.method = 'post';
	    newform.style.display = 'block';
	    newform.enctype = 'multipart/form-data';
	    $('formDivs').appendChild(newform);

		var selectContent = '<select name="data[groupfile][groupfilecategory_id]" id="category' + count + '" onchange="javascript:uF.showHideCategories(this.value, '+ count +');" class="fieldWidth50" style="width:54.5%">'+$("category").innerHTML+'</select>';
		var data = '<div class="Grp"><fieldset>'
	                        +'<div class="field">'
	                        +'<div class="fieldLabel fieldLabelWidthBig">'
	                        +'<label class="bigTextField">File</label><span style="font-size: 9pt; color: #666666;"><br/>Upto 200MB each</span>'
	                        +'</div>'
	                        +'<div>'
	                        +'<input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key"  value="'+uniqueid+'"/>'
	                        +'<input type="hidden" name="childno" id="childno"  value="'+count+'"/>'
	                        +'<input type="hidden" name="group" id="group"  value="'+group+'"/>'
	                        +'<input type="hidden" name="vx" id="vx"  value="tt"/>'
	                        +'<input type="file" name="groupfile" id="groupfile" size="33" class="bigTextField fieldWidth" onclick="uF.hidestatus();" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
						+'<span id="removeLinkSpan_'+count+'"></span>'
						+'&nbsp;<span id="element_'+uniqueid+'" style="display:none">'
                                +'<img class="percentImage" style="margin: 0pt; padding: 0pt; width: 120px; height: 16px; background-position: -120px 50%; background-image: url(/images/bramus/percentImage_back.png);" alt="" src="/images/bramus/percentImage.png" id="element_'+uniqueid+'_percentImage" title=""/>'
                        +'</span>'
                        +'&nbsp;<span id="element_'+uniqueid+'_percentText" style="display:none"></span>'
	                +'</div>'
		        +'</div>'
	            +'<div class="field">'
	                    +'<div class="fieldLabel fieldLabelWidthBig">'
	                      +'<label>Category</label>'
	                    +'</div>'
	                    +'<div>'
	                      +'<span id="loadCategories' + count + '">'+selectContent+'</span>'
	                    +'</div>';
	
			if($("fileMId") != null && typeof $("fileMId") != "undefined") {
	
				data += '<div class="field" id="createCategory'+ count +'" style="display:none;valign:middle;padding-top:15px;padding-bottom:15px;">'
	                    +'<div class="fieldLabel fieldLabelWidthBig">'
	                      +'<label>&nbsp;</label>'
			            +'</div>'
	                    +'<div class="refAddressBG fieldWidth4 leftFloat" style="padding: 15px; width:70%">'
	                      +'<div class="paddingTop10 leftFloat" style="width:90%">'
	                            +'<input name="data[Groupmessage][title]" id="strCategory'+ count +'" type="text" style="width:75%" />'
	                            +'<a id="btnCreateCat'+ count +'"  onclick="javascript:uF.addFileCategory(' + count + ');" class="addCategory">ADD</a>'
	    				  +'</div>'
	                      +'<div id="loadImageCat'+ count +'" class="leftFloat" style="padding-top:10px;"></div>'
	                      +'<div>&nbsp;</div>'
	                    +'</div>'
	            +'</div>';
			}
	
			data += '</div>'
					+'<div class="clear">&nbsp;</div>'
	                        +'<div class="field">'
	                                +'<div class="fieldLabel fieldLabelWidthBig">'
	                                  +'<label>Description</label>'
	                                +'</div>'
	                                +'<div>'
	                                  +'<textarea name="data[groupfile][description]" id="description" cols="16" rows="2" class="fieldWidth1" ></textarea>'
	                                +'</div>'
	                        +'</div>'
						+'</fieldset></div>'
	                   // +'<div id="fileUploadBlock">'
				+'</form>'
	            +'';
	
	
		$(id+count).innerHTML = data;
		//$("formDivs").appendChild(newform);
		$("fileUploadCount").value = count;
		var link = count-1;
		$("removeLinkSpan_"+link).innerHTML = '<a href="javascript:void(0)" onclick="uF.removeFilePopup(\''+id+link+'\')">Remove</a>';
	}

	/************	FILES ENDS		***************/



	/************	Milestones	***************/
	
	var milestones = Class.create({
		initialize : function() {
			groupid = '';
			msgid = '';
			tmp = '';
		},
		
		callMilestones : function(type,toshow) {
			if(toshow == '1')showLoader();
			else if($('milestoneData')){
				$('milestoneData').innerHTML = '<div class="mainContent1" style="height:80px"><img id="milesLoadingProgress" src="/images/loading.gif" class="loadingImgLeft" border="0"/></div>';
			}
			$('currPageCat').value = "mlt";
			this.groupid = $('currGrpId').value;
			new Ajax.Updater('maincontent', '/groups/milestones/getMilestones.json', {
	  			parameters: { groupid: this.groupid, type: type},
	  			evalScripts:true
			});
		},
		
		milestoneToggle : function(self,showEdit,markR,milId,showDel) {	
			$('sidebar').hide();		
			if(showEdit == '1' || (markR == '1' && $('hdnMl'+milId).value == "1") || showDel == '1') {
				var f = findPosition(self);
				/*
				intRight = 	(windowWidth/2)-365;
				if(windowWidth<1280)
					intRight = 295;
				*/
				var intRight = 	((parent.windowWidth - 1280)/2)+275;
				
				if(BrowserDetect.browser == 'Explorer' && BrowserDetect.OS == 'Windows')
				f[1] = f[1] - 200;

				if((BrowserDetect.browser == 'Chrome' ||  BrowserDetect.browser == 'Opera') && windowWidth > 1280){
					intRight = intRight - 7;
				}
				if(parent.windowWidth<=1100)
						intRight = 205;
				else if(parent.windowWidth<=1280)
					intRight = 295;					
				$('sidebar').setStyle({
					marginTop : f[1] + 'px',
					right : intRight + 'px'
				});
				
				$('sbPipe1').hide();
				$('sbPipe2').hide();
				$('markAdRead').hide();
				$('editMilLink').hide();
				$('sidebarDel').hide();
				
				if(markR == '1' && $('hdnMl'+milId).value == "1")
				{					
					$('markAdRead').innerHTML = '<a onclick="javascript:milestonesObject.markAsRead(\''+milId+'\');" href="javascript:void(0)">Mark as read</a>';
					$('markAdRead').show();
				}
				
				if(showEdit == '1'){
					$('editMilLink').innerHTML = '<a onclick="javascript:milestoneSubmitted=false;$(\'edit_result\').style.display=\'none\';milestonesObject.showEditProfile(\''+milId+'\');openStatus = 0;" href="javascript:void(0)">Edit</a>';
					$('editMilLink').show();
				}
				
				if(showDel == '1'){
					var elemId = 'mil'+milId;
					
					var func = escape("ovrDelete(\'Milestone\',\'"+milId+"\',\'"+elemId+"\')");
					$('sidebarDel').innerHTML = '<a href="javascript:void(0);" onclick="beforeDelete(this,event,\'ML\',\'0\',\''+func+'\');">Delete</a>';
					$('sidebarDel').show();	
				}
				
				if((markR == '1' && $('hdnMl'+milId).value == "1") && showEdit == '1'){
					$('sbPipe1').show();
				}
				if(showEdit == '1' && showDel == '1'){
					$('sbPipe2').show();
				}
				if(showEdit == '0' && showDel == '1' && (markR == '1' && $('hdnMl'+milId).value == "1")){
					$('sbPipe1').show();
				}
				
				$('sidebar').show();
			}
		},

		getQuickTab: function (para) {								
			var getQuickTab = this.getQuickTabSuccess.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/groups/getQuickTabfn.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&sessUID='+userID+'&para='+para;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: getQuickTab} );
		},
		
		getQuickTabSuccess: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			
			$('addQuickTab').style.display = 'none';
			//alert(originalRequest.responseText);
			//alert(windowHeight);
			var tabContent = originalRequest.responseText.split('@@**^^');
						
			$('quicktabContent').innerHTML = tabContent[0];						
			$('quicktabContent').style.maxHeight = windowHeight - 450 +'px';
			
			
			$('quicktabContentNav').innerHTML = tabContent[1];						
			$('quicktabContentNav').style.maxHeight = windowHeight - 450 +'px';
			if(tabContent[2] == '0'){
				$('noOfchangesInTab').value = 0;
				$('quicktabContent').hide();
				$('quicktabContentNav').show();
				$('assignQuickTabDiv').show();
				$('saveQuickTabDiv').hide();
				$('moreTabTitleDiv').innerHTML = '<h1>Navigate Groups</h1>';//proj2Gr
			}
			
			$('navigate_groups').style.display = 'block';
			//$('navigate_groups').style.width = $('quicktabContent').offsetWidth +'px';
			//alert($('quicktabContent').offsetWidth);
			//$('navigate_groups').style.width = $('quicktabContent').offsetWidth +'px';
		},
				
		addRemQuickTab: function (selId,para) {
			if($('quicktabCnt').value > 1 && para == '0'){
				//alert('There already two quick tabs assigned. Please remove one quicktab');
				$('quickTabErrMsgPopup').style.display='block';
				return false;
			}
			$('quickTabErrMsgPopup').style.display='none';
			$('noOfchangesInTab').value = $('noOfchangesInTab').value +1; 
			var addRemQuickTabSuccess = this.addRemQuickTabSuccess.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/groups/addRemQuickTabfn.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&id=' + selId + '&sessUID='+userID+'&para='+para;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: addRemQuickTabSuccess} );
		},
		
		addRemQuickTabSuccess: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}			
			this.getQuickTab('1');
		},
		
		saveRemQuickTab: function (para) {			
			var saveRemQuickTabSuccess = this.saveRemQuickTabSuccess.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/groups/saveRemQuickTabfn.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&groupid=' + $('currGrpId').value + '&sessUID='+userID+'&currPageCat='+$('currPageCat').value+'&para='+para;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: saveRemQuickTabSuccess} );
		},
		
		saveRemQuickTabSuccess: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}			
			if($('navigate_groups'))$('navigate_groups').style.display = 'none';
			$('headerTabs').innerHTML = originalRequest.responseText;			
		},
		
		saveRemQuickTabNewGr: function (para,para1,para2,para3) {			
			var rand   = Math.random(99999);
			var url    = '/groups/groups/saveRemQuickTabfn.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&groupid=' + $('currGrpId').value + '&sessUID='+userID+'&currPageCat='+$('currPageCat').value+'&para='+para;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onSuccess: function(originalRequest){
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
				if($('navigate_groups'))$('navigate_groups').style.display = 'none';
				$('headerTabs').innerHTML = originalRequest.responseText;				
				obj=$('quickTab'+para3);
				showGroupInfo(obj,para1,para2);				
			}} );
		},

		openCreatedTabActual: function (para,para1,para2,para3) {			
			var rand   = Math.random(99999);
			var url    = '/groups/groups/openNewlyFormedGr.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&sessUID='+userID;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: function(originalRequest){
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
				//alert(originalRequest.responseText);
				var para3 = originalRequest.responseText;				
				obj=$('quickTab'+para3);
				showGroupInfo(obj,para1,para2);				
			}} );
		},
		
		openCreatedTabOld: function (para,para1,para2,para3) {
			chk4NewTab(); 			
			var rand   = Math.random(99999);
			var url    = '/groups/groups/openNewlyFormedGr.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&sessUID='+userID;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onSuccess: function(originalRequest){
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
				//alert(originalRequest.responseText);
				var para3 = originalRequest.responseText;				
				obj=$('quickTab'+para3);
				milestonesObject.callagain(para3,para1,para2)
													
			}} );
		},
		
		
		openCreatedTab: function (para,para1,para2,para3) {

			var rand   = Math.random(99999);
			var url    = '/groups/groups/saveRemQuickTabfn.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&groupid=' + $('currGrpId').value + '&sessUID='+userID+'&currPageCat='+$('currPageCat').value+'&para=0';
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onSuccess: function(originalRequest){
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
				if($('navigate_groups'))$('navigate_groups').style.display = 'none';
				$('headerTabs').innerHTML = originalRequest.responseText;	
			
				var url1    = '/groups/groups/openNewlyFormedGr.json';
				var userID1 = $('SessStrUserID').value;
				var pars1   = 'rand=' + rand + '&sessUID='+userID1;
				var myAjax = new Ajax.Request( url1, {method: 'post', parameters: pars1, onSuccess: function(transport){
					if(transport.responseText == "expired"){      
						window.location = MainUrl + "main.php?u=signout";return;
					}								
					var para3 = transport.responseText;				
					var obj=$('quickTab'+para3);								
					milestonesObject.callagain(para3,para1,para2)
													
				}} );							
			}} );
		},
		callagain: function (para3,para1,para2) {
			var rand   = Math.random(99999);			
			var url    = '/groups/groups/openNewlyFormedGr.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&sessUID='+userID;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onSuccess: function(originalRequest){
					if(originalRequest.responseText == "expired"){      
						window.location = MainUrl + "main.php?u=signout";return;
					}
					var para3 = originalRequest.responseText;
					var obj=$('quickTab'+para3);							
					showGroupInfoNewly(obj,para1,para2);	
			}} );
		},
		
		markAsRead: function (selId) {
			var markAsReadSuccess = this.markAsReadSuccess.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/milestones/markAsRead.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&mile_id=' + selId + '&sessUID='+userID;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: markAsReadSuccess} );
		},

		markAsReadSuccess: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			paginateDataMilestone('/groups/milestones/getMilestones.json',$('milPageNum').value,$('milPageType').value);
			//paginateData('/groups/milestones/getMilestones.json',$('milPageNum').value);
		},
		
		markAllAsRead: function () {
			var markAllAsReadSuccess = this.markAllAsReadSuccess.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/milestones/markAllAsRead.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&sessUID='+userID+'&groupid='+$('currGrpId').value+'&mtype='+$('milPageType').value;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: markAllAsReadSuccess} );
		},
		
		markAllAsReadSuccess: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			paginateDataMilestone('/groups/milestones/getMilestones.json',$('milPageNum').value,$('milPageType').value);
			//paginateData('/groups/milestones/getMilestones.json',$('milPageNum').value);
		},
				
		showEditProfile : function(id) {				  	
		  	if($('add_milestone')) $('add_milestone').style.display = "none";		  
		  	if($('edit_milestone').style.display != "none")
		  	{		  
		  		$('edit_milestone').style.display = "none";
		  	}
		  	$('edit_result').innerHTML = "";
		  	$('calendar-milestone-edit').innerHTML = '';
			$('inputTitle').innerHTML = '';
			$('editLabel').innerHTML = '';
		  	milestonesObject.getEditData(id);
	  	},
	  	getEditData: function (selId) {
			var showNewData = this.showNewData.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/milestones/editMilestone.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'id=1&rand=' + rand + '&mile_id=' + selId + '&sessUID='+userID;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showNewData} );
		},
		fillHolder: function () {
			var user_id;
			var dataObj;
			var selDay = this.data.selected_day;
			var loggedInUser = this.data.loggedInUser;
			var milstoneVersion = '';
			
						
			dataObj = this.data.users;
	
			this.data.milestone.each(
			function(item)
			{
				user_id = item.milestones.user_id;
				milstoneVersion = item.milestones.version;
				$('selDateEdit').innerHTML ="<strong>Due: " + item[0].targetdate + "</strong><input id='target_date_edit' type='hidden' name='data[milestones][targetdate]' value='" + item.milestones.targetdate + "' />";
				$('inputTitle').innerHTML = "<input class='inp' type='text' name='data[milestones][title]' size='40' value='' id = 'editTitle'/>"
											+"<input type='hidden' name='data[milestones][id]' value='" + item.milestones.id + "'/>";
				$('editTitle').value = htmlspecialchars_decode(item.milestones.title);
				if (item.milestones.completeddate == 'NULL' || item.milestones.completeddate == '0000-00-00 00:00:00')
				{
					$('autocompleteEdit').checked = false;			
				}else{
					$('autocompleteEdit').checked = true;			
				}
			}
			);
			var selectHtml = '';
			dataObj.users_list.each(
			function(item)
			{
				if (user_id == item.usermst.UserID)
				{
					if (loggedInUser == item.usermst.UserID)
					{
						selectHtml += "<OPTION value='"+ item.usermst.UserID +"' selected>Me(" + item.userpersonalinfo.FullName + ")</OPTION>";
					}else{
						selectHtml += "<OPTION value='"+ item.usermst.UserID +"' selected>" + item.userpersonalinfo.FullName + "</OPTION>";
					}
				}
				else
				{
					if (loggedInUser == item.usermst.UserID)
					{
						selectHtml += "<OPTION value='" + item.usermst.UserID + "'>Me(" + item.userpersonalinfo.FullName + ")</OPTION>";
					}else{
						selectHtml += "<OPTION value='" + item.usermst.UserID + "'>" + item.userpersonalinfo.FullName + "</OPTION>";
					}
					
				}
			}
			);
			$('editLabel').innerHTML = "<select size='1' id='editSelect' class='inp' name='data[milestones][user_id]'>" + selectHtml + "</select>";
	
			var cnt = 0;
			var todolistStr = '';
			var todoopen = this.data.todoopen;
			var todoclose = this.data.todoclose;
			var todolen = todoopen.length;
			var todocloselen = todoclose.length;
			var todolistLen = this.data.todolist.length;
			var i=0;		
			
			this.data.todolist.each(
			function(item){
				$('todolisthidden').value = $('todolisthidden').value + ',' +item.todolists.id; 
				
				if(cnt == "0")
					var topClass = 'postGroupTop';
				else
					var topClass = 'postGroup';
									
				todolistStr += "<div id='todolistDiv"+item.todolists.id+"' class='milestoneGroup "+topClass+"' style='padding-bottom:15px;padding-top:15px;'>";
				
				todolistStr += "<div id='tasklistdiv"+item.todolists.id+"' >";
				todolistStr += "<div id='AddParent"+item.todolists.id+"' class='rightFloat'>";
				
				if(todolistLen < 2)
					todolistStr += "<div id='swap"+item.todolists.id+"' class='contract leftFloat' onclick='ldDispMileStone.toggleTodo("+item.todolists.id+")'><img src='/images/minimize.gif' /></div>";
				else
					todolistStr += "<div id='swap"+item.todolists.id+"' class='expand leftFloat' onclick='ldDispMileStone.toggleTodo("+item.todolists.id+")'><img src='/images/maximize.gif' /></div>";
				
				todolistStr += "</div>";
				
				if(item.todolists.description != '')
					todolistStr += "<div class='mGTitleRow1' style='margin-right:0px;'><span class='mGTitle' style='font-weight:bold;'><a href='javascript:void(0)' onclick='ldDispMileStone.toggleTodoDesc("+item.todolists.id+")'>"+stripslashes(item.todolists.title)+"</a></span> in<span style='font-weight:bold;'> "+item.groups.grname+"</span></div>";
				else
					todolistStr += "<div class='mGTitleRow1' style='margin-right:0px;'><span class='mGTitle' style='font-weight:bold;'>"+stripslashes(item.todolists.title)+"</span> in<span style='font-weight:bold;'> "+item.groups.grname+"</span></div>";
				
				todolistStr += "</div>";
				todolistStr += "<div class='mGTitleDesc' id='tododecs"+item.todolists.id+"' style='display:none;' >"+item.todolists.description+"</div>";

				if(todolistLen < 2)
					var dis = 'block';
				else
					var dis = 'none';
				
				todolistStr += "<div class='mGDetails' id='todosdiv"+item.todolists.id+"' style='padding-top:10px;display:"+dis+"'>";
				
				var cnt1 = 0;
				todoopen.each(
				function(tditem){
					if(item.todolists.id == tditem.todos.todolist_id){	
						if(cnt1 > 0)							
						todolistStr += "<div style='border-bottom:1px solid #e9e9e9;height:8px; margin-left:10px;'>&nbsp;</div>";
						
						todolistStr += "<div class='mGDetailRow' id='todoRow"+tditem.todos.id+"' >";
                   		todolistStr += "<div style='padding-bottom:5px;'>";
                   		
                   		todolistStr += "<div class='leftFloat' style='width:20px;'><input type='checkbox' name='todosChk' id='todoChk"+tditem.todos.id+"' value='2' class='borderNone' onclick='ldDispMileStone.changeStatusTodo("+tditem.todos.id+","+milstoneVersion+");' /></div>";                                                    
                     	todolistStr += "<div id='todoTitle"+tditem.todos.id+"' style='margin-left:25px;'>"+tditem.todos.title+" <span style='color:#717171;font-size:8pt;'>- "+tditem.userpersonalinfo.FullName+"</span></div>";
                   		todolistStr += "</div>";
                 		todolistStr += "</div>";
                 		
						cnt1++;	
					
					}					
				});	
					
				var compTodoCnt = 0;
				todoclose.each(
				function(tditem){
					if(item.todolists.id == tditem.todos.todolist_id) compTodoCnt++;	
				});
				
				if(compTodoCnt == "1"){
					var dis1 = 'block';
				}else if(compTodoCnt > 1){
					todolistStr += "<div class='mGDetailRow' style='padding-top:15px;'><div><a href='javascript:void(0)' onclick='ldDispMileStone.toggleTodoComp("+item.todolists.id+")'>Completed tasks</a></div></div>";
					var dis1 = 'none';
				}
				
				cnt1 = 0;	
	            todolistStr += "<div style='display:"+dis1+"' id='compTodo"+item.todolists.id+"'>";     
				todoclose.each(
				function(tditem){
					if(item.todolists.id == tditem.todos.todolist_id){
						
				  		if(cnt1 > 0 || compTodoCnt == "1")							
						todolistStr += "<div style='border-bottom:1px solid #e9e9e9;height:8px; margin-left:10px;'>&nbsp;</div>";
						
						todolistStr += "<div class='mGDetailRow' id='todoRow"+tditem.todos.id+"' >";
                   		todolistStr += "<div style='padding-bottom:5px;'>";
                   		
                     	todolistStr += "<div class='leftFloat' style='width:20px;'><input type='checkbox' class='borderNone' name='todosChk' id='todoChk"+tditem.todos.id+"' checked='checked' value='1' onclick='ldDispMileStone.changeStatusTodo("+tditem.todos.id+","+milstoneVersion+");'/></div>";                                                    
                     	todolistStr += "<div id='todoTitle"+tditem.todos.id+"' style='margin-left:25px;' class='completed'>"+tditem.todos.title+" <span style='color:#717171;font-size:8pt;'>- "+tditem.userpersonalinfo.FullName+"</span></div>";
                   		todolistStr += "</div>";
                 		todolistStr += "</div>";

						cnt1++;	
					}
				});
								
				todolistStr += "</div>";	
				todolistStr += "</div>";			
				todolistStr += "</div>";

				cnt++;
			}
			);
			$('todolistNTodos').innerHTML = todolistStr;
			
			if(todolistLen > 0){
				$('todolistNTodos').style.width = '295px';
				$('edit_milestone').style.width='955px';
				$('editTitle').className = 'inp';
				$('leftSideDiv').style.width = '450px';
				$('leftSideDiv').className = 'leftFloat';
				$('todolistNTodos').show();
				
			}
			else {
				$('leftSideDiv').className = '';
				$('editTitle').className = '';
				$('edit_milestone').style.width='880px';
				$('todolistNTodos').hide();
				
			}
			//Calendar in edit milestone page
			addMilestoneCalendar('edit', selDay);
			
			
		},
		milestoneStatus: function (milestone_id, milestone_version, status_id,classVal) {
			if($('RHSMilChk'+milestone_id))
				$('RHSMilChk'+milestone_id).disabled = true;
			if($('milChk'+milestone_id))
				$('milChk'+milestone_id).disabled = true;
					 
			var project_id = $('currGrpId').value;
			var showChangedMilestonesData = this.showChangedMilestonesData.bind(this);
			var url    = '/groups/milestones/changeMilestoneStatus.json';
			var userID = $('SessStrUserID').value;
			var pars   = '?groupid='+project_id+'&milestone_id='+milestone_id+'&milestone_version='+milestone_version+'&status_id='+status_id+'&sessUID='+userID+'&classVal='+classVal;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, 
				onComplete: showChangedMilestonesData,
				onSuccess: function(transport)
				{
					if(transport.responseText == "expired"){      
						window.location = MainUrl + "main.php?u=signout";return;
					}
					
					var data = transport.responseText;
					var dataArr  = data.split('^^^');
					
										
					if(dataArr.length > 1)
					{
						if(status_id == "2")
						{
							var url = '/groups/tasks/changeTodoStatusAll.json';
							var pars = 'sessUID='+$('SessStrUserID').value+'&MilVersion='+milestone_version+'&flag='+status_id+'&isCal=0';
							new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess:function(originalRequest){
								if(originalRequest.responseText == "expired"){      
									window.location = MainUrl + "main.php?u=signout";return;
								}
								
								var retdata = originalRequest.responseText;
								//alert(retdata);
								var retdata = retdata.split('^^');
								var milVer = retdata[0];
								var finaldiff = retdata[2];
								var milStatus = retdata[3];
					
								var url    = '/groups/tasks/sendTodoMails.json';
								var pars = '';				
								if(milStatus == '2')
									pars = 'sessUID='+$('SessStrUserID').value+'&milVer='+milVer+'&milStatus='+milStatus+'&crArr='+retdata[4]+'&asArr='+retdata[5]+'&tdArr='+retdata[6]+'&tdIds='+retdata[1];
									
								var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars});
							}
							});
						}
						else
						{
							/*sending mails on status change of milestone*/
							sendMail('Milestone',dataArr[7],'changeStatus',milestone_version);
						}
					}
					else
					{
						var evt = "close",
						    stat = 1;
						
						if(status_id == "1")
							evt = "reopen";
							
						if(data == "same")
							stat = 2;
								
						showUtlPopup('Milestone',evt,stat);
					}	
				}
			});
		},
		
		getTotPageMil: function(grpid)
		{
			var rand   = Math.random(99999);
			new Ajax.Request('/groups/milestones/getTotPageMil.json', {
	  			parameters: { rand:rand,grpid:grpid},
	  			onSuccess: function(transport)
	  				{
	  					if(transport.responseText == "expired")
				        {      
				        	window.location = MainUrl + "main.php?u=signout";return;
				        }
				        else
				        {
				        	data = (eval('(' + transport.responseText + ')'));
				        	totPage = data.totMil;
				        	
				        	$('hdnCurrPageNo').value=totPage;
				        	paginateDataMilestone('/groups/milestones/getMilestones.json',totPage,$('milPageType').value);
				        	//paginateData('/groups/milestones/getMilestones.json',totPage);
				        }	
	  				}
			});
		},
		
		showChangedMilestonesData: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
						
			var content = originalRequest.responseText.strip().split('^^^');
			
			if(content.length > 1)
			{
				if($('currPageCat').value != "ovr")
				{
					if($('mil'+content[1])){
						$('mil'+content[1]).insert ({
						 	'after'  : content[0]
						} );
				
						var str = 'div#read'+content[2]+' div.selectOptionDesc';
						var elems = $$(str);
						elems[0].insert ({
							'bottom' : $('milescomment_' + content[5])
						});
						
						str = 'div#read'+content[2]+' div.milestoneCont';
						elems = $$(str);
						elems[0].insert ({
						 	'after'  : $('milescommentUpperDiv'+content[5])
						} );
						
						$('mil'+content[1]).remove();
						
						commentMiles.hideCommentBut(content[5],content[3]);
						
						if($('milesPipe'+content[5])){//there is a task list
							if($('milesbutComment_'+content[5]).style.display != 'none'){ //comment button is shown
								$('milesPipe'+content[5]).show();
							}
							else {
								$('milesPipe'+content[5]).hide();
							}
						}
						else {
							if($('milesbutComment_'+content[5]).style.display != 'none'){ //comment button is shown
								$('milescommentUpperDiv'+content[5]).show();
							}
							else {
								$('milescommentUpperDiv'+content[5]).hide();
							}
						}
						
					}
					
					if(content.length > 6) {
						var groupid = content[7];
						var startDate = content[8];
						var completedDate = content[9];
						getNewCCD(startDate, completedDate, groupid);
					}
					
					changeBlueBg('mil'+content[2]);
					
					if($('hdnMl'+content[5]))
						$('hdnMl'+content[5]).value = "0";
				}	
				
				if(document.getElementById('RHSMil'+content[1])){			
								
					
					
					if(content[3] == 'completed'){
						//$('RHSMil'+content[1]).className = $('RHSMil'+content[1]).className.replace('tName','tName completed');
						$('RHSMilSpan'+content[1]).className = 'completed';
						//$('RHSMil'+content[1]).className = $('RHSMil'+content[1]).className.replace('overdue',' ');
						$('RHSMilChk'+content[1]).checked = true;
						//$('RHSMilChk'+content[1]).attributes["onclick"].value = content[4];
						$('RHSMilChk'+content[1]).onclick = new Function(content[4]);
						if($('commentLinkMilRHS'+ content[5]) && $('commentLinkMilRHS'+ content[5]).innerHTML == "[Comment]"){
							$('commentLinkMilRHS'+content[5]).hide();
						}
						$('RHSMil'+content[1]).id = 'RHSMil'+content[2];
						$('RHSMilChk'+content[1]).id = 'RHSMilChk'+content[2];
						$('RHSMilSpan'+content[1]).id = 'RHSMilSpan'+content[2];
						if($('milListsMy'+content[5])){
							var innerEle = $$("div#milListsMy"+content[5]+" span");
							//var innerEle = Element.descendants($('milListsMy'+content[5]));
							var innerEleLen = innerEle.length;
							for(i=0;i<innerEleLen;i++){
								if(innerEle[i] && innerEle[i].id.indexOf('RHSTaskSpan') == '0'){
									innerEle[i].className = 'completed';
									var id = innerEle[i].id.substring(11,innerEle[i].id.length);
									if($('RHSTaskChk'+id)){
										$('RHSTaskChk'+id).checked = true;
										resetOnclickStatus('RHSTaskChk'+id,2);
									}
									if($('commentLinkTaskRHS'+ id) && $('commentLinkTaskRHS'+ id).innerHTML == "[Comment]"){
										$('commentLinkTaskRHS'+ id).hide();
									}
									
	 							}
								/*if(innerEle[i].id != "" && $(innerEle[i].id).className == "mGDetailRow")
									$(innerEle[i].id).className = "mGDetailRowChecked";
								if(innerEle[i].type == "checkbox"){
									$(innerEle[i].id).checked = true;
									resetOnclickStatus(innerEle[i].id,2);					
								}*/
							}
						}
						if($('milListsMyA'+content[5])){
							var innerEle = $$("div#milListsMyA"+content[5]+" span");
							//var innerEle = Element.descendants($('milListsMyA'+content[5]));
							var innerEleLen = innerEle.length;
							for(i=0;i<innerEleLen;i++){
								if(innerEle[i] && innerEle[i].id.indexOf('RHSTaskSpan') == '0'){
									innerEle[i].className = 'completed';
									var id = innerEle[i].id.substring(11,innerEle[i].id.length);
									if($('RHSTaskChk'+id)){
										$('RHSTaskChk'+id).checked = true;
										resetOnclickStatus('RHSTaskChk'+id,2);
									}
									if($('commentLinkTaskRHS'+ id) && $('commentLinkTaskRHS'+ id).innerHTML == "[Comment]"){
										$('commentLinkTaskRHS'+ id).hide();
									}
	 							}
								/*if(innerEle[i].id != "" && $(innerEle[i].id).className == "mGDetailRow")
									$(innerEle[i].id).className = "mGDetailRowChecked";
								if(innerEle[i].type == "checkbox"){
									$(innerEle[i].id).checked = true;
									resetOnclickStatus(innerEle[i].id,2);
								}*/
							}  
						}
					}
					if(content[3] == 'overdue'){				
						//$('RHSMil'+content[1]).className = $('RHSMil'+content[1]).className.replace('tName','tName overdue');
						$('RHSMilSpan'+content[1]).className = 'overdue';
						//$('RHSMil'+content[1]).className = $('RHSMil'+content[1]).className.replace('completed',' ');
						$('RHSMilChk'+content[1]).checked = false;
						//$('RHSMilChk'+content[1]).attributes["onclick"].value = content[4];
						$('RHSMilChk'+content[1]).onclick = new Function(content[4]);
						$('RHSMil'+content[1]).id = 'RHSMil'+content[2];
						$('RHSMilChk'+content[1]).id = 'RHSMilChk'+content[2];
						$('RHSMilSpan'+content[1]).id = 'RHSMilSpan'+content[2];
						if($('commentLinkMilRHS'+ content[5])) $('commentLinkMilRHS'+content[5]).show();
					}
					if(content[3] == ''){
						//$('RHSMil'+content[1]).className = $('RHSMil'+content[1]).className.replace('tName','tName');
						//$('RHSMil'+content[1]).className = $('RHSMil'+content[1]).className.replace('completed',' ');
						$('RHSMilSpan'+content[1]).className = '';
						$('RHSMilChk'+content[1]).checked = false;
						//$('RHSMilChk'+content[1]).attributes["onclick"].value = content[4];
						$('RHSMilChk'+content[1]).onclick = new Function(content[4]);
						$('RHSMil'+content[1]).id = 'RHSMil'+content[2];
						$('RHSMilChk'+content[1]).id = 'RHSMilChk'+content[2];
						$('RHSMilSpan'+content[1]).id = 'RHSMilSpan'+content[2];
						if($('commentLinkMilRHS'+ content[5])) $('commentLinkMilRHS'+content[5]).show();
					}	
					}
				if($('RHSMilChk'+content[2]))	
					$('RHSMilChk'+content[2]).disabled = false;
					
				if($('milChk'+content[2]))
					$('milChk'+content[2]).disabled = false;
						
				$('sidebar').hide();
				
				/*** Reflect the changes in overview page *****/
				
				if($('currPageCat').value == "ovr")
				{	//alert(content['5'] + "--" + content['6'] + "--" +content['3']);
					
					var objMilHistory = $('milHist'+content['5']);
					var strMilHist = "div#milHist"+content['5']+" span"; 	
					var strMlDivTitle = 'div#mil'+content['5']+' div.postTitleBy';
					var strMlDivAsg = 'div#mil'+content['5']+' div.postTitleCont';
					
					var elemTitle = $$(strMlDivTitle);
					var elemsAsg = $$(strMlDivAsg);
					if(content['3'] == "completed")
					{
						if(elemTitle['0'])
							elemTitle['0'].className = elemTitle['0'].className+" completed";
						if(elemsAsg['0'])	
							elemsAsg['0'].className = elemsAsg['0'].className+" completed";
							
						if(objMilHistory)
						{
							objMilHistory.show();
							elemHist = $$(strMilHist);
							
							if(elemHist['0'])
								elemHist['0'].className = elemHist['0'].className.replace("completed","");
						}	
					}
					else
					{
						if(elemTitle['0'])
							elemTitle['0'].className = elemTitle['0'].className.replace("completed","");
						if(elemsAsg['0'])
							elemsAsg['0'].className = elemsAsg['0'].className.replace("completed","");
							
						if(objMilHistory)
						{
							objMilHistory.show();
							elemHist = $$(strMilHist);
							
							if(elemHist['0'])
								elemHist['0'].className = elemHist['0'].className+" completed";
								
						}	
					}
					
					if(content['6'] != "")
					{
						var tdArr = content['6'].split(",");
						tdLen = tdArr.length;
						
						for(var i=0;i<tdLen;i++)
						{
							var strTdDivTitle = 'div#taskThread_'+tdArr[i]+' div.postTitleBy';
							var strTdDivAsg = 'div#taskThread_'+tdArr[i]+' div.postTitleCont';
							
							var elemTitle = $$(strTdDivTitle);
							var elemsAsg = $$(strTdDivAsg);
							if(content['3'] == "completed")
							{
								if(elemTitle['0'])
									elemTitle['0'].className = elemTitle['0'].className+" completed";
								if(elemsAsg['0'])	
									elemsAsg['0'].className = elemsAsg['0'].className+" completed";														
							}
							else
							{
								if(elemTitle['0'])
									elemTitle['0'].className = elemTitle['0'].className.replace("completed","");
								if(elemsAsg['0'])
									elemsAsg['0'].className = elemsAsg['0'].className.replace("completed","");
							}
							commentTask.hideCommentBut(tdArr[i],content[3]);
						}
					}
					
					commentMiles.hideCommentBut(content[5],content[3]);
					/*		
					var url = '/groups/milestones/getCompletedMilestonesDetail.json';
					var pars = 'sessUID='+$('SessStrUserID').value+'&milVer='+content['5']+'&tdids='+content['6'];
					new Ajax.Request(url, {method: 'post', parameters: pars,
						onSuccess:function(originalRequest)
						{
							if(originalRequest.responseText == "expired")
					        {      
					        	window.location = MainUrl + "main.php?u=signout";return;
					        }
					        else
					        {
								data=(eval('(' + originalRequest.responseText + ')'));
								
								
								
								
								var html = "";
								var htmlMil = "";
								var cmpdClass = "";
								var postClass = "";
								var status_id = "";
								var meClass = "";
								var i=0;
								
								data.milestones.each(
									function(item) {
										meClass = "";
										postClass = "postGroup";
										status_id = item['mil'].status_id;
										
										strCmpAss = "assigned to";
										strCmpAssUsername = item['0'].milname;
										if(item['mil'].status_id == "2")
										{
											cmpdClass = " completed";
											strCmpAss = "completed by";
											strCmpAssUsername = item['0'].compname;
										}	
										else
											postClass = "postGroupTop";
											
										if(content['6'] == "")
											postClass = "postGroupTop";
											
										if(item['0'].milname.strip().toLowerCase() == "me")		
											meClass = "me";
																			
										htmlMil += '<div class="'+postClass+'">'+
				                      				'<div class="read '+meClass+' cmpTD">'+
				                        				'<div class="postCategory oneLineLimitSmall"><span title="Milestone">Milestone</span></div>'+
				                        				'<div class="postData'+cmpdClass+'">'+
				                          					'<div class="postTitleBy">';
				                        if(item['mil'].status_id == "1")
				                        	htmlMil += '<span class="act">reopened</span>';  					
				                          					
				                        htmlMil  +=					strCmpAss+' <span>'+strCmpAssUsername+'</span></div>'+
				                          					'<div class="postTitleCont">'+item['mil'].title+'</div>'+
				                        				'</div>'+
				                      				'</div>'+
				                    			'</div>';
				                    	htmlMil += '<input type="hidden" id="hdnMl'+item['mil'].id+'" value="1">';		
									});
								if(status_id != "1")
								{	
									data.todos.each(
										function(item) {
											meClass = "";
											cmpdClass = "";
											if(item['todos'].status_id == "2")
												cmpdClass = " completed";
												
											if(i==0)
												postClass = "postGroupTop";
											else
												postClass = "postGroup";
												
											if(item['0'].tdname.strip().toLowerCase() == "me")		
												meClass = "me";	
																					
											html += '<div class="'+postClass+'">'+
					                      				'<div class="read '+meClass+' cmpTD">'+
					                        				'<div class="postCategory oneLineLimitSmall"><span title="Task">Task</span></div>'+
					                        				'<div class="postData'+cmpdClass+'">'+
					                          					'<div class="postTitleBy">completed by <span>'+item['0'].tdname+'</span></div>'+
					                          					'<div class="postTitleCont">'+item['todos'].title+' - '+item['todolists'].title+'</div>'+
					                        				'</div>'+
					                      				'</div>'+
					                    			'</div>';
					                    	html += '<input type="hidden" id="hdnTd'+item['todos'].id+'" value="1">';
					                    	i++;		
										});
								}
								
								html = html +htmlMil;																																								
								
								var latestDate = Element.immediateDescendants($('overviewBody'))['0'].immediateDescendants()['0'].innerHTML;
								var obj    
								if(latestDate.strip().toLowerCase() == "today"){
					            	Element.immediateDescendants($('overviewBody'))['1'].immediateDescendants()['0'].className = Element.immediateDescendants($('overviewBody'))['1'].immediateDescendants()['0'].className.replace("postGroupTop","postGroup");
					            	//Element.immediateDescendants($('overviewBody'))['0'].remove();
					            	obj = Element.immediateDescendants($('overviewBody'))['1'];            	
					            }
					            else
					            {
					            	html = '<div class="threadDate"><span class="threadDateDisplay">Today</span></div>'+'<div class="threadGroup">'+html+'</div>';
					            	obj = $('overviewBody');
					            }
					            
					            
					            	
					        	obj.insert ({
					 				'top'  : html
					  			} );
								
								
								
								
								
							}
								
						}
					});
					*/
				}
			}
		},
		showNewData: function (originalRequest) { //alert(originalRequest.responseText);
			if(originalRequest.responseText == "expired")
	        {      
	        	window.location = MainUrl + "main.php?u=signout";return;
	        }
	        else
	        {
				this.data=(eval('(' + originalRequest.responseText + ')'));
				
				var blnIsDelOrComplete = true,
				status_id = 1;
				this.data.milestone.each(
					function(item)
					{
						if(item.milestones.status_id != "2")
							blnIsDelOrComplete = false;
						else
							status_id = 2;	
					});
				
				if(blnIsDelOrComplete)
				{
					showUtlPopup('Milestone','edit',status_id);
				}
				else
				{	
					this.fillHolder();
					centerPos(document.getElementById('edit_milestone')	, 1);
					document.getElementById('edit_milestone').style.display = 'block';
				}	
			}
		}
	});
	function hideEditMilestoneWindow() {
		window.editMilestonePopupStatus=0;		
		$('frmEditMilestone').reset();
		$('edit_milestone').style.display = 'none';		
		$('calendar-milestone-edit').innerHTML = '';
		$('inputTitle').innerHTML = '';
		$('editLabel').innerHTML = '';
		$('errEditMilestoneDate').hide();
				
	}
	/*
	function hideMilestoneWindow() {
		if($('add_milestone_with_calender'))
		$('add_milestone_with_calender').style.display='none';
		$('frm_add_milestone_with_calender').reset();
	}
	*/
	
	function resetOnclickStatus(id,status_old){
		var temp = '';
		if(BrowserDetect.browser == "Explorer"){
			temp = $(id).onclick.toString();
			if(status_old == 2)
				temp = temp.replace("changeStatusTaskData('2","changeStatusTaskData('1");
			else if(status_old == 1)
				temp = temp.replace("changeStatusTaskData('1","changeStatusTaskData('2");
					
			temp = temp.replace("function anonymous()","");
			temp = temp.replace("function onclick()","");	
			temp = temp.replace(/[\}|\{|\s]/g,"");	
		}
		else {
			temp = $(id).attributes["onclick"].value;
			if(status_old == 2)
				temp = temp.replace("changeStatusTaskData('2","changeStatusTaskData('1");
			else if(status_old == 1)
				temp = temp.replace("changeStatusTaskData('1","changeStatusTaskData('2");			
		}
		
		$(id).onclick = new Function(temp);
	}
	
	function hideMilestoneWindow(mid) {
	
			// Remove error notifications on outer pages
			if($('errMilestoneTitle')) $('errMilestoneTitle').hide();
			if($('addTitle_with_calender')) $('addTitle_with_calender').removeClassName('inpErr');
			if($('errWhoIsResponsible')) $('errWhoIsResponsible').hide()
			if($('addSelectML')) $('addSelectML').removeClassName('inpErr');
			if($('errAddMilestoneDate')) $('errAddMilestoneDate').hide();
	  		if($('milestoneCalendar')) $('milestoneCalendar').removeClassName('inpErr');
	  		
			// Remove error notifications inside calendar
			if($('errMilestoneTitleCal')) $('errMilestoneTitleCal').hide();
			if($('addTitle')) $('addTitle').removeClassName('inpErr');
			if($('errMilestoneUserCal')) $('errMilestoneUserCal').hide()
			if($('addSelectCM')) $('addSelectCM').removeClassName('inpErr');	  		
	  		
			parent.$('calendarOpenButton').style.display = 'block';
			if($('selectedIDs3'))
			$('selectedIDs3').value = $('currentGroupID').value;	
			if($('commonDropDOwn3'))
			$('commonDropDOwn3').style.display='none';

			window.addMilestonePopupStatus=0;

			if($('calenderPopupArrow'))
			$('calenderPopupArrow').style.display='none';
	
			if($('frm_add_milestone_with_calender'))
			$('frm_add_milestone_with_calender').reset();

			/*if($('frmAddMilestone')){ // commented by mosh
				$('frmAddMilestone').reset();
				var userData=new setUserData;
				userData.getDropdown($('currentGroupID').value); //reset user data
			}*/
			var positionCalenderPopup=0;

			if($('add_milestone_with_calender'))
			$('add_milestone_with_calender').style.display='none';
			
			if($('add_milestone'))
			{
				if($('add_milestone').style.display != 'none') {
					$('add_milestone').style.display = 'none';
					$('calenderPopupArrow').style.display='none';
				
				} else if($('new_todoList_popup').style.display != 'none') {
					$('new_todoList_popup').hide();
					$('frmTodo').reset();					
				}
			}
			
			/*if($('ms_popup'))
			{
				if(window.parent.$('ms_popup').style.display != 'none') {
					window.parent.$('ms_popup').style.display = 'none';
					//window.parent.$('ms_popup').innerHTML = '';
					alert($('calenderPopupArrow').className);
					//$('calenderPopupArrow').style.display='none';
				
				} else if($('new_todoList_popup').style.display != 'none') {
					$('new_todoList_popup').hide();
					$('frmTodo').reset();					
				}
			}*/
			
			//if(parent.$('im').style.display=='none')
			//parent.$('calendarOpenButton').style.display='block';  //showing calendar tab

	
			if(mid)
			{
				Effect.ScrollTo(mid,{duration: 0.1});		
				new Effect.Highlight(mid,{startcolor: startColor,endcolor: endColor,duration:1});
			}
		
	}
	function showAddProfileWithCalender(){	 
	  	var element = $('add_milestone_with_calender');	 
	  	$('completed_date_add_with_calender').value='0000-00-00';
	    centerPos(element, 1);	    
	  	document.getElementById('add_milestone_with_calender').style.display = "block";	  	
	  	document.getElementById('edit_milestone').style.display = "none";
	  	$('add_result').innerHTML = "";
		$('calendar-milestone-add').innerHTML = "";
		addMilestoneCalendar('add', 1);	  
	 }
  
	function addMilestoneCalendar(addedit, selDay) {
		  function dateChanged(calendar) {
	
		    if (calendar.dateClicked)
		    {
		    	//alert(addedit);
		    	if ( addedit == 'add')
		    	{
	
		    		$('selDateAdd').innerHTML = '<strong>Due: '+ calendar.date.print("%B %d, %Y") +'</strong>';
		      		document.getElementById('target_date_add').value = calendar.date.print("%Y-%m-%d")+ ' 00:00:00';
		    	}
		    	else
		    	{
		    		$('selDateEdit').innerHTML = "<strong>Due: "+ calendar.date.print('%B %d, %Y') +"</strong><input id='target_date_edit' type='hidden' name='data[milestones][targetdate]' value='" + calendar.date.print('%Y-%m-%d')+ " 00:00:00" + "' />";
		    	}
	
		    }
		  };
	      dateTest = new Date(document.getElementById('today_date').value*1000);
		  if (addedit == 'add')
		  {
			$('calendar-milestone-add').innerHTML = '';
			var myDate=new Date();
	
			$('selDateAdd').innerHTML = '<strong>Due: '+ myDate.print("%B %d, %Y") +'</strong>';
		    document.getElementById('target_date_add').value = myDate.print("%Y-%m-%d")+ ' 00:00:00';
	
			Calendar.setup(
		  	{
		  		flat         : "calendar-milestone-add",
	            date         : dateTest,
		  		flatCallback : dateChanged
		  	}
		  	);
		  }
		  else
		  {
			$('calendar-milestone-edit').innerHTML = '';
		  	Calendar.setup(
		  	{
		  		flat         : "calendar-milestone-edit",
		  		flatCallback : dateChanged,
	            date         : dateTest,
		  		dateStatusFunc : function(date, y, m, d) {
		                         if (selDay == m+1+"-"+d+"-"+y) return "special";
		                         else return false; // other dates are enabled
		                         // return true if you want to disable other dates
		                       }
		  	}
		  	);
	
		  }
	}

	function validateAddWithCalender(frm) {
	  	var proj_id = $('currGrpId').value;
	  	var newDate = document.getElementById('target_date_add').value;
	  	newDate = newDate.replace(/-/g,'/');
	
	  	var curDate = document.getElementById('today_add_with_calender').value;
	  	curDate = curDate.replace(/-/g,'/');
	
	  	var myDay = new Date(newDate);
	  	var today = new Date(curDate);
	  	var errOccured;
		var tempTitle = document.getElementById('addTitle_with_calender').value;
	  	if (tempTitle.strip() == '')
	  	{
	  		//$('errorMsgTitle').style.display = '';
			//$('errorMsgTitle').innerHTML = "Please name your Milestone";
			$('errMilestoneTitle').show()
			$('addTitle_with_calender').addClassName('inpErr');
	  		//return false;
	  		errOccured = 1;
	  	} else {
	  		//$('errorMsgTitle').style.display = 'none';
	  		//$('errorMsgTitle').innerHTML = "";
	  		$('errMilestoneTitle').hide();
			$('addTitle_with_calender').removeClassName('inpErr');
	  	}
	  	if ($('addSelectML').value == '0')
	  	{
	  		//$('errorMsgTitle').style.display = '';
			//$('errorMsgTitle').innerHTML = "Please select a user";
			$('errWhoIsResponsible').show()
			$('addSelectML').addClassName('inpErr');
	  		//return false;
	  		errOccured = 1;
	  	} else {
	  		//$('errorMsgTitle').style.display = 'none';
	  		//$('errorMsgTitle').innerHTML = "";
	  		$('errWhoIsResponsible').hide()
			$('addSelectML').removeClassName('inpErr');
	  	}
	  	if (today > myDay)
	  	{
	  		//$('errorMsgTitle').style.display = '';
	  		//$('errorMsgTitle').innerHTML = "Please select a day in the future";
	  		$('errAddMilestoneDate').show();
	  		$('milestoneCalendar').addClassName('inpErr');
	  		//return false;
	  		errOccured = 1;
	  	} else {
	  		//$('errorMsgTitle').style.display = 'none';
	  		//$('errorMsgTitle').innerHTML = "";
	  		$('errAddMilestoneDate').hide();
	  		$('milestoneCalendar').removeClassName('inpErr');
	  	}
	  	
	  	if(errOccured == '1') return false;
	
		if (!milestoneSubmitted) {
			milestoneSubmitted = true;
			if (micoxUpload(frm,'/groups/milestones/submitAdd/'+proj_id,'add_loading_img','<img src=/groups/img/loading.gif class=loadingImgLeft>','Error',proj_id) == false) {
				milestoneSubmitted = false;
			}
		}
	  	return false;
	}
  	  
	function validateEdit(frm) {
	  	var proj_id = $('currGrpId').value;
	  	var newDate = document.getElementById('target_date_edit').value;
	  	newDate = newDate.replace(/-/g,'/');
	
	  	var curDate = document.getElementById('today_edit').value;
	  	curDate = curDate.replace(/-/g,'/');
	
	  	var myDay = new Date(newDate);
	  	var today = new Date(curDate);
	
		var tempTitle = document.getElementById('editTitle').value;
	  	if (tempTitle.strip() == '')
	  	{
	  		//$('edit_result').style.display = '';
	  		//$('edit_result').innerHTML = "Please name your Milestone";
	  		$('errEditMilestoneTitle').show()
			$('editTitle').addClassName('inpErr');
	  		
	  		return false;
	  	} else {
	  		//$('edit_result').style.display = 'none';
	  		//$('edit_result').innerHTML = "";
	  		$('errEditMilestoneTitle').hide()
			$('editTitle').removeClassName('inpErr');
	  	}
	  	if (today > myDay)
	  	{
	  		//$('edit_result').style.display = '';
	  		//$('edit_result').innerHTML = "Please select a day in the future";
			$('errEditMilestoneDate').show()
			$('milestoneCalendar').addClassName('inpErr');

	  		return false;
	  	} else {
	  		//$('edit_result').style.display = 'none';
	  		//$('edit_result').innerHTML = "";
	  		$('errEditMilestoneDate').hide()
			$('milestoneCalendar').removeClassName('inpErr');
	  	}
	  	if (!milestoneSubmitted) {
			milestoneSubmitted = true;
			if (micoxUpload(frm,'/groups/milestones/submitEdit/'+proj_id,'edit_loading_img','<img src=/groups/img/loading.gif class=loadingImgLeft>','Error',proj_id) == false) {
				milestoneSubmitted = false;
			}
		}
	  	return false;
    }
	  
	function sendMail(type,groupID,status,milID){
		  	var url    = '/groups/milestones/sendMail.json';				
			var pars   = "groupid="+groupID+"&milID="+milID+"&type="+type+"&status="+status;
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars});
	}
	/************	ENDS		***************/

	/************	Tasks	***************/
	
	var tasks = Class.create({

		initialize : function() {
			groupid = '';
			msgid = '';
			tmp = '';
		},
		
		callTasks : function() {
			$('currPageCat').value = "td";
			showLoader();
			this.groupid = $('currGrpId').value;
			new Ajax.Updater('maincontent', '/groups/tasks/getTasks.json', {
	  			parameters: { groupid: this.groupid },
	  			evalScripts:true
			});
		},
		
		taskToggle : function(taskid,state,showDel) {
			$('sidebar').hide();
			if((state && $('hdnTd'+taskid).value == "1") || showDel == '1') {
				var f = findPosition('taskThread_'+taskid);
				/*
				intRight = 	(windowWidth/2)-365;
				if(windowWidth<1280)
					intRight = 295;
				*/
				if(BrowserDetect.browser == 'Explorer' && BrowserDetect.OS == 'Windows')
				f[1] = f[1] - 200;
				
				intRight = 	((windowWidth - 1280)/2)+275;
				if((BrowserDetect.browser == 'Chrome' ||  BrowserDetect.browser == 'Opera') && windowWidth > 1280){
					intRight = intRight - 7;
				}
				if(windowWidth<=1100)
					intRight = 205;
				else 
				if(windowWidth<=1280)
					intRight = 295;					
				$('sidebar').setStyle({
					marginTop : f[1] + 'px',
					right: intRight + 'px'
				});							
				$('sbPipe').hide();
				$('sidebar_markasread').hide();
				$('sidebarDel').hide();
				$('sidebar').show();
				if((state && $('hdnTd'+taskid).value == "1") && showDel == '1'){
					$('sbPipe').show();
				}
			
				if((state && $('hdnTd'+taskid).value == "1")){							
					$('sidebar_markasread').innerHTML = '<a onclick="javascript:tasksObject.markAsRead(\''+taskid+'\',\''+showDel+'\');" href="javascript:void(0)">Mark as read</a>';							
					$('sidebar_markasread').show();
				}
				if(showDel == '1'){
					var elemId = 'taskThread_'+taskid;
					
					var func = escape("ovrDelete(\'Task\',\'"+taskid+"\',\'"+elemId+"\')");
					$('sidebarDel').innerHTML = '<a href="javascript:void(0);" onclick="beforeDelete(this,event,\'TK\',\'0\',\''+func+'\');">Delete</a>';
					$('sidebarDel').show();
				}
			}
		},
		
		show_hide_profilePer: function(divID)
		{
			Effect.BlindUp(divID,{duration: 0.5});
			
				var showProfilePer = this.showProfilePer.bind(this);
				var url    = '/groups/groups/profileHideShow.json';				
				var pars   = "par=1";
				var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onComplete: showProfilePer});
		},
		
		showProfilePer: function(response)
		{
			if(response.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
		
		},
		
		markAsRead: function (selId,showDel) {

			//var markAsReadSuccess = this.markAsReadSuccess.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/tasks/markAsRead.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&task_id=' + selId + '&sessUID='+userID;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onSuccess: function(originalRequest){
			
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			
			changeBlueBg('mse'+selId);
			$('mse'+selId).className = $('mse'+selId).className.replace('unRead','read');
			$('sidebar_markasread').hide();
			$('sbPipe').hide();
			$('taskThread_'+selId).attributes["onmouseover"].value = 'javascript:tasksObject.taskToggle("'+selId+'", false,'+showDel+')';
			}} );
		},

		/*markAsReadSuccess: function (originalRequest) {
			//paginateData('/groups/tasks/getTasks.json',$('taskPageNum').value);
		},  
		*/		
		markAllAsRead: function () {
			var markAllAsReadSuccess = this.markAllAsReadSuccess.bind(this);
			var rand   = Math.random(99999);
			var url    = '/groups/tasks/markAllAsRead.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&sessUID='+userID+'&groupid='+$('currGrpId').value;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: markAllAsReadSuccess} );
		},
		
		markAllAsReadSuccess: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			paginateData('/groups/tasks/getTasks.json',$('taskPageNum').value);
		},
		
		showhideAddTask : function(listid) {
			var box = 'addTaskBox'+listid;
			//var link = 'addTaskLink'+listid;
			var taskForm = 'thisTask'+listid;
			var result = 'result'+listid;			
			if ($(box).style.display != 'none') {
				//$(link).show();
				Effect.toggle(box,'slide',{duration: 0.5});
				
			} else {
				//$(link).hide();
				$(taskForm).reset();
				$(result).innerHTML = '';
				Effect.SlideDown(box,{duration: 0.5});
			}
		},
		
		addThisTask : function(listid,groupId) {						
			var title = $('title'+listid).value.strip();
			var resp_id = $('resp'+listid).value.strip();
			
			//var result = 'result'+listid;
			var errOccured;
			
			if (title == '')
			{
				$('errAddTitleShort'+listid).show();
				$('title'+listid).addClassName('inpErr');
				//return false;
				errOccured = 1;
			} else {
				$('errAddTitleShort'+listid).hide();
				$('title'+listid).removeClassName('inpErr');
			}
			
			if(resp_id == '0')
			{
				$('errAddUserShort'+listid).show();
				$('resp'+listid).addClassName('inpErr');
				//return false;
				errOccured = 1;
			} else {
				$('errAddUserShort'+listid).hide();
				$('resp'+listid).removeClassName('inpErr');
			}
			
			if(!errOccured)
			{
				//$(result).innerHTML = '';			
				tasksObject.getTaskData(title,resp_id,listid,groupId);
			}
		
		},
		getTaskData: function (title,id,listid,groupId) {			
			var showNewTaskData = this.showNewTaskData.bind(this);			
			var rand   = Math.random(99999);			
			var url    = '/groups/tasks/addTask.json';
			var userID = $('SessStrUserID').value;
			//var pars   = 'id=1&rand=' + rand + '&title=' + title+'&id='+id+'&listid='+listid+'&sessUID='+userID+'&groupid='+groupId;
			var myAjax = new Ajax.Request( url, {method: 'post', 
							parameters: {id:1, rand:rand, title:title, id:id, listid:listid, sessUID:userID,groupid:groupId}, 
							onComplete: showNewTaskData} );
		},
		hideCreateTasks: function(){
			$('new_todoList_popup').style.display = 'none';	
			$('frmTodo').reset();
			
			$('errTaskTitle').hide()
			$('todo_title').removeClassName('inpErr');
			
			$('errTaskUser').hide()
			$('responsible').removeClassName('inpErr');
			
			$('errTasklistTitle').hide();
			$('todolist_title').removeClassName('inpErr');
			
			$('new_todoList').style.display = 'none';
			if($('new_todo'))$('new_todo').style.display = 'none';
			$('addtodo').innerHTML = '';
			$('add_result').innerHTML = '';
			$('addlist_result').innerHTML = '';
		},
		showhide: function(obj){			
			if ($(obj).style.display == 'block' || $(obj).style.display == '') {
				$(obj).style.display = 'none';
			} else {
				$(obj).style.display = 'block';
			}
		},
		showhideWithSign: function(obj,signDiv){			
			if ($(obj).style.display == 'block' || $(obj).style.display == '') {
				//signDiv.className = 'expand';
				signDiv.innerHTML = '<img class="rightFloat" src="/images/maximize.gif" />';
				$(obj).style.display = 'none';
			} else {
				//signDiv.className = 'contract';
				signDiv.innerHTML = '<img class="rightFloat" src="/images/minimize.gif" />';
				$(obj).style.display = 'block';
			}
		},
		showNewTaskData: function (originalRequest) { //alert(originalRequest.responseText);
			if(originalRequest.responseText == "expired")
	        {      
	        	window.location = MainUrl + "main.php?u=signout";return;
	        }
	        else
	        {

				this.data=(eval('(' + originalRequest.responseText + ')'));
				
				if(this.data.isdelete)
				{
					var tskListId = this.data.tasklistid;
					if(this.data.reason == "2")
						$('taskErrMsg'+tskListId).innerHTML = "You cannot add new tasks because the milestone attached to this task list is just marked as complete.";
					else
						$('taskErrMsg'+tskListId).innerHTML = "You cannot add new tasks because this task list has just been deleted by the author.";
							
				
					$('taskErrMsg'+tskListId).show();
				}
				else
				{
					//removing blank state if any
					if($('taskBlankList'))$('taskBlankList').innerHTML = '';
					
					// displaying new task 			
					var user = this.data.loggedInUser;
					var lastInsertId = this.data.lastInsertId;
					var totalOpenTodos = this.data.totalOpenTodos;
					var grpId = this.data.grpId;
					var user_name = '';
					var classReadValue = '';
					var classMeValue = '';
					var openclassLineTop = '';
					var mseOver = '';
					var slidedown = 'slidedown'+lastInsertId;	
					this.data.opened_tasks.each(
						function(item) {
							if(totalOpenTodos>1) {
								openclassLineTop = 'postGroupTop';
								//add existing div a top line
								
							}else{
								openclassLineTop = 'postGroupTop';
							}
								
							$('thisTask'+item['todos'].todolist_id).reset(); 
							if(item['events'].viewed == ''){
							 classReadValue = 'read';
							 mseOver = 'javascript:tasksObject.taskToggle("'+item['todos'].id+'", false, 1);';
							}else{
							 classReadValue = 'unRead';
							 mseOver = 'javascript:tasksObject.taskToggle("'+item['todos'].id+'", true, 1);';
							}
		
							if(user == item['todos'].responsible_user_id){
								user_name = 'assigned to Me'; 
								classMeValue = ' me';
							}else{	
								user_name = 'assigned to '+item['userpersonalinfo'].FullName; 
								classMeValue = '';
							}
							var chkOnclickStr = "tasksObject.changeStatusTaskData('2','"+item['todos'].id+"','"+item['todos'].todolist_id+"','"+openclassLineTop+"')";
		                    var commentHtml = commentTask.CommentBoxHtml(item['todos'].id,grpId);
		                    var newTaskStr = ""
					            		+"<div id='wrapper'><div  id='"+ slidedown +"' style='display:none;'>"
					            		 +"<div id='taskThread_"+ item['todos'].id +"'  class='"+openclassLineTop+"' onmouseover='"+mseOver+"' onmouseout='javascript:$(\"sidebar\").hide();'>"
					            			+"<div id='taskOuterDiv"+ item['todos'].id +"' >"
					            			+"<div class='"+classReadValue+"' id='mse"+ item['todos'].id +"'>"
						            			+"<div class='selectOption'> <input type='checkbox' class='borderNone' onclick="+chkOnclickStr+" /></div>"
							              		+"<div class='selectOptionDesc'>"
							                		+"<div class='milestoneBy "+classMeValue+"'>"+ user_name +"</div>"
							              			//+"<div class='milestoneCont "+classMeValue+"'><div class='leftFloat paddingRigth10'>"+anchor(item['todos'].title)+"<a id='"+commentTask.par+"butComment_"+item['todos'].id+"' onclick='commentTask.openComment("+item['todos'].id+","+grpId+")' href='javascript:void(0);' class='butComment' style='margin-left:5px;'>Comment</a></div><div class='clearLeft'>Â </div></div>"
							              			+"<div class='milestoneCont "+classMeValue+"'><div class='leftFloat paddingRigth10'>"+anchor(item['todos'].title)+"</div><div class='clearLeft'> </div></div>"
							              			+"<div class='paddingTop10'><a id='"+commentTask.par+"butComment_"+item['todos'].id+"' onclick='commentTask.openComment("+item['todos'].id+","+grpId+")' href='javascript:void(0);' class='butComment' >Comment</a></div><div class='clearRight'> </div>"
							              			+commentHtml
							              		+"</div>"																
										  	+"</div>"
										  +"</div></div></div></div>";
								                            	
										  					   		 
							var innerEle = Element.descendants($('thread'+item['todos'].todolist_id));
							var innerEleLen = innerEle.length;
							for(i=0;i<innerEleLen;i++){
								if(innerEle[i].id.strip() != "" && $(innerEle[i].id).className == "postGroupTop"){							
									$(innerEle[i].id).className = "postGroup";
									break;
								}
							}
									  					   		 
							$('addNewTaskDiv'+item['todos'].todolist_id).innerHTML = newTaskStr + $('addNewTaskDiv'+item['todos'].todolist_id).innerHTML;
						}
		
					);
					Effect.SlideDown(slidedown, {duration: 1});
					this.loadRHS();
					// displaying new task ends 
					if(this.data.isMail == "yes")
					{
						var group_id = $('currGrpId').value;
						var todoId = this.data.lastInsertId;
						var type = 'todo';
						var rand   = Math.random(99999);
						var url    = '/groups/tasks/sendMail4ChangeTodoStatus.json';						
						var pars   = 'rand=' + rand + '&tdID=' + todoId + '&groupid=' + group_id + '&type='+type;
						var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars} );
						
					}
	        	}	
				
			}
		},
		
		loadRHS: function () { 
			var url    = '/groups/tasks/loadRhs.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'status='+status+'&groupid='+$('currGrpId').value;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onSuccess: function (originalRequest){
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
				$('myMilTasksRHS').innerHTML = originalRequest.responseText;
			
			}} );
		},
		
		getTotPageTask: function(grpid)
		{
			var rand   = Math.random(99999);
			new Ajax.Request('/groups/tasks/getTotPageTask.json', {
	  			parameters: { rand:rand,grpid:grpid},
	  			onSuccess: function(transport)
	  				{
	  					if(transport.responseText == "expired")
				        {      
				        	window.location = MainUrl + "main.php?u=signout";return;
				        }
				        else
				        {
				        	data = (eval('(' + transport.responseText + ')'));
				        	totPage = data.totTsk;
				        	
				        	$('hdnCurrPageNo').value=totPage;
				        	paginateData('/groups/tasks/getTasks.json',totPage);
				        }	
	  				}
			});
		},
		
		changeStatusTaskData: function (status,todo_id,todo_list_id,sepCalss) {
			if($('RHSTaskChk'+todo_id))
				$('RHSTaskChk'+todo_id).disabled = true;
				
			if($('select'+todo_id))
				$('select'+todo_id).disabled = true;	
				 
			var showChangeStatusTaskData = this.showChangeStatusTaskData.bind(this);
			var url    = '/groups/tasks/changeStatus.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'status='+status+'&todo_id='+todo_id+'&listid='+todo_list_id+'&sessUID='+userID+'&sepClass='+sepCalss+'&groupid='+$('currGrpId').value;
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onSuccess: showChangeStatusTaskData} );
		},
		showChangeStatusTaskData: function (originalRequest) { //alert(originalRequest.responseText);
			if(originalRequest.responseText == "expired")
	        {      
	        	window.location = MainUrl + "main.php?u=signout";return;
	        }
	        else
	        {
				this.data=(eval('(' + originalRequest.responseText + ')'));
				
				if(this.data.stat)
				{
					var evt = "close",
						    stat = 1;
						
						if(this.data.status_id == "1")
							evt = "reopen";
							
						if(this.data.stat == "comp")
							stat = 2;
								
						showUtlPopup('Task',evt,stat);					
				}
				else
				{
					var loggedInUser = this.data.loggedInUser;
					var statusChangetask = this.data.statusChangetask;
					var group_id = this.data.group_id;
					var sepClass = this.data.sepClass;
					var user_name = '';
					var classMeValue = '';
					var classComp = '';
					var chk = '';
					this.data.tasksData.each(
						function(item) {
						var highlightDiv = 'mse'+item['todos'].id;						 
							var classReadValue = 'read';
							if(item['todos'].status_id == '1'){
								classComp = '';
								chk = '';
								var chkOnclickStr = "tasksObject.changeStatusTaskData('2','"+item['todos'].id+"','"+item['todos'].todolist_id+"','"+sepClass+"')";
								if(loggedInUser == item['todos'].responsible_user_id){
									user_name = 'assigned to Me'; 
									classMeValue = ' me';
								}else{	
									user_name = 'assigned to '+item['userpersonalinfo'].FullName; 
									classMeValue = '';
								}
							}else{
								classComp = 'completed';
								chk = "checked='checked'";
								var chkOnclickStr = "tasksObject.changeStatusTaskData('1','"+item['todos'].id+"','"+item['todos'].todolist_id+"','"+sepClass+"')";
								if(loggedInUser == item['todos'].completed_reopened_user_id){
									user_name = 'completed by Me on '+item['0'].modifiedFormat; 
									classMeValue = ' me';
								}else{	
									user_name = 'completed by '+item['userpersonalinfo'].FullName+' on '+item['0'].modifiedFormat; 
									classMeValue = '';
								}
							}
							
	
		                   
							if($('taskOuterDiv'+statusChangetask)){
								
								 var newTaskStr = ""
					            		//+"<div>"
					            			+"<div class='"+classReadValue+"' id='"+highlightDiv+"'>"
						            			+"<div class='selectOption'> <input "+chk+" type='checkbox' class='borderNone' onclick="+chkOnclickStr+" /></div>"
							              		+"<div class='selectOptionDesc'>"
							                		+"<div class='milestoneBy "+classMeValue+" "+classComp+"'>"+ user_name +"</div>"
							              			+"<div class='milestoneCont "+classMeValue+"'>"
							              				+"<div class='leftFloat paddingRigth10 "+classComp+"'>"
							              					+anchor(item['todos'].title)
							              				+"</div>"
							              				+"<div class='clearLeft'>Â </div>"
							              			+"</div>"
							              			+"<div class='paddingTop10'> <a id='taskbutComment_"+statusChangetask+"' onclick='commentTask.openComment(\""+statusChangetask+"\",\""+group_id+"\")' href='javascript:void(0);' class='butComment' style='display:none;'>Comment</a></div>"
							              			+"<div id='taskcomment_"+statusChangetask+"' class='comments' style='display: block;'>"
							              			+ $('taskcomment_'+statusChangetask).innerHTML
							              			+"</div>"
							              		+"</div>"																
										  //	+"</div>"
										  +"</div>";
											   													
								$('taskOuterDiv'+statusChangetask).innerHTML = newTaskStr;
								//new Effect.Highlight(highlightDiv, { startcolor: startColor,endcolor: endColor, duration:2});
							}
							
							//$('taskOuterDiv'+statusChangetask).className = classComp;
					
							
							
							if($('taskhdnCommCount'+statusChangetask) && $('taskhdnCommCount'+statusChangetask).value == 0)
							{
								$('taskcomment_'+statusChangetask).style.display='none';
							}
			
							if(item['todos'].status_id == '2'){
								//if($('RHSTask'+item['todos'].id)) $('RHSTask'+item['todos'].id).className = 'mGDetailRowChecked';
								if($('RHSTaskSpan'+item['todos'].id))$('RHSTaskSpan'+item['todos'].id).className = 'completed';
								if($('RHSTaskChk'+item['todos'].id)){
								 	$('RHSTaskChk'+item['todos'].id).checked = true;
								 	resetOnclickStatus('RHSTaskChk'+item['todos'].id,2);
								}
								if($('commentLinkTaskRHS'+ item['todos'].id) && $('commentLinkTaskRHS'+ item['todos'].id).innerHTML == "[Comment]"){
									$('commentLinkTaskRHS'+ item['todos'].id).hide();
								}
							}else{
								//if($('RHSTask'+item['todos'].id)) $('RHSTask'+item['todos'].id).className = 'mGDetailRow';
								if($('RHSTaskSpan'+item['todos'].id))$('RHSTaskSpan'+item['todos'].id).className = '';
								if($('RHSTaskChk'+item['todos'].id)){
								 	$('RHSTaskChk'+item['todos'].id).checked = false;
								 	resetOnclickStatus('RHSTaskChk'+item['todos'].id,1);
								}
								if($('commentLinkTaskRHS'+ item['todos'].id)){
									$('commentLinkTaskRHS'+ item['todos'].id).show();
								}
							}	
		
							if($('RHSTaskChk'+item['todos'].id))
								$('RHSTaskChk'+item['todos'].id).disabled = false;
								
							if($('select'+item['todos'].id))
								$('select'+item['todos'].id).disabled = false;	
								
							if($('currPageCat').value == "ovr")
							{
								var strTdDivTitle = 'div#taskThread_'+item['todos'].id+' div.postTitleBy';
								var strTdDivAsg = 'div#taskThread_'+item['todos'].id+' div.postTitleCont';
								
								var elemTitle = $$(strTdDivTitle);
								var elemsAsg = $$(strTdDivAsg);
								if(item['todos'].status_id == '2')
								{
									if(elemTitle['0'])
										elemTitle['0'].className = elemTitle['0'].className+" completed";
									if(elemsAsg['0'])	
										elemsAsg['0'].className = elemsAsg['0'].className+" completed";														
								}
								else
								{
									if(elemTitle['0'])
										elemTitle['0'].className = elemTitle['0'].className.replace("completed","");
									if(elemsAsg['0'])
										elemsAsg['0'].className = elemsAsg['0'].className.replace("completed","");
								}
							}
							else
							{
								if($('sidebarDel').style.display != 'none'){
									$('sidebar_markasread').hide();
									$('sbPipe').hide();
								}
								else{
									$('sidebar').hide();
								}
								
								changeBlueBg('mse'+statusChangetask);
								
								if($('hdnTd'+statusChangetask))
									$('hdnTd'+statusChangetask).value = "0";
							}
							
							commentTask.hideCommentBut(item['todos'].id,classComp);									
						}
		
					);
							
					if(statusChangetask != "undefined" && group_id != "undefined")
					{					
						var rand   = Math.random(99999); 
						var url    = '/groups/tasks/sendMail4ChangeTodoStatus.json';						
						var pars   = 'rand=' + rand + '&tdID=' + statusChangetask + '&groupid=' + group_id + '&type=todo';
						var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars} );
					}
					
				
					
				var lastMilInsertId = this.data.lastMilInsertId;
				var lastMilId = this.data.lastMilId;
				var milVersion = this.data.milVersion;
				var milStatus = this.data.milStatus; // overdue=1,upcomin=2,completed=3
				var onClkEvent = this.data.onClkEvent;
				var fnStatusVal = 1;
				if(milStatus == '1' || milStatus == '2')fnStatusVal = 2;
				//alert(lastMilInsertId+'=='+milStatus);	
				if(lastMilInsertId != '-1') ///if there is any change in the state of milestone
				{
					if($('RHSMil'+lastMilId))
					{
						//for overdue
						if(milStatus == '1'){
							//$('RHSMil'+lastMilId).className = $('RHSMil'+lastMilId).className.replace('tName','tName overdue');
							//$('RHSMil'+lastMilId).className = $('RHSMil'+lastMilId).className.replace('completed',' ');
							$('RHSMilSpan'+lastMilId).className = 'overdue';
							$('RHSMilChk'+lastMilId).checked = false;
							$('RHSMilChk'+lastMilId).onclick = new Function(onClkEvent);
							$('RHSMil'+lastMilId).id = 'RHSMil'+lastMilInsertId;
							$('RHSMilChk'+lastMilId).id = 'RHSMilChk'+lastMilInsertId;
							$('RHSMilSpan'+lastMilId).id = 'RHSMilSpan'+lastMilInsertId;
							if($('commentLinkMilRHS'+ milVersion)){
								$('commentLinkMilRHS'+milVersion).show();
							}
						}
						//for upcoming
						if(milStatus == '2'){
							$('RHSMilSpan'+lastMilId).className = '';
							//$('RHSMil'+lastMilId).className = $('RHSMil'+lastMilId).className.replace('completed',' ');
							$('RHSMilChk'+lastMilId).checked = false;
							$('RHSMilChk'+lastMilId).onclick = new Function(onClkEvent);
							$('RHSMil'+lastMilId).id = 'RHSMil'+lastMilInsertId;
							$('RHSMilChk'+lastMilId).id = 'RHSMilChk'+lastMilInsertId;
							$('RHSMilSpan'+lastMilId).id = 'RHSMilSpan'+lastMilInsertId;
							if($('commentLinkMilRHS'+ milVersion)){
								$('commentLinkMilRHS'+milVersion).show();
							}
						}
						
						//for completed
						if(milStatus == '3'){
							//$('RHSMil'+lastMilId).className = $('RHSMil'+lastMilId).className.replace('tName','tName completed');
							$('RHSMilSpan'+lastMilId).className = 'completed';
							//$('RHSMil'+lastMilId).className = $('RHSMil'+lastMilId).className.replace('overdue','');
							$('RHSMilChk'+lastMilId).checked = true;
							$('RHSMilChk'+lastMilId).onclick = new Function(onClkEvent);
							$('RHSMil'+lastMilId).id = 'RHSMil'+lastMilInsertId;
							$('RHSMilChk'+lastMilId).id = 'RHSMilChk'+lastMilInsertId;
							$('RHSMilSpan'+lastMilId).id = 'RHSMilSpan'+lastMilInsertId;
							if($('commentLinkMilRHS'+ milVersion) && $('commentLinkMilRHS'+ milVersion).innerHTML == "[Comment]"){
								$('commentLinkMilRHS'+milVersion).hide();
							}
						}
					}
					
					/*sending mails on status change of milestone*/
					sendMail('Milestone',group_id,'changeStatus',milVersion);
				}
				
				if($('currPageCat').value == "ovr")
				{
					if(this.data.milestones.id && (this.data.milestones.closed == "1" || this.data.milestones.reopened == "1"))
					{
						var milver = this.data.milestones.id;
						var objMilHistory = $('milHist'+milver);
						var strMilHist = "div#milHist"+milver+" span"; 	
						var strMlDivTitle = 'div#mil'+milver+' div.postTitleBy';
						var strMlDivAsg = 'div#mil'+milver+' div.postTitleCont';
						
						var elemTitle = $$(strMlDivTitle);
						var elemsAsg = $$(strMlDivAsg);
						if(this.data.milestones.closed == "1")
						{
							if(elemTitle['0'])
								elemTitle['0'].className = elemTitle['0'].className+" completed";
							if(elemsAsg['0'])	
								elemsAsg['0'].className = elemsAsg['0'].className+" completed";
								
							if(objMilHistory)
							{
								objMilHistory.show();
								elemHist = $$(strMilHist);
								
								if(elemHist['0'])
									elemHist['0'].className = elemHist['0'].className.replace("completed","");
							}	
						}
						else
						{
							if(elemTitle['0'])
								elemTitle['0'].className = elemTitle['0'].className.replace("completed","");
							if(elemsAsg['0'])
								elemsAsg['0'].className = elemsAsg['0'].className.replace("completed","");
								
							if(objMilHistory)
							{
								objMilHistory.show();
								elemHist = $$(strMilHist);
								
								if(elemHist['0'])
									elemHist['0'].className = elemHist['0'].className+" completed";
									
							}	
						}
					}
					/*
					var html = "";
					var cmpdClass = "";
					var meClass = "";
					
					//for writing milestone on dashboard
					if(this.data.milestones.id && (this.data.milestones.closed == "1" || this.data.milestones.reopened == "1"))
					{
						cmpdClass="";
						meClass = "";
						
						strCmpAss = "assigned to";
						strCmpAssUsername = this.data.milestones.assigned;
						if(this.data.milestones.closed == "1")
						{
							cmpdClass="completed";
							strCmpAss = "completed by";
							strCmpAssUsername = "Me";
						}	
							
						if(this.data.milestones.assigned.strip().toLowerCase() == "me")
							meClass = "me";	
											
						html += '<div class="postGroupTop">'+
			              				'<div class="read '+meClass+'">'+
			                				'<div class="postCategory oneLineLimitSmall"><span title="Milestone">Milestone</span></div>'+
			                				'<div class="postData '+cmpdClass+'">'+
			                  					'<div class="postTitleBy">';
	      					
			                if(this.data.milestones.reopened == "1")
			                	html += '<span class="act">reopened</span>';  					
	      					
			                html  +=					strCmpAss+' <span>'+strCmpAssUsername+'</span></div>'+
			                  					'<div class="postTitleCont">'+this.data.milestones.title+'</div>'+
			                				'</div>'+
			              				'</div>'+
			            			'</div>';
				
			            	html += '<input type="hidden" id="hdnMl'+this.data.milestones.id+'" value="1">';
										
					}
					
					//for writing tasks on dashboard
					this.data.tasksData.each(
						function(item) {
							if(item['0'].tdname.strip().toLowerCase() == "me")
								meClass = "me";
							
							strCmpAss = "assigned to";
							strCmpAssUsername = item['0'].tdname;	
							if(item['todos'].status_id == "2")
							{
								cmpdClass = " completed";
								strCmpAss = "completed by";
								strCmpAssUsername = "Me";
							}								
							html += '<div class="postGroup">'+
	                      				'<div class="read '+meClass+' cmpTD">'+
	                        				'<div class="postCategory oneLineLimitSmall"><span title="Task">Task</span></div>'+
	                        				'<div class="postData'+cmpdClass+'">'+
	                          					'<div class="postTitleBy">'+strCmpAss+' <span>'+strCmpAssUsername+'</span></div>'+
	                          					'<div class="postTitleCont">'+item['todos'].title+' - '+item['todolists'].title+'</div>'+
	                        				'</div>'+
	                      				'</div>'+
	                    			'</div>';
	                    	html += '<input type="hidden" id="hdnTd'+item['todos'].id+'" value="1">';		
						});	
						
					var latestDate = Element.immediateDescendants($('overviewBody'))['0'].immediateDescendants()['0'].innerHTML;
					var obj    
					if(latestDate.strip().toLowerCase() == "today"){
		            	Element.immediateDescendants($('overviewBody'))['1'].immediateDescendants()['0'].className = Element.immediateDescendants($('overviewBody'))['1'].immediateDescendants()['0'].className.replace("postGroupTop","postGroup");
		            	//Element.immediateDescendants($('overviewBody'))['0'].remove();
		            	obj = Element.immediateDescendants($('overviewBody'))['1'];            	
		            }
		            else
		            {
		            	html = '<div class="threadDate"><span class="threadDateDisplay">Today</span></div>'+'<div class="threadGroup">'+html+'</div>';
		            	obj = $('overviewBody');
		            }
		
		        	obj.insert ({
		 				'top'  : html
		  			} );
					
					*/
				}
	        }	
		  }
		}
	});
	function sendTodoMail(group_id,todolistId,type)
	{
		var rand   = Math.random(99999); 
		var url    = '/groups/tasks/sendMail4ChangeTodoStatus.json';						
		var pars   = 'rand=' + rand + '&tdID=' + todolistId + '&groupid=' + group_id + '&type='+type;
		var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars} );	
	}
	function setNoneSelected() { 
                var old = $('ff');
                old.className = '';
                old.id = 'tt';

                var news = $("tt");
                news.id = 'ff';
                news.className = 'selOption';
	}
	
	function stopPropagation(eventHandle) {
		if (!eventHandle) var eventHandle = window.event;
	        if (eventHandle) eventHandle.cancelBubble = true;
        	if (eventHandle.stopPropagation) eventHandle.stopPropagation();
	}
	function showDropDownTask(eventHandle) {

		stopPropagation(eventHandle);

		if($('todo_dropdown').style.display == 'none') {
			var pos = findPosTask('','todo_text');
			var top = Number(pos[1] + 4) + 'px';
			$('todo_dropdown').show();
			$('todo_dropdown').setStyle({
				top : top
			});
			var style = ''; 
			var h = getDim('todo_dropdown_content'); 
			if(h > 300) {
				$('todo_dropdown_content').style.overflow = 'auto';
				$('todo_dropdown_content').style.height = '300px';
			} else {
				$('todo_dropdown_content').style.overflow = '';
				$('todo_dropdown_content').style.height = '';
			}
		} else
			$('todo_dropdown').hide();
	}
	
	function getDim (id) {  
		$(id).style.height="auto";
		if (document.all) gh = $(id).offsetHeight+10;
		else gh = $(id).offsetHeight;
		return gh;
	}
	function toggleClassTask(objHandle, state) {
		if(state) { 
			try { 
				//$('ff').className = ''; 
				//$('ff').id = 'tt'; 
			} catch(e) {}
			objHandle.className = 'selOption';
		} else {
			if(objHandle.id != 'ff') 
				objHandle.className = '';
		}
	}
	function changeSelOption(objHandle) {
		var old = $('ff');
		if(old.childNodes[0].innerHTML != objHandle.innerHTML) {
	                old.className = '';
        	        old.id = 'tt';
		}
                objHandle.parentNode.id = 'ff';
	}
	function changeTodoinc(title, ms, objHandle, eventHandle) {

		stopPropagation(eventHandle);

		if(ms != '0') {
			var todoinc = $('todoinc_'+ms).value;
			if(todoinc == 0) {
				todoinc++;
				$('todolist_title').value = title;
				$('selected_ms_name').value = title;
			} else {
				todoinc++;
				$('todolist_title').value = stripslashes(title) +"["+ todoinc +"]";	
				$('selected_ms_name').value = stripslashes(title) +"["+ todoinc +"]";
			}

			changeSelOption(objHandle);

			$('milestone_id').value = ms;
			$('ms').value = ms;
			$('todo_text').value = stripslashes(title);
		} else {
			changeSelOption(objHandle);
			$('milestone_id').value = 'null';
			$('ms').value = '';
			$('selected_ms_name').value = '';
			$('todolist_title').value = '';			
			$('todo_text').value = stripslashes(title);
		}
		$('todo_dropdown').hide();
	}
	
	function addslashes(str) {
		str=str.replace(/\'/g,'\\\'');
		str=str.replace(/\"/g,'\\"');
		str=str.replace(/\\/g,'\\\\');
		str=str.replace(/\0/g,'\\0');
		return str;
	}

	function stripslashes(str) {
		str=str.replace(/\\'/g,'\'');
		str=str.replace(/\\"/g,'"');
		str=str.replace(/\\\\/g,'\\');
		str=str.replace(/\\0/g,'\0');
		return str;
	}
	
	function formValidation(event) {
	if(document.getElementById('subHeadTodo')){
		var temp = document.getElementById('todolist_title').value;
		var errOccured;
		
		if (temp.strip() == '')
		{
			//document.getElementById('addlist_result').innerHTML = '&nbsp;';
			//document.getElementById('addlist_result').style.display = "block"; 
			//document.getElementById('addlist_result').innerHTML = '<div class="err">Please name your task list</div>';
			$('errTasklistTitle').show();
			$('todolist_title').addClassName('inpErr');
			//return false;
			errOccured = 1;
		}else{
			$('errTasklistTitle').hide();
			$('todolist_title').removeClassName('inpErr');
		}
		
		if(errOccured == 1) return false;
		
		showDropDownTask(event);
		document.getElementById('addlist_result').innerHTML = '';
		document.getElementById('subHeadTodo').innerHTML = "Add new task to "+document.getElementById('todolist_title').value+"";
		if($('todolist_title').value.strip().toLowerCase() != $('selected_ms_name').value.strip().toLowerCase()) {
			$('todoinc').value = '';
		} else {
			var ms = $('ms').value;
			var todoinc = $('todoinc_'+ms).value;
			todoinc++;
			$('todoinc_'+ms).value = todoinc;
		}
		
		showhide(document.getElementById('new_todoList'));
	  	if($('new_todo'))showhide(document.getElementById('new_todo'));
	 }else{
	 	var temp = document.getElementById('todolist_title').value;
	 	var errOccured;
	 	
		if (temp.strip() == '')
		{
			//document.getElementById('addlist_result').innerHTML = '&nbsp;';
			//document.getElementById('addlist_result').style.display = "block"; 
			//document.getElementById('addlist_result').innerHTML = '<div class="err">Please name your task list</div>';
			$('errTasklistTitle').show();
			$('todolist_title').addClassName('inpErr');
			//return false;
			errOccured = 1;
			
		}else{
			$('errTasklistTitle').hide();
			$('todolist_title').removeClassName('inpErr');
		}
		
		
		if (document.getElementById('todo_title').value.strip() == '' && document.getElementById('Grp').style.display == 'none') {
			//document.getElementById('add_result').style.display = 'block';
			//document.getElementById('add_result').innerHTML = '<div class="err">Please name your Task</div>';
			$('errTaskTitle').show();
			$('todo_title').addClassName('inpErr');
			//return false;
			errOccured = 1;
		} else {
			$('errTaskTitle').hide();
			$('todo_title').removeClassName('inpErr');
			
		}
		if(($('calenderLastInnerMainDiv') && $('calenderLastInnerMainDiv').style.display != 'none')  || $('currPageCat').value == "mlt")
		var tmp = document.getElementById('addSelect').options[document.getElementById('addSelect').selectedIndex].value
		else
		var tmp = document.getElementById('responsible').options[document.getElementById('responsible').selectedIndex].value
		
		if(tmp == '0')
		{
			//document.getElementById('add_result').style.display = 'block';		
			//document.getElementById('add_result').innerHTML = '<div class="err">Please select who\'s responsible for the task</div>';
			$('errTaskUser').show();
			$('addSelect').addClassName('inpErr');
			//return false;
			errOccured = 1;
		} else {
			$('errTaskUser').hide();
			$('addSelect').removeClassName('inpErr');
		}
		
		if(errOccured == 1) return false;
		
		document.getElementById('addlist_result').innerHTML = '';
		//document.getElementById('subHeadTodo').innerHTML = "Add new task to "+document.getElementById('todolist_title').value+"";
		if($('todolist_title').value.strip().toLowerCase() != $('selected_ms_name').value.strip().toLowerCase()) {
			$('todoinc').value = '';
		} else {
			var ms = $('ms').value;
			var todoinc = $('todoinc_'+ms).value;
			todoinc++;
			$('todoinc_'+ms).value = todoinc;
			//$('todoinc').value = todoinc;
		}
		return true;
	 } 	
	  	
	}
	
	function saveTodo(groupID) {
		if (!todoSubmitted) {
			if(saveTodoLists()){ 
				todoSubmitted = true;
				document.getElementById('loading_img').style.display = "block"; 
				if (micoxUpload('frmTodo','/groups/tasks/addTodoList/'+groupID,'add_result','','Error') == false) { 
					todoSubmitted = false; 
				} else {
					if(document.getElementById('noTolist')) document.getElementById('noTolist').innerHTML = ''; 
					setNoneSelected();
				}
			}
		} 
		return false;
	}
	
	function addMoreTodo(loggedinUser) {
		document.getElementById('add_result').innerHTML = '';
		var errOccured;

		if (document.getElementById('todo_title').value.strip() == '') {
			//document.getElementById('add_result').style.display = 'block';
			//document.getElementById('add_result').innerHTML = '<div class="err">Please name your Task</div>';
			$('errTaskTitle').show()
			$('todo_title').addClassName('inpErr');
			//return false;
			errOccured = 1;
		} else {
			$('errTaskTitle').hide()
			$('todo_title').removeClassName('inpErr');
		}
		
		if(($('calenderLastInnerMainDiv') && $('calenderLastInnerMainDiv').style.display != 'none') || $('currPageCat').value == "mlt"){
			var tmp = document.getElementById('addSelect').options[document.getElementById('addSelect').selectedIndex].value;
		}else{
			var tmp = document.getElementById('responsible').options[document.getElementById('responsible').selectedIndex].value;
		}
		
		if(tmp == '0')
		{
			//document.getElementById('add_result').style.display = 'block';		
			//document.getElementById('add_result').innerHTML = '<div class="err">Please select who\'s responsible for the task</div>';
			$('errTaskUser').show();
			if($('responsible')) $('responsible').addClassName('inpErr');
			if($('addSelect')) $('addSelect').addClassName('inpErr');
			//return false;
			errOccured = 1;
		} else {
			$('errTaskUser').hide();
			if($('responsible')) $('responsible').removeClassName('inpErr');
			if($('addSelect')) $('addSelect').removeClassName('inpErr');
		}
		
		if(errOccured) return false;
		
		document.getElementById('add_result').style.display = 'none';			
		document.getElementById('Grp').style.display = 'block';
		document.getElementById('newList').style.display = 'block';
		var cnt;
		if(document.getElementById('test').value != ""){		
		cnt = 0;
		cnt = parseInt(document.getElementById('test').value)+parseInt(1);
		}else{		
		cnt = 1;
		}
		
		if(($('calenderLastInnerMainDiv') && $('calenderLastInnerMainDiv').style.display != 'none')  || $('currPageCat').value == "mlt"){
		//$('calenderLastInnerMainDiv') && $('calenderLastInnerMainDiv').style.display != 'none'){
			document.getElementById('test').value = cnt;									
			var ni = document.getElementById('addtodo');
			//var newdiv = document.createElement('div');
			//var divIdName = 'my'+1;
			//newdiv.setAttribute('id',divIdName);
			///newdiv.className = "addedtoDo";
			
			var highlight = 'highlight'+cnt;
			if(cnt== '1'){
			var classString = '<div><div id="wrapper"><div id="'+highlight+'" style="display:none;"><div class="field" >'
			}
			else
			{
			var classString = '<div><div id="wrapper"><div id="'+highlight+'"  style="display:none;"><div class="field borderDashedBottom">'
			}
			
			var userDisplayStr = '';
			if(loggedinUser == document.getElementById('addSelect').options[document.getElementById('addSelect').selectedIndex].value)
			 userDisplayStr = 'Me';
			else
			 userDisplayStr =  document.getElementById('addSelect').options[document.getElementById('addSelect').selectedIndex].text;
			 var newdiv = '';
			newdiv = classString+'<input type="hidden" name="data[todos][title][]" value="'+ document.getElementById('todo_title').value +'" /><input type="hidden" name="data[todos][responsible_user_id][]" value='+ document.getElementById('addSelect').value +' /><div class="fieldLabel" style="width:64%;padding-right:0px">'+ document.getElementById('todo_title').value +'</div><div class="rightFloat smallFont rightAlign1" style="width:35%"> assigned to '+ userDisplayStr+'</div><div style="clear:left"></div></div></div></div>';
						
						
			document.getElementById('todo_title').value = "";
			ni.innerHTML = newdiv + ni.innerHTML;
			//ni.appendChild(newdiv);
			//Effect.ScrollTo(highlight,{duration: 1});
			//new Effect.Highlight(highlight, { startcolor: startColor,endcolor: endColor, duration:2});
			Effect.SlideDown(highlight, {duration:1});
			//setTimeout("$('innerContentsTskList').scrollTop = $('innerContentsTskList').scrollHeight;",1000);
		}else if($('responsible')){
			document.getElementById('test').value = cnt;									
			var ni = document.getElementById('addtodo');
			var newdiv = document.createElement('div');
			//var divIdName = 'my'+1;
			//newdiv.setAttribute('id',divIdName);
			///newdiv.className = "addedtoDo";
			
			var highlight = 'highlight'+cnt;
			if(cnt== '1'){
				var classString = '<div id="wrapper"><div id="'+highlight+'" style="display:none;"><div class="field" >'
			}
			else
			{
				var classString = '<div id="wrapper"><div id="'+highlight+'"  style="display:none;"><div class="field borderDashedBottom">'
			}
			
			var userDisplayStr = '';
			if(loggedinUser == document.getElementById('responsible').options[document.getElementById('responsible').selectedIndex].value)
			 userDisplayStr = 'Me';
			else
			 userDisplayStr =  document.getElementById('responsible').options[document.getElementById('responsible').selectedIndex].text;
			var newdiv = ''; 
			newdiv = classString+'<input type="hidden" name="data[todos][title][]" value="'+ document.getElementById('todo_title').value +'" /><input type="hidden" name="data[todos][responsible_user_id][]" value='+ document.getElementById('responsible').value +' /><div class="fieldLabel" style="width:64%;padding-right:0px">'+ document.getElementById('todo_title').value +'</div><div class="rightFloat smallFont rightAlign" style="width:35%"> assigned to '+ userDisplayStr+'</div><div style="clear:left"></div></div></div>';
						
			ni.innerHTML = newdiv + ni.innerHTML;			
			document.getElementById('todo_title').value = "";
			//ni.appendChild(newdiv);
			//Effect.ScrollTo(highlight,{duration: 1});
			//new Effect.Highlight(highlight, { startcolor: startColor,endcolor: endColor, duration:2});
			Effect.SlideDown(highlight, {duration:1});
		}	
	
	}
	
	function decTodoinc() {
		if($('new_todoList').style.display == 'none') {
			var ms = $('ms').value;
			if(ms != '') {
				var todoinc = $('todoinc_'+ms).value;
				todoinc--;
				$('todoinc_'+ms).value = todoinc;
			}
		}
		try {
			setNoneSelected();
	    } catch(e) {}
	}	
	/************	ENDS		***************/			
	
	function stopPropagation(eventHandle) {
		if (!eventHandle) var eventHandle = window.event;
		if (eventHandle) eventHandle.cancelBubble = true;
		if (eventHandle.stopPropagation) eventHandle.stopPropagation();
	}
	
	/* drop down function list */
	
	function divPresentFunctionAll(id,divPresent){
		if(document.getElementById(divPresent).value == "")
			document.getElementById(divPresent).value = id;
		else
			document.getElementById(divPresent).value = document.getElementById(divPresent).value +","+id;	
	}
	
	function divPresentFunction(id,divPresent){		
		if(document.getElementById(divPresent).value == "")
			document.getElementById(divPresent).value = id;
		else
			document.getElementById(divPresent).value = document.getElementById(divPresent).value +","+id;	
	}
	function classChange(divId){

		if(document.getElementById(divId).className == 'customizeDropDown'){
			document.getElementById(divId).className = 'customizeDropDownHover';
		}else{
			document.getElementById(divId).className = 'customizeDropDown';
		}
	}
	
	function toggleClass(divId,enbDis){
	 if(enbDis == "1"){
		if(document.getElementById(divId).className == 'selOption'){
		//document.getElementById(divId).className = '';
		}else{
		document.getElementById(divId).className = 'selOption';
		}
	 }else{
	 	if(document.getElementById(divId).className == 'selOptionDisable'){
		document.getElementById(divId).className = '';
		}else{
		document.getElementById(divId).className = 'selOptionDisable';
		}
	 }	
	}

	function toggleClass2(divId,enbDis){
	 if(enbDis == "1"){
		if(document.getElementById(divId).className == 'selOption'){
		//document.getElementById(divId).className = '';
		}else{
		document.getElementById(divId).className = 'selOption';
		}
	 }else{
	 	if(document.getElementById(divId).className == 'selOptionDisable'){
		document.getElementById(divId).className = '';
		}else{
		document.getElementById(divId).className = 'selOptionDisable';
		}
	 }	
	}

	function toggleClass1(divId,enbDis,para){
	 if(enbDis == "1"){
		if(document.getElementById(divId).className == 'selOption'  && 'div'+document.getElementById('selectedIDs'+para).value+para+'ID' != divId	){
		document.getElementById(divId).className = '';
		}else{
		//document.getElementById(divId).className = 'selOption';
		}
	 }else{
	 	if(document.getElementById(divId).className == 'selOptionDisable'){
		document.getElementById(divId).className = '';
		}else{
		document.getElementById(divId).className = 'selOptionDisable';
		}
	 }	
	}

	function showDropDown(mainDDdiv,noofDivsForHeight,calHeight,innerDiv,inp,par,selId,allDivs,positionDiv){	
		var loc= findPosCreateGr(inp,positionDiv);
		var lt = loc[0];
		var tp = loc[1];
	
		if(BrowserDetect.browser && BrowserDetect.browser.toLowerCase()=="explorer" && BrowserDetect.version < 8)
		{
			lt=lt+11;
			tp=tp+91;
		}
		else
		{
			tp=tp-4;
		}
		//alert(lt+'=='+tp);
		
		if(BrowserDetect){}
		document.getElementById(mainDDdiv).style.left = lt+'px';
		document.getElementById(mainDDdiv).style.top = tp+'px';
		
		if(document.getElementById(mainDDdiv).style.display == 'none')
		document.getElementById(mainDDdiv).style.display = 'block';
		else
		document.getElementById(mainDDdiv).style.display = 'none';
		
		
		var divs = document.getElementById(noofDivsForHeight).value;
		divs = divs.split(',');	
		
		for(var i=0;i<divs.length;i++){	
		var hgt = document.getElementById(divs[i]).offsetHeight;
		document.getElementById(divs[i]).className = '';
		document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value) + parseInt(hgt);
		}
		
		document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value)+ parseInt(20);
		
		var divsAll = document.getElementById(allDivs).value;
		divsAll = divsAll.split(',');	
		
		for(var i=0;i<divsAll.length;i++){	
	
		document.getElementById(divsAll[i]).className = '';
		
		}
		
		document.getElementById(innerDiv).style.height = document.getElementById(calHeight).value+'px';	
		
		var defaultSel = 'div'+document.getElementById(selId).value+par+'ID';
		document.getElementById(defaultSel).className = 'selOption';
		document.getElementById(innerDiv).scrollTop = document.getElementById(defaultSel).offsetTop - 50;
		document.getElementById(calHeight).value = "0";
		
	}

	function showDropDownTalk(mainDDdiv,noofDivsForHeight,calHeight,innerDiv,inp,par,selId,allDivs,positionDiv){
		if(document.getElementById(mainDDdiv).style.display == 'none'){
			document.getElementById(mainDDdiv).style.display = 'block';
			var loc= findPositionTalk(positionDiv);
			var lt = loc[0];
			var tp = loc[1];
			if(BrowserDetect.browser == "Explorer"){
				if(BrowserDetect.version == "7"){
					if(par == "shoutBox"){
						var loc1= findPos1('leftSpace');
						var lt1 = loc1[0];
						var marginTop = tp + 241;
						if($('sc4') && $('sc4').style.display != 'none')
							marginTop = marginTop + $('sc4').offsetHeight + 20;
						if($('sc2') && $('sc2').style.display != 'none')
							marginTop = marginTop + $('sc2').offsetHeight + 20;	
							
						document.getElementById(mainDDdiv).style.left = lt + lt1 + 116 +'px';	
						document.getElementById(mainDDdiv).style.top = marginTop +'px';			
					}
					else if (par == "inviteTab"){
						document.getElementById(mainDDdiv).style.left = lt -82 + 'px';	
						document.getElementById(mainDDdiv).style.top = tp + 52 +'px';
					}
					else if (par == "cm"){
						document.getElementById(mainDDdiv).style.left = lt -85 + 'px';	
						document.getElementById(mainDDdiv).style.top = tp + 150 + 'px';
					}
				}
			}
			
			//alert(loc[0]); alert(loc[1]);
			//document.getElementById(mainDDdiv).style.left = lt-10+'px';
			//document.getElementById(mainDDdiv).style.top = tp-10+'px';
	 
		}else{
			document.getElementById(mainDDdiv).style.display = 'none';
		}
				
		var divs = document.getElementById(noofDivsForHeight).value;
		divs = divs.split(',');				
		for(var i=0;i<divs.length;i++){	
			var hgt = document.getElementById(divs[i]).offsetHeight;
			document.getElementById(divs[i]).className = '';
			document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value) + parseInt(hgt);
		}
		
		document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value)+ parseInt(20);
		
		var divsAll = document.getElementById(allDivs).value;
		divsAll = divsAll.split(',');			
		for(var i=0;i<divsAll.length;i++){		
			document.getElementById(divsAll[i]).className = '';		
		}
		
		document.getElementById(innerDiv).style.height = document.getElementById(calHeight).value+'px';	
		
		var defaultSel = 'div'+document.getElementById(selId).value+par+'ID';
		
		document.getElementById(defaultSel).className = 'selOption';
		document.getElementById(innerDiv).scrollTop = document.getElementById(defaultSel).offsetTop - 50;
		document.getElementById(calHeight).value = "0";
		
	}

	function showDropDowntodo(mainDDdiv,noofDivsForHeight,calHeight,innerDiv,inp,par,selId,allDivs,positionDiv){
		var loc= findPos(inp,positionDiv);
		var loc1= findPos1('new_todoList_popup');
		
		var lt = loc[0];
		var tp = loc[1];
		
		if(BrowserDetect.browser == "Explorer" && BrowserDetect.version == 8){
			document.getElementById(mainDDdiv).style.left = lt+loc1[0]-30 +'px';
			document.getElementById(mainDDdiv).style.top = tp+loc1[1]-120 +'px';
		}
		else {
			document.getElementById(mainDDdiv).style.left = lt+loc1[0]+'px';
			document.getElementById(mainDDdiv).style.top = tp+loc1[1]+'px';
		}
		
		if(document.getElementById(mainDDdiv).style.display == 'none')
			document.getElementById(mainDDdiv).style.display = 'block';
		else
			document.getElementById(mainDDdiv).style.display = 'none';
		
		var divs = document.getElementById(noofDivsForHeight).value;
		divs = divs.split(',');	
			
		document.getElementById(calHeight).value = 0;//added by subhendu
		for(var i=0;i<divs.length;i++){	
			var hgt = document.getElementById(divs[i]).offsetHeight;	
			document.getElementById(divs[i]).className = '';
			document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value) + parseInt(hgt);
		}
		
		document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value)+ parseInt(20);
		
		var divsAll = document.getElementById(allDivs).value;
		divsAll = divsAll.split(',');	
		
		for(var i=0;i<divsAll.length;i++){	
			//if(i<1)
				//document.getElementById(divsAll[i]).className = 'selOption';
			//else
				document.getElementById(divsAll[i]).className = '';		
		}
		
		var defaultSel = 'div'+document.getElementById(selId).value+par+'ID';
		document.getElementById(defaultSel).className = 'selOption';
		document.getElementById(innerDiv).style.height = document.getElementById(calHeight).value+'px';	
		document.getElementById(innerDiv).scrollTop = document.getElementById(defaultSel).offsetTop - 50;
		document.getElementById(calHeight).value = "0";
		
	}
	
	function showDropDown1(mainDDdiv,noofDivsForHeight,calHeight,innerDiv,inp,par,selId,allDivs,positionDiv){

		var loc= findPosLevel(inp,positionDiv);
		
		
		var lt = loc[0];
		var tp = loc[1];
		
		document.getElementById(mainDDdiv).style.left = lt+'px';
		
		document.getElementById(mainDDdiv).style.top = tp+'px';
		if(document.getElementById(mainDDdiv).style.display == 'none')
			document.getElementById(mainDDdiv).style.display = 'block';
		else
			document.getElementById(mainDDdiv).style.display = 'none';

		var divs = document.getElementById(noofDivsForHeight).value;
		divs = divs.split(',');	
		
		for(var i=0;i<divs.length;i++){	
			var hgt = document.getElementById(divs[i]).offsetHeight;
			document.getElementById(divs[i]).className = '';
			document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value) + parseInt(hgt);
		}
		document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value)+ parseInt(20);
		
		var divsAll = document.getElementById(allDivs).value;
		divsAll = divsAll.split(',');	
		
		for(var i=0;i<divsAll.length;i++){		
			document.getElementById(divsAll[i]).className = '';		
		}
	 
	 	document.getElementById(innerDiv).style.height = document.getElementById(calHeight).value+'px';	
		
		var defaultSel = 'div'+document.getElementById(selId).value+par+'ID';
		document.getElementById(defaultSel).className = 'selOption';
		document.getElementById(innerDiv).scrollTop = document.getElementById(defaultSel).offsetTop - 50;
		document.getElementById(calHeight).value = "0";
	
	}

	function showDropDownMil(mainDDdiv,noofDivsForHeight,calHeight,innerDiv,inp,par,selId,allDivs,positionDiv){

		var loc= findPosLevel(inp,positionDiv);
		var loc1= findPos1('add_milestone');
		
		var lt = loc[0];
		var tp = loc[1];
	
		if(BrowserDetect.browser == "Explorer"){
			if(BrowserDetect.version == 8){
				document.getElementById(mainDDdiv).style.left = lt+loc1[0]+24+'px';	
				document.getElementById(mainDDdiv).style.top = tp+loc1[1]+'px';
			}
			else {
				document.getElementById(mainDDdiv).style.left = lt+loc1[0]+30+24+'px';	
				document.getElementById(mainDDdiv).style.top = tp+loc1[1]+30+69+'px';
			}
		}
		else{
			document.getElementById(mainDDdiv).style.left = lt+loc1[0]+24+'px';	
			document.getElementById(mainDDdiv).style.top = tp+loc1[1]+'px';
		}
		
		if(document.getElementById(mainDDdiv).style.display == 'none')
			document.getElementById(mainDDdiv).style.display = 'block';
		else
			document.getElementById(mainDDdiv).style.display = 'none';
		
		
		
		var divs = document.getElementById(noofDivsForHeight).value;
		divs = divs.split(',');			
		for(var i=0;i<divs.length;i++){	
			var hgt = document.getElementById(divs[i]).offsetHeight;
			document.getElementById(divs[i]).className = '';
			document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value) + parseInt(hgt);
		}
		document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value)+ parseInt(20);
		
		var divsAll = document.getElementById(allDivs).value;
		divsAll = divsAll.split(',');			
		for(var i=0;i<divsAll.length;i++){		
			document.getElementById(divsAll[i]).className = '';		
		}
	 
	 	document.getElementById(innerDiv).style.height = document.getElementById(calHeight).value+'px';	
		
		var defaultSel = 'div'+document.getElementById(selId).value+par+'ID';
		document.getElementById(defaultSel).className = 'selOption';
		document.getElementById(innerDiv).scrollTop = document.getElementById(defaultSel).offsetTop - 50;
		document.getElementById(calHeight).value = "0";
	
	}

	function getElementsByNameFun(chkName,selIds,selVals) {
		var chkArr = chkName+'[]';
		var chks =  document.getElementsByName(chkArr);
		var matches = [];
		var matches1 = [];
		for (var i = 0; i < chks.length; i++)
	        {
	                if (chks[i].checked){    
	                	matches.push(chks[i].id);
	                	matches1.push(chks[i].value);						
	                }
	        }
	    document.getElementById(selIds).value = matches;
		document.getElementById(selVals).value = matches1;   
	}

	function finalValue(id,name,chkName,selIds,selVals,mainDiv,par){
			if(document.getElementById('msgGroupId'))
			document.getElementById('msgGroupId').value = id;
			
			
			if(document.getElementById('createCategory'))
			document.getElementById('createCategory').style.display = 'none';
			
			
			var tmpStr = 'enc'+id+''+par;
			if(document.getElementById(tmpStr))
			document.getElementById('actualGroupEncID1').value = document.getElementById(tmpStr).value;
			
			ldDispMsg.getDropdown(id);
			document.getElementById(selIds).value = id;
			document.getElementById(selVals).value = name;
			document.getElementById(mainDiv).style.display = 'none';					
	}

	function finalValueTodo(id,name,chkName,selIds,selVals,mainDiv,par){
			if(document.getElementById('msgGroupId'))
			document.getElementById('msgGroupId').value = id;
			
			
			if(document.getElementById('createCategory'))
			document.getElementById('createCategory').style.display = 'none';
			
			
			var tmpStr = 'enc'+id+''+par;
			if(document.getElementById(tmpStr))
			document.getElementById('actualGroupEncID1').value = document.getElementById(tmpStr).value;
			
			var editMSOver = new setUserDataForTodo;
			editMSOver.getDropdown(id);
			
			document.getElementById(selIds).value = id;
			document.getElementById(selVals).value = name;
			document.getElementById(mainDiv).style.display = 'none';		
	}
	
	function RefreshCalenderForLevel(id,name,chkName,selIds,selVals,mainDiv,par){
			calenderRefresher.getAllMilestonesData(id,'reffreshingForLevel','');
			document.getElementById(selIds).value = id;
			document.getElementById(selVals).value = name;
			document.getElementById(mainDiv).style.display = 'none';
			if($('prj_id'))
			$('prj_id').value=id;	
	}

	function openCalendar(id,name,chkName,selIds,selVals,mainDiv,par){				
		calenderRefresher.getAllMilestonesDataNew("overviewCalendar",id,'reffreshingForLevel','');			
		document.getElementById(selIds).value = id;
		document.getElementById(selVals).value = name;
		document.getElementById(mainDiv).style.display = 'none';
		if($('prj_id'))
		$('prj_id').value=id;			
	}	
	function FilterCalenderForLevel(id,name,chkName,selIds,selVals,mainDiv,par){
			document.getElementById(selIds).value =  id;
			document.getElementById(selVals).value =  name;
			document.getElementById(mainDiv).style.display = 'none';
			if($('prj_id'))
			$('prj_id').value=id;
			arrUnRead = $$('div.filterMil');		
			var elemCount = arrUnRead.length;
					
			if(id == 'overviewCalendar'){
				for(i=0;i<elemCount;i++){
					arrUnRead[i].style.display = 'block'; 
				}	
			}else{
				for(i=0;i<elemCount;i++){
				 var tempStr = arrUnRead[i].id;
				 var needle = 'milestoneGroupIdDiv_'+id+'_';
					if(tempStr.search(needle) > -1){
						arrUnRead[i].style.display = 'block'; 
					}else{
						arrUnRead[i].style.display = 'none'; 
					}					 
				}				
			}
			
			
			arrUnRead = $$('div.filterBirthdays');		
				var elemCount = arrUnRead.length;
				
				if(id == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == id){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}
			
			arrUnRead = $$('div.filterAnnis');		
				var elemCount = arrUnRead.length;
				
				if(id == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == id){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}
				
				
		showHideBorder();								
			
	}
	

	/*	function to show border when upcoming milestone is present or else dont show the border	*/	
	function showHideBorder() {
		var cn1 = 'upcomingBorder';
		var cn2 = 'delayCompleteBorder';
		var divs = $$('div.dateDesc');
		for(var i=0; i<divs.length; i++) {
			//var childs = divs[i].select('div.upcoming'); //NOT WORKING IN CHROME AND IE
			var childs = getElementsByClassName1('upcoming', divs[i]);
			var childsCount = 0; 
			if(childs.length > 0) {
				for(var z=0; z<childs.length; z++) {
					if(childs[z].parentNode.style.display != 'none' && childs[z].style.display != 'none') {
						childsCount++;
					}
				}
				if(divs[i].parentNode.className != 'todayBorder') {
					if(childsCount > 0) {
						divs[i].parentNode.className = divs[i].parentNode.className.replace(cn2, cn1);
					} else {
						divs[i].parentNode.className = divs[i].parentNode.className.replace(cn1, cn2);
					}
				}
			}
		}
	}
	
	
	function finalValueMilSt(id,name,chkName,selIds,selVals,mainDiv,par){
			if(document.getElementById('msgGroupId'))
			document.getElementById('msgGroupId').value = id;
			
			
			var tmpStr = 'enc'+id+''+par;
			if(document.getElementById(tmpStr))
			document.getElementById('actualGroupEncID1').value = document.getElementById(tmpStr).value;
			var editMSOver=new setUserData;
			editMSOver.getDropdown(id);
			document.getElementById(selIds).value = id;
			document.getElementById(selVals).value = name;
			document.getElementById(mainDiv).style.display = 'none';		
	}

	function findPos1(obj) {
		var curleft = curtop=curwidth = curheight=0;
		if(typeof obj != "object")
		var obj = document.getElementById(obj);
		if(document.all){
		    var obj1 = obj.getBoundingClientRect();
		   	curleft = parseInt(obj1.left);
			curtop = parseInt(obj1.top + document.documentElement.scrollTop);			
		} else {
			curleft = parseInt(obj.offsetLeft);
			curtop = parseInt(obj.offsetTop);		
		}
		curwidth=parseInt(obj.offsetWidth);
		curheight=parseInt(obj.offsetHeight);
		return [curleft,curtop,curwidth,curheight];
	}

	function findPos(obj,positionDiv) {		
		var obj = positionDiv;	
		var curleft = curtop = 0;
		var obj = document.getElementById(obj);
	
		if(document.all){
			curleft = parseInt(obj.offsetLeft)+parseInt(14)+parseInt(30);
			curtop = parseInt(obj.offsetTop)+parseInt(37)+parseInt(106);
		}else{
			curleft = parseInt(obj.offsetLeft)+parseInt(14);
			curtop = parseInt(obj.offsetTop)+parseInt(37);
		}
		curheight = obj.offsetHeight;
		return [curleft,curtop,curheight];
	}

	function findPosLevel(obj,positionDiv) {		
		var obj = positionDiv;	
		var curleft = curtop = 0;
		var obj = document.getElementById(obj);
	
		if(document.all){
			curleft = parseInt(obj.offsetLeft)-10;
			curtop = parseInt(obj.offsetTop)+parseInt(40);
		}else{
			curleft = parseInt(obj.offsetLeft)-10;
			curtop = parseInt(obj.offsetTop)+parseInt(40);
		}
		curheight = obj.offsetHeight;
		return [curleft,curtop,curheight];
	}
	

	
	//Quick Tab Starts
	function trgQuickTab(groupId,level,grpName,QTId)
	{
		var obj;
		obj=$('quickTab'+QTId);
		if(QTId == '3'){
							
			if(level=="1"){var msgTxt = "Discussions";var empTxt = "Employees";}else{ var msgTxt = "Discussions";var empTxt = "Members";}
			var str = '';
			str += '<a onclick="$(\'navDDquickTab'+QTId+'\').style.display=\'none\';showGroupInfo(this,'+groupId+','+level+')" href="javascript:void(0);" id="quickTab'+QTId+'">'+grpName+'</a>';
			str += '<ul id="navDDquickTab3" class="tabsDropDown" style="display:none;">';
			str += '<li><a href="javascript:void(0);" onclick="$(\'navDDquickTab'+QTId+'\').style.display=\'none\';showGroupInfo(this,'+groupId+','+level+')">Overview</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'mess\','+groupId+',\'quickTab'+QTId+'\','+level+')">'+msgTxt+'</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'mil\','+groupId+',\'quickTab'+QTId+'\','+level+')">Milestones</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'tks\','+groupId+',\'quickTab'+QTId+'\','+level+')">Tasks</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'fls\','+groupId+',\'quickTab'+QTId+'\','+level+')">Files</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'emp\','+groupId+',\'quickTab'+QTId+'\','+level+')">'+empTxt+'</a></li>';
			str += '</ul>';
			$('liquickTab'+QTId).style.display = 'block';
			$('liquickTab'+QTId).innerHTML = str;
			$('liquickTab'+QTId).attributes["onmouseover"].value = 'shownavDD("quickTab3","1",'+groupId+');';
			$('liquickTab'+QTId).attributes["onmouseout"].value = 'shownavDD("quickTab3","0",'+groupId+');';
			obj=$('quickTab'+QTId);
			
			var rand   = Math.random(99999);
			var url    = '/groups/groups/add3rdTabfn.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&groupid=' + groupId + '&sessUID='+userID;
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess: function(transport){
				if(transport.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
				showGroupInfo(obj,groupId,level);
			}});
		}else{		
		showGroupInfo(obj,groupId,level);
		}
	}
	
	function shownavDD(id,para,grId){
    	if(para == '1'){
    		$('li'+id).className = 'onMouseOver';
    		$('navDD'+id).style.display = 'block';
    	}else{
    		
    		if($('currGrpId').value == grId){
    			 if($('generalTabDMT') || $('subdomain')){
    				$('li'+id).className = '';
    			 }else{
    			 	$('li'+id).className = 'sel';
    			 }	
    		}else{
    		$('li'+id).className = '';
    		}
    		
    		$('navDD'+id).style.display = 'none';    		
    	}
    	
    }
    
	function shownavProfile(para){
    	if(para == '1'){
    		$('profileLink').className = 'onMouseOver';
    		$('navProfile').style.display = 'block';
    	}else{
    		if($('currPageCat').value == "prf")
    		$('profileLink').className = 'sel';
    		else
    		$('profileLink').className = '';
    		    		    		    		
    		$('navProfile').style.display = 'none';    		
    	}
    	
    }
        
	function setSecondHeader(obj){
		talk_file_cnt = 0;
        var drodowns = new Array();
        drodowns[0] = 'homeNavigation';            
        drodowns[1] = 'messNavigation';
        drodowns[2] = 'milNavigation';
        drodowns[3] = 'tksNavigation';
        drodowns[4] = 'flsNavigation';
        drodowns[5] = 'empNavigation';
        drodowns[6] = 'EPNavigation';
        drodowns[7] = 'PPNavigation';
        drodowns[8] = 'MPNavigation';
        drodowns[9] = 'generalSettingsLink';
        var ddlen = drodowns.length;
        for (var j = 0; j < ddlen; j++){                        
            if($(drodowns[j]))$(drodowns[j]).className = '';
            if(j==1 || j== 5)
                $(drodowns[j]).className = 'ieNavigate';
        }        
        if ((obj == "messNavigation") || (obj == "empNavigation"))
        $(obj).className = 'sel ieNavigate ';
        else
        $(obj).className = 'sel ';                    
    }
	
	function navDDClick(prefixVar,grpId,str,level){
		$('navDD'+str).style.display = 'none';
		//$('currGrpId').value = grpId;
		//$('activityTab').show();
		if(level == "1"){$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Employees</a>';}else{$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Members</a>';}
		setHeader($(str),grpId,level);
		setVars(grpId,level);		
		setSecondHeader(prefixVar+'Navigation');
		//setTimeout("setSecondHeader('"+prefixVar+"Navigation');",1000);
		if(prefixVar == 'mess')messagesObject.callMessages();
		else if(prefixVar == 'mil')milestonesObject.callMilestones('O','1');
		else if(prefixVar == 'tks')tasksObject.callTasks();
		else if(prefixVar == 'fls')loadFiles('F',0,0,1);
		else if(prefixVar == 'emp')objPeople.callPeople();
	}
	
	function setHeadersForMailers(prefixVar,grpId,level,grpName)
	{
		if($$('a.clsGrp'+grpId).length > 0)
		{
			grpObj = $$('a.clsGrp'+grpId)['0'];
		}
		else
		{
			QTId = '3';	
			groupId = grpId;
			grpName = unescape(stripslashes(grpName));
			grpName = grpName.capitalize();		
			if(level=="1"){var msgTxt = "Discussions";var empTxt = "Employees";}else{ var msgTxt = "Discussions";var empTxt = "Members";}
			var str = '';
			str += '<a onclick="$(\'navDDquickTab'+QTId+'\').style.display=\'none\';showGroupInfo(this,'+groupId+','+level+')" href="javascript:void(0);" id="quickTab'+QTId+'">'+grpName+'</a>';
			str += '<ul id="navDDquickTab3" class="tabsDropDown" style="display:none;">';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'mess\','+groupId+',\'quickTab'+QTId+'\','+level+')">'+msgTxt+'</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'mil\','+groupId+',\'quickTab'+QTId+'\','+level+')">Milestones</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'tks\','+groupId+',\'quickTab'+QTId+'\','+level+')">Tasks</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'fls\','+groupId+',\'quickTab'+QTId+'\','+level+')">Files</a></li>';
			str += '<li class="divider"><div>&nbsp;</div></li>';
			str += '<li><a href="javascript:void(0);" onclick="navDDClick(\'emp\','+groupId+',\'quickTab'+QTId+'\','+level+')">'+empTxt+'</a></li>';
			str += '</ul>';
			$('liquickTab'+QTId).style.display = 'block';
			$('liquickTab'+QTId).innerHTML = str;
			$('liquickTab'+QTId).attributes["onmouseover"].value = 'shownavDD("quickTab3","1",'+groupId+');';
			$('liquickTab'+QTId).attributes["onmouseout"].value = 'shownavDD("quickTab3","0",'+groupId+');';
			grpObj=$('quickTab'+QTId);
			var rand   = Math.random(99999);
			var url    = '/groups/groups/add3rdTabfn.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&groupid=' + grpId + '&sessUID='+userID;
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onComplete: function(transport){
				if(transport.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}			
			}});
		}
		if(level == "1"){$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Employees</a>';}else{$('messNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'messNavigation\');messagesObject.callMessages()">Discussions</a>';$('empNavigation').innerHTML = '<a href="javascript:void(0);"  onclick="setSecondHeader(\'empNavigation\');objPeople.callPeople()">Members</a>';}
		setHeader(grpObj,grpId,level);
		setVars(grpId,level);		
		setSecondHeader(prefixVar+'Navigation');
	}
		
	var seldiv='';
	var globalClassVar,globalAdd_RemId,globalAddQT;
	globalAddQT=0;
	function changeSelRemClass(divId,flag,classVar,addRem,id)
	{		
		window.globalAddQT = addRem;	
		if($(window.seldiv))$(window.seldiv).className =$(window.seldiv).className.replace(globalClassVar+'Sel',globalClassVar);
		
		if(flag=='1'){//1 = mouseover
		
			window.globalClassVar=classVar;
			window.globalAdd_RemId=id;
			window.seldiv=divId;
			
			if($(divId).className.match('Sel')==null)
			{
				$(divId).className =$(divId).className.replace(classVar,classVar+'Sel');
			}
			
			if(addRem == 'a')
			{
				$('addQuickTab').innerHTML = 'Bookmark this';
				//$('addQuickTab').attributes["onclick"].value = "milestonesObject.addRemQuickTab('"+id+"','0')";
			}else{
				$('addQuickTab').innerHTML = 'Remove';
				//$('addQuickTab').attributes["onclick"].value = "milestonesObject.addRemQuickTab('"+id+"','1')";
			}
			
			var f = findPosition(divId);
			$('addQuickTab').style.display = 'block';
			
			if(classVar=='currentQucikTabs')
			{
				topPaddingQuickTab = -4;
				leftPaddingQuickTab = 30;
			} else {
				topPaddingQuickTab = 2;
				leftPaddingQuickTab = 30;
				
			}
			var scrolled = document.getElementById("quicktabContent").scrollTop;
			if(BrowserDetect.browser == 'Explorer'){	
				$('addQuickTab').setStyle({ top : f[1]-topPaddingQuickTab -70 + 'px' });
				$('addQuickTab').setStyle({ left : f[0]-leftPaddingQuickTab + 15 + 'px' });	
			}else{
				$('addQuickTab').setStyle({ marginTop : f[1]-topPaddingQuickTab - scrolled + 'px' });
				$('addQuickTab').setStyle({ marginLeft : f[0]-leftPaddingQuickTab + 'px' });
			}			
		}else{
			
			$('addQuickTab').style.display = 'none';
		}
		
	}
	//Quick Tab Ends
	
	//function to close all comment boxes without any text in one click
	/*function closeCommentBox()
	{
		var arr = new Array();
        arr = document.getElementsByTagName( "DIV" );
        
        for(var i=0; i < arr.length; i++){
        	var tagObj = arr.item(i);
        	var tid = tagObj.id.split('_');
        	if(tid[0] == 'TA2' && tagObj.style.display != 'none' && document.getElementById('commentArea'+tid[1]).value == ''){        		
        		//alert(tagObj.id);
        		document.getElementById('TA2_'+tid[1]).style.display = 'none';
        		document.getElementById('TA1_'+tid[1]).style.display = 'block';
        	}
        }			
	}*/
	
	//function to close the comment box on clicking the discard button
	function closeMessageBox(id){		
		if(document.getElementById('commentArea'+id).value != ''){
			//alert('ask user');
			document.getElementById('close_comment_comfirm'+id).style.display = 'block';
		}else{
			document.getElementById('TA2_'+id).style.display = 'none';
        	document.getElementById('TA1_'+id).style.display = 'block';
		}
	}
	/******************** Manage this intranet *************************/
	function showManageThisDomainView(){
		//grpId = $('currGrpId').value;
		//showDiv = this.afterSuccessTalk.bind(this);  onSuccess: showDiv
		scroll(0,0);
		showLoader();
		$('currPageCat').value = "dm";
		var memTab = '0';
		if($('domainAdminFlag').value == "YES"){
			memTab = '1';		
			var url='/groups/domains/domainsettings.json';
		}else{
			memTab = '0';
			var url='/groups/domains/view';
			var headerElements = $$('li.sel');
			var elemCount = headerElements.length;
			
			var headerElements = $$('li.sel');
			var elemCount = headerElements.length;
			for(i=0;i<elemCount;i++){
				if(headerElements[i])headerElements[i].className='';
			}
		}
		
		new Ajax.Updater('maincontent', url, {
  			parameters: {memTabShow : memTab  },
  			evalScripts:true
		});
		
		if($('domainMgmthref'))
		this.setHeader($('domainMgmthref'),'ovr','mtd')
	}

	function resetQuicklinks(serailno,qid, flag){
		$('quickLinkTitle'+qid).className = 'bigTextField fieldWidth3 inputDefault';
		$('quickLinkTitle'+qid).value = 'Enter link name';
		$('quickLink'+qid).className = 'fieldWidth3 inputDefault';
		$('quickLink'+qid).value = 'Enter link here';
		saveQuickLink(serailno, qid,flag);	
	}

	function qLinkFocus(id,type, serialno){
		var val = id.value.strip();
		if(type == 'input'){
			if(val == 'Enter link name'){
			id.value = '';
			id.className = 'bigTextField fieldWidth3';
			$('linkNameErr'+serialno).hide();
			}		
		}
		
		if(type == 'textarea'){
			if(val == 'Enter link here')
			id.value = '';
			id.className = 'fieldWidth3';
			$('linkLinkErr'+serialno).hide();
		}			
	}
	
	function qLinkBlur(id,type){
		var val = id.value.strip();
		if(type == 'input'){
			if(val == ''){
			id.className = 'bigTextField fieldWidth3 inputDefault';
			id.value = 'Enter link name';			
			}		
		}
		
		if(type == 'textarea'){
			if(val == ''){
			id.className = 'fieldWidth3 inputDefault';
			id.value = 'Enter link here';
			}
		}			
	}
	
	function saveQuickLink(serialno, id, flag){
		if(flag == '0')
		var url    = '/groups/domains/saveQuickLink.json';
		
		if(flag == '1')
		var url    = '/groups/domains/resetQuickLink.json';
		
		var rand   = Math.random(9999);

		var pars   = "rand="+rand+"&serialno="+serialno+"&linkname="+escape($('quickLinkTitle'+id).value)+"&link="+escape($('quickLink'+id).value);
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess:function(transport)
					{
						if(transport.responseText == "expired")
						{
							window.location = MainUrl + "main.php?u=signout";return;
						}
						else
						{							
							var res = transport.responseText.split('^^');
							
							if(res[1] == 2){							
								$('quickLinkTitle'+id).addClassName('inpErr');
								$('quickLink'+id).removeClassName('inpErr');
								$('linkLinkErr'+id).hide();
								$('linkNameErr'+id).show();
							}
							
							if(res[1] == 3){
								$('quickLinkTitle'+id).removeClassName('inpErr');
								$('quickLink'+id).addClassName('inpErr');
								$('linkLinkErr'+id).innerHTML = 'Please enter hyperlink';
								$('linkNameErr'+id).hide();
								$('linkLinkErr'+id).show();
							}
							
							if(res[1] == 4){
								$('quickLinkTitle'+id).removeClassName('inpErr');
								$('quickLink'+id).addClassName('inpErr');
								$('linkLinkErr'+id).innerHTML = 'Please enter proper hyperlink';
								$('linkNameErr'+id).hide();
								$('linkLinkErr'+id).show();
							}
							
							if(res[1] == 1){
								$('quickLinkTitle'+id).removeClassName('inpErr');
								$('quickLink'+id).removeClassName('inpErr');
								$('linkNameErr'+id).hide();
								$('linkLinkErr'+id).hide();
								var url    = '/groups/domains/getQuickLink.json';
								var rand   = Math.random(9999);						
								var pars   = "rand="+rand;
								var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess:function(transport)
										{
											if(transport.responseText == "expired")
											{
												window.location = MainUrl + "main.php?u=signout";return;
											}
											else
											{																			
												$('quickLinkBody').innerHTML = transport.responseText;
											}
										}	
								});								
							}
														
						}
					}	
		});	
	}
	
	function showManageThisDomainViewWidMember(para){
		showLoader();
		$('currPageCat').value = "dm";
		if(para == "general"){		
			var url='/groups/domains/domainsettings.json';
		}else if(para == "member"){
			var url='/groups/domains/member';
		}else if(para == "quickLink"){
			var url='/groups/domains/quickLinks.json';
		}
		
		new Ajax.Updater('maincontent', url, {
  			parameters: {memTabShow : 1},
  			evalScripts:true
		});
				
		if($('domainMgmthref'))
		this.setHeader($('domainMgmthref'),'ovr','mtd')
	}
	
	function showManageThisDomainViewAdmin(){		
		var url='/groups/domains/domainsettings.json';	
		var memTab = '0';	
		new Ajax.Updater('maincontent', url, {
  			parameters: {memTabShow : memTab }
  			
		});
	}
	
	function  afterShowManageThisDomainView()
	{
	if($('currGrpIdMTD'))
	  $('currGrpId').value=$('currGrpIdMTD').value;  //setting from ovr to loggedin network level
	 
	}

	function showDomainSettings(){
		//grpId = $('currGrpId').value;
		//showDiv = this.afterSuccessTalk.bind(this);  onSuccess: showDiv
		var url='/groups/domains/view';
		new Ajax.Updater('maincontent', url, {
  			parameters: { }
  			
		});
	}

	function openRemoveCategoryPopup(catName,catId)
	 {
		// 	alert("name"+catName+"  "+catId);
	 	/*$('catToRemoveDisplay').innerHTML='Click on OK to remove the"'+catName+'" category';
	 	$('catToRemove').value=catName;
	 	$('removeCategory').style.display=	'block';
	 	$('catRemoveId').value=catId;
	 	*/
	 	
	 	var url    = '/groups/domains/getCatDDSettings.json';
		var userID = $('SessStrUserID').value;	
		var rand   = Math.random(9999);

		var pars   = "sessUID="+userID+"&rand="+rand+"&remCat="+catId;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess:function(transport)
					{
						if(transport.responseText == "expired")
						{
							window.location = MainUrl + "main.php?u=signout";return;
						}
						else
						{
							$('loadCategories').innerHTML = transport.responseText; 
							$('catToRemoveDisplay').innerHTML='Click on OK to remove the"'+catName+'" category';
						 	$('catToRemove').value=catName;
						 	centerPos($('removeCategory'), 1);
						 	$('removeCategory').style.display=	'block';
						 	$('catRemoveId').value=catId;
						}
					}	
		});
	 }
		
	function show_reasonBox(res){
		$('reasonError').hide();
	    $('reasonText').removeClassName('inpErr');
	    $('reasonText').value = '';
	    $('reasonSuccess').style.display = 'none'; 
  		if(res == '0'){
  			if(document.getElementById('reason').style.display=='block'){
  				document.getElementById('reason').style.display='none';
  				document.getElementById('sendButton').style.display='none';
  			}else{
  				document.getElementById('reason').style.display='block';
  				document.getElementById('sendButton').style.display='block';
  			}
  		}else{
  			document.getElementById('creator_to_manager_confirm').style.display = 'block';
  		}
  	}
	
	var whoisclass=Class.create();
	whoisclass.prototype=
	{
		data: [],
		selId: '',
		initialize: function() {
	    },
	    
	    isAdmin: function(emailId,domain){    	
	    	var domain = domain;//"remindo.com";//later to be removed from here
	    	var emailId = emailId;//"subhendu@remindo.com";
	    	var userID = $('SessStrUserID').value;    
			var show = this.adminPage.bind(this);
			var url    = "/groups/domains/isAdmin.json";
			var rand   = Math.random(9999);
			var pars   = "?emailId="+emailId+"&domain="+domain+"&sessUID="+userID;
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: show} );                                
	                                 
		},
	       
	    adminPage: function(originalRequest){
	    	if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}		
			var strCal = originalRequest.responseText;
			
			
			var sp_res = strCal.split("+");
					document.getElementById('domainAdminId').value = sp_res[0];
        			if(sp_res[1] == '0')
        			{
        				if(document.getElementById('reason').style.display=='block')
        				{
        					document.getElementById('reason').style.display='none';
        					document.getElementById('sendButton').style.display='none';
        				}
        				else
        				{
        					document.getElementById('reason').style.display='block';
        					document.getElementById('sendButton').style.display='block';
        				}
        				     
        				$('reasonError').hide();
	    				$('reasonText').removeClassName('inpErr');        				
        				//$('reasonError').style.display = 'none';
	    				$('reasonText').value = '';
	    				$('reasonSuccess').style.display = 'none'; 
        			}
        			else
        			{        			
        				var obj_whois;
        				obj_whois = new whoisclass();        				
        				obj_whois.convertCreatortoManager();
        			}
		},
		
		checkdsubdomain: function(ob){
			$('reasonError').hide();
			if($('reasonText'))$('reasonText').removeClassName('inpErr');
			if(document.getElementById('subdomain').value.strip()=="" || document.getElementById('subdomain').value.strip()=="your company's name goes here"){
				$('serrordiv').show();
				$('serrordiv').innerHTML='Please enter web address';				
				return false;
			}		
			var prog=$("susubdomain");
			prog.style.display="";
				var url    = "/modules/php/susubdomain.php";		
				var pars   = "?subdomainname="+escape(ob.value)+"&step=avlsubdomain"; 				
				var myAjax = new Ajax.Request( url, {method: "post", parameters: pars, onComplete: function(originalRequest){
					if(originalRequest.responseText == "expired"){      
						window.location = MainUrl + "main.php?u=signout";return;
					}
					prog.style.display="none";
					if(originalRequest.responseText.strip() != '0'){
						$('serrordiv').show();
						$('serrordiv').innerHTML= originalRequest.responseText;
					}else{
						$('serrordiv').hide();
						$('serrordiv').innerHTML = '';
					}
				
				}} );
		},
		
		sendRequestMail: function(admin_name){
			$('reasonError').hide();
			if($('reasonText'))$('reasonText').removeClassName('inpErr');        	
			if(document.getElementById('subdomain').value.strip()=="" || document.getElementById('subdomain').value.strip()=="your company's name goes here"){
				$('serrordiv').show();
				$('serrordiv').innerHTML='Please enter web address';				
				return false;
			}else if(document.getElementById('subdomain').value.strip() !=""){

					var url    = "/modules/php/susubdomain.php";		
					var pars   = "?subdomainname="+escape(document.getElementById('subdomain').value)+"&step=avlsubdomain"; 				
					var myAjax = new Ajax.Request( url, {method: "post", parameters: pars, onComplete: function(originalRequest){
						if(originalRequest.responseText == "expired"){      
							window.location = MainUrl + "main.php?u=signout";return;
						}
						if(originalRequest.responseText.strip() != '0'){
							$('serrordiv').show();
							$('serrordiv').innerHTML= originalRequest.responseText;
							return false;
						}else{
							if(document.getElementById('reasonText').value.strip()==""){
								$('reasonError').innerHTML='Sorry but you need to fill both fields to get your web address. If you don\'t want to do this right away, you can skip this step now and do it later.';				
								$('reasonError').show();
								$('reasonText').addClassName('inpErr');
								return false;
							}
						
							var userID = $('SessStrUserID').value;    
							var reason = document.getElementById('reasonText').value;
							//document.getElementById('reasonText').value ='';			
							//var showRequestResult = this.showRequestResult.bind(this);
							var url    = "/groups/domains/sendRequestMail";		
							var pars   = "?reason="+escape(reason)+"&admin_name="+escape(admin_name)+"&sessUID="+userID+"&domainAdminId="+document.getElementById('domainAdminId').value+"&subdomainName="+document.getElementById('subdomain').value; 
							
							var myAjax = new Ajax.Request( url, {method: "post", parameters: pars, onComplete: function(originalRequest){
															
								if(originalRequest.responseText == "expired^"){  
		  							window.location = MainUrl + "main.php?u=signout";return;
		  						}else if(originalRequest.responseText.strip() != ""){
		  							var res = originalRequest.responseText.strip().split('__%%');
		  							$('msgSent').show();
		  							$('msgSent').innerHTML = res[1];			  						
		  							var redStr = "top.location = 'http://"+res[0]+"."+SNameNoWww+".com'";		  							
		  							setTimeout(redStr,15000);		  							
		  						}
							}} );
						}
					}} );				
			}  	
		

		},
		
		
		redirectWindow: function(url){		
			top.location = "http://"+url+"."+SNameNoWww+".com"; 
		},
		
		sendRequestMail1: function(admin_name){
			document.getElementById('reasonSuccess').style.display = 'none';
			if(document.getElementById('reasonText').value.length == 0){
				document.getElementById('reasonError').style.display = 'block';
				$('reasonText').addClassName('inpErr');
				return false;
			}else{
				document.getElementById('reasonError').style.display = 'none';
				$('reasonText').removeClassName('inpErr');
				var userID = $('SessStrUserID').value;    
				var reason = document.getElementById('reasonText').value;
				document.getElementById('reasonText').value ='';			
				var showRequestResult = this.showRequestResult.bind(this);
				var url    = "/groups/domains/sendRequestMailToAdmin";		
				var pars   = "?reason="+escape(reason)+"&admin_name="+admin_name+"&sessUID="+userID+"&domainAdminId="+document.getElementById('domainAdminId').value; 
				
				var myAjax = new Ajax.Request( url, {method: "post", parameters: pars, onComplete: showRequestResult} );
			}
		},
		
		showRequestResult: function(originalRequest){		
			if(originalRequest.responseText == "expired"){  
	  			window.location = MainUrl + "main.php?u=signout";return;
	  		}else{
				var strCal = originalRequest.responseText;
				document.getElementById('reasonSuccess').innerHTML=strCal;
				document.getElementById('reasonSuccess').style.display = 'block';
				setTimeout("document.getElementById('reasonSuccess').style.display = 'none';",20000);			
	 		}
		},
		
		approveRejectUser: function(user_id, action){		
			if(user_id == ''){
				var rad_val = "";
				for (var i=0; i < document.manageDomainRequest.manDomReq.length; i++){
	   				if (document.manageDomainRequest.manDomReq[i].checked){
	      				var rad_val = document.manageDomainRequest.manDomReq[i].value;
	      			}
	   			}
	   			if(rad_val == ""){
	   				document.getElementById('reqSelectError').style.display = "block";	
	   				return false;
	   			}else{
	   				user_id = rad_val;
	   				document.getElementById('reqSelectError').style.display = "none";
	   			}
	   		}   		
			
			var userID = $('SessStrUserID').value;
			var approveRejectUserResult = this.approveRejectUserResult.bind(this);
			var url    = "/groups/domains/approveRejectUser";		
			var pars   = "?selUserID="+user_id+"&action="+action+"&sessUID="+userID; 
			
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: approveRejectUserResult} );
			
		},
		
		approveRejectUserResult: function(originalRequest){		
			if(originalRequest.responseText == "expired"){  
	  			window.location = MainUrl + "main.php?u=signout";return;
	  		}else{
				var strCal = originalRequest.responseText;
				strCal = strCal.split('~^br^br^--');
				//alert(strCal[0]);
				
				var userID = $('SessStrUserID').value;
				var approveRejectUserResult1 = this.approveRejectUserResult1.bind(this);
				var url    = "/groups/groups/postApproveRejectUser";		
				var pars   = "?action="+strCal[1]+"&sessUID="+userID; 				
				var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: approveRejectUserResult1} );
					
	 		}
		},
		
		approveRejectUserResult1: function(originalRequest){		
			if(originalRequest.responseText == "expired"){  
	  			window.location = MainUrl + "main.php?u=signout";return;
	  		}else{
				var strCal = originalRequest.responseText;
				strCal = strCal.split('^~#br^^^');
				if(strCal[0].strip() == 'reject'){				
					$('addRemoveAdminDiv').innerHTML = strCal[1];
					if(strCal[1].strip() == '')
					$('addRemoveAdminDiv').style.display = 'none';
				}
				
				if(strCal[0].strip() == 'accept'){
					window.location.reload();
					/*var latestDate = Element.immediateDescendants($('overviewBody'))['0'].immediateDescendants()['0'].innerHTML;    
					if(latestDate.strip().toLowerCase() == "today"){
		            	Element.immediateDescendants($('overviewBody'))['1'].className = "postGroup";
		            	Element.immediateDescendants($('overviewBody'))['0'].remove();            	
		            }
			            
			        html = '<div class="threadDate"><span class="threadDateDisplay">Today</span></div>'+strCal[1];
			            	
		        	$('overviewBody').insert ({
		 				'top'  : html
		  			} ); 
		  							
					$('addRemoveAdminDiv').innerHTML = '';					
					$('addRemoveAdminDiv').style.display = 'none';
					*/
				}								
	 		}
		},
		
		checkManager: function(){		
			var userID = $('SessStrUserID').value;
			var checkManagerResult = this.checkManagerResult.bind(this);
			var url    = "/groups/domains/checkManager";		
			var pars   = "?sessUID="+userID;	
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: checkManagerResult} );
			
		},
		
		checkManagerResult: function(originalRequest){		
			if(originalRequest.responseText == "expired"){  
	  			window.location = MainUrl + "main.php?u=signout";return;
	  		}else{
				var strCal = originalRequest.responseText;
				var res = strCal.split('#'); 
				//alert(res[0]); alert(res[1]);
				if(res[0] == res[1]){
					window.location = "/groups/domains/domainsettings";
				}else{
					window.location = "groups/domains/view";
				}
	 		}
		},
		
		convertCreatortoManager: function(){ 
			var userID = $('SessStrUserID').value;
			var CCMResult = this.CCMResult.bind(this);
			var url    = "/groups/domains/convertCreatortoManager";
			var pars   = "?sessUID="+userID;	
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: CCMResult} );
		},
		
		CCMResult: function(originalRequest){
			if(originalRequest.responseText == "expired"){  
	  			window.location = MainUrl + "main.php?u=signout";return;
	  		}else{			
				var strCal = originalRequest.responseText;
				//window.location = "/groups/groups/view/";
				//alert(strCal);	
	 		}
		},
		
		creatorTakeoverManager : function(){
			var userID = $('SessStrUserID').value;
			var CTMResult = this.CTMResult.bind(this);
			var url    = "/groups/domains/creatorTakeoverManager";
			var pars   = "?sessUID="+userID;	
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: CTMResult} );
		},
		
		CTMResult: function(originalRequest){
			if(originalRequest.responseText == "expired"){  
	  			window.location = MainUrl + "main.php?u=signout";return;
	  		}else{
	  			var strCal = originalRequest.responseText;			
				document.getElementById('creator_to_manager_confirm').style.display='none';
				//alert(strCal);
	  		}
		},
		
		abc : function(){
			alert('gjhghjg');
		} 
	};

	
	/*********************End of Manage This intranet **********************/
	
	
	
	/************************ Start of general setting.js **********************/
	
	function loadGeneralSettings()
	{		
		grpId = $('currGrpId').value;
		$('currPageCat').value = "gns";
		showLoader();
		var userID = $('SessStrUserID').value;
		
		new Ajax.Updater('maincontent', '/groups/groups/generalsettings.json', {
  			parameters: { groupid: grpId,sessUID:userID},
  			evalScripts: true,
  			onSuccess: function(transport)
  			{
  				if(transport.responseText == "expired")
		        {      
		        	window.location = MainUrl + "main.php?u=signout";return;
		        }
  			}
		});
	}
	
	var generalInfo = Class.create();
	generalInfo.prototype = {
		data: [],
		fileCategory: [],
		messageCategory: [],
		addedFileCategory:'',
		addedMessageCategory:'',
		allFileCategories:'',
		allMessageCategories:'',	
		errorMessage:'',
		companyName:'',
		companyDomain:'',
		parentGroupId:'',
		parentGroupLevelId:'',
		levelId:'',
		initialize: function () {},
		
		initializefn: function () {
			this.addedMessageCategory = $('messageCategoryArray').value;
			this.addedFileCategory = $('fileCategoryArray').value;
			$("errMsg_Msg").style.display = 'none';
			if($("errMsg"))$("errMsg").style.display = 'none';
	
			if($("parentFileCatCount").value == 0){
				if($("divParentFileCat"))$("divParentFileCat").style.display = 'none';		
			}
			
			if($("parentMsgCatCount").value == 0){
				$("divParentMsgCat").style.display = 'none';
			}
			
			if(($("parentFileCatCount").value == 0) && ($("parentMsgCatCount").value == 0)){
				if($("useThisDiv"))$("useThisDiv").style.display = 'none';
			}else{
				if($("useThisDiv"))$("useThisDiv").style.display = '';
			}
			
		},
		
		removeDuplicateCategories: function(duplicateCategories) {
			var uniqueCategories = ',';
			if (typeof duplicateCategories == 'string'){
				duplicateCategories = duplicateCategories.split(',');
			}
			for (i = 0; i < duplicateCategories.length; i++){
				var dupCat = duplicateCategories[i].replace(/^\s*/, '').replace(/\s*$/, '');
				if (uniqueCategories.indexOf(',' + dupCat + ',') == -1){
					uniqueCategories = uniqueCategories + dupCat + ',';
				}
			}
			return uniqueCategories.replace(/^,/, '').replace(/,$/, '');
		},
		
		addFileCategory: function(){
				var addedStr = ($('filecategory').value).strip();
				if(addedStr == ''){
					$('errMsg').style.display='';
					$('errMsg').innerHTML = "<font>Category name cannot be blank.</font>";					
					return ;
				}
				if(addedStr.indexOf(",",0) == -1){			
					if(addedStr.length > 25){ 
						$('filecategory').value = '';
						$('filecategory').focus();
						$('errMsg').style.display='';
						$('errMsg').innerHTML = "<font color='red'>Cannot exceed 25 characters.</font>";					
						return ;
					}
					
					if(!(addedStr.blank())){
						$('errMsg').style.display = 'none';
						
						if(this.validateAddedFileCategory(addedStr)!="Duplicate"){// Check for duplicate value of category value
							if(this.addedFileCategory.length == 0){
								this.addedFileCategory = addedStr;
							}else{
								this.addedFileCategory += "," + addedStr;
							}
						}
					}
				}
				else{
					if(!(addedStr.blank())){
						tmpStr = addedStr.split(",");
						catGtTen = "";
						counter = 0;
						intTmpStrLength = parseInt(tmpStr.length);
						for(cnt=0;cnt<intTmpStrLength;cnt++){
							if(tmpStr[cnt].length>25){
								if(counter == 0){
									catGtTen=tmpStr[cnt];		
								}else{
									catGtTen+=","+tmpStr[cnt];			
								}
								counter++;
							}else{
								if(this.validateAddedFileCategory(tmpStr[cnt])!="Duplicate"){// Check for duplicate value of category value
									if(this.addedFileCategory.length == 0){
										if(!tmpStr[cnt].blank()){
											this.addedFileCategory = tmpStr[cnt];
										}
									}else{
										if(!tmpStr[cnt].blank()){
											this.addedFileCategory += "," + tmpStr[cnt];
										}
									}
								}
							}	
						}
						if(catGtTen!=""){
						$('errMsg').style.display = '';
							$('errMsg').innerHTML = "<font color='red'>Categories : "+catGtTen+" exceeded 25 characters.</font>";
						}	
					}				
				}
				
				if($('useFileCategory').checked == true){
					this.allFileCategories = (this.addedFileCategory.concat(",")).concat($('parentFileCategoryArray').value);
				}else{
					this.allFileCategories = this.addedFileCategory;				
				}
				
				if($('useMsgCatForFile').checked == true){
					this.allFileCategories = this.allMessageCategories + "," + this.allFileCategories;
				}
				
				var dispCategory = '';
				var strTempCat = this.allFileCategories.split(",");
				
				var index = parseInt(strTempCat.length);
	
				for(i=0;i<index;i++){
					if(i == 0){
						dispCategory = "<div class='addNameCell'>"+strTempCat[i]+"</div>";
					}else{
						dispCategory += "<div class='addNameCell'>"+strTempCat[i]+"</div>";
					}
				}
				$('filecategory').value = '';
				$('filecategory').focus();
				$('fileCategoryRow').innerHTML = dispCategory;
		},
		addMessageCategory: function(){
			var addedStr = ($('messagecategory').value).strip();
				if(addedStr == ''){
					//$('errMsg_Msg').style.display='';
					//$('errMsg_Msg').innerHTML = "<font>Category name cannot be blank.</font>";
					$('errAddCategorySettings').show();
					$('messagecategory').addClassName('inpErr');					
					return ;
				} else {
					$('errAddCategorySettings').hide();
					$('messagecategory').removeClassName('inpErr');
				}
				if(addedStr.indexOf(",",0) == -1){
					if(addedStr.length > 25){
						$('messagecategory').value = '';
						$('messagecategory').focus();
						$('errMsg_Msg').innerHTML = "<font color='red'>Cannot exceed 25 characters.</font>";
						$('errMsg_Msg').style.display = '';
						return ;
					}
					
					if(!(addedStr.blank())){
						$('errMsg_Msg').style.display = 'none';
						if(this.validateAddedMessageCategory(addedStr)!="Duplicate"){// Check for duplicate value of category value
							if(this.addedMessageCategory.length == 0){
								this.addedMessageCategory = addedStr;
							}else{
								this.addedMessageCategory += "," + addedStr;
							}
						}
					}
				}else{
					if(!(addedStr.blank())){
						tmpStr = addedStr.split(",");
						catGtTen = "";
						counter = 0;
						intTmpStrLength = parseInt(tmpStr.length);
						for(cnt=0;cnt<intTmpStrLength;cnt++){
							if(tmpStr[cnt].length>25){
								if(counter == 0){
									catGtTen=tmpStr[cnt];		
								}else{
									catGtTen+=","+tmpStr[cnt];			
								}
								counter++;
							}else{
								if(this.validateAddedMessageCategory(tmpStr[cnt])!="Duplicate"){// Check for duplicate value of category value
									if(this.addedMessageCategory.length == 0){
										if(!tmpStr[cnt].blank()){
											this.addedMessageCategory = tmpStr[cnt];
										}
									}else{
										if(!tmpStr[cnt].blank()){
											this.addedMessageCategory += "," + tmpStr[cnt];
										}
									}
								}
							}	
						}
						if(catGtTen!=""){
							$('errMsg_Msg').innerHTML = "<font color='red'>Categories : "+catGtTen+" exceeded 25 characters.</font>";
							$('errMsg_Msg').style.display = '';
						}	
					}				
				}
				
				if($('useMsgCategory').checked == true){
					this.allMessageCategories = (this.addedMessageCategory.concat(",")).concat($('parentMessageCategoryArray').value);
				}else{
					this.allMessageCategories = this.addedMessageCategory;
				}
				
				var dispCategory = '';
				var strTempCat = this.allMessageCategories.split(",");
				
				var index = parseInt(strTempCat.length);
				var index1 = parseInt(strTempCat.length)-parseInt(1);
				var highlight = 'highlight'+index1;
				for(i=0;i<index;i++){
					highlightTemp = 'highlight'+i;
					if(i == 0){
						dispCategory = "<div class='addNameCell'>"+strTempCat[i]+"</div>";
					}else{
						dispCategory += "<div class='addNameCell' id='"+highlightTemp+"'>"+strTempCat[i]+"</div>";
					}
				}
				$('messagecategory').value = '';
				$('messagecategory').focus();
				$('messageCategoryRowParent').show();
				$('messageCategoryRow').innerHTML = dispCategory;
				new Effect.Highlight(highlight, { startcolor: startColor,endcolor: endColor ,duration: 1});
		},
		
		addMessageCategoryPopup: function(){
			var strCategory = $("strCategory").value;
		
			if(strCategory.blank())
			{
				$("errorMsgCatPopup").style.display = 'block';
				$("errorMsgCatPopup").innerHTML = "Please enter category";
				$("strCategory").focus();
				return;
			}
			
			if(strCategory.indexOf(',') != "-1")
			{
				$("errorMsgCatPopup").style.display = 'block';
				$("errorMsgCatPopup").innerHTML = "<font color='red'>You can enter only one category at a time.</font>";
				$("strCategory").focus();
				return;
			}
			
			if(strCategory.length > 25)
			{
				$("errorMsgCatPopup").style.display = 'block';
				$("errorMsgCatPopup").innerHTML = "<font color='red'>Cannot exceed 25 characters.</font>";
				$("strCategory").focus();
				return;
			}
			var addMessageCategoryResponse = this.addMessageCategoryResponsePopup.bind(this);
			var url    = "/groups/messages/addMessageCategory.json";
			var pars   = "?groupid="+$('currGrpIdMTD').value+"&txtCategory="+strCategory+"&from=messages";
			var myAjax = new Ajax.Request( url, {method: "post", parameters: pars, onComplete: addMessageCategoryResponse} );		
		},
	
		addMessageCategoryResponsePopup: function(originalRequest){
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			if(originalRequest.responseText == "exists")
			{
				$("errorMsgCatPopup").style.display = 'block';
				$("errorMsgCatPopup").innerHTML = "Category exists";
				$("strCategory").value = "";
				$("strCategory").focus();
			}
			else if(originalRequest.responseText == '0') 
			{
				$("errorMsgCatPopup").style.display = 'block';
				$("errorMsgCatPopup").innerHTML = "You are not authorized to create a category";
				$("strCategory").value = "";
				$('createCategory').hide();
			}
			else
			{
				$("errorMsgCatPopup").innerHTML = '&nbsp;';
				$("errorMsgCatPopup").style.display = 'none';
				$("strCategory").value = "";
				$('createCategory').hide();
				
			 	var url    = '/groups/domains/getCatDDSettings.json';
				var userID = $('SessStrUserID').value;	
				var rand   = Math.random(9999);
		
				var pars   = "sessUID="+userID+"&rand="+rand+"&remCat="+$('catRemoveId').value;
				var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess:function(transport)
							{
								if(transport.responseText == "expired")
								{
									window.location = MainUrl + "main.php?u=signout";return;
								}
								else
								{
									$('loadCategories').innerHTML = transport.responseText; 									
								}
							}	
				});
				$("loadCategories").show();
				/*var data = originalRequest.responseText;
				data = eval( "("+ data +")" );
				var newData = data.split('##~~##');
				$('category').innerHTML = newData[0];
				*/				
			}
		},
		
		
		renderCategories: function(originalRequest){

			 	var url    = '/groups/domains/renderCategories.json';
				var userID = $('SessStrUserID').value;	
				var rand   = Math.random(9999);
				var groupid = $('groupId').value;
				var pars   = "sessUID="+userID+"&rand="+rand+"&groupid="+groupid;
				var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars, onSuccess:function(transport)
							{
								if(transport.responseText == "expired")
								{
									window.location = MainUrl + "main.php?u=signout";return;
								}
								else
								{																		
									data = transport.responseText;
									var newData = data.split('~~#~');
									if($("settingCategories"))$("settingCategories").innerHTML= newData[0];
									if($("messageCategoryArray"))$("messageCategoryArray").innerHTML= newData[1];									 									
								}
							}	
				});
				
		},
		
		validateAddedMessageCategory:function(strCat){
			var strTemp = this.addedMessageCategory.split(",");
			var count = parseInt(strTemp.length);
			for(i=0;i<count;i++){
				if(strCat.toLowerCase() == strTemp[i].toLowerCase()){
					return "Duplicate";
				}
			}
		},
		validateAddedFileCategory:function(strCat){
			var strTemp = this.addedFileCategory.split(",");
			var count = parseInt(strTemp.length);
			for(i=0;i<count;i++){
				if(strCat.toLowerCase() == strTemp[i].toLowerCase()){
					return "Duplicate";
				}
			}
		},	
		useMessageCategory: function(){
			$('errMsg_Msg').style.display = 'none';
			if($('errMsg'))$('errMsg').style.display = 'none';
			if($('messageCategoryStatus').value == 0){
				this.messageCategory = $('parentMessageCategoryArray').value;
				if(this.addedMessageCategory.length > 0){
					this.allMessageCategories = (this.addedMessageCategory.concat(",")).concat(this.messageCategory);
				}else{
					this.allMessageCategories = this.messageCategory;
				}
				$('messageCategoryStatus').value = 1;
				$('messageCategoryRow').innerHTML = '&nbsp;';
	
				this.allMessageCategories = this.removeDuplicateCategories(this.allMessageCategories);
	
				var tmpStr = this.allMessageCategories.split(",");
				var catText = "";
				for(i=0;i<tmpStr.length;i++){
					if(i==0){
						catText="<div class='addNameCell'>"+tmpStr[i]+"</div>";
					}else{
						catText+="<div class='addNameCell'>"+tmpStr[i]+"</div>";
					}
				}
				$('messageCategoryRow').innerHTML = catText;
			}else{
				$('messageCategoryStatus').value = 0;
				this.messageCategory = [];
				if(this.addedMessageCategory.length == 0){
					$('messageCategoryRow').innerHTML = '&nbsp;';
					this.allMessageCategories = "";
				}else{
					var tmpStr = this.addedMessageCategory.split(",");
					var catText = "";
					for(i=0;i<tmpStr.length;i++){
						if(i==0){
							catText="<div class='addNameCell'>"+tmpStr[i]+"</div>";	
						}else{
							catText+="<div class='addNameCell'>"+tmpStr[i]+"</div>";	
						}
					}
					$('messageCategoryRow').innerHTML = catText;
					this.allMessageCategories = this.addedMessageCategory;
				}
			}
		},
		useFileCategory: function(){
			$('errMsg_Msg').style.display = 'none';
			$('errMsg').style.display = 'none';
			if($('fileCategoryStatus').value == 0){
				this.fileCategory = $('parentFileCategoryArray').value;
				if(this.addedFileCategory.length > 0){
					this.allFileCategories = (this.addedFileCategory.concat(",")).concat(this.fileCategory);
				}else{
					this.allFileCategories = this.fileCategory;
				}
				if($("useMsgCatForFile").checked==true){
					if(((this.allFileCategories).toString()).length!=0){
						this.allFileCategories += ","+this.allMessageCategories;
					}else{
						this.allFileCategories = this.allMessageCategories;
					}
				}
	
				this.allFileCategories = this.removeDuplicateCategories(this.allFileCategories);
	
				$('fileCategoryRow').innerHTML = '&nbsp;';
				var tmpStr = this.allFileCategories.split(",");
				for(i=0;i<tmpStr.length;i++){
					if(i==0){
						catText="<div class='addNameCell'>"+tmpStr[i]+"</div>";	
					}else{
						catText+="<div class='addNameCell'>"+tmpStr[i]+"</div>";	
					}
					
				}
				$('fileCategoryRow').innerHTML = catText;
				$('fileCategoryStatus').value = 1;
			}else{
				$('fileCategoryStatus').value = 0;
				this.fileCategory = [];
					if($("useMsgCatForFile").checked){
						this.allFileCategories = this.allMessageCategories+","+this.addedFileCategory;
					}else{
						this.allFileCategories = this.addedFileCategory;
					}
	
					this.allFileCategories = this.removeDuplicateCategories(this.allFileCategories);
	
					if(((this.allFileCategories).toString()).legth!=0){
						var tmpStr = (this.allFileCategories).split(",");
						
						var catText = "";
						for(i=0;i<tmpStr.length;i++){
							if(i==0){
								catText="<div class='addNameCell'>"+tmpStr[i]+"</div>";
							}else{
								catText+="<div class='addNameCell'>"+tmpStr[i]+"</div>";							
							}
						}
						$('fileCategoryRow').innerHTML = catText;
						this.allFileCategories = this.allFileCategories;
					}else{
						$('fileCategoryRow').innerHTML = '&nbsp;';
						this.allFileCategories = "";
					}
				//}
			}
		},
		useMessageCategoryForFiles: function(){
			var tmpCategories = "";
			if($("fileCatStatus").value == 0){
				if($("useMsgCategory").checked){
					if(((this.allMessageCategories).toString()).length!=0){
						tmpCategories = (this.allMessageCategories).toString();
					}
				}else{
					if(((this.addedMessageCategory).toString()).length!=0){
						tmpCategories = (this.addedMessageCategory).toString();
					}
				}
				
				if(tmpCategories.length == 0){
					$("useMsgCatForFile").checked=false;
					$("fileCatStatus").value = 0;				
					return;	
				}
				
				if(((this.allFileCategories).toString()).length!=0){
					if(tmpCategories!=''){
						tmpCategories = tmpCategories.concat(",").concat((this.allFileCategories).toString());
					}else{
						tmpCategories = (this.allFileCategories).toString();					
					}
				}else{			
					if(((this.addedFileCategory).toString()).length != 0){
						tmpCategories = tmpCategories + "," + (this.addedFileCategory).toString();				
					}
				}
	
				tmpCategories = this.removeDuplicateCategories(tmpCategories);
	
				if(((tmpCategories).toString()).length!=0){
					var tmpStr = tmpCategories.split(",");
					for(i=0;i<tmpStr.length;i++){
						if(i==0){
							catText="<div class='addNameCell'>"+tmpStr[i]+"</div>";						
						}else{
							catText+="<div class='addNameCell'>"+tmpStr[i]+"</div>";						
						}
					}
					$('fileCategoryRow').innerHTML = catText;
					$("fileCatStatus").value = 1;
					this.allFileCategories = tmpCategories;				
				}else{
					$("useMsgCatForFile").checked=false;
					$("fileCatStatus").value = 0;
				}
			}else{
				tmpCategories = "";	
				$('fileCategoryRow').innerHTML = "&nbsp;";
				if($("useFileCategory").checked){
					tmpCategories = $("parentFileCategoryArray").value;
				}
	
				if(((this.addedFileCategory).toString()).length!=0){
					if((tmpCategories.toString()).length!=0){
						tmpCategories+=","+(this.addedFileCategory).toString();
					}else{
						tmpCategories=(this.addedFileCategory).toString();
					}
				}
	
				tmpCategories = this.removeDuplicateCategories(tmpCategories);
				
				if(((tmpCategories).toString()).length!=0){
					var tmpStr = tmpCategories.split(",");
					for(i=0;i<tmpStr.length;i++){
						if(i==0){
							catText="<div class='addNameCell'>"+tmpStr[i]+"</div>";						
						}else{
							catText+="<div class='addNameCell'>"+tmpStr[i]+"</div>";						
						}
					}
					$('fileCategoryRow').innerHTML = catText;
					this.allFileCategories = tmpCategories;	
				}			
				$("fileCatStatus").value = 0;
				this.allFileCategories = tmpCategories;	
			}
		},
		generateDisplayCategory: function(strCategoryArray){
			var tmpStr = strCategoryArray.split(",");
			var catText = "<div class='addNameCell'>";
			for(i=0;i<tmpStr.length;i++){
				catText+=tmpStr[i]+"</div><div class='addNameCell'>";
			}
			catText+="</div>";
			
			return catText;
		},
		saveCategoryData: function(){
		
		if($('messageCategoryRow') && $('messageCategoryRow').innerHTML == '')
		{
			$('errAddCategorySettings').show()
			$('messagecategory').addClassName('inpErr');
			return false;
		} else {
			$('errAddCategorySettings').hide()
			$('messagecategory').removeClassName('inpErr');
		}
			$('finalMessageCategoryArray').value = this.allMessageCategories;
			$('finalFileCategoryArray').value = this.allFileCategories;
	
			/*
			$('frmCategory').method="post";
			if($('type').value=='P'){
				$('frmCategory').action = "/groups/projects/updatecategories";
			}else{
				$('frmCategory').action = "/groups/groups/updatecategories";
			}
			$('frmCategory').submit();		
			*/
	
			$('saveloading').style.display = '';
	
			if ($('type').value=='P'){
				micoxUpload($('frmCategory'), '/groups/projects/updatecategories', 'saveloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
			}else{
				micoxUpload('frmCategory', '/groups/groups/updatecategories', 'saveloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
			}
			
			if(this.addedMessageCategory)this.addedMessageCategory = '';
			if($('messageCategoryRow'))$('messageCategoryRow').innerHTML = '';
		},
		
		//---------By ravindra sep 24 2009--------------------------------------
		
		saveAclData: function(){  // This function saves ACl data
			if ($('type').value=='P'){
				micoxUpload($('frmAcl'), '/groups/groups/updateAcessControl', 'saveloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
			}else{
				micoxUpload($('frmAcl'), '/groups/groups/updateAcessControl', 'saveloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
			}
		},
		createSubdomain: function(domain_name){
				var subd_name;
				subd_name = $('subdomain_name').value;
				
				if(subd_name == ''){
					$('createSbErrMsg').innerHTML = 'You must enter a web address in the text field';
					$('createSbErrMsg').style.display = 'block';
					$('subdomain_name').addClassName('inpErr');
					return false;
				}else{
					   var iChars = "!@#$%^&*()+=[]\\\';,./{}|\":<>?~";
					   var dataLen = subd_name.length;
					   if(dataLen > 30){
					   		$('createSbErrMsg').style.display = 'block';
					   		$('createSbErrMsg').innerHTML = 'Use 1 to 30 characters for the URL including letters, numbers, underscores or hyphens';
					   		$('subdomain_name').addClassName('inpErr');
					   		return false;
					   }
					    
					   for (var i = 0; i < dataLen; i++) {
					  	if (iChars.indexOf(subd_name.charAt(i)) != -1) {
					  		$('createSbErrMsg').style.display = 'block';					  	  
					  	  	$('createSbErrMsg').innerHTML = 'Use 1 to 30 characters for the URL including letters, numbers, underscores or hyphens';
					  	  	$('subdomain_name').addClassName('inpErr');							
					  		return false;
					  	}
					  }
					  $('createSbErrMsg').style.display = 'none';
					  $('createSbErrMsg').innerHTML = '';
					  $('subdomain_name').removeClassName('inpErr');
				}
				
				var createSubdomainResult = this.createSubdomainResult.bind(this);
				var url    = "/groups/domains/createSubdomain.json";
				var pars   = "?sub_domain="+subd_name+"&domain="+domain_name;	
				var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: createSubdomainResult} );
				
				//micoxUpload($('frmAcl'), '/groups/projects/updateAcessControl', 'saveloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
			
		},
		
		createSubdomainResult: function(originalRequest){
			if(originalRequest.responseText == "expired"){  
	  			window.location = MainUrl + "main.php?u=signout";return;
	  		}else{
				var strCal = originalRequest.responseText;
				var showText = strCal.split('#');
				if(showText[0] == 1){
					$('createSbErrMsg').innerHTML = showText[1];
					$('createSbErrMsg').style.display = 'block';
					$('successSbMsg').style.display = 'none';
				}else if(showText[0] == 2){
					$('subdomain_name').style.display = 'none'; 
					$('domiannameInput').innerHTML = "https://"+showText[2]+".remindo.com";
					$('create_sd').innerHTML = '';
					$('subdomainTitle').innerHTML = '';					
					$('successSbMsg').innerHTML = showText[1];
					$('successSbMsg').style.display = 'block';
					$('createSbErrMsg').style.display = 'none';
					setTimeout("$('successSbMsg').style.display='none';",5000);
					
				}
	 		}
		},
		
		addClientInfoPopUp: function(){
			$('addClientInfoPopUp').style.display='block';
			//group.getInvitationMessage('G');
			$('pname').focus();
			centerPos($('addClientInfoPopUp'), 1);
		},
		
		showContacts : function(obj){
			
			var objId = obj.id;
			
			var val = obj.value;
			var index = val.indexOf("##");
			var id = val.substr(0,index);
			var value = val.substr(index+2);
		
			if(value == "new"){
				$('selectPerson').style.display='none';
				$('listUsers').innerHTML = '';
				this.resetForm();
				$('email').disabled= false;
				$('clientId').value = '';
			}else{
				this.resetForm();
				$('selectPerson').style.display='block';
				$('clientId').value=id;
				var url    = '/groups/groups/showContacts.json';
				
				var rand   = Math.random(9999);
				var userID = $('SessStrUserID').value; 
				var pars   = 'network=' + value + '&sessUID='+userID+'&rand=' + rand;
				var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: 
					function(transport){
						if(transport.responseText == "expired"){  
		 				 	window.location = MainUrl + "main.php?u=signout";return;
		  				}else{
		  					var dat = (eval('(' + transport.responseText + ')'));
							var html = "<div class='leftFloat'><a href='javascript:void(0);' onClick=javascript:generalsettings.getUserDetails(-1,'')><img src='/images/addIconBig.png' width='40' border='0' title = 'New Person' alt = 'New Person'></a></div>";
							
							for(i =0; i<dat.length; i++){
								var user = dat[i]; //user contains name,id,image
								var uid = user.a.user_id;
								var image = user.up.DefaultPhoto;
								var name = user.up.FullName;
								html = html + "<div class='leftFloat'><a href='javascript:void(0);' onClick=javascript:generalsettings.getUserDetails('"+uid+"','"+image+"')><img src='"+ image +"' width=40 border=0 title = '" + name + "' alt = '" + name+ "'></a></div>";
							}
							$('listUsers').innerHTML = html;
						}
					}
				} );		
			}			
			$('pname').focus();
		},
		
		resetForm: function(){
			$('enterClientInfo').reset();
			$('err_pname').style.display = 'none';
			$('err_phoneno').style.display = 'none';
			$('err_email').style.display = 'none';
			$('err1_email').style.display = 'none';
			$('err2_email').style.display = 'none';
			$('err3_email').style.display = 'none';
			$('errorMessage').style.display = 'none';
			$('err_client').style.display='none';
			$('email').className = 'fieldWidth1';
			$('phoneno').className = 'fieldWidth1';
			$('pname').className = 'fieldWidth1';		
		},
		
		getUserDetails: function(id,photo){			
			var email = $('email');
			this.resetForm();
			
			if(id == -1){
				email.disabled= false;	
			}else{
				var url    = '/groups/groups/getUserDetails.json';
				var rand   = Math.random(9999);
				var userID = $('SessStrUserID').value;
				var grpId = $('currGrpId').value; 
				var pars   = 'UserId=' + id +'&grpId=' + grpId+'&page=setting&sessUID='+userID + '&rand=' + rand;
				var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onSuccess: function(transport){
				
					if(transport.responseText == "expired"){  
		 				 window.location = MainUrl + "main.php?u=signout";return;
		  			}else{
		  				var detail = (eval('(' + transport.responseText + ')'));
		  				if(detail == "")
		  				{
		  					$('err_client').style.display='block';
		  					email.disabled= false;
		  				}
		  				else
		  				{	
		  					$('err_client').style.display='none';	
							$('pname').value = detail[0].UP.FullName;
							$('phoneno').value = detail[0].UP.MobileNo.replace(/@-/g, '').replace(/@/g, '').replace(/-$/g,'');
						
							$('email').value = detail[0].UE.EmailID;
							$('address1').value = detail[0].UP.Add1;
							$('address2').value = detail[0].UP.Add2;
							$('country').value = detail[0].UP.Country;
							$('state').value = detail[0].UP.State;
							$('city').value = detail[0].UP.City;
							$('zip').value = detail[0].UP.ZipCode;
							$('website_url').value = detail[0].UPos.PosCompanyWebsite;
							$('userid').value = id;
							$('photo').value = photo;
							$('email').disabled= true;
						}
					}
				},
				onComplete: function(){
					generalsettings.chkClientWithEmail();
					group.chkMailIdWithDb($('email').value,'','errNum_email');
					if($('err_client').style.display=='block'){
						$('err_email').style.display= 'none';
						$('email').className = 'fieldWidth1';
					}				
				}
				} );				
			}		
		},
		
		hideEnterClientDiv: function(){
			$('selectPerson').style.display='none';
			this.resetForm();
			$('addClientInfoPopUp').style.display='none';
			$('client').selectedIndex = 0;
			$('pname').className = 'fieldWidth1';
			$('phoneno').className = 'fieldWidth1';
			$('email').className = 'fieldWidth1';
		},
		
		submitUserDetails: function(){		
			if(this.validateForm()){			
				$('email').disabled=false; 
				$('grpId').value = $('currGrpId').value;
				messagesObject.micoxUpload('enterClientInfo','/groups/groups/submitUserDetails','updateInfoloading','<img src=/groups/img/loading.gif class=loadingImgLeft>','Error'); 
				this.hideEnterClientDiv();
				setTimeout('loadGeneralSettings();', 1000);	
			}
			if($('err_client'))$('err_client').style.display='none';
				
		},
				
		validateForm: function(){			
			//this.errorMessage = "There are some errors, error messages are shown above";
			var bool = false;
		
			var bool1 = this.validateMandatoryFields('email'); 
			var bool2 = this.validateMandatoryFields('phoneno');
			var bool3 = this.validateMandatoryFields('pname');
			
			if(bool1 && bool2 && bool3) bool = true;
			
			/*if(!bool){
				$('errorMessage').innerHTML = this.errorMessage;
				$('errorMessage').style.display = '';
			}else {
				$('errorMessage').style.display = 'none';
			}*/
			return bool;
		},
		
		validateMandatoryFields: function(idName){		
			var fid = idName;
			var bool = true; 
			$('err_'+fid).style.display = 'none';
			
			if(idName == 'email'){
				$('err1_'+fid).style.display = 'none';
				$('err2_'+fid).style.display = 'none';
	          	$('err3_'+fid).style.display = 'none';
			}
			
			if(($(fid).value).blank()){
				$('err_'+fid).style.display = '';
				bool = false; 
			}else{
				$('err_'+fid).style.display = 'none';
				bool = true; 
			}
			if(idName == 'email' && !($(fid).value).blank()){
				var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
				if($(fid).value.search(emailRegEx) == -1){
	          		$('err1_'+fid).style.display = '';
	          		bool = false; 
				}else{
					var val = $('client').value;
					var idx = val.indexOf("##");
					var client = val.substr(idx+2);
					client = client.toLowerCase();
					if(client != "new" && $('hid_email').value == 0){
						$('err3_email').style.display = '';
						bool = false;	
					}
					else $('err3_email').style.display = 'none';			
				}	
			}

			if(bool)
				$(fid).className = 'fieldWidth1';
			else
				$(fid).className = 'fieldWidth1 inpErr';
	
			return bool;	
		},
		
		chkClientWithEmail: function() {
			$('hid_email').value = -1;
			var bool1 = this.validateMandatoryFields('email');
			
			if(bool1){
				var val = $('client').value;
				var idx = val.indexOf("##");
				var client = val.substr(idx+2);
				client = client.toLowerCase();
				var email = $('email').value;
				var companyid =  $('clientId').value;
				var userid = $('userid').value;
				var userID = $('SessStrUserID').value;
				var bool = false;
				var url = '/groups/groups/chkClientWithEmail.json';
				var pars   = '?client='+client+'&email='+email+'&sessUID='+userID;
				var ret = true;
				var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onSuccess: 
			
				function(transport){					
					if(transport.responseText == "expired"){  
	 					 window.location = MainUrl + "main.php?u=signout";return;
	  				}else{
	  					var dat = (eval('(' + transport.responseText + ')'));
						if(dat.bool == 0){
							$('err3_email').style.display = '';
							$('hid_email').value = 0;		
							$(fid).className = 'fieldWidth1 inpErr';			
						}else{
							$('err3_email').style.display = 'none';
							$('hid_email').value = 1;
							$(fid).className = 'fieldWidth1';
						}
						companyid = dat.companyid;
						userid = dat.userid;
					}
				},
				onComplete: function(){	
					$('clientId').value = companyid;
					$('userid').value = userid;
					//if(userid == '')userid = -1;
				}
				} );	
			}
		},
		
		
		getEditPopUpInfo: function(id) {
			var rand   = Math.random(9999);
			var url = '/groups/groups/getEditPopUpInfo.json';
			var userID = $('SessStrUserID').value; 
			var pars   = '?id='+id+'&sessUID='+userID+'&rand='+rand;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: 
			
			function(transport){	
				
				if(transport.responseText == "expired"){  
	 				 window.location = MainUrl + "main.php?u=signout";return;
	  			}else{
	  				var dat = (eval('(' + transport.responseText + ')'));
					$('pop_id').value = id;
					$('pop_name').innerHTML = "<h1>" + dat[0].gcu.name +"'s details</h1>";
					$('pop_phoneno').value = dat[0].gcu.phoneno.replace(/@-/g, '').replace(/@/g, '').replace(/-$/g,'');
					$('pop_email').value = dat[0].gcu.email;
					$('pop_add1').value = dat[0].gcu.address1;
					$('pop_add2').value = dat[0].gcu.address2;
					$('pop_city').value = dat[0].gcu.city;
					$('pop_state').value = dat[0].gcu.state;
					$('pop_country').value = dat[0].gcu.country;
					$('pop_zip').value = dat[0].gcu.zip;
					$('pop_website_url').value = dat[0].gcu.website_url;
					$('groupsEditPopUp').style.display='block';
					centerPos($('groupsEditPopUp'), 1);
				}
			}
				
			} );	
			
		},
		
		deleteUserPopUp: function(id){
			$('delId').value = id;
			var network = $('network'+id).innerHTML;
			var divs = document.getElementsByName(network);
			var msg = '';
			var name = $('name'+id).innerHTML;
			if(divs.length == 1) msg = "If you delete "+ name + "'s contact information, there will be no client details for "+network+". Are you sure "+
										"you want to proceed?";
			else msg = "Are you sure you want to delete "+ name + "'s information ?";
			
			$('delMsg').innerHTML = msg;
			
			$('delPopUp').style.display='block';
			centerPos($('delPopUp'), 1);
		},
		
		
		delGrpUserdetails: function(){
			var rand   = Math.random(9999);
			var url = '/groups/groups/delGrpUserdetails.json';
			var userID = $('SessStrUserID').value;
			var id = $('delId').value; 
			var pars   = '?id='+id+'&sessUID='+userID+'&rand='+rand;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: 
			
			function(transport){	
				if(transport.responseText == "expired"){  
	 				 window.location = MainUrl + "main.php?u=signout";return;
	  			}else{
	  				$('delPopUp').style.display = 'none';
	  				$('user'+id).remove();
	  			}
			}
			});
		
		},
		
		
		saveGrpUserDetails: function(){
			var rand   = Math.random(9999);
			var url = '/groups/groups/saveGrpUserDetails.json';
			var userID = $('SessStrUserID').value; 
			var pars   = '?id='+$('pop_id').value+'&phoneno='+$('pop_phoneno').value.replace(/@-/g, '').replace(/@/g, '').replace(/-$/g,'')+'&email='+$('pop_email').value+'&add1='+$('pop_add1').value+'&add2='+$('pop_add2').value+
						 '&city='+$('pop_city').value+'&state='+$('pop_state').value+'&country='+$('pop_country').value+'&zip='+$('pop_zip').value+'&website='+$('pop_website_url').value+'&sessUID='+userID+'&rand='+rand;		 
			this.updateGrpUserDetails();
			
			var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: function(transport){
				if(transport.responseText == "expired"){  
	 				window.location = MainUrl + "main.php?u=signout";return;
	  			}else{
	  				$('groupsEditPopUp').style.display='none';
	  			}
			}
			}); 
		},
		
		updateGrpUserDetails: function(){
			var uid = $('pop_id').value;
			var html = '';
			
			html += '<div class=" block">'+
	        				'<div class="rightFloat"> ' +
	        					'<a href="javascript:void(0);" onclick = "generalsettings.getEditPopUpInfo('+uid+');">Edit</a> ' +
	        							'| ' +
	        					'<a href="javascript:void(0);" onclick = "generalsettings.deleteUserPopUp('+uid+');">Delete</a> ' +
							'</div>'+
	        					'<span id = "network'+uid+'">' + $('network'+uid).innerHTML + '</span>'+
	    				'</div>'+
	    				'<div class="vCard">'+
	    				
	    					'<div class="vCardImage"><img width = "50" src="'+$('photo'+uid).value+'" /></div>'+
	    					'<input type="hidden" id="photo'+uid+'" value="'+$('photo'+uid).value+'">'+
	        				'<div class="vCardInfo">'+
	            				'<div class="vCardName" id="name'+ uid +'">'+$('name'+uid).innerHTML +'</div>'+
	   							'<div class="vCardEmail" id="email'+ uid +'" name="email'+ uid +'">'+ $('pop_email').value+ '</div>';
	            				
	           	if($('pop_add1').value != ''){		
	           		html += 	'<div class="vCardRow" id="address1'+ uid +'" name="address1'+ uid +'">'+
	                				$('pop_add1').value+
	           					'</div>';
	           	}
	           	if($('pop_add2').value != ''){		
	           		html += 	'<div class="vCardRow" id="address2'+ uid +'" name="address2'+ uid +'">'+
	                				$('pop_add2').value+
	           					'</div>';
	           	}
	           	
	           	if($('pop_city').value != ''){			
					html +=		'<div class="vCardRow">'+
								'<span id="city'+ uid +'" name="city'+ uid +'">' + $('pop_city').value +'</span>';
						if($('pop_zip').value != '')	
							html += '<span id="zip'+ uid +'" name="zip'+ uid +'"> - ' + $('pop_zip').value +'</span>';
						html += '</div>';	
				}		
				
	 
	  			var str1 = 		'<span id="state'+ uid +'" name="state'+ uid +'">' + $('pop_state').value + '</span>';
				var str2 = 		'<span id="country'+ uid +'" name="country'+ uid +'">' + $('pop_country').value + '</span>';
				
				html +=  		'<div class="vCardRow">';
								
	            if($('pop_state').value != '' && $('pop_country').value != '')		
						html +=		str1 + ', ' +str2;
				else if($('pop_state').value != '')	
						html +=		str1;
				else if($('pop_country').value != '')	
						html +=		str2;
						
				html += 		'</div>'+
					 			'<div class="vCardRow">'+
	                				'<div class="vCardLabel">Mobile:</div>'+
	                				'<div class="vCardCont">'+  $('pop_phoneno').value.replace(/@-/g, '').replace(/@/g, '').replace(/-$/g,'') +'</div>'+
	                			
	           					'</div>';
	           								
				if($('pop_website_url').value != ''){							
	              html +=		'<div class="vCardRow"><a href="'+returnFullUrl($('pop_website_url').value)+'" target="_blank">'+$('pop_website_url').value+'</a>'+
	               				'</div>';
				}
				
				html +=  	'</div></div>';
			
			
			$('user'+uid).innerHTML = html;
		
		},
				
		removeCategories:function(catRemoveName,catRemove,catAssign){  // This function Removes categoires used for messages and files			
			var userID = $('SessStrUserID').value; 
			var show = this.handleRemoveCategories.bind(this);
		   	var url    = "/groups/domains/removeCategories.json";
			var rand   = Math.random(9999);
			var pars   = "?catRemove="+catRemove+"&rand=" +rand+"&par=1&catAssign="+catAssign+"&sessUID="+userID+"&catRemoveName="+catRemoveName;
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: show} );				
		},
		
		handleRemoveCategories:function(originalRequest){
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			var strCal = originalRequest.responseText;
			var catinfo=strCal.split("+");
			if(catinfo[0]==0){
				openRemoveCategoryPopup(catinfo[1],catinfo[2]);				
				if($('createCategory'))$('createCategory').style.display = 'none';
			}else{
			
				 generalsettings.renderCategories();
				 $('addQuickTabCat').style.display=	'none';					
				 $('removeCategory').style.display=	'none';
			}
		//alert(catinfo[0]);
		},	
		
		showhideAddCat : function(listid) {
			this.addedMessageCategory = '';
			if($('useMsgCategory'))$('useMsgCategory').checked = false;
			var box = 'addNewCatBox';
			var link = 'addNewCatLink';
					
			if ($(box).style.display != 'none') {
				$(link).show();
				$(box).hide();
				//Effect.toggle(box,'slide',{duration: 0.5});
			} else {
				$(link).hide();
				$(box).show();			
				//Effect.SlideDown(box,{duration: 0.5});
				$('errAddCategorySettings').hide()
				$('messagecategory').removeClassName('inpErr');
			}
		},
		
		savePrjGrpDetails: function(){
			/*check if user has changed any fields */
			//alert($('GroupPhonenoHdn').value+" == "+$('GroupWebsite_urlHdn').value+" == "+$('GroupZipHdn').value+" == "+$('GroupCityHdn').value+" == "+$('GroupStateHdn').value+"=="+$('GroupCountryHdn').value+"=="+$('GroupAddress1Hdn').value +"=="+$('GroupNameHdn').value);
			if($('GroupPhonenoHdn') && $('GroupPhonenoHdn').value == $('GroupPhoneno').value && $('GroupWebsite_urlHdn') && $('GroupWebsite_urlHdn').value == $('GroupWebsite_url').value && $('GroupZipHdn') && $('GroupZipHdn').value == $('GroupZip').value && $('GroupCityHdn') && $('GroupCityHdn').value == $('GroupCity').value && $('GroupStateHdn') && $('GroupStateHdn').value == $('GroupState').value && $('GroupCountryHdn') && $('GroupCountryHdn').value == $('GroupCountry').value && $('GroupAddress2Hdn') && $('GroupAddress2Hdn').value == $('GroupAddress2').value && $('GroupAddress1Hdn') && $('GroupAddress1Hdn').value == $('GroupAddress1').value && $('GroupNameHdn') && $('GroupNameHdn').value == $('GroupName').value)
			{
				return false;
			}
			else
			{	$('btnSaveGrpDetails').addClassName('butDisabled');
				$('updateloading').style.display = '';
				if($('type').value=='P'){				
					micoxUpload($('ProjectGeneralsettings/Form'), '/groups/projects/generalsettings', 'updateloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
				}else{				
					if($('GroupName').value.strip() == ''){
						alert("Name can not be blank");
						return false;
					}
					micoxUpload($('GroupGeneralsettings/Form'), '/groups/groups/generalsettings', 'updateloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
				}
				//assigning the changed values to the hidden fields
				if($('GroupPhonenoHdn'))$('GroupPhonenoHdn').value = $('GroupPhoneno').value;
				if($('GroupWebsite_urlHdn'))$('GroupWebsite_urlHdn').value = $('GroupWebsite_url').value;
				if($('GroupZipHdn'))$('GroupZipHdn').value = $('GroupZip').value;
				if($('GroupCityHdn'))$('GroupCityHdn').value = $('GroupCity').value;
				if($('GroupStateHdn'))$('GroupStateHdn').value = $('GroupState').value;
				if($('GroupCountryHdn'))$('GroupCountryHdn').value = $('GroupCountry').value;
				if($('GroupAddress2Hdn'))$('GroupAddress2Hdn').value = $('GroupAddress2').value;
				if($('GroupAddress1Hdn'))$('GroupAddress1Hdn').value = $('GroupAddress1').value;
				if($('GroupNameHdn'))$('GroupNameHdn').value = $('GroupName').value;
			}
			$('btnSaveGrpDetails').removeClassName('butDisabled');
		},
		
		savePrjGrpDetails1: function(){
			/*check if user has changed any fields */
			
			if($('GroupNameHdn') && $('GroupNameHdn').value == $('GroupName').value && $('GroupDescriptionHdn') && $('GroupDescriptionHdn').value == $('GroupDescription').value)
			{
				$('errGeneralNameSettings').hide();
				$('GroupName').removeClassName('inpErr');
				return false;
			}
			else
			{
				$('updateloading').style.display = '';
				if($('type').value=='P'){
					micoxUpload($('ProjectGeneralsettings/Form'), '/groups/projects/generalsettings', 'updateloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
				}else{				
					if($('GroupName').value.strip() == ''){
						//alert("Name can not be blank");
						$('errGeneralNameSettings').show();
						$('GroupName').addClassName('inpErr');
						$('updateloading').hide();
						return false;
					} else {
						$('errGeneralNameSettings').hide();
						$('GroupName').removeClassName('inpErr');
						micoxUpload($('GroupGeneralsettings/Form'), '/groups/groups/generalsettings', 'updateloading', '<img src=/groups/img/loading.gif class=loadingImgLeft>', 'Error');
					}
				}
				//assigning the changed values to the hidden fields
				if($('GroupNameHdn'))$('GroupNameHdn').value = $('GroupName').value;
				if($('GroupDescriptionHdn'))$('GroupDescriptionHdn').value = $('GroupDescription').value;				
			}
		}
	};

	var generalsettings = new generalInfo();

	var uploadFileSubmitted=false;
	
	function showhide_id(id) {
		if (document.getElementById(id).style.display == 'none')
		{
			document.getElementById(id).style.display='block';
		}else{
			document.getElementById(id).style.display='none';
		}
	}
	
	function afterUpload(updatesave){
		if(updatesave == 'U'){
			//$('navgGrp').innerHTML = $("GroupName").value.capitalize(); /*Reflecting new group name in navigator*/
			//$('bcrumpCurr').innerHTML = $("GroupName").value.capitalize(); /*Reflecting new group name in bread crump*/
			//$('navigate_groups_list').firstDescendant().innerHTML = $("GroupName").value.toUpperCase()+" HOME"; /*Reflecting new group name in home tab*/
			
			$('updateloading').style.display = 'none';
			$('updatesuccess').style.display = '';
			setTimeout('$(\'updatesuccess\').style.display=\'none\'', 3000);
		}else if (updatesave = 'S'){
			$('saveloading').style.display = 'none';
			$('savesuccess').style.display = '';
			setTimeout('$(\'savesuccess\').style.display=\'none\'', 3000);
		}
	}

	/*********************** End of general setting js **********************************/
	
	/////calendar tab flipping
	
	var calendarOver = Class.create();
	calendarOver.prototype = {
		data: [],	
		initialize: function () {
			this.groupid = 0;		
		},	
		
		flipCalendar : function(val,grpId,type){
			var dateOfRef = document.getElementById('start4today').value;
			var prevOrNext =  document.getElementById('div2Bmoved').value;
			var today = '';
			
			if(type == "t"){
				if(prevOrNext>dateOfRef){
					var today = "p";
				}else if(prevOrNext<dateOfRef){
					var today = "n";
				}else if(prevOrNext == dateOfRef){			
					window.alreadyOnThePagePopupStatus=1;
					document.getElementById('alreadyOnThePagePopup').style.display='block';
				
					var calPos=findPos1('showMilestones');		
					document.getElementById('alreadyOnThePagePopup').style.top = calPos[1]+70+'px';
					document.getElementById('alreadyOnThePagePopup').style.left = calPos[2]-385+calPos[0]+'px';
					return false;
				}
			}
		
			if(document.getElementById('calendarFlag'))
				var milOrOver = 'milestones';
			else
				var milOrOver = 'overview';
		 	
		 	if(type == "n" || today == "n"){
		      
			      if(val == 'start4today') parent.CCDK = 0;
			      else parent.CCDK = parent.CCDK + 1;
			      
			      /*if(grpId == 'overviewCalendar') parent.CCDG = 0;
			      else parent.CCDG = grpId;*/
			      this.groupid = grpId;
			      //parent.CCDG = 0;
			      
			      //alert(parent.CCDK +' :: '+ typeof parent.CCD[parent.CCDK]);
			      if(!parent.CCD[parent.CCDK] || parent.CCD[parent.CCDK] == '') {
			      	
		      		  $('calender_loader').show();
				      var getCalVar = this.getCal.bind(this);
				      var url    = '/groups/groups/getCalData.json';	
				      var pars   = 'startDate='+document.getElementById(val).value+'&groupid='+grpId+'&type='+type+'&next='+document.getElementById('start4next').value+'&prev='+document.getElementById('start4prev').value+'&today='+today+'&milOrOver='+milOrOver;
				      var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: getCalVar} );
				      
			      } else {
			      
				      //alert('here');
				      //alert(parent.CCD[parent.CCDG][parent.CCDK][0]);
				      this.getCal();
			      
			      }
			      
		      }else if(type == "p" || today == "p"){

			      if(val == 'start4today') parent.CCDK = 0;		 	
			      else parent.CCDK = parent.CCDK - 1;
			      
			      /*if(grpId == 'overviewCalendar') parent.CCDG = 0;
			      else parent.CCDG = grpId;*/
			      this.groupid = grpId;
			      //parent.CCDG = 0;
			      
			      //alert(parent.CCDK +' :: '+ typeof parent.CCD[parent.CCDK]);
			      if(!parent.CCD[parent.CCDK] || parent.CCD[parent.CCDK] == '') {
			      
				      $('calender_loader').show();
				      var getCalVar = this.getCalPrev.bind(this);
				      var url    = '/groups/groups/getCalData.json';	
				      var pars   = 'startDate='+document.getElementById(val).value+'&groupid='+grpId+'&type='+type+'&next='+document.getElementById('start4next').value+'&prev='+document.getElementById('start4prev').value+'&today='+today+'&milOrOver='+milOrOver;
				      var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: getCalVar} );
				      
			      } else {
			      
				      //alert('here');
				      this.getCalPrev();
			      
			      }
		      }
		      	 
		  	/*if(type == "n" || today == "n"){
			  	var getCalVar = this.getCal.bind(this);
				var url    = '/groups/groups/getCalData.json';	
				var pars   = 'startDate='+document.getElementById(val).value+'&groupid='+grpId+'&type='+type+'&next='+document.getElementById('start4next').value+'&prev='+document.getElementById('start4prev').value+'&today='+today+'&milOrOver='+milOrOver;
				var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: getCalVar} );
		 	}else if(type == "p" || today == "p"){
			 	//alert(document.getElementById(val).value+type);
			 	var getCalVar = this.getCalPrev.bind(this);
				var url    = '/groups/groups/getCalData.json';	
				var pars   = 'startDate='+document.getElementById(val).value+'&groupid='+grpId+'&type='+type+'&next='+document.getElementById('start4next').value+'&prev='+document.getElementById('start4prev').value+'&today='+today+'&milOrOver='+milOrOver;
				var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: getCalVar} );
		 	}*/
			
		},
			
		getCalPrev: function (originalRequest) {
			
			var strCal0 = strCal1 = strCal2 = strCal3 = strCal4 = 0;
		      if(originalRequest) {
		      	
		      	  $('calender_loader').hide();

			      if(!parent.CCD[parent.CCDK]) parent.CCD[parent.CCDK] = new Array();
			
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}			      
			      var strCal = originalRequest.responseText.split('^br^br^');
			      strCal0 = strCal[0];
			      strCal1 = parent.CCD[parent.CCDK][1] = strCal[1];
			      strCal2 = parent.CCD[parent.CCDK][2] = strCal[2];
			      strCal3 = parent.CCD[parent.CCDK][3] = strCal[3];
			      
			      parent.CCDKSD[strCal3] = parent.CCDK;

			      parent.CCD[parent.CCDK][0] = htmlspecialchars(strCal0);
			      
			      //alert("strcal : " + parent.CCD[parent.CCDG][parent.CCDK][0]);
				      
		      } else {
		      
			      strCal0 = htmlspecialchars_decode(parent.CCD[parent.CCDK][0]);
			      strCal1 = parent.CCD[parent.CCDK][1];
			      strCal2 = parent.CCD[parent.CCDK][2];
			      strCal3 = parent.CCD[parent.CCDK][3];
			      
			      //alert('laoding saved data for : ' +  parent.CCDG +' :: '+ parent.CCDK);
		      }
		      
			strCal4 = this.groupid;		      	
			//var strCal = originalRequest.responseText.split('^br^br^');			
			var fsdfsd = strCal3;
			$('div2Bmoved').value = strCal3;
			$('calenderInner').innerHTML =  strCal0;
			hideMilestones($('red'));
			hideMilestones($('blue'));
			hideMilestones($('green'));
			
			$('tableCalInner').style.height = (parent.windowSize[0]*0.87 - 125) + "px";
			$('tableCalInner').style.width = (parent.windowSize[1]*0.9 - 69) + "px";
			$('calenderInner').style.height = (parent.windowSize[0]*0.87 - 100) + "px";	
			
			if(strCal1 != ""){
				$('start4prev').value = strCal1;        
				$('start4next').value = strCal2;
			}else{
				$('start4prev').value = $('start4prevOri').value;        
				$('start4next').value = $('start4nextOri').value;
			}
			
			var id = this.groupid;
			arrUnRead = $$('div.filterMil');		
			var elemCount = arrUnRead.length;
					
			if(id == 'overviewCalendar'){
				for(i=0;i<elemCount;i++){
					arrUnRead[i].style.display = 'block'; 
				}	
			}else{
				for(i=0;i<elemCount;i++){
				 var tempStr = arrUnRead[i].id;
				 var needle = 'milestoneGroupIdDiv_'+id+'_';
					if(tempStr.search(needle) > -1){
						arrUnRead[i].style.display = 'block'; 
					}else{
						arrUnRead[i].style.display = 'none'; 
					}					 
				}				
			}
			
			
			arrUnRead = $$('div.filterBirthdays');		
				var elemCount = arrUnRead.length;
				
				if(id == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == id){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}
			
			arrUnRead = $$('div.filterAnnis');		
				var elemCount = arrUnRead.length;
				
				if(id == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == id){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}
		},
				
		getCal: function (originalRequest) {
		
			var strCal0 = strCal1 = strCal2 = strCal3 = 0;
		      if(originalRequest) {
		      	
		      	$('calender_loader').hide();

				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}

			      if(!parent.CCD[parent.CCDK]) parent.CCD[parent.CCDK] = new Array();
			      
			      var strCal = originalRequest.responseText.split('^br^br^');
			      strCal0 = strCal[0];
			      strCal1 = parent.CCD[parent.CCDK][1] = strCal[1];
			      strCal2 = parent.CCD[parent.CCDK][2] = strCal[2];
			      strCal3 = parent.CCD[parent.CCDK][3] = strCal[3];
			      
			      parent.CCDKSD[strCal3] = parent.CCDK;
			      
			      parent.CCD[parent.CCDK][0] = htmlspecialchars(strCal[0]);
			      
			      //alert("strcal : " + parent.CCD[parent.CCDG][parent.CCDK][0]);
				      
		      } else {
		      
			      strCal0 = htmlspecialchars_decode(parent.CCD[parent.CCDK][0]);
			      strCal1 = parent.CCD[parent.CCDK][1];
			      strCal2 = parent.CCD[parent.CCDK][2];
			      strCal3 = parent.CCD[parent.CCDK][3];

			      //alert('laoding saved data for : ' + parent.CCDG +' :: '+ parent.CCDK);
			      //alert(parent.CCD[parent.CCDG][parent.CCDK][0] +' :: '+ parent.CCD[parent.CCDG][parent.CCDK][1]+' :: '+ parent.CCD[parent.CCDG][parent.CCDK][2]+' :: '+ parent.CCD[parent.CCDG][parent.CCDK][3]);
		      }
			strCal4 = this.groupid;	
		
						
			//var strCal = originalRequest.responseText.split('^br^br^');			
			var fsdfsd = strCal3;
			$('div2Bmoved').value = strCal3;
			$('calenderInner').innerHTML =  strCal0;			
			hideMilestones($('red'));
			hideMilestones($('blue'));
			hideMilestones($('green'));
			$('tableCalInner').style.height = (parent.windowSize[0]*0.87 - 125) + "px";
			$('tableCalInner').style.width = (parent.windowSize[1]*0.9 - 69) + "px";
			$('calenderInner').style.height = (parent.windowSize[0]*0.87 - 100) + "px";			
			
			if(strCal1 != ""){
				$('start4prev').value = strCal1;        
				$('start4next').value = strCal2;
			}else{			
				$('start4prev').value = $('start4prevOri').value;        
				$('start4next').value = $('start4nextOri').value;
			}
			
			var id = strCal4;
			arrUnRead = $$('div.filterMil');		
			var elemCount = arrUnRead.length;
					
			if(id == 'overviewCalendar'){
				for(i=0;i<elemCount;i++){
					arrUnRead[i].style.display = 'block'; 
				}	
			}else{
				for(i=0;i<elemCount;i++){
				 var tempStr = arrUnRead[i].id;
				 var needle = 'milestoneGroupIdDiv_'+id+'_';
					if(tempStr.search(needle) > -1){
						arrUnRead[i].style.display = 'block'; 
					}else{
						arrUnRead[i].style.display = 'none'; 
					}					 
				}				
			}
			
			
			arrUnRead = $$('div.filterBirthdays');		
				var elemCount = arrUnRead.length;
				
				if(id == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == id){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}
			
			arrUnRead = $$('div.filterAnnis');		
				var elemCount = arrUnRead.length;
				
				if(id == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == id){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}			
		}	
	};

	var liveDisplayMileStone = Class.create();
	liveDisplayMileStone.prototype = {
		data: [],
		initialize: function () {},
		
	    getMileStoneData: function (groupId,milVersion) {
			window.milestoneNTodosStatus=1;
			$('add_milestone').hide();
			var getMilestone = this.getMilestoneFn.bind(this);
			var url    = '/groups/groups/getMilestoneTodoData.json';
			var pars   = 'milVersion='+milVersion;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: getMilestone} );
		},
		
		fillHolder: function () {
			$('milestoneNTodos').style.display = 'block';		   
			var user_id;
			var dataObj;
			var selDay = this.data.selected_day;
			var loggedInUser = this.data.loggedInUser;		
			var tdate = this.data.tdate;
			dataObj = this.data.users;
			var selectHtml = '';
			var milestone_status = '';
																						
			var origMilID = this.data.originalMilID;
			$('editMilID').value = origMilID;																						
																						
			var milstoneVersion;		
			this.data.milestone.each(
			function(item){
	            user_id = item.milestones.user_id;
				milstoneVersion = item.milestones.version;
				var groupId = item.milestones.group_id;	
							
				$('addNewtaskLink').innerHTML = "<a class='but' href='javascript:void(0)' onclick=ldDispMileStone.closewindow('"+groupId+"','"+milstoneVersion+"');$('test').value='';><span>Add new task list</span></a>&nbsp;&nbsp;"
												+ "<a class='but' href='javascript:void(0)' onclick='javascript:ldDispMileStone.chkOpenBox();'><span>Done</span></a>";
				var finaldiff = item[0].finaldiff;					
				var day1 = "";
				
				if(finaldiff == "1" || finaldiff == "-1"){
					day1 = "day";
				}else if(finaldiff == "0"){
					day1 = "";
				}else{
					day1 = "days";
				}
				
				var tar = '';		
				if(finaldiff > 0){		
					tar = finaldiff+" "+day1+" away";
				}else if(finaldiff < 0){
					tar = -1*finaldiff;
					tar = tar+" "+day1+" ago";
				}else{
					tar = "(Today)";
				}
				
				if(loggedInUser == item.milestones.creater_user_id){
					$('milestoneDelLink').show();
					$('milestonePipe').show();
					var elemId = 'milestoneGroupIdDiv_'+groupId+'_'+milstoneVersion;
					var func = escape("ovrDelete(\'MilestoneCal\',\'"+item.milestones.id+"\',\'"+elemId+"\')");
					func = 'beforeDelete(this,event,\'ML\',\'0\',\''+func+'\')';
					$('milestoneDelLink').innerHTML = '<a href="javascript:void(0);" onclick="'+func+'">Delete</a>';
				}
				else{
					$('milestoneDelLink').hide();
					$('milestonePipe').hide();
				}
				
				var countComm = item[0].countComm;
				var str = 'Comment';
				
				if(countComm > 0){
					str = countComm + ' ' + str;
					if(countComm > 1)
						str = str + 's';
				}
				
				$('milestoneCommLink').innerHTML = '<a id="milestoneCommNum" href="javascript:void(0);" onclick="showCommentRHS(\''+milstoneVersion+'\',\'MCal\')">' + str +'</a>';
				
				var cName = '';
				if(item.milestones.status_id == '2'){
					cName = 'complete';
					$('milestoneEditLink').hide();
					if($('milestoneCommNum').innerHTML == 'Comment'){
						$('milestoneCommLink').hide();
						$('milestonePipe').hide();
					}
					$('addNewtaskLink').style.display ='none';
					$('spaceBar').className = '';				
				}else if(finaldiff < 0){
					cName = 'delayed';
					$('milestoneEditLink').show();
					$('milestoneCommLink').show();
					if($('milestoneDelLink').style.display != 'none')
						$('milestonePipe').show();
					$('addNewtaskLink').style.display ='block';
					$('spaceBar').className = 'fieldSpacer1';
				}else{
					cName = '';
					$('milestoneEditLink').show();
					$('milestoneCommLink').show();
						if($('milestoneDelLink').style.display != 'none')
					$('milestonePipe').show();
					$('addNewtaskLink').style.display ='block';
					$('spaceBar').className = 'fieldSpacer1';
				}
				
				$('addtaskList').checked = false;	
				$('editMilButton').innerHTML = "<a href='javascript:void(0);' class='but' onclick=validateEditCal('frmEditMilestone','"+groupId+"')><span class='lineHit30'>Edit this milestone</span></a>";
			    $('milestone_version').value=milstoneVersion;
			    $('milestone_status_id').value = item.milestones.status_id;
			    $('prj_id').value=groupId;
				
	            if(item.milestones.status_id == '2'){
	            	 milestone_status = 'none';
	                 $('milestonechk').innerHTML="<input type='checkbox' id='milChk"+milstoneVersion+"' checked='checked' class='borderNone' onclick='ldDispMileStone.strikeAll("+milstoneVersion+")' />";
	            }else{
	            	 milestone_status = '';
	                 $('milestonechk').innerHTML="<input type='checkbox' id='milChk"+milstoneVersion+"' class='borderNone' onclick='ldDispMileStone.strikeAll("+milstoneVersion+")' />";
	            }
	            
	            $('MStoneClassDecider').className = cName;	            
				$('milestoneTitle').innerHTML = "<input type='hidden' id='todolisthidden' class='borderNone' /><span class='mGTitle' style='font-weight:bold;'>"+item.milestones.title+"</span> in <span style='font-weight:bold;'>"+item.groups.name+"</span> "+tar+" - "+item[0].formatedDate+"</div>";
			}
			);
			
			
			
			dataObj.users_list.each(
			function(item){					
				if (user_id == item.usermst.UserID){
					if (loggedInUser == item.usermst.UserID){
						selectHtml += "<OPTION value='"+ item.usermst.UserID +"' selected>Me(" + item.userpersonalinfo.FullName + ")</OPTION>";
					}else{
						selectHtml += "<OPTION value='"+ item.usermst.UserID +"' selected>" + item.userpersonalinfo.FullName + "</OPTION>";
					}
				}else{
					if (loggedInUser == item.usermst.UserID){
						selectHtml += "<OPTION value='" + item.usermst.UserID + "'>Me(" + item.userpersonalinfo.FullName + ")</OPTION>";
					}else{
						selectHtml += "<OPTION value='" + item.usermst.UserID + "'>" + item.userpersonalinfo.FullName + "</OPTION>";
					}
				}
			}
			);
			
			var cnt = 0;
			var todolistStr = '';
			var todoopen = this.data.todoopen;
			var todoclose = this.data.todoclose;
			var todolen = todoopen.length;
			var todocloselen = todoclose.length;
			var todolistLen = this.data.todolist.length;
			var i=0;		
					
			this.data.todolist.each(
			function(item){
				$('todolisthidden').value = $('todolisthidden').value + ',' +item.todolists.id; 
				
				if(cnt == "0")
					var topClass = 'postGroupTop';
				else
					var topClass = 'postGroup';
				
				var todolist_status = 'none';
				var todolpipe_status = 'none';
					
				if(loggedInUser == item.todolists.creator){
					todolist_status = '';
					elemId = '';
					func = escape("ovrDelete(\'TaskListCal\',\'"+item.todolists.id+"\',\'"+elemId+"\')");
					func = 'beforeDelete(this,event,\'TKL\',\'0\',\''+func+'\')';
					
					if(milestone_status == '')
						todolpipe_status = '';
				}
				
					
				var addtaskvar = "addTask"+item.todolists.id;
				var mseOvr= "toggleeditLinkList('"+addtaskvar+"','0');";
				var mseOut= "toggleeditLinkList('"+addtaskvar+"','1');";						
				todolistStr += "<div id='todolistDiv"+item.todolists.id+"' class='milestoneGroup "+topClass+"' style='padding-bottom:15px;padding-top:15px;'>";
				
				todolistStr += "<div id='tasklistdiv"+item.todolists.id+"' >";
				todolistStr += "<div id='AddParent"+item.todolists.id+"' class='rightFloat'>";
				todolistStr += "<div id='todoButtonsDiv"+item.todolists.id+"' class='leftFloat butComment'><span id='"+addtaskvar+"' style='display:"+ milestone_status +"'><a href='javascript:void(0);' onclick=document.getElementById('addTododBox"+item.todolists.id+"').style.display='block';>Add task</a></span>";
				todolistStr += "<span id='TodoListPipe"+item.todolists.id+"' style='display:"+ todolpipe_status+ "'>&nbsp;&nbsp;|&nbsp;&nbsp;</span>";
					
				if(todolist_status != 'none')
					todolistStr += '<span id="TodoListDelLink'+item.todolists.id+'"><a href="javascript:void(0);" onclick ="'+func+'">Delete</a> </span>';
				
				todolistStr += '</div>';
				if(todolistLen < 2)
					todolistStr += "<div id='swap"+item.todolists.id+"' class='contract leftFloat' onclick='ldDispMileStone.toggleTodo("+item.todolists.id+")'><img src='/images/minimize.gif' /></div>";
				else
					todolistStr += "<div id='swap"+item.todolists.id+"' class='expand leftFloat' onclick='ldDispMileStone.toggleTodo("+item.todolists.id+")'><img src='/images/maximize.gif' /></div>";
				
				todolistStr += "</div>";
				
				if(item.todolists.description != '')
					todolistStr += "<div class='mGTitleRow1'  ><span class='mGTitle' style='font-weight:bold;'><a href='javascript:void(0)' onclick='ldDispMileStone.toggleTodoDesc("+item.todolists.id+")'>"+stripslashes(item.todolists.title)+"</a></span> in<span style='font-weight:bold;'> "+item.groups.grname+"</span></div>";
				else
					todolistStr += "<div class='mGTitleRow1'  ><span class='mGTitle' style='font-weight:bold;'>"+stripslashes(item.todolists.title)+"</span> in<span style='font-weight:bold;'> "+item.groups.grname+"</span></div>";
				
				todolistStr += "</div>";
				todolistStr += "<div class='mGTitleDesc' id='tododecs"+item.todolists.id+"' style='display:none;' >"+item.todolists.description+"</div>";
				
				///code for adding tasks
				todolistStr += "<div style='overflow: visible;display:none; margin-top:10px;' id='addTododBox"+item.todolists.id+"' class='toDoBox'>";
				todolistStr += "<form id='frm_"+item.todolists.id+"' method='post' onsubmit='return false;' action='/groups/groups/addTodoCal/"+item.todolists.id+"' style='margin:0px'>";							
				todolistStr += "<div>";
				
				
				todolistStr += "<div class='commentsTotalNo gainlayout'>";
          		todolistStr += "<div class='rightFloat'>";
          		todolistStr += "<img src='/images/cross1.gif' style='cursor: pointer;' onclick=closeaddtaskbox('"+item.todolists.id+"') />";
          		todolistStr += "</div>";
          		todolistStr += "<span class='todoBoxTitle'>Add new task</span>";
          		todolistStr += "</div>";
				
				
				/*todolistStr += "<div class='closeGrey rightFloat'><a onclick=closeaddtaskbox('"+item.todolists.id+"') href='javascript:void(0)' id='todoLink_284'>ggg</a></div>";*/
				todolistStr += "</div>";						
				todolistStr += "<div class='commentsCont'>";
				todolistStr += "<div id='result_"+item.todolists.id+"'></div>";
				todolistStr += "<fieldset>";
				todolistStr += "<div>";										
				todolistStr += "<div class='field'>";									
				todolistStr += "<div class='fieldLabel fieldLabelWidthBig'>";
				todolistStr += "<label>New task</label>";
				todolistStr += "</div>";										
				todolistStr += "<div>";
				todolistStr += "<input type='text' class='fieldWidth50' id='title' value='' size='50' name='title'/>";
				todolistStr += "</div>";
				todolistStr += "</div>";
				todolistStr += "<div class='field'>";
				todolistStr += "<div class='fieldLabel fieldLabelWidthBig'>";
				todolistStr += "<label>Who's responsible?</label>";
				todolistStr += "</div>";
				todolistStr += "<div>";			
				todolistStr += "<select id='responsible' name='data[todos][responsible_user_id][]' class='fieldWidth3' >";
				todolistStr += selectHtml;																																	
				todolistStr += "</select>";										
				todolistStr += "</div>";
				todolistStr += "</div>";
				
				todolistStr += "<div style='display:none' class='err' id='taskErrMsg"+item.todolists.id+"'>You cannot add new tasks because this task list has just been deleted by the author</div>";
				
				todolistStr += "<div>";
				todolistStr += "<a onclick=addThisTodoCal('frm_"+item.todolists.id+"','div_opened_"+item.todolists.id+"','"+item.todolists.id+"','todoCount_"+item.todolists.id+"','todoLink_"+item.todolists.id+"','result_"+item.todolists.id+"','div_closed_"+item.todolists.id+"','"+milstoneVersion+"') class='butBlue' href='javascript:void(0);'>";
				todolistStr += "<span class='lineHit30'>Create this task</span>";
				todolistStr += "</a>";
				todolistStr += "</div>";										
				todolistStr += "</div>";	
				todolistStr += "</fieldset>";							
				todolistStr += "</div>";
				todolistStr += "</form></div>";
				
				///adding tasks ends
				
				if(todolistLen < 2)
					var dis = 'block';
				else
					var dis = 'none';
				
				todolistStr += "<div class='mGDetails' id='todosdiv"+item.todolists.id+"' style='padding-top:10px;display:"+dis+"'>";
				
				var cnt1 = 0;
				
				todoopen.each(
				function(tditem){
					if(item.todolists.id == tditem.todos.todolist_id){							
				  		var mouseEvent = 'onmouseout="javascript:$(\'todoDiv'+tditem.todos.id+'\').hide();" onmouseover="showOnMouseOver(\'todoDiv'+tditem.todos.id+'\');"';
		     			
		     			var countComm = tditem[0].countComm;
						var str = 'Comment';
						
						if(countComm > 0){
							str = countComm + ' ' + str;
							if(countComm > 1)
								str = str + 's';
						}							
				  		
				  		if(cnt1 > 0)							
						todolistStr += "<div style='border-bottom:1px solid #e9e9e9;height:8px; margin-left:10px;'>&nbsp;</div>";
						
						todolistStr += "<div class='mGDetailRow' id='todoRow"+tditem.todos.id+"' "+mouseEvent+">";
                   		todolistStr += "<div style='padding-bottom:5px;'>";
                   		todolistStr += '<div id="todoDiv'+tditem.todos.id+'" style="display:none" class="butComment rightFloat">';
                   		
                   		todolistStr += '<span id="todoComm'+tditem.todos.id+'"> <a id="todoCommNum'+tditem.todos.id+'" href="javascript:void(0);"  onclick="showCommentRHS(\''+tditem.todos.id+'\',\'TCal\')">'+str+'</a> </span>';
                   		if(loggedInUser == tditem.todos.creater_user_id){
		     				elemId = 'todoRow'+tditem.todos.id;
							func = escape("ovrDelete(\'TaskCal\',\'"+tditem.todos.id+"\',\'"+elemId+"\')");
							func = 'beforeDelete(this,event,\'TK\',\'0\',\''+func+'\')';
		     				todolistStr += '<span id="todoPipe'+tditem.todos.id+'">&nbsp;&nbsp;|&nbsp;&nbsp;</span>'
                   					  	  +'<span id="todoDel'+tditem.todos.id+'"><a href="javascript:void(0);" onclick="'+func+'">Delete</a> </span>';
		     			}
                   					  
                   		todolistStr +=	'</div>';
                   		todolistStr += "<div class='leftFloat' style='width:20px;'><input type='checkbox' name='todosChk' id='todoChk"+tditem.todos.id+"' value='2' class='borderNone' onclick='ldDispMileStone.changeStatusTodo("+tditem.todos.id+","+milstoneVersion+");' /></div>";                                                    
                     	todolistStr += "<div id='todoTitle"+tditem.todos.id+"' style='margin-left:25px;'>"+tditem.todos.title+" <span style='color:#717171;font-size:8pt;'>- "+tditem.userpersonalinfo.FullName+"</span></div>";
                   		todolistStr += "</div>";
                 		todolistStr += "</div>";
                 		
						cnt1++;	
					}
				});	
					
					
				var compTodoCnt = 0;
				todoclose.each(
				function(tditem){
					if(item.todolists.id == tditem.todos.todolist_id) compTodoCnt++;	
				});
				
				if(compTodoCnt == "1"){
					var dis1 = 'block';
				}else if(compTodoCnt > 1){
					todolistStr += "<div class='mGDetailRow' style='padding-top:15px;'><div><a href='javascript:void(0)' onclick='ldDispMileStone.toggleTodoComp("+item.todolists.id+")'>Completed tasks</a></div></div>";
					var dis1 = 'none';
				}
				
				cnt1 = 0;	
	                
	            todolistStr += "<div style='display:"+dis1+"' id='compTodo"+item.todolists.id+"'>";     
				todoclose.each(
				function(tditem){
					if(item.todolists.id == tditem.todos.todolist_id){
					
						var mouseEvent = 'onmouseout="javascript:$(\'todoDiv'+tditem.todos.id+'\').hide();" onmouseover="showOnMouseOver(\'todoDiv'+tditem.todos.id+'\');"';
		     			
		     			var countComm = tditem[0].countComm;
						var str = 'Comment';
						var todoCommStyle = 'display:none';
						if(countComm > 0){
							todoCommStyle = '';
							str = countComm + ' ' + str;
							if(countComm > 1)
								str = str + 's';
						}
						
				  		if(cnt1 > 0 || compTodoCnt == "1")							
						todolistStr += "<div style='border-bottom:1px solid #e9e9e9;height:8px; margin-left:10px;'>&nbsp;</div>";
						
						todolistStr += "<div class='mGDetailRow' id='todoRow"+tditem.todos.id+"' "+mouseEvent+">";
                   		todolistStr += "<div style='padding-bottom:5px;'>";
                   		todolistStr += '<div id="todoDiv'+tditem.todos.id+'" style="display:none" class="butComment rightFloat">';
                   		
                   		todolistStr += '<span id="todoComm'+tditem.todos.id+'" style="'+todoCommStyle+'"> <a id="todoCommNum'+tditem.todos.id+'" href="javascript:void(0);" onclick="showCommentRHS(\''+tditem.todos.id+'\',\'TCal\')">'+str+'</a> </span>';
                   		if(loggedInUser == tditem.todos.creater_user_id){
		     				elemId = 'todoRow'+tditem.todos.id;
							func = escape("ovrDelete(\'TaskCal\',\'"+tditem.todos.id+"\',\'"+elemId+"\')");
							func = 'beforeDelete(this,event,\'TK\',\'0\',\''+func+'\')';
		     				todolistStr += '<span id="todoPipe'+tditem.todos.id+'" style="'+todoCommStyle+'">&nbsp;&nbsp;|&nbsp;&nbsp;</span>'
                   					  	  +'<span id="todoDel'+tditem.todos.id+'"><a href="javascript:void(0);" onclick="'+func+'">Delete</a> </span>';
		     			}
                   					  
                   		todolistStr +=	'</div>';
                     	todolistStr += "<div class='leftFloat' style='width:20px;'><input type='checkbox' class='borderNone' name='todosChk' id='todoChk"+tditem.todos.id+"' checked='checked' value='1' onclick='ldDispMileStone.changeStatusTodo("+tditem.todos.id+","+milstoneVersion+");'/></div>";                                                    
                     	todolistStr += "<div id='todoTitle"+tditem.todos.id+"' style='margin-left:25px;' class='completed'>"+tditem.todos.title+" <span style='color:#717171;font-size:8pt;'>- "+tditem.userpersonalinfo.FullName+"</span></div>";
                   		todolistStr += "</div>";
                 		todolistStr += "</div>";

						cnt1++;	
					}
				});
								
				todolistStr += "</div>";	
				todolistStr += "</div>";			
				todolistStr += "</div>";

				cnt++;
			}
			);
			
			$('todolistNTodos').innerHTML = todolistStr;
			hideShowButtonDivWithCond('todoview');
			
			this.data.milestone.each(
			function(item){
				user_id = item.milestones.user_id;
				$('selDateEdit').innerHTML ="<strong>Due: " + item[0].targetdate + "</strong><input id='target_date_edit' type='hidden' name='data[milestones][targetdate]' value='" + item.milestones.targetdate + "' />";
				$('inputTitle').innerHTML = "<input type='text' name='data[milestones][title]' size='' value='" + item.milestones.title.replace(/'/g,"&#39") + "' id = 'editTitle' class='inp'/>"
											+"<input type='hidden' name='data[milestones][id]' value='" + item.milestones.id + "'/>";
				var autcom = '';											
				if (item.milestones.completeddate == 'NULL' || item.milestones.completeddate == '0000-00-00 00:00:00' || item.milestones.completeddate == ''){
					$('autocompleteEdit').checked = false;
					var autcom = 'off';			
				}else{
					$('autocompleteEdit').checked = true;
					var autcom = 'on';			
				}
				
				$('milInfoinput').value = item.milestones.title+'^*^'+user_id+'^*^'+item.milestones.targetdate+'^*^'+autcom;
				
			}
			);
			
			$('editLabel').innerHTML = "<select size='1' id='editSelect' name='data[milestones][user_id]' style='width:61%'>" + selectHtml + "</select>";
			
			//Calendar in edit milestone page
			addMilestoneCalendar1('edit', selDay);
			
		},
		
		toggleTodo: function (id) {
			if(document.getElementById('todosdiv'+id).style.display == 'none'){
				document.getElementById('todosdiv'+id).style.display = 'block';
				$('swap'+id).innerHTML="<img src='/images/minimize.gif' />";
				$('swap'+id).className='contract leftFloat';
			}else if(document.getElementById('todosdiv'+id).style.display == 'block'){
				document.getElementById('todosdiv'+id).style.display = 'none';
				$('swap'+id).innerHTML="<img src='/images/maximize.gif' />";
				$('swap'+id).className='expand leftFloat';
			}
		},
		
		hideCreateTodos: function(){
			$('new_todoList_popup').style.display = 'none';
			
			$('errTaskTitle').hide();
			$('todo_title').removeClassName('inpErr');
			$('errTaskUser').hide();
			$('errTasklistTitle').hide();
			$('todolist_title').removeClassName('inpErr');
			
			//Effect.Fade('new_todoList_popup',{duration: 0.5});
			//setTimeout("$('frmTodo').reset();$('new_todoList').style.display = 'none';$('new_todo').style.display = 'none';$('addtodo').innerHTML = '';$('add_result').innerHTML = '';$('addlist_result').innerHTML = '';",1000);
			$('frmTodo').reset();
			$('addtodo').innerHTML = '';
			$('Grp').style.display = 'none'; // added by subhendu
			$('task_list_desc_link').style.display = 'block'; // added by subhendu
			$('task_list_desc').style.display = 'none'; // added by subhendu
			$('addlist_result').innerHTML = '';
			$('addlist_result').style.display = 'none';	
			$('tasklist_comfirm_popup').style.display = 'none';		
			if($('new_todo'))$('new_todo').style.display = 'none';		
			$('add_result').innerHTML = '';
			$('addlist_result').innerHTML = '';
			
		},
		closewindow: function (groupId,milstoneVersion) {		
			var milData = this.milfunction.bind(this);
			var url    = '/groups/groups/getMilInfo.json';
			var pars   = 'milstoneVersion='+milstoneVersion+'&groupid='+groupId;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: milData} );
		},
		
		milfunction: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}	
			this.data=(eval('(' + originalRequest.responseText + ')'));
			var lastInserid = this.data.lastinserid;
			if(lastInserid.strip() == "" || this.data.statusid == "2")
			{
				if(this.data.statusid == "2")
					showUtlPopup('Milestone','addTL',2);
				else
					showUtlPopup('Milestone','addTL',1);					
			}
			else
			{
				var title = this.data.milTitle;
				var grpid = this.data.grpid;
				var milstoneVersion = this.data.milstoneVersion;
				var milTodoinc = this.data.milTodoinc;
				var inc = (milTodoinc > 0) ? '[' + ++milTodoinc + ']' : '';
				
				document.getElementById('milestoneNTodos').style.display = 'none';
			  	window.milestoneInfoPopupStatus=0;
			  	calenderRefresher.getAllMilestonesDataNew('overviewCalendar','+grpid+','add_result','+lastInserid+');
			  	$('new_todoList').style.display = 'block';
			  	document.getElementById('milestone_id').value =milstoneVersion;
			  	document.getElementById('milestone_name').innerHTML ='<h1>'+title+' task list</h1>';
			  	adjustDivPosition('innerContentsTskList','new_todoList_popup', 1, 22, 12, 9);
			  	ldDispMsg.getTodolevelDropdown(grpid);
			  	editMSOver.getDropdown(grpid);
			  	document.getElementById('todolist_title').value = title + inc;
			}  			
		},
		
		chkOpenBox: function (){	
			var oriVal = $('milInfoinput').value;
			oriVal = oriVal.split('^*^');
			
			if($('autocompleteEdit').checked)
				var autoCom = 'on';
			else
				var autoCom = 'off';
				
			if($('editMil').style.display != 'none' && (oriVal[0] != document.getElementById('editTitle').value || oriVal[1] != $('editSelect').options[$('editSelect').selectedIndex].value || oriVal[2] != $('target_date_edit').value || $('addtaskList').checked || oriVal[3] != autoCom)){
				var pos = findPos1('milestoneNTodos');
				document.getElementById('unsavedInfoPopup').style.top = pos[1]+20+"px";		
				document.getElementById('unsavedInfoPopup').style.left = pos[0]+pos[2]-485+"px";
				document.getElementById('unsavedInfoPopup').style.display='block';
				document.getElementById('unsavedInfoPopup').style.display = 'block';
			}else{
				document.getElementById('milestoneNTodos').style.display = 'none';	
				window.milestoneInfoPopupStatus=0;
				var milestone_version=document.getElementById('milestone_version').value;
				var prj_id=document.getElementById('prj_id').value;
				
				if($('errEditMilestoneTitleCal'))	$('errEditMilestoneTitleCal').hide();
				if($('errEditMilestoneDayCal'))	$('errEditMilestoneDayCal').hide();

				/* If no changes done in the popup	// commented by mosh
				if(parent.intCalendarEdit == "1") {  
					calenderRefresher.getAllMilestonesDataNew('overviewCalendar',prj_id,'add_result',milestone_version);
					callResource('none', 'stateless', 'selectedIDs5', false);
				}*/
			}	
		},
		
		toggleTodoDesc: function (id) {
			var ele = 'tododecs'+id;
			if(document.getElementById('tododecs'+id).style.display == 'none'){
				document.getElementById('tododecs'+id).style.display = 'block';					
			}else if(document.getElementById('tododecs'+id).style.display == 'block'){
				document.getElementById('tododecs'+id).style.display = 'none';
			}		
		},
		
		toggleTodoComp: function (id) {
			if(document.getElementById('compTodo'+id).style.display == 'none'){
				document.getElementById('compTodo'+id).style.display = 'block';	
			}else if(document.getElementById('compTodo'+id).style.display == 'block'){
				document.getElementById('compTodo'+id).style.display = 'none';
			}
		},
		
		
		changeStatusTodo: function (todoId,MilVersion) {
			
			if($('todoChk'+todoId))
				$('todoChk'+todoId).disabled = true;
				
			var todoStatus = document.getElementById('todoChk'+todoId).value;		
			var todochangedStutus = this.todochangedStutus.bind(this);
			var url    = '/groups/groups/changeStatusTodoFn.json';
			var pars   = 'todo_id='+todoId+'&status='+todoStatus+'&MilVersion='+MilVersion;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: todochangedStutus} );		
			parent.intCalendarEdit = 1;		
		},
	
	    strikeAll: function (MilVersion) {
	    	if($('milChk'+MilVersion))
				$('milChk'+MilVersion).disabled = true;
	    	parent.intCalendarEdit=1;
			if(document.getElementById('milChk'+MilVersion).checked)
				var flag = "2";
			else
				var flag = "1";

	        var strikeOut = this.strikeOut.bind(this);		
			var url = '/groups/tasks/changeTodoStatusAll.json';
			var pars = 'sessUID='+$('SessStrUserID').value+'&MilVersion='+MilVersion+'&flag='+flag+'&isCal=1';
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: strikeOut} );	
	    },
	
	    strikeOut: function (originalRequest) {	    
			var retdata = originalRequest.responseText;
			//alert(retdata);
			var retdata = retdata.split('^^');
			
			if(retdata.length > 2)
			{
				var milVer = retdata[0];
				var finaldiff = retdata[2];
				var milStatus = retdata[3];
		
				if($('milChk'+milVer))
					$('milChk'+milVer).disabled = false;
					
				if(milStatus == '2') {
					if(retdata[11]) {
						var r = retdata[11].split('-');
						for(var i=0; i<r.length; i++){
							$('addTask'+r[i]).hide();
							$('TodoListPipe'+r[i]).hide();
						}
					}
					var groupid = retdata[7];
				    var recievedDate = retdata[8];
				    var startDate = retdata[9];
				    var completedDate = retdata[10];
			    } else {
			    	if(retdata[8]) {
						var r = retdata[8].split('-');
						for(var i=0; i<r.length; i++){
							$('addTask'+r[i]).show();
							if($('TodoListDelLink'+r[i]))
								$('TodoListPipe'+r[i]).show();	
						}	
					}
			    	//if($('addTask'+retdata[8])) $('addTask'+retdata[8]).show();
			    	var groupid = retdata[4];
				    var recievedDate = retdata[5];
				    var startDate = retdata[6];
				    var completedDate = retdata[7];
			    }
			      
				var cName = '';
				if(milStatus == '2'){
					cName = 'complete';
					$('milestoneEditLink').style.display= 'none';
					if($('milestoneCommNum').innerHTML == 'Comment'){
						$('milestoneCommLink').hide();
						$('milestonePipe').hide();
					}
					$('addNewtaskLink').style.display ='none';
					$('spaceBar').className = '';				
				}else if(finaldiff < 0){
					cName = 'delayed';
					$('milestoneEditLink').show();
					$('milestoneCommLink').show();
					if($('milestoneDelLink').style.display != 'none')
						$('milestonePipe').show();
					$('addNewtaskLink').style.display ='block';
					$('spaceBar').className = 'fieldSpacer1';
				}else{
					cName = '';
					$('milestoneEditLink').show();
					$('milestoneCommLink').show();
					if($('milestoneDelLink').style.display != 'none')
						$('milestonePipe').show();
					$('addNewtaskLink').style.display ='block';
					$('spaceBar').className = 'fieldSpacer1';
				}
			 	$('MStoneClassDecider').className = cName;	
				
				if(retdata[1] != ""){ 
					chkdata = retdata[1].split(',');
					
					for(var i = 0;i<chkdata.length;i++){
						//$('todoRow'+chkdata[i]).className ='mGDetailRowChecked';
						$('todoTitle'+chkdata[i]).className ='completed';
						$('todoChk'+chkdata[i]).checked = 'true';
						$('todoChk'+chkdata[i]).value = '1';
						if($('todoCommNum'+chkdata[i]).innerHTML == 'Comment'){
							$('todoComm'+chkdata[i]).hide();
							if($('todoPipe'+chkdata[i]))
								$('todoPipe'+chkdata[i]).hide();
						}
					}
				}
				hideShowButtonDivWithCond('todoview');
			//alert(retdata[0]+'=='+retdata[2]+'=='+retdata[3]+'=='+retdata[4]+'=='+retdata[5]+'=='+retdata[6]);
				/* TO send mails*/
				var url    = '/groups/tasks/sendTodoMails.json';
				var pars = '';				
				if(milStatus == '2')
					pars = 'sessUID='+$('SessStrUserID').value+'&milVer='+milVer+'&milStatus='+milStatus+'&crArr='+retdata[4]+'&asArr='+retdata[5]+'&tdArr='+retdata[6];
				else
					pars = 'sessUID='+$('SessStrUserID').value+'&milVer='+milVer+'&milStatus='+milStatus;		
		
					var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars});
					
				
				this.manipulateCachedData(milStatus, finaldiff, milVer, groupid, startDate, recievedDate, completedDate);
	    	}
	    	else
	    	{
	    		var evt = "close",
			    stat = 1;
			
				if(retdata['1'] == "1")
					evt = "reopen";
					
				if(retdata['0'] == "comp")
					stat = 2;
						
				showUtlPopup('Milestone',evt,stat);
	    	}	
			
		},
		
		
		manipulateCachedData : function(milStatus, finaldiff, milVer, groupid, startDate, recievedDate, completedDate) {
			
			/*	Below code is used to manipulate chached calender data	*/
		      
		      if(milStatus == '2') { 
			      var tmpClass1 = /delayed|upcoming/;
			      var tmpClass2 = 'complete'; 
		      } else {
			      var tmpClass1 = 'complete'; 
			      if(finaldiff < 0) var tmpClass2 = 'delayed';
			      else var tmpClass2 = 'upcoming'; 
		      }
		      
			//// By clicking more than twice on 'complete/reopen this milestone' checkbox ////
			
			if(!$('datadiv' + milVer)) { //alert('final diff : '+finaldiff+' :: '+milStatus);

				if(milStatus == '2') { // for green (completed)
				
					// remove entry from other page
					var key = parent.CCDKSD[startDate];
					
					if(typeof key != "undefined") {
						parent.CCD[key][0] = htmlspecialchars_decode(parent.CCD[key][0]);
					    parent.CCD[key][0] = parent.CCD[key][0].replace(parent.CCDTMP, '');
					    parent.CCD[key][0] = htmlspecialchars(parent.CCD[key][0]);
					}
					
					if(finaldiff > 0) parent.CCDTMP = parent.CCDTMP.replace('class="upcoming', 'class="complete');
					else parent.CCDTMP = parent.CCDTMP.replace('class="delayed', 'class="complete');

					// place the div on todays page
					var searchString = 'ds'+recievedDate+'">';
					var replaceString = searchString + parent.CCDTMP;
					parent.CCD[0][0] = htmlspecialchars_decode(parent.CCD[0][0]);
					parent.CCD[0][0] = parent.CCD[0][0].replace(searchString, replaceString);
					parent.CCD[0][0] = htmlspecialchars(parent.CCD[0][0]);

					parent.CCDTMP = '';
					$('calenderInner').innerHTML = htmlspecialchars_decode(parent.CCD[parent.CCDK][0]);
					parent.CCD[key][0] = htmlspecialchars(parent.CCD[key][0]);

									
				} else { // for blue(upcoming) and red(overdue)
				
					// remove the entry from todays div
					parent.CCD[0][0] = htmlspecialchars_decode(parent.CCD[0][0]);
					parent.CCD[0][0] = parent.CCD[0][0].replace(parent.CCDTMP, '');
					parent.CCD[0][0] = htmlspecialchars(parent.CCD[0][0]);
					
					if(finaldiff > 0) parent.CCDTMP = parent.CCDTMP.replace('class="complete', 'class="upcoming');
					else parent.CCDTMP = parent.CCDTMP.replace('class="complete', 'class="delayed');
					
					// place the div on that page
					var searchString = 'ds'+recievedDate+'">';
					var replaceString = searchString + parent.CCDTMP;
					var key = parent.CCDKSD[startDate];
					
					if(typeof key != "undefined") {
						parent.CCD[key][0] = htmlspecialchars_decode(parent.CCD[key][0]);
					
					    if(parent.CCD[key][0].match(searchString)) {
						    parent.CCD[key][0] = parent.CCD[key][0].replace(searchString, replaceString);
					    }
					}
					
					parent.CCDTMP = '';
					$('calenderInner').innerHTML = htmlspecialchars_decode(parent.CCD[parent.CCDK][0]);
					parent.CCD[key][0] = htmlspecialchars(parent.CCD[key][0]);					
				}

				return false;
			}
			
//alert($('milestoneGroupIdDiv_'+ groupid +'_' + milVer));	      
		      if($('milestoneGroupIdDiv_'+ groupid +'_' + milVer)) {
		      
				      var child = $('datadiv' + milVer);
				      var childP = $('milestoneGroupIdDiv_'+ groupid +'_' + milVer);
//alert('milestoneGroupIdDiv_'+ groupid +'_' + milVer +' -- '+ $('milestoneGroupIdDiv_'+ groupid +'_' + milVer));
		
				      // If first entry in todays box and today box is always on landing page
				      if($('ds' + recievedDate)) {
				      
					      var s = $('ds' + recievedDate).childElements();
					      if(s[0]) {
						      if(s[0].className == 'empty first') {
							      s[0].remove();
						      }
					      }
//alert('1 ' + milStatus);
		
							var s = $('ds' + recievedDate).childElements();
					    	var tmpClass = child.className.replace(tmpClass1, tmpClass2);
					    	$('datadiv' + milVer).className = tmpClass; 
					      
					      	if(s[0]) Element.insert( s[0], {'before' : childP} );
					      	else $('ds' + recievedDate).appendChild(childP);
		
					      
				      } else {

//alert('2 ' + milStatus);			
						var tmpClass = child.className.replace(tmpClass1, tmpClass2);
						var tmp = '<div id="milestoneGroupIdDiv_'+ groupid +'_'+ milVer +'" style="display: block;" class="filterMil"><div style="display: block;" class="'+tmpClass+'" id="datadiv'+milVer+'" onclick="javascript:ldDispMileStone.getMileStoneData(0,'+milVer+'); window.levelDropDownStatus=0; window.milestoneInfoPopupStatus=1;">'+child.innerHTML+'</div></div>';
						parent.CCDTMP = tmp;
											      
					      var searchString = 'ds'+recievedDate+'">';
					      var replaceString = searchString + tmp;
		
					      if(milStatus == '2') var key = 0;
					      else var key = parent.CCDKSD[startDate];
		//alert(key);
					      if(typeof key != "undefined") {
						      parent.CCD[key][0] = htmlspecialchars_decode(parent.CCD[key][0]);
		
						      if(parent.CCD[key][0].match(searchString)) {
							      parent.CCD[key][0] = parent.CCD[key][0].replace(searchString, replaceString);
						      }
											      
						      parent.CCD[key][0] = htmlspecialchars(parent.CCD[key][0]);
					      }
					      
					      $(childP).remove();	// bug - when user click to check box twice
				      }
		
				      parent.CCD[parent.CCDK][0] = $('calenderInner').innerHTML;
		      
		      
		      
		      } else {
		      
					//alert(parent.CCDKSD[startDate] +' :: '+ startDate);
					//var key = parent.CCDKSD[startDate];alert(typeof parent.CCD[key].length);
					//if(key) parent.CCD[key] = [];
					//if(key) parent.CCD.splice(parent.CCD[key], 1);
		      		//alert(parent.CCD[key].length);

					getNewCCD(startDate, completedDate, groupid);
			}
			
		},
		
		todochangedStutus: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}	
			var retdata = originalRequest.responseText;
			retdata = retdata.split('^^');
			
			if(retdata.length > 2)
			{
				var todoId = retdata[0].strip();
				var groupId = retdata[4].strip();
				
				if($('todoChk'+todoId))
					$('todoChk'+todoId).disabled = false;
				
				//document.getElementById('todoChk'+todoId).setAttribute("value","2");
				if(document.getElementById('todoChk'+todoId).value == "1"){            
					if(parent.$('calendarContainer').style.display != 'none')
					{
						document.getElementById('todoChk'+todoId).value = '2';
						//document.getElementById('todoRow'+todoId).className = 'mGDetailRow';						
						$('todoComm'+todoId).show();
						if($('todoDel'+todoId))
							$('todoPipe'+todoId).show();	
					}					
					$('todoTitle'+todoId).className ='';
				}else{
					if(parent.$('calendarContainer').style.display != 'none')
					{
						document.getElementById('todoChk'+todoId).value = '1';
						document.getElementById('todoChk'+todoId).setAttribute("checked","checked");
						//document.getElementById('todoRow'+todoId).className = 'mGDetailRowChecked';						
						if($('todoCommNum'+todoId).innerHTML == 'Comment'){
							$('todoComm'+todoId).hide();
							if($('todoPipe'+todoId))
								$('todoPipe'+todoId).hide();
						}	
					}
					$('todoTitle'+todoId).className ='completed';							
				}
				
				if(parent.$('calendarContainer').style.display != 'none')
				{
					var cName = '';
					if(retdata[1].strip() == '2'){
						cName = 'complete';
						$('addNewtaskLink').style.display ='none';
						$('spaceBar').className = '';
						var chk = true;
						$('milestoneEditLink').hide(); // hide edit milestone link
						if($('milestoneCommNum').innerHTML == 'Comment'){
							$('milestoneCommLink').hide();
							$('milestonePipe').hide();
						}
						//$('addTask'+retdata[5]).hide(); // hide add task link
						if(retdata[5]) {
							var r = retdata[5].split('-');
							for(var i=0; i<r.length; i++){
								if($('addTask'+r[i]))
									$('addTask'+r[i]).hide();
								if($('TodoListPipe'+r[i]))
									$('TodoListPipe'+r[i]).hide();	
							}
						}
					}else if(retdata[2].strip() < 0){
						cName = 'delayed';
						$('addNewtaskLink').style.display ='block';
						$('spaceBar').className = 'fieldSpacer1';
						var chk = false;
						$('milestoneEditLink').show(); // show edit milestone link
						$('milestoneCommLink').show();
						if($('milestoneDelLink').style.display != 'none')
							$('milestonePipe').show();
						//$('addTask'+retdata[5]).show(); // show add task link
						
						if(retdata[5]) {
							var r = retdata[5].split('-');
							for(var i=0; i<r.length; i++){
								if($('addTask'+r[i]))$('addTask'+r[i]).show();
								if($('TodoListDelLink'+r[i]) && $('TodoListPipe'+r[i]))
									$('TodoListPipe'+r[i]).show();	
							}	
						}
						
					}else{
						
						cName = '';
						$('addNewtaskLink').style.display ='block';
						$('spaceBar').className = 'fieldSpacer1';
						var chk = false;
						$('milestoneEditLink').show(); // show edit milestone link
						$('milestoneCommLink').show();
						if($('milestoneDelLink').style.display != 'none')
							$('milestonePipe').show();
						//$('addTask'+retdata[5]).show(); // show add task link
						if(retdata[5]) {
							var r = retdata[5].split('-');
							for(var i=0; i<r.length; i++){
								if($('addTask'+r[i]))$('addTask'+r[i]).show();
								if($('TodoListDelLink'+r[i]) && $('TodoListPipe'+r[i]))
									$('TodoListPipe'+r[i]).show();
							}	
						}
					}
					
					
					if($('editMil').style.display != 'none'){
						$('spaceBar').className = '';
					}
					else {
						hideShowButtonDivWithCond('todoview');
					}	
					
					$('milChk'+retdata[3].strip()).checked = chk;	
					$('MStoneClassDecider').className = cName;	
					
					//this.manipulateCachedData(milStatus, finaldiff, milVer, groupId, startDate, recievedDate, completedDate);
					this.manipulateCachedData(retdata[1], retdata[2], retdata[3], groupId, retdata[7], retdata[6], retdata[8]);
					
					var milVer = retdata[3].strip();
				}
				
				/*sending mails for closed/opened milestones*/
				if(retdata[1])
				{
					sendMail('Milestone',groupId,'changeStatus',milVer);
				}
				
				/*sending mails for changed todos */
				if(todoId != "" && groupId != "")
				{					
					var rand   = Math.random(99999); 
					var url    = '/groups/tasks/sendMail4ChangeTodoStatus.json';						
					var pars   = 'rand=' + rand + '&tdID=' + todoId + '&groupid=' + groupId + '&type=todo';
					var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars} );
				}
			}
			else
			{
				var evt = "close",
			    stat = 1;
			
				if(retdata['1'] == "1")
					evt = "reopen";
					
				if(retdata['0'] == "comp")
					stat = 2;
						
				showUtlPopup('Task',evt,stat);
			}	
			
		},
		
		addGetTodoData: function (title,id,divid,listid,cls_div,milVer) { 
			//$(divid).innerHTML += "<div id='loadingProgress'><img src='/groups/img/loading.gif' class='loadingImgLeft' border='0' /></div>";
			var showNewData = this.showNewData.bind(this);
			title = escape(title);
			var rand   = Math.random(99999);
			this.divid = divid;
			this.dividcls = cls_div;
			var url    = '/groups/groups/addTodoCal.json';
			var userID = $('SessStrUserID').value;
			var pars   = 'rand=' + rand + '&title=' + title+'&id='+id+'&listid='+listid+'&sessUID='+userID+'&milVer='+milVer; 
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onSuccess: showNewData} );
		},
		
		showNewData: function (originalRequest) {	
			if(originalRequest.responseText == "expired"){      
	        	window.location = MainUrl + "main.php?u=signout";return;
	        }else{
			    this.data=(eval('(' + originalRequest.responseText + ')'));
			    
			    if(this.data.isdelete)
				{
					var tskListId = this.data.tasklistid;
					if(this.data.reason == "2")
						$('taskErrMsg'+tskListId).innerHTML = "You cannot add new tasks because the milestone attached to this task list is just marked as complete.";
					else
						$('taskErrMsg'+tskListId).innerHTML = "You cannot add new tasks because this task list has just been deleted by the author.";
							
					
					$('taskErrMsg'+tskListId).show();
				}
				else
				{
				    var milstoneVersion = this.data.milstoneVersion;
				    var milestoneReopen=this.data.milestoneReopen;
				    var loggedInUser = this.data.loggedInUser;
				    
				    if(milestoneReopen=='1'){
				    	$('MStoneClassDecider').className = '';
				    	$('milChk'+milstoneVersion).checked=false;
				    	$('milestoneEditLink').style.display='block';
				    	$('milestoneCommLink').show();
						if($('milestoneDelLink').style.display != 'none')
							$('milestonePipe').show();
				    }
				    
				    var tasklistId = this.data.tasklistId;    	
				    var dataObj = this.data.lastTodo;			
					dataObj.each(
					function(item){			
							var mouseEvent = 'onmouseout="javascript:$(\'todoDiv'+item.todos.id+'\').hide();" onmouseover="showOnMouseOver(\'todoDiv'+item.todos.id+'\');"';
			     			
							todolistStr = '';						
							todolistStr += '<div class="mGDetailRow" id="todoRow'+item.todos.id+'" '+mouseEvent+'>';
			           		todolistStr += '<div style="padding-bottom:5px;">';
			           		todolistStr += '<div id="todoDiv'+item.todos.id+'" style="display:none" class="butComment rightFloat">';
	                   		
	                   		todolistStr += '<span id="todoComm'+item.todos.id+'"> <a id="todoCommNum'+item.todos.id+'" href="javascript:void(0);" onclick="showCommentRHS(\''+item.todos.id+'\',\'TCal\')">Comment</a> </span>';
	                   		if(loggedInUser == item.todos.creater_user_id){
			     				elemId = 'todoRow'+item.todos.id;
								func = escape("ovrDelete(\'TaskCal\',\'"+item.todos.id+"\',\'"+elemId+"\')");
								func = 'beforeDelete(this,event,\'TK\',\'0\',\''+func+'\')';
			     				todolistStr += '<span id="todoPipe'+item.todos.id+'">&nbsp;&nbsp;|&nbsp;&nbsp;</span>'
	                   					  	  +'<span id="todoDel'+item.todos.id+'"><a href="javascript:void(0);" onclick="'+func+'">Delete</a> </span>';
			     			}
	                   					  
	                   		todolistStr +=	'</div>';
			             	todolistStr += "<div class='leftFloat' style='width:20px;'><input type='checkbox' name='todosChk' class='borderNone' id='todoChk"+item.todos.id+"' value='2' onclick='ldDispMileStone.changeStatusTodo("+item.todos.id+","+milstoneVersion+");' /></div>";                                                    
			             	todolistStr += "<div id='todoTitle"+item.todos.id+"' style='margin-left:25px;'>"+item.todos.title+" <span style='color:#717171;font-size:8pt;'>- "+item.userpersonalinfo.FullName+"</span></div>";
			           		todolistStr += "</div>";
			         		todolistStr += "</div>";
			         		todolistStr += "<div style='border-bottom:1px solid #e9e9e9;height:8px; margin-left:10px;'>&nbsp;</div>";			
					} 
					);
					
					$('todosdiv'+tasklistId).style.display = 'block';
					//$('swap'+tasklistId).innerHTML = '-';
					$('swap'+tasklistId).className = $('swap'+tasklistId).className.replace('expand', 'contract');
					$('swap'+tasklistId).firstChild.src = '/images/minimize.gif';
					
					$('todosdiv'+tasklistId).innerHTML = todolistStr + $('todosdiv'+tasklistId).innerHTML; 
					hideShowButtonDivWithCond('todoview');
					
					if(this.data.isMail == "yes"){
						var group_id = this.data.group_id;
						var todoId = this.data.lastInsertId;
						var type = 'todo';
						var rand   = Math.random(99999);
						var url    = '/groups/tasks/sendMail4ChangeTodoStatus.json';						
						var pars   = 'rand=' + rand + '&tdID=' + todoId + '&groupid=' + group_id + '&type='+type;
						var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars} );
					}
	        	}			        	
	        }	
	
		},
			
		getMilestoneFn: function (originalRequest) {
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			this.data=(eval('(' + originalRequest.responseText + ')'));
			
			if(this.data.isDelete)
			{
				showUtlPopup('Milestone','view',1);				
			}
			else
			{			
				this.fillHolder();		
				var leftCoordinate = (document.getElementById("calendarLayer").offsetWidth - 725 - 24)/2;
				
				var maxHeight = document.getElementById("calendarLayer").offsetHeight - (0.1*document.getElementById("calendarLayer").offsetHeight) - 245;
		
				if(this.data.todolist.length == "0"){
					var topCoordinate = document.getElementById("calendarLayer").offsetTop + (document.getElementById("calendarLayer").offsetHeight - 300)/2;
				}else{
					var topCoordinate = document.getElementById("calendarLayer").offsetTop + (0.05*document.getElementById("calendarLayer").offsetHeight);
				}
				
				$('todolistNTodos').style.maxHeight =maxHeight+"px";
				$('milestoneNTodos').style.left=leftCoordinate+"px";
				$('milestoneNTodos').style.top=topCoordinate+"px";
				$('milestoneNTodos').style.width = '725px';
				
				document.getElementById('editMilTitle').style.display = 'none';
				document.getElementById('editMil').style.display = 'none';
				document.getElementById('milTitle').style.display = 'block';
				document.getElementById('todoview').style.width = '100%';
				document.getElementById('todoview').style.background = '';			
				document.getElementById('todoviewinner').style.padding = '10px';
			}		
		}
	};

	function getNewCCD(startDate, completedDate, groupid) {

		groupid = 'overviewCalendar';

		if(startDate != '') {
			var startKey = parent.CCDKSD[startDate.strip()]; //alert(typeof startDate +' :: '+ startKey);
			if(typeof startKey != "undefined") {
				var url    = "/groups/groups/calenderOverview.json";
				var pars   = 'startDate='+startDate+'&groupId='+groupid+'&milOrOver=overview';
				var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: function(originalRequest) { parent.CCD[startKey][0] = htmlspecialchars(originalRequest.responseText); }} );
			}
		}
		
		if(completedDate != '' && startDate != completedDate) {
			var completeKey = parent.CCDKSD[completedDate]; //alert(completedDate +' :: '+ completeKey);
			if(typeof completeKey != "undefined") {
				var url    = "/groups/groups/calenderOverview.json";
				var pars   = 'startDate='+completedDate+'&groupId='+groupid+'&milOrOver=overview';
				var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: function(originalRequest) { parent.CCD[completeKey][0] = htmlspecialchars(originalRequest.responseText); }} );
			}
		}
	}

	/*	get milestone data through comet	*/
	function getCCDVF(tmp) {
		if(tmp) var data = tmp;
		else var data = parent.getCCDV;

		var checkDUP = new Array();
		if(data) {	//alert(data.length +' :: '+ data.toSource());
			for(var i=0; i<data.length; i++) {	//alert(data[i]['startDate'] +' :: '+ typeof data[i]);
				if(data[i].startDate != '0' && !in_array(data[i].startDate, checkDUP)) {	//alert(data[i].startDate);
					checkDUP.push(data[i].startDate);
					getNewCCD(data[i].startDate, '', data[i].group_id);
				}
				
				if(data[i].cmpDate != '0' && !in_array(data[i].cmpDate, checkDUP)) {	//alert(data[i].cmpDate);
					checkDUP.push(data[i].cmpDate);
					getNewCCD(data[i].cmpDate, '', data[i].group_id);
				}
			}
		}
		parent.getCCDV.remove(0, parent.getCCDV.length-1);
	}

	function addMilestoneCalendar1(addedit, selDay){
		  function dateChanged(calendar){		
		    if (calendar.dateClicked){	    	
		    		$('selDateEdit').innerHTML = "<strong>Due: "+ calendar.date.print('%B %d, %Y') +"</strong><input id='target_date_edit' type='hidden' name='data[milestones][targetdate]' value='" + calendar.date.print('%Y-%m-%d')+ " 00:00:00" + "' />";	    
		    }
		  };
	 
     	 dateTest = new Date(document.getElementById('today_date').value*1000);
	 
		 $('calendar-milestone-edit').innerHTML = '';
	  	 Calendar.setup({
	  		flat         : "calendar-milestone-edit",
	  		flatCallback : dateChanged,
            date         : dateTest,
	  		dateStatusFunc : function(date, y, m, d) {
	                         if (selDay == m+1+"-"+d+"-"+y) return "special";
	                         else return false; // other dates are enabled
	                         // return true if you want to disable other dates
	                       }
	  	 }
	  	 );
  	}
	  function taskListCloseConfirm(){
		if($('commonDropDOwn7'))$('commonDropDOwn7').hide();
		if(document.getElementById('todolist_title').value != '' || document.getElementById('todolist_desc').value != '' || document.getElementById('todo_title').value != '' || document.getElementById('Grp').style.display == 'block')
		{   var pos = findPos1('new_todoList_popup');
			document.getElementById('tasklist_comfirm_popup').style.top = pos[1]+20+"px";		
			document.getElementById('tasklist_comfirm_popup').style.left = pos[0]+pos[2]-485+"px";
			document.getElementById('tasklist_comfirm_popup').style.display='block';	
		}
		else
		{	$('tasklist_comfirm_popup').style.display='none';
			if($('loading_img')) $('loading_img').style.display ='none';//by subhendu
			$('new_todoList_popup').style.display='none';
			$('task_list_desc_link').style.display = 'block';
			$('task_list_desc').style.display = 'none';
		}
	 }
  	function closeaddtaskbox(tasklistid){
	  	document.getElementById('addTododBox'+tasklistid).style.display='none';
	  	document.getElementById('result_'+tasklistid).innerHTML = '';
	  	document.getElementById('title').value='';  
  	}
  
 	function validateEditCal(frm,proj_id){
	  	var newDate = document.getElementById('target_date_edit').value;
	  	newDate = newDate.replace(/-/g,'/');
	  	var errOccured;
	
	  	var curDate = document.getElementById('today_edit').value;
	  	curDate = curDate.replace(/-/g,'/');
	
	  	var myDay = new Date(newDate);
	  	var today = new Date(curDate);
	
		var tempTitle = document.getElementById('editTitle').value;
	  	if (tempTitle.strip() == ''){
	  		//$('edit_result').style.display = '';
	  		//$('edit_result').innerHTML = "Please name your Milestone";
	  		$('errEditMilestoneTitleCal').show();
	  		$('editTitle').addClassName('inpErr');
	  		//return false;
	  		errOccured = 1;
	  	}else{
	  		//$('edit_result').style.display = 'none';
	  		//$('edit_result').innerHTML = "";
	  		$('errEditMilestoneTitleCal').hide();
	  		$('editTitle').removeClassName('inpErr');
	  	}
	  	if (today > myDay){
	  		//$('edit_result').style.display = '';
	  		//$('edit_result').innerHTML = "Please select a day in the future";
	  		$('errEditMilestoneDayCal').show();
	  		$('milestoneCalendar').addClassName('inpErr');
	  		//return false;
	  		errOccured = 1;
	  	}else{
	  		//$('edit_result').style.display = 'none';
	  		//$('edit_result').innerHTML = "";
	  		$('errEditMilestoneDayCal').hide();
	  		$('milestoneCalendar').removeClassName('inpErr');
	  	}
	  	
	  	if (errOccured == 1) return false;
	  	
	  	var editMilID = $('editMilID').value;
	  	//$('milestoneGroupIdDiv_'+ proj_id +'_'+ editMilID).remove();
		if($('datadiv'+ editMilID)) $('datadiv'+ editMilID).parentNode.remove();
	  	
	  	if (!milestoneSubmitted){
	  		parent.intCalendarEdit=1;
			milestoneSubmitted = true;
			if (micoxUpload(frm,'/groups/groups/submitEditMilCal/'+proj_id,'edit_loading_img','<img src=/groups/img/loading.gif class=loadingImgLeft>','Error',proj_id) == false) {
				milestoneSubmitted = false;
			}
		}
	  	//return milestoneSubmitted;
    }

	function afterSubmitEditMilCal(project_id, mileVersion, title) {
		document.getElementById('milestoneNTodos').style.display = 'none';
		window.milestoneInfoPopupStatus=0;
		document.getElementById('milestone_id').value = mileVersion;
		document.getElementById('milestone_name').innerHTML ='<h1>'+ stripslashes(title) +' task list</h1>';
		adjustDivPosition('innerContentsTskList','new_todoList_popup', 1, 22, 12, 9);
		ldDispMsg.getTodolevelDropdown(project_id);
		editMSOver.getDropdown(project_id);
		document.getElementById('todolist_title').value = stripslashes(title);
	}
	
	function afterSubmitEditMilInner(project_id, mileVersion, title) {
		$('add_milestone_with_calender').hide();
		$('edit_milestone').hide();
		$('milestone_id').value = mileVersion;
		$('milestone_name').innerHTML ='<h1>'+ stripslashes(title) +' task list</h1>';
		$('todols_grpId').value = project_id;
		editMSOver.getDropdown(project_id);
		$('todolist_title').value = stripslashes(title) + " [" + (parseInt($('todoinc_'+mileVersion).value) + 1) + "]";
		
		$('innerContentsTskList').style.height = '320px';
		$('new_todoList_popup').style.width = '60%';
		centerPos($('new_todoList_popup'), 1);
		$("new_todoList_popup").style.display ='block';	
	}

    function addThisTodoCal(name,div,listid,div1,div2,result,cls_div,milVer) {		
		$(result).innerHTML = '';
		var title = '';
		var resp_id = '';
		var allNodes = $(name);
		
		for(i = 0; i < allNodes.length; i++){			
	    	if (allNodes[i].name == 'title'){
				title = allNodes[i].value;
	    	}else if (allNodes[i].type == 'select-one'){
	    		resp_id = allNodes[i].value;
	    	}
		}
		
		$(result).innerHTML = '';
		if (title.strip() == ''){
			$(result).innerHTML = '<div class="err">Please name your Task</div>';
			return false;
		}
		
		parent.intCalendarEdit=1;
		ldDispMileStone.addGetTodoData(title,resp_id,div,listid,cls_div,milVer);
		
		for(i = 0; i < allNodes.length; i++){
	    	if (allNodes[i].name == 'title'){
				allNodes[i].value = '';
	    	}else if (allNodes[i].type == 'select-one'){
	    		allNodes[i].selectedIndex = 0;
	    	}			
		}			
	}

  	function displayCalendar(){
		function dateIsSpecial(year, month, day) {
			var m = SPECIAL_DAYS[month];
			if (!m) return false;
		    var cnt = 0;
		    for (var i=0; i<m.length;i++){
		    	cnt += 1;
		    	if (m[i] == day+'-'+year){
		    		return true;
		    	}
		    }
		    return false;
	  	};
	    dateTest = new Date(document.getElementById('today_date').value*1000);
		  $('calendar-container').innerHTML = '';
		  Calendar.setup(
		    {
		      flat         : "calendar-container", // ID of the parent element
	          date         : dateTest,
		      //flatCallback : dateChanged,       // our callback function
		      dateStatusFunc : function(date, y, m, d) {
		                         if (dateIsSpecial(y, m+1, d)) return "special";
		                         else return false; // other dates are enabled
		                         // return true if you want to disable other dates
		                       }
		    }
		);
    }
  
    function validateAddMilestoneCalenderOverview(frm,proj_id){  
	  	
	  	if($('calendar-milestone-add')) {
	  		var newDate = document.getElementById('target_date_add').value;
		  	newDate = newDate.replace(/-/g,'/');
		
		  	var curDate = document.getElementById('today_add_with_calender').value;
		  	curDate = curDate.replace(/-/g,'/');
		  	
	  	} else {
		  	var newDate = document.getElementById('target_date').value;
		  	newDate = newDate.replace(/-/g,'/');
		
		  	var curDate = document.getElementById('today_add').value;
		  	curDate = curDate.replace(/-/g,'/');
		}
	
	  	var myDay = new Date(newDate);
	  	var today = new Date(curDate);
		var tempTitle = document.getElementById('addTitle').value;
		var errOccured;
		
	  	if (tempTitle.strip() == ''){
	  		//$('errorMsgTitleMilestone').style.display = '';
			//$('errorMsgTitleMilestone').innerHTML = "Please name your Milestone";
			$('errMilestoneTitleCal').show();
			$('addTitle').addClassName('inpErr');
	  		//return false;
	  		errOccured = 1;
	  	}else{
	  		//$('errorMsgTitleMilestone').style.display = 'none';
	  		//$('errorMsgTitleMilestone').innerHTML = "";
	  		$('errMilestoneTitleCal').hide();
			$('addTitle').removeClassName('inpErr');
	  	}
	  	if ($('addSelectCM').value == '0'){
	  		//$('errorMsgTitleMilestone').style.display = '';
			//$('errorMsgTitleMilestone').innerHTML = "Please select a user";
			$('errMilestoneUserCal').show();
			$('addSelectCM').addClassName('inpErr');
	  		//return false;
	  		errOccured = 1;
	  	}else{
	  		//$('errorMsgTitleMilestone').style.display = 'none';
	  		//$('errorMsgTitleMilestone').innerHTML = "";
	  		$('errMilestoneUserCal').hide();
			$('addSelectCM').removeClassName('inpErr');
	  	}
	  	if (today > myDay){
	  		$('errorMsgTitleMilestone').style.display = '';
	  		$('errorMsgTitleMilestone').innerHTML = "Please select a day in the future";
	  		return false;
	  	}else{
	  		$('errorMsgTitleMilestone').style.display = 'none';
	  		$('errorMsgTitleMilestone').innerHTML = "";
	  	}
	  	
	  	if(errOccured == 1) return false;
		
		if (!milestoneSubmitted) {
			parent.intCalendarEdit=1;
			milestoneSubmitted = true;
			$('tmpTaskListName').value = tempTitle;
			if (micoxUpload(frm,'/groups/milestones/submitAddCal/'+proj_id,'add_loading_img','<img src=/groups/img/loading.gif class=loadingImgLeft>','Error',proj_id) == false) {
				milestoneSubmitted = false;
			}
		}
	  	return false;
    }
   
    function showAddProfileForCalender(targetdate,displaydate){
	  	if($('milAddSuccess')){
		  	$('milAddSuccess').className = '';
			$('milAddSuccess').innerHTML = '';
		}
		 $('selectedVal3').value = $('selectedValdropdownCal').value;
	    
	    if($('selectedValdropdownCal').value == "ALL GROUPS"){//proj2Gr
	    	$('selectedVal3').value = firstgrpName;
	    }
	    var defID = $('selectedIDsdropdownCal').value;
	    if($('selectedIDsdropdownCal').value == "overviewCalendar")
	   	{	
	    	defID =  firstgrpId; //firstgrpId is set in calendatWithDropDown.ctp
	    }
	    $('selectedIDs3').value= defID;
	    var editMSOver=new setUserData;    
	    editMSOver.getDropdown(defID);
	    $('completed_date_add').value='0000-00-00';
	    
	    //$('calendar-milestone-popup').hide();
	  	var element = $('add_milestone');
	    $('target_date').value=targetdate;
	   
	    $('milestone_due_date').innerHTML="<h1>Milestone - "+displaydate+"</h1>";
	  	$('add_result').innerHTML = "";
	  	$('addTitle').value = '';
	  	$('isAddTaskList').checked = false;
	  	$('addMilestoneAutocomplete').checked = false;
    	
    }
  
  	function showAddProfileForResource(targetdate, which, which_id, whom){ //alert(targetdate +' - '+ which +' - '+ which_id +' - '+ whom);
	  	
	  	if($('milAddSuccess')){
		  	$('milAddSuccess').className = '';
			$('milAddSuccess').innerHTML = '';
		}

	    $('selectedVal3').value = which;
	    $('selectedIDs3').value = which_id;
	    var editMSOver=new setUserData;    
	    
	    editMSOver.tempID = whom;
	    editMSOver.getDropdown(which_id);
    }
  
	var setUserDataForTodo = Class.create();
	setUserDataForTodo.prototype = {
		data: [],
		selId: '',
		initialize: function() {},
		
		getDropdown: function(id){
			this.loadUserDropDown(id);
		},
		
		loadUserDropDown: function(groupId)
		{
			$("errorMsgTitle").innerHTML = '&nbsp;';
			$("errorMsgTitle").style.display = 'none';
			
			if($("displaAllMessageCategories"))
				$("displaAllMessageCategories").innerHTML = "<img src='/groups/img/loading.gif' class='loadingImgLeft' align='middle'/>";
			
			var showUser = this.ShowUserDropDown.bind(this);		
			var url    = "/groups/milestones/getUsersPerGroup.json";
			var rand   = Math.random(9999);
			var pars   = "?groupid="+groupId+"&rand=" +rand+"&par=1";
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: showUser} );
			
		},
		
		ShowUserDropDown: function(originalRequest){
			if(originalRequest) {
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
			    this.data = (eval('(' + originalRequest.responseText + ')'));
			    //parent.GU[parent.GUGI] = this.data; 
		    } //else this.data = parent.GU[parent.GUGI];
		    
			$("GroupUsers").innerHTML="";	
			//this.data = (eval('(' + originalRequest.responseText + ')'));
			var strTempTxt = "<select class='inp' id='addSelect' name='data[todos][responsible_user_id][]'><option value='0' selected>Select user</option>";
			var tmpStr = "";
			var userid=$('loggedInUser').value;	
			   
			this.data.each(function(item, index) {
				if(userid==item['usermst']['UserID'])
				 	strTempTxt+="&nbsp;<OPTION value='"+item['usermst']['UserID']+"'> Me ("+item['userpersonalinfo']['FullName']+")</OPTION>";
				else
					strTempTxt+="&nbsp;<OPTION value='"+item['usermst']['UserID']+"'>"+item['userpersonalinfo']['FullName']+"</OPTION>";
			});
			
			tmpStr+= "</select>";
			var finalStr = strTempTxt + tmpStr;
			$("GroupUsers").innerHTML=finalStr;	
		}
	};

	var setUserData = Class.create();
	setUserData.prototype = {
		data: [],
		selId: '',
		initialize: function() {
			this.tempID = '';
		},
		
		fillHolder: function (){
			var user_id;
			var dataObj;
			var selDay = this.data.selected_day;
			var loggedInUser = this.data.loggedInUser;
			//alert("test");
			
			
			dataObj = this.data.users;
		
			this.data.milestone.each(
			function(item){
				user_id = item.milestones.user_id;
				$('selDateEdit').innerHTML ="<strong>Due: " + item[0].targetdate + "</strong><input id='target_date_edit' type='hidden' name='data[milestones][targetdate]' value='" + item.milestones.targetdate + "' />";
				$('inputTitle').innerHTML = "<input type='text' name='data[milestones][title]' size='40' value='" + item.milestones.title + "' id = 'editTitle'/>"
											+"<input type='hidden' name='data[milestones][id]' value='" + item.milestones.id + "'/>";				
			}
			);
			var selectHtml = '';
			dataObj.users_list.each(
			function(item){
				if (user_id == item.usermst.UserID){
					if (loggedInUser == item.usermst.UserID){
						selectHtml += "<OPTION value='"+ item.usermst.UserID +"' selected>Me(" + item.userpersonalinfo.FullName + ")</OPTION>";
					}else{
						selectHtml += "<OPTION value='"+ item.usermst.UserID +"' selected>" + item.userpersonalinfo.FullName + "</OPTION>";
					}
				}else{
					if (loggedInUser == item.usermst.UserID){
						selectHtml += "<OPTION value='" + item.usermst.UserID + "'>Me(" + item.userpersonalinfo.FullName+ ")</OPTION>";
					}else{
						selectHtml += "<OPTION value='" + item.usermst.UserID + "'>" + item.userpersonalinfo.FullName + "</OPTION>";
					}					
				}
			}
			);
			$('editLabel').innerHTML = "<select size='1' id='editSelect' name='data[milestones][user_id]'>" + selectHtml + "</select>";			
			//Calendar in edit milestone page
			addMilestoneCalendar('edit', selDay);
		},
		
		showNewData: function (originalRequest) { //alert(originalRequest.responseText);
			if(originalRequest.responseText == "expired"){      
	        	window.location = MainUrl + "main.php?u=signout";return;
	        }else{
				this.data=(eval('(' + originalRequest.responseText + ')'));
				this.fillHolder();
			}
		},
		
		getDropdown: function(id){
			this.loadUserDropDown(id);
		},
		
		loadUserDropDown: function(groupId){
			$("errorMsgTitle").innerHTML = '&nbsp;';
			$("errorMsgTitle").style.display = 'none';
			
			if($("displaAllMessageCategories"))
				$("displaAllMessageCategories").innerHTML = "<img src='/groups/img/loading.gif' class='loadingImgLeft' align='middle'/>";
			
			/*parent.GUGI = groupId;
			if(parent.GUGI != '') {
			    if(!parent.GU[parent.GUGI] || parent.GU[parent.GUGI] == '') {*/
			      
				    var showUser = this.ShowUserDropDown.bind(this);		
					var url    = "/groups/milestones/getUsersPerGroup.json";
					var rand   = Math.random(9999);
					var pars   = "?groupid="+groupId+"&rand=" +rand+"&par=1";
					var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: showUser} );				
			      
			    /*} else {
			      
				    this.ShowUserDropDown();
			    }
		    }*/
							
		},
		
		ShowUserDropDown: function(originalRequest){
			if(originalRequest) {
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
			    this.data = (eval('(' + originalRequest.responseText + ')'));
			    //parent.GU[parent.GUGI] = this.data; 
		    } //else this.data = parent.GU[parent.GUGI];
						
			//this.data = (eval('(' + originalRequest.responseText + ')'));			
			var strTempTxt = "<select class='inp' id='addSelectCM' name='data[milestones][user_id]' onclick='window.addMilestonePopupStatus=1'>";			
		    var userid=$('loggedInUser').value;	
			var doNotShow = false;
			var selected = false;
			if(this.tempID != '') var compareVal = this.tempID;
			else var compareVal = ''; 

			if(compareVal != '') strTempTxt += "<option value='0'>Select user</option>";
			else {
				selected = true;
				strTempTxt += "<option value='0' selected>Select user</option>";
			}

			this.data.each(function(item, index) {
				//alert(compareVal +' - '+ userid +' ++ '+ userid +' - '+ item['usermst']['UserID']);
				if(!doNotShow) {
					if(compareVal == userid || userid == item['usermst']['UserID']) {
						doNotShow = true;
						var innerVal = " Me ("+item['userpersonalinfo']['FullName']+")";
					}
				} else var innerVal = item['userpersonalinfo']['FullName'];
				
				if(compareVal == userid && !selected) {
					selected = true;
					strTempTxt+="&nbsp;<OPTION value='"+item['usermst']['UserID']+"' selected='selected'>"+ innerVal +"</OPTION>";
				} else if(compareVal  == item['usermst']['UserID'] && !selected) {
					selected = true;
				 	strTempTxt+="&nbsp;<OPTION value='"+item['usermst']['UserID']+"' selected='selected'>"+ innerVal +"</OPTION>";
				} else
					strTempTxt+="&nbsp;<OPTION value='"+item['usermst']['UserID']+"'>"+ innerVal +"</OPTION>";
			});
	
			/*this.data.each(function(item, index) {			
				if(userid==item['usermst']['UserID'])
					strTempTxt+="&nbsp;<OPTION id='"+item['usermst']['UserID']+"' value='"+item['usermst']['UserID']+"' selected> Me ("+item['userpersonalinfo']['FullName']+")</OPTION>";
				else
					strTempTxt+="&nbsp;<OPTION id='"+item['usermst']['UserID']+"' value='"+item['usermst']['UserID']+"'>"+item['userpersonalinfo']['FullName']+"</OPTION>";
			});*/
			
			strTempTxt+= "</select>";
			$("GroupUsersMil").innerHTML=strTempTxt;
			
			if(this.tempID != '') {
				$('calendar-milestone-popup').show();	   
			   	$('calendar-milestone-popup').innerHTML = '<div class="fieldLabel fieldLabelWidthBig"><label>When\'s this due?</label></div><div class="GroupUsersMil"><div id="calendar-milestone-add"></div><DIV class="calFooter" id="selDateAdd" style="display:none"><strong>'+$('dueDateCalendar').value+'</strong> </DIV><input id="target_date_add" type="hidden" name="data[milestones][targetdate]" value="" /><input id="today_add_with_calender" type="hidden" name="today_add" value="'+$('todaydateCalendar').value+'" /></div>';
			    $('completed_date_add').value='0000-00-00';
		
				addMilestoneCalendar('add', 1);
			    
			  	var element = $('add_milestone');
			    $('milestone_due_date').innerHTML="<h1>Add milestone</h1>";
			  	$('add_result').innerHTML = "";
		
			  	$('innerContentML').setStyle({ overflowY:'auto' }); 
				adjustDivPosition('innerContentML','add_milestone', 1, 22, 12, 9);
				$('add_milestone').style.display = "block";
			}
		}
	};
	
	
	var milestoneSubmitted=false;

	var calenderRefresher=Class.create();
	calenderRefresher.prototype={
	    data: [],
		selId: '',
		initialize: function() {
			this.actualGrpId = '';
		},
		 
		getAllMilestonesData: function(groupId,add_edit,inserid){
	         if(add_edit!='reffreshingForLevel'){
	            if($('add_milestone_with_calender')){ //if form is submitted from milestone tab	            
		            hideMilestoneWindow();
			        return;
	            }
	          	
	          	if($('selectedIDsdropdownCal'))
	            	groupId=$('selectedIDsdropdownCal').value;
	            else
					groupId=$("currentGroupID").value;   // Calender should show current group instead of milestone created group
		    }
						
			if(document.getElementById('calendarFlag'))
				var milOrOver = 'milestones';
			else
				var milOrOver = 'overview';
		  			   
			var show = this.ShowCalender.bind(this);
		    var startDate=$("div2Bmoved").value;
		   	var url    = "/groups/groups/calenderOverview.json";
			var rand   = Math.random(9999);
			var pars   = "?groupId="+groupId+"&rand=" +rand+"&par=1&startDate="+startDate+"&milOrOver=" +milOrOver;
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: show} );				
			
		     if($('milestoneDiv')){  //if its milestone Tab			   
			   var milestonedata= new allMilestonesData;
		        milestonedata.getAllMilestonesData(groupId,add_edit,inserid);
		        
			 }else{
			   window.addMilestonePopupStatus=0;
			 }  
		  		
		},
		
		ShowCalender: function(originalRequest){
			if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}	
		 	$('frmAddMilestone').reset();
			var strCal = originalRequest.responseText;
			var divNameMil = $("div2Bmoved").value;
			$('calenderInner').innerHTML=strCal;
			$('tableCalInner').style.height = (parent.windowSize[0]*0.87 - 125) + "px";
			$('tableCalInner').style.width = (parent.windowSize[1]*0.9 - 69) + "px";
			$('calenderInner').style.height = (parent.windowSize[0]*0.87 - 100) + "px";	
			
			hideMilestones($('red'));
			hideMilestones($('blue'));
			hideMilestones($('green'));	
			
			if(comet.page == 'overview') { 
				comet.connect('overview',1);
			}
			
			hideMilestoneWindow();
		},
		
		ShowCalenderNew: function(originalRequest){
		
			if(originalRequest) {
			      //alert('saving data');
			      
			      if(originalRequest.responseText == "expired"){      
						window.location = MainUrl + "main.php?u=signout";return;
					}
			      
			      var strCal = originalRequest.responseText;
			      
			      if(!parent.CCD[parent.CCDK]) parent.CCD[parent.CCDK] = new Array();
			      
			      parent.CCD[parent.CCDK][0] = htmlspecialchars(strCal);
			      parent.CCD[parent.CCDK][1] = $('start4prev').value;        
			      parent.CCD[parent.CCDK][2] = $('start4next').value;
			      parent.CCD[parent.CCDK][3] = $('div2Bmoved').value;
			      
		      } else var strCal = htmlspecialchars_decode(parent.CCD[parent.CCDK][0]);
				
				$('calender_loader_1').hide();
				
				$('frmAddMilestone').reset();
				var divNameMil = $("div2Bmoved").value;
				$('calenderInner').innerHTML=strCal;
				
				arrUnRead = $$('div.filterMil');		
				var elemCount = arrUnRead.length;		
				if(this.actualGrpId == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 var tempStr = arrUnRead[i].id;
					 var needle = 'milestoneGroupIdDiv_'+this.actualGrpId+'_';
						if(tempStr.search(needle) > -1){
							arrUnRead[i].style.display = 'block'; 
						}else{
							arrUnRead[i].style.display = 'none'; 
						}					 
					}				
				}
				
				arrUnRead = $$('div.filterBirthdays');		
				var elemCount = arrUnRead.length;
				
				if(this.actualGrpId == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == this.actualGrpId){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}
				
				arrUnRead = $$('div.filterAnnis');		
				var elemCount = arrUnRead.length;
				
				if(this.actualGrpId == 'overviewCalendar'){
					for(i=0;i<elemCount;i++){
						arrUnRead[i].style.display = 'block'; 
					}	
				}else{
					for(i=0;i<elemCount;i++){
					 tempStr = arrUnRead[i].id.split('_');
					 tempstr1 = tempStr[1].split(',');
					 var tempstr1Len = tempstr1.length;
					 
					 for(j=0;j<tempstr1Len;j++){
						if(tempstr1[j] == this.actualGrpId){
							arrUnRead[i].style.display = 'block';
							break;
						}else{
							arrUnRead[i].style.display = 'none'; 
						}
					 }
					
										 
					}				
				}
				
				$('tableCalInner').style.height = (parent.windowSize[0]*0.87 - 125) + "px";
				$('tableCalInner').style.width = (parent.windowSize[1]*0.9 - 69) + "px";
				$('calenderInner').style.height = (parent.windowSize[0]*0.87 - 100) + "px";	
				
				hideMilestones($('red'));
				hideMilestones($('blue'));
				hideMilestones($('green'));	
				
				if(comet.page == 'overview') { 
					comet.connect('overview',1);
				}
				
				hideMilestoneWindow();
	      },
	      
		getAllMilestonesDataNew: function(groupId,actualGrpId,add_edit,inserid){
			 	
	         if(add_edit!='reffreshingForLevel'){
	            if($('add_milestone_with_calender')){ //if form is submitted from milestone tab	            
		            hideMilestoneWindow();
			        return;
	            }
	          	/*
	          	if($('selectedIDsdropdownCal'))
	            	groupId=$('selectedIDsdropdownCal').value;
	            else
					groupId=$("currentGroupID").value;   // Calender should show current group instead of milestone created group
				*/	
		    }
						
			if(document.getElementById('calendarFlag'))
				var milOrOver = 'milestones';
			else
				var milOrOver = 'overview';
		  	
			//if(actualGrpId != $("prj_id").value)
			this.actualGrpId = $('selectedIDsdropdownCal').value;
				

			if(!parent.CCD[parent.CCDK] || parent.CCD[parent.CCDK] == '') {

				var show = this.ShowCalenderNew.bind(this);	  
				var startDate=$("div2Bmoved").value;
			  
				parent.CCDKSD[startDate] = parent.CCDK; // store the start date against the key

			    var url    = "/groups/groups/calenderOverview.json";
			    var rand   = Math.random(9999);
			    var pars   = "?groupId="+groupId+"&rand=" +rand+"&par=1&startDate="+startDate+"&milOrOver=" +milOrOver;
			    var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: show} );
			      
			    this.getCalendarData(groupId);
			      
			    if($('milestoneDiv')){  //if its milestone Tab			   
				  var milestonedata= new allMilestonesData;
			      milestonedata.getAllMilestonesData(groupId,add_edit,inserid);
		      
				}else{
				  window.addMilestonePopupStatus=0;
				}
			      
			} else {
			
			      //alert('here : ' + parent.CCD[parent.CCDG].length);
			      this.ShowCalenderNew();
			
			}
		},
		
		/*	silently call server and fillup the calender cache array	*/
    	getCalendarData : function(groupId) {
  
			var show = this.afterGetCalendarData.bind(this);
			var startDate = $("div2Bmoved").value;
			var next = $('start4next').value;
			var prev = $('start4prev').value;
			var url    = "/groups/groups/COCD.json";
			var rand   = Math.random(9999);
			var pars   = 'startDate='+startDate+'&groupid='+groupId+'&type=np&next='+next+'&prev='+prev+'&today=0&milOrOver=overview';
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: show} );
			
		},
	      
	      afterGetCalendarData : function(originalRequest) {
	      
		      if(originalRequest) {
				  var data = originalRequest.responseText.split('##~~##');
				  
				  if(!parent.CCD[-1]) parent.CCD[-1] = new Array();
				  var d = data[0].split('^br^br^');
			      parent.CCD[-1][0] = htmlspecialchars(d[0]);
			      parent.CCD[-1][1] = d[1];
			      parent.CCD[-1][2] = d[2];
			      parent.CCD[-1][3] = d[3];
			      parent.CCDKSD[d[3]] = -1;

				  if(!parent.CCD[1]) parent.CCD[1] = new Array();
			      var d = data[1].split('^br^br^');
			      parent.CCD[1][0] = htmlspecialchars(d[0]);
			      parent.CCD[1][1] = d[1];        
			      parent.CCD[1][2] = d[2];
			      parent.CCD[1][3] = d[3];
			      parent.CCDKSD[d[3]] = 1;
			  }
	      
	      },
	      
	      /*	recreate milestone html on the fly and stick it into the calendar	*/
	      recreateHTML : function(groupid, lastid, date, data, by, showME, startDate) { //alert(groupid +' :: '+ lastid +' :: '+ date +' :: '+ data +' :: '+ by +' :: '+ showME +' :: '+ startDate);

			hideMilestoneWindow();

	      	var textHead = '<div id="milestoneGroupIdDiv_'+ groupid +'_'+ lastid +'" style="display: block;" class="filterMil">';
		    var textBody = '<div onclick="javascript:ldDispMileStone.getMileStoneData(\'\', '+ lastid +'); window.levelDropDownStatus=0; window.milestoneInfoPopupStatus=1;" id="datadiv'+ lastid +'" class="" style="display: block;">'
		    					+'<span class="tTask">'+ stripslashes(data) +'</span><span class="tName"> - '+ stripslashes(by) +'</span>'
		      				  +'</div>';
		    var textFoot = '</div>';

			if($('ds'+date)) {
			    var par = $('ds'+date);
			    var children = par.childElements(); //alert(children.length +' :: '+ children[0].innerHTML);
			      
			    if(children.length > 0 && children[0].className == 'empty first') {
				    par.innerHTML = textHead + textBody + textFoot;
				    $('datadiv'+ lastid).className = (showME) ? 'upcoming self first' : 'upcoming first';
			    } else {
				    var newDiv = Builder.node('div', {id : 'milestoneGroupIdDiv_'+ groupid +'_'+ lastid}).addClassName('filterMil').update( textBody );
				    
				    if(children.length > 0) Element.insert( children[children.length-1], {'after' : newDiv} );
				    else par.appendChild(newDiv);
				    
				    $('datadiv'+ lastid).className = (showME) ? 'upcoming self' : 'upcoming';
			    }
	
			    parent.CCD[parent.CCDK][0] = htmlspecialchars($('calenderInner').innerHTML);
			    
			} else {
				// when calling from resource tab and milestone page
				
				if(showME) var tmpClass = 'upcoming first self';
				else var tmpClass = 'upcoming first';
				
			    var tmp = '<div id="milestoneGroupIdDiv_'+ groupid +'_'+ lastid +'" style="display: block;" class="filterMil"><div style="display: block;" class="'+tmpClass+'" id="datadiv'+lastid+'" onclick="javascript:ldDispMileStone.getMileStoneData(0,'+lastid+'); window.levelDropDownStatus=0; window.milestoneInfoPopupStatus=1;"><span class="tTask">'+ stripslashes(data) +'</span><span class="tName"> - '+ stripslashes(by) +'</span></div></div>';
			      
			    var searchString = 'ds'+ date +'">';
			    var replaceString = searchString + tmp;

				var key = parent.CCDKSD[startDate];

			    if(typeof key != "undefined") {
				    parent.CCD[key][0] = htmlspecialchars_decode(parent.CCD[key][0]);

					if(parent.CCD[key][0].match(searchString)) {
					    parent.CCD[key][0] = parent.CCD[key][0].replace(searchString, replaceString);
				    }
									      
				    parent.CCD[key][0] = htmlspecialchars(parent.CCD[key][0]);
			    }

			}
	    },
				
		hidepopup: function(popupid){
			 $(popupid).style.display = 'none';
			 $('calenderPopupArrow').style.display='none';
		}
	};

/****************** Fuction Related to calender Popup used on Overview and milestome Page **************************/
	/****************** By Ravindra Maurya 11 jun 2009 *****************************************/
	
	function invalidPopup(element){
		window.invalidPopupStatus=1;
		//alert(1);
		//var winSize = parent.document.viewport.getDimensions(),dynHeight;
		//var popWidth,inalidPopWidth=350,inalidPopHeight=200; //setting invalid popup width & height
		//other code is in calling html page in Event.observe function	
	}

	function setPopupPosition(element) {
		$('calendar-milestone-popup').innerHTML = '';
	 	$('calendar-milestone-popup').style.display = 'none';
		window.levelDropDownStatus=1;
		window.addMilestonePopupStatus=1;
		var  dateNum,elementPos,calPos,diffX,diffY,secondPos,firstPos,heightLimit,x_axis,y_axis;
		var popupWidth=600;
		var popupHeight=515;
		heightLimit=120;

		dateNumPos=findPos1('dateNum'+element);		
		elementPos=findPos1(element);		
		calInner=findPos1('calenderInner');
		calPos=findPos1('calendarLayer');
				
		diffX=elementPos[2];
		
		if(document.all){		
		    x_axis=elementPos[0]+diffX-2;
		    y_axis=elementPos[1];  
	    }else{	  
		    x_axis=elementPos[0]+calPos[0]+diffX+10;
		    y_axis=elementPos[1]+calPos[1]+93;  
	    }
	    
	    dateNumWidth=dateNumPos[2];
	   
	    if(!document.all)
	   		y_axis=y_axis-$('calenderInner').scrollTop; // setting Y parameter as per scroll
	  
	    
	  	//getting center position
	     var centerX;
	     var centerY,popupWidth;
	     var hdisplay,vDisplay;
	     var hVar,vVar,arrowClass,arrowVar;
	     var winSize = browserWindowSizeWithIM();
	
		 var winWidth = winSize[1];
		 var winHeight = winSize[0];
	    
		 centerX=winWidth/2;
		 centerY=winHeight/2;

		 if(x_axis>centerX){     // Horizontal settlement
			hVar=-popupWidth-dateNumWidth-5;
			arrowVar=popupWidth-10;
			hDisplay='left';
			arrowclass='calenderPopRht';
		 }else{
		  	arrowVar=-10;
		  	hVar=5;
		    hDisplay='right';
		    arrowclass='calenderPopLft';
		  }
		
		 if(y_axis>centerY){    //vertical settlement
		 	vVar=-popupHeight+heightLimit+120;
		  	diffY=y_axis+vVar;
		  	if(diffY < 0 )   //if popup goes above 0 
		   		vVar=vVar-diffY+40;  
		  	vDisplay='top';
		 }else{
		   	vVar=-heightLimit;
		  	diffY=y_axis-vVar;
		    if(diffY < 0  )
		 	vVar=vVar-diffY;		
		    vDisplay='bottom';
		 }
		  
		 diffY=y_axis+vVar;	   
		 
		 if(diffY<=0){		
			if(y_axis<95){
				y_axis=calInner[1]+25;
				vVar=-calInner[1]-35;
			}else{
				vVar=vVar-diffY;
			}
		 }
		
		 if((diffY+popupHeight) >winHeight) 	
			vVar=vVar-(diffY+popupHeight-winHeight);

		//logic for hiding calendar Tab
		/*var caltab=parent.window.$('calendarOpenButton').style;
				
		if(x_axis+popupWidth >parseInt(caltab.left) && y_axis+popupHeight> parseInt(caltab.top) && parseInt(caltab.top)< (y_axis+popupHeight)   && hDisplay=='right')
			parent.window.$('calendarOpenButton').style.display='none';
		else
			parent.window.$('calendarOpenButton').style.display='block';
		*/
		$('calenderPopupArrow').className =arrowclass;
		$('calenderPopupArrow').style.display='block';
	    $('calenderPopupArrow').style.top=y_axis+'px';
	    
	    var l = hVar+x_axis;
	  	if(l < 0) {
	  		$('add_milestone').style.left = '0px';
	  		//window.parent.$('ms_popup').style.left = '0px';
	  		$('calenderPopupArrow').style.left = arrowVar + hVar + x_axis - l + 'px';
	  	} else {
		  	$('add_milestone').style.left=hVar+x_axis+'px';
		  	//window.parent.$('ms_popup').style.left=hVar+x_axis+'px';
		  	$('calenderPopupArrow').style.left = arrowVar + hVar + x_axis + 'px';
		}
	  	
	    $('add_milestone').style.top=vVar+y_axis+'px';
	    $('add_milestone').style.width = '600px';
	    $('add_milestone').style.height = '530px';
	    $('innerContentML').style.height = '';
	    $('add_milestone').style.display = "block";
	    var hidecalButton = parseInt($('add_milestone').style.left.replace(/px/g,''))+581;	   
	    if(hidecalButton > $('tableCal').style.width.replace(/px/g,'')){
	    	 parent.$('calendarOpenButton').style.display = 'none';
	    }
	    
	    //window.parent.$('ms_popup').innerHTML = $('add_milestone').innerHTML;
	    //window.parent.$('ms_popup').style.top=vVar+y_axis+'px';
	    //window.parent.$('ms_popup').style.width = '600px';
	    //window.parent.$('ms_popup').style.height = '530px';
	    //window.parent.$('ms_popup').style.display = "block";
	    //alert($('add_milestone').innerHTML);
	}
	
	/*********** Popup Related Function Ends here *******************/
     
	///calendar tab ends

	var liveDisplayMessage = Class.create();
	liveDisplayMessage.prototype = {
		data: [],	
		initialize: function () {},	

		//function to get the level dropdown
		getTodolevelDropdown: function(id){
			/*parent.GUGI = id;
			if(parent.GUGI != '') {
				if(!parent.GUGD[parent.GUGI] || parent.GUGD[parent.GUGI] == '') {*/		
					var getlvlDropdown = this.getlvlDropdown.bind(this);		
					var url    = "/groups/groups/myDropdown.json";
					var rand   = Math.random(9999);
					var pars   = "id="+id;
					var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: getlvlDropdown} );
					
				/*} else {
					
					this.getlvlDropdown();
					
				}
			}*/		
		},
	
		//function to generate the dropdown
		fillHolder1: function () {		
			var indentation = this.data.indentation;
			var chkBoxFlag = this.data.chkBoxFlag;
			var heightPara = this.data.heightPara;
			var para = this.data.para;
			var select = this.data.select;
			var selectGr = this.data.selectGr;
			var valArrCnt = this.data.valArrCnt;
			var ddSTr = '';
			
			/* Dropdown for more than 1 value */
			if(valArrCnt <= 2)
			{
				$('selectedVal'+para).className="customizeDropDownSingle";
				$('selectedVal'+para).attributes["onmouseover"].value="";
				$('selectedVal'+para).attributes["onmouseout"].value="";
				$('selectedVal'+para).attributes["onclick"].value="";
			} else {
				$('selectedVal'+para).className="customizeDropDown";
				$('selectedVal'+para).attributes["onmouseover"].value="classChange('selectedVal"+para+"')";
				$('selectedVal'+para).attributes["onmouseout"].value="classChange('selectedVal"+para+"')";
				$('selectedVal'+para).attributes["onclick"].value="showDropDowntodo('commonDropDOwn"+para+"','divPresent"+para+"','divheight"+para+"','innerDiv"+para+"','selectedVal"+para+"','"+para+"','selectedIDs"+para+"','divPresentAll"+para+"','levelLabeltodocal')";
			}
			
			$('divPresent'+para).value = '';
			$('divPresentAll'+para).value = ''						
			var i = 0;
			this.data.ddArr.each(function(item, index) {								
				if(item.id == selectGr){
					$('selectedVal'+para).value = item.name;
					$('selectedIDs'+para).value = item.id;
				}
				
				var defaultclass='';
											
				if(indentation == "0"){							
					if(item.enb == "0"){
						defaultclass = "listLvl0Disable";
					}else{
						defaultclass = "listLvl0";
					}
				}else{
					if(item.enb == "0"){
						defaultclass = item.classs+"Disable";
					}else{
						defaultclass = item.classs;
					}
				}
				
				if(chkBoxFlag == "0"){							
					chkString = '';
				}else{
					if(item.enb == "0")
						var chkString = '<div style="float:right; padding:9px 5px 0px 0px"><input type="checkbox" name="dropDownChk'+para+'[]" class="borderNone" disabled="true"/></div>';
					else
						var chkString = '<div style="float:right; padding:9px 5px 0px 0px"><input name="dropDownChk'+para+'[]" id="'+item.id+'" value="'+item.name+'" type="checkbox" class="borderNone" onclick="getElementsByNameFun(\'dropDownChk'+para+'\',\'selectedIDs'+para+'\',\'selectedVal'+para+'\');"/></div>';
				}
				
				if(chkBoxFlag == "0"){							
					if(item.enb == "0")
						var chkChkBox = '';
					else
						var chkChkBox = 'onclick="finalValueTodo(\''+item.id+'\',\''+item.name+'\',\'\',\'selectedIDs'+para+'\',\'selectedVal'+para+'\',\'commonDropDOwn'+para+'\',\''+para+'\');"';
				}else{
					var chkChkBox = '';
				}
			
				var tstr = 'div'+item.id+para+'ID';
				ddSTr += '<div id="div'+item.id+para+'ID" onMouseOver=toggleClass2(\''+tstr+'\',\''+item.enb+'\'); onMouseOut=toggleClass1(this.id,'+item.enb+',\''+para+'\'); '+chkChkBox+'>'+chkString+'<div class="'+defaultclass+'">'+item.name+'</div></div>';
				if(i<heightPara){ 				
					divPresentFunction('div'+item.id+para+'ID','divPresent'+para);
				}
				
				divPresentFunctionAll('div'+item.id+para+'ID','divPresentAll'+para);
				i++;
			});
			$("lvlDropDown").innerHTML = ddSTr;		
		},
	
		getlvlDropdown: function(originalRequest){
			if(originalRequest) {
				if(originalRequest.responseText == "expired"){      
					window.location = MainUrl + "main.php?u=signout";return;
				}
				this.data = (eval('(' + originalRequest.responseText + ')'));
				//parent.GUGD[parent.GUGI] = this.data;
			} /*else {
				this.data = parent.GUGD[parent.GUGI];
			}*/
			//$('todolist_title').value = $('tmpTaskListName').value;	
			//this.data=(eval('(' + originalRequest.responseText + ')'));
			this.fillHolder1();
			$("new_todoList_popup").style.display ='block';
		}
	};

	function browserWindowSizeWithIM() {
		var browserWinWidth = 0, browserWinHeight = 0;
		if( typeof( parent.window.innerWidth ) == 'number' ) {
			//Non-IE
			browserWinWidth = parent.window.innerWidth;
			browserWinHeight = parent.window.innerHeight;
		} else if( parent.window.document.documentElement && ( parent.window.document.documentElement.clientWidth || parent.window.document.documentElement.clientHeight ) ) {
			//IE 6+ in 'standards compliant mode'
			browserWinWidth = parent.window.document.documentElement.clientWidth;
			browserWinHeight = parent.window.document.documentElement.clientHeight;
		} else if( parent.window.document.body && ( parent.window.document.body.clientWidth || parent.window.document.body.clientHeight ) ) {
			//IE 4 compatible
			browserWinWidth = parent.window.document.body.clientWidth;
			browserWinHeight = parent.window.document.body.clientHeight;
		}
		
		return [browserWinHeight,browserWinWidth];
	}

	function getScrollWidth(){
	   var w = window.pageXOffset || document.body.scrollLeft || document.documentElement.scrollLeft;	          
	   return w ? w : 0;
	}
	
	function getScrollHeight(){
	   var h = window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop;	           
	   return h ? h : 0;
	}

function talkKeyDownHandler(e){
	var valueKey = '';
	var shiftKey = false;

	if (document.all) {
		var evnt = window.event;
		valueKey = evnt.keyCode;
		shiftKey = evnt.shiftKey;
	} else {
		valueKey = e.keyCode;
             shiftKey = e.shiftKey;
	}

	if((shiftKey != true) && (valueKey == 13)) {
		var messageTrim = trimMessage($('talkTxtArea').value);
		if (messageTrim != '') {
			$('talkTxtArea').style.display = 'none';
			$('talkTxtAreaDefault').style.display = 'block';
			$('talkProject').style.display='none';
			saveTalkMsg();
		}
		return false;
	}
}

function trimMessage (str) {
	var	str = str.replace(/^\s\s*/, ''),
		ws = /\s/,
		i = str.length;
	while (ws.test(str.charAt(--i)));
	return str.slice(0, i + 1);
}


///peoples
var gorp;
var id;
var objPeople;
var createSubmitted;
var intervalId=0;
var companyIndex = 0;
var firstTime = 1;
var companyDomain = '';

var displayPeople = Class.create();
displayPeople.prototype = {
	data: [],
	
	requestCounter: 0,
	initialize: function () {
		this.userRoles = '';
		this.globalRoles = '';
		this.admin = '';
		this.groupid = '';
		//this.userGroup = new Array();
		this.checkPPLAuthRet = true;
	},

	hideError : function() {
		$('errorMsg').style.display = 'none';
		$('errorZeroUserMsg').style.display = 'none';
		$('errorCN').style.display = 'none';
	},
	
	checkPeople : function (groupid) {
		var cpr = this.checkPeopleResp.bind(this);
		new Ajax.Request('/groups/groups/gu.json', {'method':'post', 'parameters':{'groupid':groupid}, onSuccess:cpr});
	},
	
	checkPeopleResp : function(resp) {
		if(resp.responseText == "expired"){      
			window.location = MainUrl + "main.php?u=signout";return;
		}
		var data = eval('('+ resp.responseText +')');//alert(data);alert(data.length);
		var s = $('inviteUsers').getElementsByTagName('input');
		for(var i=0; i<s.length; i++) {
			var e = s[i].value.split('~');
			for(var t=0; t<data.length; t++) { //alert(e[0]);alert(data[t]);
				if(data[t] != e[0]) {
					s[i].checked = false;
					s[i].disabled = false;
				} else {
					s[i].checked = true;
					s[i].disabled = true;
				}
			}
		}
	},
	
	/*checkPPLAuth : function() {
		this.groupid = ($('currGrpId').value == 'ovr') ? $('selectedIDsinviteTab').value : $('currGrpId').value;
		if(this.userRoles[this.groupid] < this.globalRoles['Moderator']['level'])
			if(this.groupid != $('networkId').value)
				this.checkPPLAuthRet = false;
			else
				this.checkPPLAuthRet = true;
		else
			this.checkPPLAuthRet = true;
	},*/
	
	/*showAuthErr : function() {
		this.checkPPLAuth();
		if(!this.checkPPLAuthRet) {
			$('inviteError').innerHTML = 'Important : To add user to the Talk Team group, please contact the group administrator, '+ this.admin[this.groupid]['FullName'] +', at '+ this.admin[this.groupid]['Email'] +' as you do not have enough priviledges.';
			$('inviteError').show();
		} else {
			$('inviteError').hide();
		}
	},*/
	
	callPeople : function() {
			$('currPageCat').value = "ppl";
			showLoader();
			this.groupid = $('currGrpId').value;			
			new Ajax.Updater('maincontent', '/groups/peoples/group', {
	  			parameters: { groupid: this.groupid },
	  			evalScripts:true
			});
	},
	
	fillHolder: function () {
	
		var peopleInGroup = new Array();
		if (nothing != 'updateUser') { 
			var gorp = this.data.gorp;
			var id = this.data.id;
			var createdby = this.data.createdby;
			var nothing = this.data.updateUser;
			var comp = this.data.comp;
			//alert(nothing+ " " + comp);
			var loginuser = this.data.loginuser;
			var roles = this.data.roles;
			var userRole = this.data.userRole;
			var internalUsers = this.data.internalUsers;
			var externalUsers = this.data.externalUsers;
			var clientUsers = this.data.clientUsers;
			var indexComp = 0;
			
			var grpType = this.data.type;
			//alert(grpType+ " " + id);
            var level=this.data.level;
            
			$('pplGrpType').value = grpType;
			if($('pplEmailMsg'))
				$('pplEmailMsg').innerHTML = "This is the e-mail that is sent to the people you are inviting to the "+ (grpType == 'G' ? 'group' : 'subgroup') +".  If you feel like writing one, great.  If not, we'll send a default message.<br> ";//proj2Gr
			var html = '';
			$('grpProjId').value = id;
		
			html = '';
			
			if (userRole >= roles['Moderator']['level']) {
				html +=	'<div>' 
					+'<div class="rightFloat" style="padding-bottom:10px">'
					+'<a href="javascript:void(0);" class="but" onclick="createSubmitted=0;openInvitePopup()">'
					+'<span class="lineHit30">Add new user</span>'
					+'</a></div>'					 
					+'<!-- All company and group -->';
			}
			
			html += '<div style="clear:right"></div>';
			var odd = true;
			var oldCompanyName = '';
			var disabled = '';
			var updateUser = 'updateUser';

			internalUsers.each( function (internalUser) { 
				if (oldCompanyName != internalUser[0].companyname) {
					indexComp++;
					if (oldCompanyName != '') {
						html +=	'<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>		' +
								'	</div></form>';								
					}
                   
					oldCompanyName = internalUser[0].companyname;
					var companyName = internalUser[0].companyname;
					companyName = companyName.toUpperCase();
					html += '<form name="users_'+indexComp+'" id="users_'+indexComp+'" enctype="multipart/form-data" onsubmit="return false;" method="post"> ';
					html += '<div style="clear:left">&nbsp;</div><div class="grpTitle" ><h1><strong>' + companyName + '</strong></h1></div>' 
							+'<div class="groupThreadContent">'
							+'<div class="peopleGrpRows">';
				}
				if (gorp == 'group') {

					disabled = '';
					if (internalUser.groups_users.user_id == loginuser || internalUser.groups_users.user_id == createdby || userRole < roles['Moderator']['level']) {
						disabled = 'disabled';
					}					
                   // alert(internalUser.groups_users.user_id);
                    
				html += '<div class="people">'
					+'<div>'
						+'<div class="leftFloat"><img onMouseOver="showVCard(\''+internalUser.groups_users.user_id+'\',this,event);" onMouseOut="hideVCard(\''+internalUser.groups_users.user_id+'\');" src="'+ internalUser[0].image +'" width="50" border="0"></div>'
						+'<div class="peopleUsageData">'
							+'<h2><a href="javascript:void(0);" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\']; showProfilePage(obj,\'public\',\''+ internalUser[0].encryptuserid +'\');" >' + internalUser.userpersonalinfo.username + '</a></h2>'
							+'<p class="blueColor">'+ emailIdWrap(internalUser.usercompanynetworkmst.emailid) +'</p>'
							+'<p><span>storage used:</span><span class="blueColor">' + internalUser[0].sizeusage + ' MB</span></p>';
				
				if(internalUser[0].LastLogin != '')
				{	
					html += '<p><span>last signed in:</span><span class="blueColor"> ' + internalUser[0].LastLogin + '</span></p>';
				}
				else
				{
					html += '<p> &nbsp; </p>';
				}
				
				var adminChecked = ''; var modChecked = ''; var userChecked = '';
				var adminClass = ''; var modClass = ''; var userClass = '';
				
				if(internalUser.groups_users.role_id == roles['Administrator']['id'])
				{
					adminChecked = 'checked '; adminClass = 'selected ';
				}
				else if(internalUser.groups_users.role_id == roles['Moderator']['id'])
				{
					modChecked = 'checked '; modClass = 'selected ';
				}
				else if(internalUser.groups_users.role_id == roles['User']['id'])
				{
					userChecked = 'checked '; userClass = 'selected ';
				}
			if($('networkId').value == $('currGrpId').value) 
				disabled = 'disabled';
            if (userRole <= roles['Moderator']['level'])
                   {
            
				html +=	'</div>'
					+'</div>'
					+'<div class="borderTop paddingTopBottom">'
						+'<p class="leftFloat pRadio">'
							+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['User']['id'] + '^' + internalUser.groups_users.role_id + '" ' + userChecked + disabled + ' style="margin:2px"></p><p class="leftFloat '+ userClass +' pRadio" style="padding-top: 2px;" >&nbsp;User</p><p class="leftFloat paddingLeft10 pRadio">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Moderator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + modChecked + disabled + '></p><p class="leftFloat '+ modClass +'">&nbsp;Moderator</p><p class="leftFloat paddingLeft10">';
				if(userRole == roles['Moderator']['level'])
					disabled = 'disabled';
				html +=		'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + adminChecked + disabled + ' ></p><p class="leftFloat '+ adminClass +'" pRadio style="padding-top: 2px;">&nbsp;Manager</p><p class="leftFloat paddingLeft10" pRadio>'
                            
					+'</div>'
				+'</div>';
                   }
                   else 
                       {
               
                         var Remove='SEPr';
                          html +=	'</div>'
					+'</div>'
					+'<div class="borderTop paddingTopBottom">'
							+'<div><p class="leftFloat pRadio"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['User']['id'] + '^' + internalUser.groups_users.role_id + '" ' + userChecked + disabled + ' style="margin:2px"></p><p class="leftFloat '+ userClass +' pRadio" style="padding-top: 2px;">&nbsp;User</p><p class="leftFloat paddingLeft10 pRadio">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Moderator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + modChecked + disabled + '></p><p class="leftFloat '+ modClass +'">&nbsp;Moderator</p><p class="leftFloat paddingLeft10">'
							+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + adminChecked + disabled + ' ></p><p class="leftFloat '+ adminClass +' pRadio" style="padding-top: 2px;">&nbsp;Manager</p><div class="clearLeft">&nbsp;</div></div>';
                           // +'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id+ '" value="'+ internalUser.groups_users.user_id+Remove + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^'+  '" ></p><p class="leftFloat">&nbsp;Remove</p>'
               if(createdby != internalUser.groups_users.user_id && $('networkId').value != $('currGrpId').value && internalUser.groups_users.user_id != loginuser)          
                    html += '<div><p class="leftFloat pRadio"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id+ '" value="'+ internalUser.groups_users.user_id+Remove + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^'+internalUser.groups_users.role_id+'^'+internalUser.userpersonalinfo.username+  '"  style="margin:2px" ></p><p style="padding-top: 2px;">&nbsp;Remove</p></div>';
               html  +='</div>'
				+'</div>';
                       }
           
					/*html += '<tr class="' + (odd ? 'alt' : '') + '">' +
						'<td>' + internalUser.userpersonalinfo.username + '</td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['User']['id'] + '" ' + (internalUser.groups_users.role_id == roles['User']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Moderator']['id'] + '" ' + (internalUser.groups_users.role_id == roles['Moderator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '" ' + (internalUser.groups_users.role_id == roles['Administrator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td style="text-align: right">' + internalUser[0].sizeusage + ' MB</td>' +
						'<td>' + internalUser.usermst.lastlogin + '</td>' +
						'</tr>';*/
				} else {
					
					disabled = '';
					if (internalUser.projects_users.user_id == loginuser || internalUser.projects_users.user_id == createdby || userRole < roles['Moderator']['level']) {
						disabled = 'disabled';
					}	
					
				var adminChecked = ''; var modChecked = ''; var userChecked = '';
				var adminClass = ''; var modClass = ''; var userClass = '';
				
				if(internalUser.projects_users.role_id == roles['Administrator']['id'])
				{
					adminChecked = 'checked '; adminClass = 'selected ';
				}
				else if(internalUser.projects_users.role_id == roles['Moderator']['id'])
				{
					modChecked = 'checked '; modClass = 'selected ';
				}
				else if(internalUser.projects_users.role_id == roles['User']['id'])
				{
					userChecked = 'checked '; userClass = 'selected ';
				}
				
					html += '<div class="people">'
					+'<div>'
						+'<div class="leftFloat"><img src="../../img/pic.gif"></div>'
						+'<div style="margin-left:67px; padding-bottom:5px;">'
							+'<h2>' + internalUser.userpersonalinfo.username + '</h2>'
							+'<p class="blueColor">ben.jhonson@ uberdesign.com</p>'
							+'<p><span>storage used:</span><span class="blueColor">' + internalUser[0].sizeusage + ' MB</span></p>'
							+'<p><span>last signed in:</span><span class="blueColor"> ' + internalUser.usermst.lastlogin + '</span></p>'
						+'</div>'
					+'</div>'
					+'<div class="borderTop paddingTopBottom">'
						+'<p class="leftFloat pRadio">'
							+'<input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['User']['id'] + '" ' + userChecked + disabled + ' style="margin:2px"></p><p class="leftFloat '+ userClass +' pRadio" style="padding-top: 2px;">&nbsp;User</p><p class="leftFloat paddingLeft10 pRadio">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Moderator']['id'] + '" ' + modChecked + disabled + '></p><p class="leftFloat '+ modClass +'">&nbsp;Moderator</p><p class="leftFloat paddingLeft10">'
							+'<input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Administrator']['id'] + '" ' + adminChecked + disabled + ' ></p><p class="leftFloat '+ adminClass +' pRadio" style="padding-top: 2px;">&nbsp;Manager</p><p></p>'
					+'</div>'
				+'</div>';				

					/*html += '<tr class="' + (odd ? 'alt' : '') + '">' +
						'<td>' + internalUser.userpersonalinfo.username + '</td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['User']['id'] + '" ' + (internalUser.projects_users.role_id == roles['User']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Moderator']['id'] + '" ' + (internalUser.projects_users.role_id == roles['Moderator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Administrator']['id'] + '" ' + (internalUser.projects_users.role_id == roles['Administrator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td style="text-align: right">' + internalUser[0].sizeusage + ' MB</td>' +
						'<td>' + internalUser.usermst.lastlogin + '</td>' +
						'</tr>';*/

				}
							
						odd = !odd;
			});

			if (oldCompanyName != '') {
				html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';
						
				if (userRole >= roles['Moderator']['level'] && $('networkId').value != $('currGrpId').value) 
				{ 	
					var url = '/groups/peoples/saveUsers/'+userRole+'~~'+loginuser+'~~'+createdby+'~~'+'~~'+$('currGrpId').value;
					html += '<div style="text-align:left;padding:0px 20px 20px 40px;width:400px;color:red;display:none" id="errorMessageMyNW">&nbsp;</div>'
						+'<div style="padding:0px 20px 20px 40px;">'
						+''
						//+'<div class="butRow"><a href="javascript:void(0);" class="but" onclick="micoxUpload(\'users\',\''+url+'\',\'add_loading_img\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;">'
						+'<div class="butRow leftFloat" style="padding:10px 0"><a href="javascript:void(0);" class="but" onclick="javascript:people_confirm(\'users_'+indexComp+'\',\''+url+'\',\'add_loading_img\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;">'
                        +'<span>Update</span>'
						+'</a></div><div id="add_loading_img" class="paddingLeft10 paddingTop10">&nbsp;</div>'							
						+'</div><div class="clear"></div>'
						+'</div>'
						+'<br style="clear: both;"/>';
				}
				else
				{
					html += '</div>';
				}
				html += '</form>';
			}
			
			oldCompanyName = '';
			
			var arr_clientUsers = new Array();
			var arr_userid = new Array();
			var arr_html1 = new Array();
			var arr_html2 = new Array();

			externalUsers.each( function (externalUser) 
			{	
				if(peopleInGroup[externalUser.groups_users.user_id] != undefined) return;	
				else peopleInGroup[externalUser.groups_users.user_id] = externalUser.groups_users.user_id;
				
				if (oldCompanyName != externalUser[0].companyname) 
				{
					
					for(var i = 0;i<arr_html1.length;i++)
						html = html + arr_html1[i];
				
					if(arr_html1.length > 0) 
						html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div> ';		
					if(arr_html1.length > 0 && arr_html2.length > 0)
						html += '<div class="peopleGrp">';
						
					for(var i = 0;i<arr_html2.length;i++)
						html = html + arr_html2[i];
						
					if(arr_html2.length > 0 || arr_html1.length > 0){
						
						if(arr_html2.length > 0)
							html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';
				
						if (userRole > roles['Moderator']['level']) 
						{ 	
							var url = '/groups/peoples/saveUsers/'+userRole+'~~'+loginuser+'~~'+createdby+'~~'+oldCompanyName+'~~'+$('currGrpId').value;
							var img = 'add_loading_img_'+oldCompanyName;
							html += '<div style="text-align:left;padding:0px 20px 20px 40px;width:400px;color:red;display:none" id="errorMessage_'+oldCompanyName+'">&nbsp;</div>'
									+'<div style="padding:0px 20px 20px 40px;">'
									+''
									//+'<div class="butRow"><a href="javascript:void(0);" class="but" onclick=micoxUpload("users",'+url+',"add_loading_img_'+oldCompanyName+'","<img src=/groups/img/loading.gif class=loadingImgLeft>","Error");return false;>'
									+'<div class="butRow leftFloat" style="padding:10px 0"><a href="javascript:void(0);" class="but" onclick="javascript:people_confirm(\'users_'+indexComp+'\',\''+url+'\',\''+img+'\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;">'
                        			+'<span>Update</span>'
									+'</a></div><div id="add_loading_img_'+oldCompanyName+'" class="paddingLeft10 paddingTop10">&nbsp;</div>'							
									+'</div><div class="clear"></div>';
									//+'</div>'
									//+'<br style="clear: both;"/>';
									
						}
					}		
						
					if (oldCompanyName != '') 
					{ 			
						//html += '</div>';
						//html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';
						html += '</div><div>&nbsp;</div></form>';
					}				
					indexComp++;
					
					oldCompanyName = externalUser[0].companyname;
					var companyName = externalUser[0].companyname;
					
					arr_clientUsers = new Array();
					arr_userid = new Array();
					
					arr_html1 = new Array();
					arr_html2 = new Array();
		
					for( var i=0;i<clientUsers.length;i++){
						var clientUser = clientUsers[i];
						if((clientUser[0].companyname).toUpperCase() == companyName.toUpperCase()){
							arr_clientUsers.push(clientUser);
							arr_userid.push(clientUser[0].userid);
						}
					}
					
					companyName = companyName.toUpperCase();
					html += '<form name="users_'+indexComp+'" id="users_'+indexComp+'" enctype="multipart/form-data" onsubmit="return false;" method="post"> '; 
					html += '<div class="grpTitle" ><h1><strong>' + companyName + '</strong></h1></div>' 
							+ '<div class="groupThreadContent">';
				//	if(arr_userid.length > 0)
						html += '<div class="peopleGrpRows">';		
				//	else html += '<div class="peopleGrp">';		
				}
				
				var search = arr_userid.indexOf(externalUser[0].userid);
				var Remove='SEPr';
				var removeHtml = '';	
				if (userRole > roles['Moderator']['level']) {
					removeHtml += '<div class="borderTop paddingTop10">'
							+'<p class="leftFloat pRadio">'
							+'<input type="hidden" id="'+externalUser.groups_users.user_id+ '^' + externalUser.groups_users.group_id+'" value="0">'
							+'<input type="radio" class="borderNone" name="radio^' + externalUser.groups_users.user_id+ '^' + externalUser.groups_users.group_id + '" id="radio^' + externalUser.groups_users.user_id+ '^' + externalUser.groups_users.group_id+ '" value="'+ externalUser.groups_users.user_id+Remove + '^' + externalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^'+externalUser.groups_users.role_id+'^'+externalUser.userpersonalinfo.username+  '"  onClick=uncheckButton(this,"'+externalUser.groups_users.user_id+ '^' + externalUser.groups_users.group_id+'"); ></p><p class="leftFloat pRadio" style="padding-top: 2px;">&nbsp;Remove</p>'
							+'</div>';
				}
				
				if(search != -1){
				
					var clientUser = arr_clientUsers[search];
					
					var html1= '<div class="vCard leftFloat" style=" width:350px;margin-bottom: 20px;">'+
    				
    								'<div class="vCardImage"><img onMouseOver="showVCard(\''+externalUser.groups_users.user_id+'\',this,event);" onMouseOut="hideVCard(\''+externalUser.groups_users.user_id+'\');" width = "50" src="'+externalUser[0].image+'" /></div>'+
    							
        							'<div class="vCardInfo">'+
            							'<div class="vCardName">'+ externalUser.userpersonalinfo.username +'</div>'+
   										'<div class="vCardEmail">'+ clientUser.gcu.email + '</div>';
   													
           			if(clientUser.gcu.address1 != ''){		
           				html1 += 		'<div class="vCardRow">'+
                						clientUser.gcu.address1+
           								'</div>';
           			}
           			if(clientUser.gcu.address2 != ''){		
           				html1 += 		'<div class="vCardRow">'+
                						clientUser.gcu.address2+
           								'</div>';
           			}
           	
           			if(clientUser.gcu.city != ''){			
						html1 +=			'<div class="vCardRow">'+
										'<span>' + clientUser.gcu.city +'</span>';
						if(clientUser.gcu.zip != '')	
							html1 += '<span> - ' + clientUser.gcu.zip +'</span>';
						html1 += 	'	</div>';	
					}		
			
 
  					var str1 = 		'<span>' + clientUser.gcu.state + '</span>';
					var str2 = 		'<span>' + clientUser.gcu.country + '</span>';
			
					html1 +=  			'<div class="vCardRow">'; 				
           			if(clientUser.gcu.state != '' && clientUser.gcu.country != '')		
						html1 +=		str1 + ', ' +str2;
					else if(clientUser.gcu.state != '')	
						html1 +=		str1;
					else if(clientUser.gcu.country != '')	
						html1 +=		str2;
					
					html1 += 			'</div>'+
				 						'<div class="vCardRow">'+
                							'<div class="vCardLabel">Mobile:</div>'+
                							'<div class="vCardCont">'+  clientUser.gcu.phoneno.replace(/@-/g, '').replace(/@/g, '').replace(/-$/g,'') +'</div>'+
                			
           								'</div>';
           								
					if(clientUser.gcu.website_url != ''){							
             			 html1 +=		'<div class="vCardRow"><a href="'+returnFullUrl(clientUser.gcu.website_url)+'" target="_blank">'+clientUser.gcu.website_url+'</a>'+
               							'</div>';
					}
					
					if(externalUser[0].LastLogin != '')
					{	
						html1 += '<div class="vCardRow">'+
								'<p><span>last signed in:</span><span class="blueColor"> ' + externalUser[0].LastLogin + '</span></p>'+
								'</div>';
					}
					else
					{
						html1 += '<p> &nbsp; </p>';
					}
					
					html1 += '</div>';
					
					html1 += removeHtml;
					html1 += '</div><div class="leftFloat" style="width: 20px;"> &nbsp; </div>';
					
					arr_html1.push(html1);
			
				}
				
				else{
				
					var html2 = '<div class="people">'
						+'<div>'
							+'<div class="leftFloat"><img onMouseOver="showVCard(\''+externalUser.groups_users.user_id+'\',this,event);" onMouseOut="hideVCard(\''+externalUser.groups_users.user_id+'\');" src="'+ externalUser[0].image +'" width="50" border="0"></div>'
						+'<div style="margin-left:67px; padding-bottom:5px;">'
							+'<h2><a href="javascript:void(0);" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\']; showProfilePage(obj,\'public\',\''+externalUser[0].encryptuserid+'\');">' + externalUser.userpersonalinfo.username + '</a></h2>'
							+'<p class="blueColor">'+ emailIdWrap(externalUser[0].emailid?externalUser[0].emailid:externalUser.usercompanynetworkmst.emailid) +'</p>';
					if(externalUser[0].LastLogin != '')
					{	
						html2 += '<p><span>last signed in:</span><span class="blueColor"> ' + externalUser[0].LastLogin + '</span></p>';
					}
					else
					{
						html2 += '<p> &nbsp; </p>';
					}
					html2 += '</div></div>';
					
					html2 += removeHtml;
				
					html2 += '</div>';	
					arr_html2.push(html2);
				}
				
									
				
			});

			for(var i = 0;i<arr_html1.length;i++)
					html = html + arr_html1[i];
			html +=	'<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div>';
			if(arr_html1.length > 0 && arr_html2.length > 0) html += '</div> <div class="peopleGrp">';
			//else if(arr_html2.length > 0) html += '</div> <div class="peopleGrpRows">';			
			for(var i = 0;i<arr_html2.length;i++)
					html = html + arr_html2[i];	
					
			//html += '</div>';
			html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';
			//alert(arr_html2.length);		
			if(arr_html2.length > 0 || arr_html1.length > 0) {
				//alert(userRole + " " + roles['Moderator']['level']);
				if (userRole > roles['Moderator']['level']) 
						{ 	
							var url = '/groups/peoples/saveUsers/'+userRole+'~~'+loginuser+'~~'+createdby+'~~'+oldCompanyName+'~~'+$('currGrpId').value;
							var img = 'add_loading_img_'+oldCompanyName;
							html += '<div style="text-align:left;padding:0px 20px 20px 40px;width:400px;color:red;display:none" id="errorMessage_'+oldCompanyName+'">&nbsp;</div>'
									+'<div style="padding:0px 20px 20px 40px;">'
									+''
									//+'<div class="butRow"><a href="javascript:void(0);" class="but" onclick="micoxUpload(\'users\',\''+url+'\',\'add_loading_img\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;">'
									+'<div class="butRow leftFloat" style="padding:10px 0"><a href="javascript:void(0);" class="but" onclick="javascript:people_confirm(\'users_'+indexComp+'\',\''+url+'\',\''+img+'\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;">'
                        			+'<span>Update</span>'
									+'</a></div><div id="add_loading_img_'+oldCompanyName+'" class="paddingLeft10 paddingTop10">&nbsp;</div>'							
									+'</div><div class="clear"></div>'
									+'</div>'
									+'<br style="clear: both;"/>';									
		
						}
						else
						{
							html += '</div>';
						}
			
			}	
			
			
			html += '<div>&nbsp;</div>';

			html += '</form>';
			html += '</div></div>';
			html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';		
			$('leftPanel').innerHTML = html;
		}
		
		if(nothing == 'addUser') {
			//$('successmessage').innerHTML = '<div class="success">Successfully added the people.</div>';
			$('addUserTab').style.display='none';
			//$('successpopupppl').style.display = '';
			//centerPos($('successpopupppl'), 1);
			//setTimeout('$(\'successpopupppl\').style.display = \'none\'',2000);
		} else if (nothing == 'updateUser') {
			if(comp == ''){
				$('errorMessageMyNW').style.display = '';
				$('errorMessageMyNW').innerHTML = '<div class="confirm">Successfully made changes.</div>';
				setTimeout('$(\'errorMessageMyNW\').innerHTML = \'\';$(\'errorMessageMyNW\').style.display = \'none\';',2500);
			}
			else {
				$('errorMessage_'+comp).style.display = '';
				$('errorMessage_'+comp).innerHTML = '<div class="confirm">Successfully made changes.</div>';
				var newComp = "$('errorMessage_"+comp+"').innerHTML = '';$('errorMessage_"+comp+"').style.display = 'none'";
				setTimeout(newComp,2500);	
			}

		}
		createSubmitted = 0;
	},
	getPeopleData: function (gorp, id, updateUser, company, level) {
       // alert(gorp + " 2: " + id + " 3: " + updateUser + " 4: " + company + " 5: " + level);

		if($('currPageCat').value != 'ppl') {
			$('addUserTab').hide();
			return false;
		}

		var showPeopleData = this.showPeopleData.bind(this);
		var rand   = Math.random(99999);
		var url    = '/groups/peoples/getpeopledata.json';
		var userID = $('SessStrUserID').value;
		id = $('currGrpId').value;
		//alert("company is : " +company );
        var level=level;
		var pars   = 'id=' + id + '&gorp=' + gorp + '&company='+ company +'&updateUser=' + updateUser + '&sessUID='+userID+ '&level='+level;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showPeopleData} );
	},
	showPeopleData: function (originalRequest) { //alert(originalRequest.responseText);
        //alert(originalRequest.responseText);
		if(originalRequest.responseText == "expired")
        {      
        	window.location = MainUrl + "main.php?u=signout";return;
        }
        else
        {
			this.data=(eval('(' + originalRequest.responseText + ')'));		
			this.fillHolder();
			//positionFooter();
		}
	},
	getPrimaryGroupUserInfo: function(groupId,additionalGroupUsersCnt,otherGroupUsersCnt){
		$('allusers').innerHTML = "<img src='/groups/img/loading.gif' class='loadingImgLeft' align='middle'/>";
		var setPrimaryGroupUserInfo = this.setPrimaryGroupUserInfo.bind(this);
		var url    = '/groups/groups/getPrimaryGroupUserInfo.json';
		var rand   = Math.random(9999);
		var userID = $('SessStrUserID').value;
		var pars   = '?groupId='+groupId+'&rand=' + rand+'&additionalGroupUsersCnt=' + additionalGroupUsersCnt+'&otherGroupUsersCnt=' + otherGroupUsersCnt + '&sessUID='+userID;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: setPrimaryGroupUserInfo} );
	},
	setPrimaryGroupUserInfo: function(originalRequest){
		if(originalRequest.responseText == "expired")
        {      
        	window.location = MainUrl + "main.php?u=signout";return;
        }
        else
        {
			this.data = (eval('(' + originalRequest.responseText + ')'));
			this.companyName = this.data.companyinfo[0][0].companyname;
			this.companyDomain = this.data.companyinfo[0].CN.companydomain;

			/*this.userRoles = this.data.otherinfo.userRole;
			this.globalRoles = this.data.otherinfo.roles;
			this.admin = this.data.otherinfo.admin;*/
			//this.showAuthErr();
			
			companyDomain = this.data.companyinfo[0].CN.companydomain;
			$('compUserCount').value = parseInt(this.data.groupuserinfo.length);
			$('companynames').value = '';

			var oldCompanyName = '';
			var tmpStrText = '';
			var hiddenCountText = '';
			var count = 0;
			var newCompanyName = '';
			var groupid = this.groupid;						
			var groupsLength = parseInt(this.data.groupuserinfo.length); 
			$('hidGroupLength').value = groupsLength;

			if(firstTime == 0) companyIndex = 0;
			//alert(1);
			this.data.groupuserinfo.each(function(item,index){
				if (oldCompanyName != item['0'].companyname) {
					if (oldCompanyName != '') {
							tmpStrText +=	'		</div> <!-- addNameRow -->';
						if ((count % 3) != 0) {
							tmpStrText +=	'		</div> <!-- addNameRow -->' +
											'	</div> <!-- addName -->';
						}
						tmpStrText +=  hiddenCountText + count + '">';
					}
					companyIndex++;
					newCompanyName = item['0'].companyname;
					newCompanyName = newCompanyName.toUpperCase();
	
					hiddenCountText =	'<input type="hidden" name="selectedCompUserCount_' + companyIndex + '" id="selectedCompUserCount_' + companyIndex + '" value="0">' +
										'<input type="hidden" name="chkAllCompUserStatus_' + companyIndex + '" id="chkAllCompUserStatus_' + companyIndex + '" value="0">' +
										'<input type="hidden" name="compUserCount_' + companyIndex + '" id="compUserCount_' + companyIndex + '" value="';
					tmpStrText +=	'<div class="companyGrp Grp">' +								
									'		<div id="companyName">' +
									'			<div class="leftFloat"><input name="chkAllCompUsers_' + companyIndex + '" id="chkAllCompUsers_' + companyIndex + '" type="checkbox" class="borderNone" onclick="javascript:objPeople.chkAllCompUsers(\'' + companyIndex + '\')"/></div><div style="padding: 4px 0px 0px 25px;">&nbsp;<strong> ' + newCompanyName + '</strong></div>' +
									'			<div class="clearLeft"></div>'+
									'		</div>' +
									'</div>' +
									'<div class="addName1" id="companyGroupUserInfo">';
	
					//$('companynames').value = $('companynames').value + ',' + item['0'].companyname + '_' + companyIndex;
					
					oldCompanyName = item['0'].companyname;				
					count = 0;
				}
				$('hidCompanyIndex').value = companyIndex;
				
				firstTime = 0;
				
				if ((count % 3) == 0) {
					tmpStrText +=	'	<div class="addNameRow1">';
				}

				var tmp_checked = '';
				item.groups.each(function(a,b){
					if(a == groupid) {
						tmp_checked = 'checked="checked" disabled="disabled"';
					}
				});
					
	
				tmpStrText +=	'<div class="addNameCell1">' +							
								'<div class="leftFloat" style="padding-right:5px"><input name="currentGroupUsers_' + companyIndex + '_' + count + '" id="currentGroupUsers_' + companyIndex + '_' + count + '"' + ' type="checkbox" class="borderNone" value="' + item.users.userid + '~' + item.users.fullname + '" onclick="javascript:objPeople.chkEnableUser(\'' + companyIndex + '\', ' + count + ')" '+ tmp_checked +'/></div> ' + 
								ucwords(item.users.fullname) +							 
								'<div class="clearLeft"></div></div>';
				
				if ((count % 3) == 2) {
					tmpStrText +=	'		<span>&nbsp;</span>' + 
									'	</div><!-- addNameRow -->';
				}
	
				count++;
			});
			

			tmpStrText +=	'</div></div>';
			tmpStrText +=  hiddenCountText + count + '">';
			$('companynames').value = $('companynames').value.replace(/^,/, '').replace(/,$/, '');

			if(groupsLength > 0 )
			{	
				$('allusers').innerHTML = tmpStrText;						
			}
			else
			{	
				$('invite_email').style.display = '';
				$('inviteUsers').style.display = 'none';
			}
			
			this.setAdditionalGroupUserInfo(this.companyDomain, this.data.additionalGroupUsersCnt);
	
			this.setOtherGroupUserInfo(this.data.otherGroupUsersCnt);
		}
	},
	getInvitationMessage:function(type){
		var setInvitationMessage = this.setInvitationMessage.bind(this);
		var url    = '/groups/groups/getinvitationmessage.json';
		var rand   = Math.random(9999);
		var userID = $('SessStrUserID').value;
		var pars   = '?type='+type+'&rand=' + rand + '&sessUID='+userID;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: setInvitationMessage} );		
	},
	setInvitationMessage:function(originalRequest){
		if(originalRequest.responseText == "expired")
        {      
        	window.location = MainUrl + "main.php?u=signout";return;
        }
        else
        {
			this.data = (eval('(' + originalRequest.responseText + ')'));
			if(trimAll(this.data[0].groupinvitationmessages.message) != "") {
				if($('invitationMessageText')) $('invitationMessageText').value = this.data[0].groupinvitationmessages.message;
			}
		}
	},
	setAdditionalGroupUserInfo: function(companyDomain,additionalGroupUsersCnt){
		var tmpDisplay = "";
		var tmpStrText = "<div style='margin-right:30px;padding-left:0px;' class='Grp'><fieldset style='padding-top:0px;padding-left:0px;'>";
//		"<div class='createGrpSubTitle'>INVITE PEOPLE FROMs YOUR COMPANY</div>";
		for(i=1;i<=additionalGroupUsersCnt;i++)
		{
			if(i > 3) tmpDisplay = " style='display:none; padding-left:0px;'"; else  tmpDisplay = " style='padding-left:0px;'";
			tmpStrText+="<div class='field' id='invField"+i+"' "+tmpDisplay+"><div>"+
			"<input id='additionalGroupUser"+i+"' onkeypress='return barFormSubmit(event);' onblur=validateAddEmail(0,this.value,'additionalGroupUserValidate"+i+"','@"+companyDomain+"','additionalGroupUserRes"+i+"','additionalGroupUser',"+i+","+additionalGroupUsersCnt+");objPeople.validateAddUserMailId(this.value,'additionalGroupUserValidate"+i+"','@"+companyDomain+"','additionalGroupUserRes"+i+"'); type='text' size='40' class='rightAlign' />@"+
			companyDomain+"<span id='additionalGroupUserValidate"+i+"'></span><input type='hidden' value='0' name='additionalGroupUserRes"+i+"' id='additionalGroupUserRes"+i+"' /></div></div>";
		}
		tmpStrText+="</fieldset></div>";
		$('additionaGroupUserDIV').innerHTML = tmpStrText;
	},
	setOtherGroupUserInfo: function(otherGroupUsersCnt){
		var tmpDisplay = "";
		var tmpStrText = "<div style='margin-top: 0px; margin-bottom: 10px;' class='fieldSpacer' id='projectSelectFieldSpacer'>&nbsp;</div>"; 
		tmpStrText += "<div class='content fieldWidth2 Grp' style='padding-left:0;'><fieldset style='padding-top:10px;margin-top:-25px;padding-left:0;'>"+
		"<div class='createGrpSubTitle' style='color:#000000'>INVITE CLIENTS AND PARTNERS</div>";
		
		
		tmpStrText += '<div style="font-size: 12pt;" class="paddingTopBottom">Clients and partners can only be invited to groups and not the overall intranet. This ensures that group remains mutually exclusive and one client can\'t see your interactions with another client or read your internal discussions.</div>';//proj2Gr
//		"<div class='createGrpSubTitle'>INVITE PARTNERS TO JOIN YOUR "+ ($('pplGrpType').value == 'G' ? 'PROJECT' : 'SUBPROJECT') +"</div>";		
		tmpStrText += "<div class=\"paddingTopBottom\">eg: sean@uberart.net, seansmith1989@gmail.com</div>";
		
		for(i=1;i<=otherGroupUsersCnt;i++)
		{
			if(i > 3) tmpDisplay = " style='display:none; padding-left:0px;'"; else  tmpDisplay = " style='padding-left:0px;'";

//			tmpStrText+="<div class='field'><div>"+
			tmpStrText+="<div class='field' id='invOthersField"+i+"' "+tmpDisplay+"><div>"+
			"<input id='outsideCompanyUser"+i+"' onkeypress='return barFormSubmit(event);' onblur=validateUserEmail(0,this.value,\'outsideCompanyUserValidate"+i+"\',\'outsideCompanyUserRes"+i+"\',\'outsideCompanyUser\',"+i+","+otherGroupUsersCnt+",\'\');objPeople.validateUserMailId(this.value,\'outsideCompanyUserValidate"+i+"\',\'outsideCompanyUserRes"+i+"\'); type='text' size='40' />"+
			"<span id='outsideCompanyUserValidate"+i+"'></span><input type='hidden' value='0' name='outsideCompanyUserRes"+i+"' id='outsideCompanyUserRes"+i+"' /></div></div>";
		}
		tmpStrText+="</fieldset></div>";
		$('otherGroupserDiv').innerHTML = tmpStrText;
	},
	chkAllCompUsers: function(companyIndexVal){
		if($('chkAllCompUserStatus_' + companyIndexVal).value == 0){
			var compUserCount = parseInt($('compUserCount_' + companyIndexVal).value);
			var selectedCompUserCount = 0;
			for(i=0;i<compUserCount;i++){
				if(!$('currentGroupUsers_'+companyIndexVal+'_'+i).disabled) {
					$('currentGroupUsers_'+companyIndexVal+'_'+i).checked = true;	
					selectedCompUserCount += 1;
				}
			}
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;			
			$('chkAllCompUserStatus_'+companyIndexVal).value = 1;
		}else
		{
			var compUserCount = parseInt($('compUserCount_'+companyIndexVal).value);
			var selectedCompUserCount = parseInt($('selectedCompUserCount_'+companyIndexVal).value);
			for(i=0;i<compUserCount;i++){
				if(!$('currentGroupUsers_'+companyIndexVal+'_'+i).disabled) {
					$('currentGroupUsers_'+companyIndexVal+'_'+i).checked = false;	
					selectedCompUserCount -= 1;
				}
			}
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;			
			$('chkAllCompUserStatus_'+companyIndexVal).value = 0;
		}
	},
	chkEnableUser: function(companyIndexVal, val){
		var compUserCount = parseInt($('compUserCount_' + companyIndexVal).value);
		var selectedCompUserCount = parseInt($('selectedCompUserCount_'+companyIndexVal).value);
		if($('currentGroupUsers_'+companyIndexVal+'_'+val).checked == true)
		{
			selectedCompUserCount+=1;
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;
		}else
		{
			selectedCompUserCount-=1;
			$('chkAllCompUsers_'+companyIndexVal).checked = false;
			$('chkAllCompUserStatus_'+companyIndexVal).value = 0;
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;	
		}

		if(selectedCompUserCount == compUserCount)
		{
			$('chkAllCompUsers_'+companyIndexVal).checked = true;
			$('chkAllCompUserStatus_'+companyIndexVal).value = 1;
		} else {
			$('chkAllCompUsers_'+companyIndexVal).checked = false;
			$('chkAllCompUserStatus_'+companyIndexVal).value = 0;
		}
	},
	validateUserMailId :function(userMailId,errorElement,errorElementNumb) {
		userMailId = userMailId.replace(/^\s+|\s+$/g, '');		
		if($(errorElement).innerHTML == '&nbsp;&nbsp;<img src="/images/cross.gif" style="vertical-align: middle;">' || $(errorElement).innerHTML == '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">Please remove the duplicate E-mail Ids.</font>' || userMailId == '') {
		} else {
			this.chkMailIdWithDb(userMailId,errorElement,errorElementNumb);
		}
	},
	validateAddUserMailId :function(userMailId,errorElement,domainName,errorElementNumb) {
		userMailId = userMailId.replace(/^\s+|\s+$/g, '');
		if($(errorElement).innerHTML == '&nbsp;&nbsp;<img src="/images/cross.gif" style="vertical-align: middle;">' || $(errorElement).innerHTML == '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">Please remove the duplicate E-mail Ids.</font>' || userMailId == '') {
		} else {
			this.chkMailIdWithDb(userMailId+domainName,errorElement,errorElementNumb);
		}
	},
	chkMailIdWithDb: function(userMailId,errorElement,errorElementNumb) {
		var chkMailIdWithDbResponse = this.chkMailIdWithDbResponse.bind(this);
		var url = '/groups/groups/userEmailValid.json';
		var pars   = '?userMailId='+userMailId+'&errorElement='+errorElement+'&errorElementNumb='+errorElementNumb;
		this.requestCounter++;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: chkMailIdWithDbResponse} );
	},
	chkMailIdWithDbResponse: function (originalRequest) {
		if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
		}
		this.data1=(eval('(' + originalRequest.responseText + ')'));
		switch(parseInt(this.data1['strInvitationStatus'])) {
			/*case 0:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>You can not invite yourself.</font>";
					parent.$(this.data1['errorElementNumb']).value="0";
					break;
			case 1:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>New quick invite mail will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="1";
					break;
			case 3:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>Quick invite mail will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="3";
					break;
			case 4:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>No mail will be sent, as user is in DND list.</font>";
					parent.$(this.data1['errorElementNumb']).value="4";
					break;
			case 6:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>User Invited for Groups. User has blocked you.</font>";
					parent.$(this.data1['errorElementNumb']).value="6";
					break;
			case 7:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>New request will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="7";
					break;
			case 8:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>Request will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="8";
					break;
			case 9:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>User will be invited.</font>";
					parent.$(this.data1['errorElementNumb']).value="9";
					break;
			default:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'>";
					parent.$(this.data1['errorElementNumb']).value="0";
					break;
			*/
			
			//please don't remove comments below as it is dynamically changing the innerHTML without validation
			//and also affecting in emailing process...
			case 0:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="0";
					break;
			case 1:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="1";
					break;
			case 3:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="3";
					break;
			case 4:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="4";
					break;
			case 6:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="6";
					break;
			case 7:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="7";
					break;
			case 8:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="8";
					break;
			case 9:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="9";
					break;
			default:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="0";
					break;
	
		}
		this.requestCounter--;
	}
};

function showAddUserTab(id) {
	$('invite_email').style.display = 'none';
	$('invite_emailCG').style.display = 'none';
//	$("additionaGroupUserDIV").innerHTML= '';
//	$("otherGroupserDiv").innerHTML= '';
	$("additionaGroupUserDIVCG").innerHTML= '';
	$("otherGroupserDivCG").innerHTML= '';
	$('allusers').innerHTML = '';
	$('allusersCG').innerHTML = '';
	$('inviteError').hide();
	$("companynames").value= '';
	$("companynamesCG").value= '';
	$("compUserCount").value= '';
	$("compUserCountCG").value= '';
	$("additionalGroupUsers").value= '';
	$("additionalGroupUsersCG").value= '';
	$('useInvitationMsgFlag').value = 0;
	$('useInvitationMsgFlagCG').value = 0;
	$("otherGroupUsers").value= '';
	$("otherGroupUsersCG").value= '';
	$("additionalGroupUsersTrack").value= '';
	$("additionalGroupUsersTrackCG").value= '';
	$("otherGroupUsersTrack").value= '';
	$("otherGroupUsersTrackCG").value= '';
	$("selectedCurrentGroupUsers").value= '';
	$("selectedCurrentGroupUsersCG").value= '';
	
	objPeople.hideError();
	objPeople.groupid = id;
	objPeople.getPrimaryGroupUserInfo(id,'10','10');
	
	if($('currGrpId').value == 'ovr' || $('currGrpId').value == $('networkId').value) {
		$('inviteUsers').hide();
		$('otherGroupserDiv').hide();
		$('invite_email').show();
		
		$('inviteFooterInfo').show();
		
		if($('currGrpId').value == 'ovr') {
			$('inviteLevelDropdown').show();
			$('selectedValinviteTab').value = $('networkName').value.capitalize() + ' Intranet';
			$('selectedIDsinviteTab').value = $('networkId').value;
			$('invitePopupTitle').update("Invite your team and clients");
		} else {
			$('invitePopupTitle').update("Invite your team to "+$('currGrpName').value.capitalize()); //Intranet level
			$('inviteLevelDropdown').hide();
		}
		
	} else { //Project - Subproject level
		$('invitePopupTitle').update("Invite your team and clients to "+$('currGrpName').value.capitalize());
		
		$('inviteUsers').show();
		$('otherGroupserDiv').show();
		$('invite_email').hide();
		$('inviteLevelDropdown').hide();
		$('inviteFooterInfo').hide();
	}

	setTimeout("$('addUserTab').style.display='block';centerPos(document.getElementById('addUserTab'), 1);",1000);
	
	
	openStatus=0;
	//Effect.Appear('addUserTab',{duration: 0.5});
	
}

function people_confirm(form,url_action,id_element,html_show_loading,html_error_http)
{
    
    var count=0;
    var doc = $(form);
  
    for(var i=0; i<doc.elements.length; i++)
    {
       
     if(doc.elements[i].checked == true && doc.elements[i].value.match('SEPr'))
      {
        count++;
        //alert("The field name is: " + doc.elements[i].name + " and itâ€™s value is: " + doc.elements[i].value + ".<br />");
        
        //stringObject.substr(start,length);
        var people=doc.elements[i].value.substr(doc.elements[i].value.lastIndexOf('^')+1);

        if(count >= 2)
            {
              //  alert('2');
                $('remove_confirm_popup').style.display = 'block';
                $('rem_people_list').innerHTML= $('rem_people_list').innerHTML+'<br>- '+people;

            }
            else
            {
                    //alert('1');
                    $('remove_confirm_popup').style.display = 'block';
                    $('rem_people_list').innerHTML='- '+people;
            }
        }
    }

   if(count!=0)
   {
       $('confirm_people').innerHTML='<a href="javascript:void(0);" class="butBlue" onclick=" document.getElementById(\'remove_confirm_popup\').style.display=\'none\';micoxUpload(\''+form+'\',\''+url_action+'\',\''+id_element+'\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;"><span class="lineHit30">Yes</span></a>';
      centerPos(document.getElementById('remove_confirm_popup'), 1);
   }
   else
   {
       micoxUpload(form,url_action,id_element,'<img src=/groups/img/loading.gif class=loadingImgLeft>','Error');
       return false;
   }
}

function showhidePPL(obj)
{
	/*
	if (obj.style.display == 'block') {
		obj.style.display = 'none';
	} else {
		obj.style.display = 'block';
	}
	*/
	Effect.toggle(obj, 'blind',{duration: 0.5});
}


function isEmailAdd(B){
	var A=1;
	var D=/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum)$/;
	var G=/^(.+)@(.+)$/;
	var S='\\(\\)><@,;:\\\\\\"\\.\\[\\]';
	var O="[^\\s"+S+"]";
	var F='("[^"]*")';
	var T=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
	var L=O+"+";var E="("+L+"|"+F+")";
	var P=new RegExp("^"+E+"(\\."+E+")*$");
	var I=new RegExp("^"+L+"(\\."+L+")*$");B=trimAll(B);B=B.toLowerCase();
	var C=B.match(G);

	if(C==null){return false}
	var K=C[1];
	var J=C[2];
	for(cntUser=0;cntUser<K.length;cntUser++){
		if((K.charCodeAt(cntUser)>96 && K.charCodeAt(cntUser)<123) || K.charCodeAt(cntUser)==46 || K.charCodeAt(cntUser)==45 || K.charCodeAt(cntUser)==95 || (K.charCodeAt(cntUser)>47 && K.charCodeAt(cntUser)<58)){}
		else{return false}
	}

	for(cntDomain=0;cntDomain<J.length;cntDomain++){
		if((J.charCodeAt(cntDomain)>96 && J.charCodeAt(cntDomain)<123) || J.charCodeAt(cntDomain)==46 || (J.charCodeAt(cntDomain)>47 && J.charCodeAt(cntDomain)<58)||J.charCodeAt(cntDomain)==45){}
		else{return false}
	}

	if(K.match(P)==null){return false}

	var N=J.match(T);

	if(N!=null){
		for(var R=1;R<=4;R++){
			if(N[R]>255){return false}
		}
	}

	var M=new RegExp("^"+L+"$");
	var H=J.split(".");
	var Q=H.length;
		
	for(R=0;R<Q;R++){
		if(H[R].search(M)==-1){return false}
	}

	if(A&&H[H.length-1].length!=2&&H[H.length-1].search(D)==-1){return false}
	if(Q<2){return false}

	return true
}


createSubmitted=0;
function addMoreUsers() {
	var cntUsers;
	var tmpStr = '';
	var mailidCnt = '0';
	var groupid = ($('currGrpId').value == 'ovr') ? $('selectedIDsinviteTab').value : $('currGrpId').value;
		
	/*objPeople.checkPPLAuth();
	if(!objPeople.checkPPLAuthRet) {
		$('errorCA').style.display = '';
		createSubmitted=0;
		return false;
	}*/

		
	if($('hidCompanyIndex').value)
		var companyIndex = $('hidCompanyIndex').value;
	
	$('useInvitationMsgFlag').value = 0;
	if($("useInvitationMsg") && $("useInvitationMsg").checked == true){
		$('useInvitationMsgFlag').value = 1;
	}

	$('selectedCurrentGroupUsers').value = "";
	
	for (j=1; j<=companyIndex; j++)
	{
		cntUsers = $('compUserCount_' + j).value;
		for(i=0;i<cntUsers;i++){
			if($('currentGroupUsers_'+j+'_'+i).checked == true && $('currentGroupUsers_'+j+'_'+i).disabled == false){
				mailidCnt = mailidCnt + 1;
				tmpStr = ($('currentGroupUsers_'+j+'_'+i).value).split("~");
				$('selectedCurrentGroupUsers').value = tmpStr[0] + "," + $('selectedCurrentGroupUsers').value;
			}
		}
	}
	
	var additionalGroupUserDetails = '';
	var tmpStr = '';
	var tmpVal = '';
	counter = 0;
	cnter = 5;
	$('additionalGroupUsers').value = "";
	$('additionalGroupUsersTrack').value = "";
	for(i=1;i<=cnter;i++){
	
		userEmailId = trimAll($('additionalGroupUser'+i).value);
		userEmailIdValidChk = $('additionalGroupUserRes'+i).value;
		
		if(userEmailId != '' && userEmailIdValidChk != '0'){
			tmpStr = userEmailId+"@"+this.companyDomain;
			tmpVal = userEmailIdValidChk;
			mailidCnt = mailidCnt + 1;
			if($('additionalGroupUsers').value == '') {
				$('additionalGroupUsers').value = tmpStr;
			} else {
				$('additionalGroupUsers').value = tmpStr + "," + $('additionalGroupUsers').value;
			}
			
			if($('additionalGroupUsersTrack').value == '') {
				$('additionalGroupUsersTrack').value = tmpVal;
			} else {
				$('additionalGroupUsersTrack').value = tmpVal + "," + $('additionalGroupUsersTrack').value;
			}
			
			if(counter == 0){
				additionalGroupUserDetails=tmpStr;
			}else{
				additionalGroupUserDetails=additionalGroupUserDetails + ", " +tmpStr;
			}
			counter++;
		}
	}

	if($('inviteUsers').style.display == 'none') {
		if(counter == 0) {
			$('errorCN').style.display = 'block';
			createSubmitted=0;
			return false;
		} else {
			$('errorCN').style.display = 'none';
		}
	}

	
	var outsideGroupUserDetails = '';
	var tmpStr = '';
	var tmpVal = '';
	counter = 0	;
	cnter = 5;
	$('otherGroupUsers').value = "";
	$('otherGroupUsersTrack').value = "";
	for(i=1;i<=cnter;i++){
		userEmailId = trimAll($('outsideCompanyUser'+i).value);
		userEmailIdValidChk = $('outsideCompanyUserRes'+i).value;
		if(userEmailId != '' && userEmailIdValidChk != '0'){
			tmpStr = userEmailId;
			tmpVal = userEmailIdValidChk;
			mailidCnt = mailidCnt + 1;
			if($('otherGroupUsers').value == '') {
				$('otherGroupUsers').value = tmpStr;
			} else {
				$('otherGroupUsers').value = tmpStr + "," + $('otherGroupUsers').value;
			}
			
			if($('otherGroupUsersTrack').value == '') {
				$('otherGroupUsersTrack').value = tmpVal;
			} else {
				$('otherGroupUsersTrack').value = tmpVal + "," + $('otherGroupUsersTrack').value;
			}
				if(counter == 0 ){
				outsideGroupUserDetails=tmpStr;
			}else{
				outsideGroupUserDetails=outsideGroupUserDetails + ", " +tmpStr;
			}
			counter++;
		}
	}
	
	if(mailidCnt == '0') { 
		if($('hidGroupLength').value == 0)
			$('errorZeroUserMsg').style.display = '';		
		else
			$('errorMsg').style.display = '';
		createSubmitted=0;
	} else { 
		$('errorZeroUserMsg').style.display = 'none';
		$('errorMsg').style.display = 'none';
		
		$('grpProjId').value = groupid;
		
		messagesObject.micoxUpload($('frmAddUser'),'/groups/peoples/addNewUsers/','loading','<img src=/groups/img/loading.gif class=loadingImgLeft>','Error', '');
		createSubmitted=0;
	}
	
}

function openInvitePopup() {
		createSubmitted = 0;
		var grpID = ($('currGrpId').value == 'ovr') ? $('networkId').value : $('currGrpId').value;
		showAddUserTab(grpID);
	}
function checkRequestCounter() {
	if(objPeople.requestCounter == 0) {
		clearInterval(intervalId);		
		addMoreUsers();
	}
}

	function uncheckButton(button,id) {
		
		if($(id).value == 0) {
			
			$(id).value =1;
		}
		else{
			button.checked=false;
			$(id).value = 0;
		}
	}
	
	//peoples end
	var seldivCat = '';
	var globalClassVarCat,globalAdd_RemIdCat,globalAddQTCat,globalAdd_RemNameCat;
	globalAddQTCat=0;
	function changeSelRemClassCat(divId,flag,classVar,addRem,id,name)
	{		
		window.globalAddQTCat = addRem;	
		if($(window.seldivCat))$(window.seldivCat).className =$(window.seldivCat).className.replace(globalClassVarCat+'Sel',globalClassVarCat);
		
		if(flag=='1'){//1 = mouseover
		
			window.globalClassVarCat=classVar;
			window.globalAdd_RemIdCat=id;
			window.globalAdd_RemNameCat=name;
			window.seldivCat=divId;
			
			if($(divId).className.match('Sel')==null)
			{
				$(divId).className =$(divId).className.replace(classVar,classVar+'Sel');
			}

			$('addQuickTabCat').innerHTML = 'Remove';
			//$('addQuickTabCat').attributes["onclick"].value = "generalsettings.removeCategories('"+name+"',"+id+",0)";

			var f = findPosition(divId);
			$('addQuickTabCat').style.display = 'block';
			
			if($('memberTabDMT'))
			var topPaddingQuickTab = -45;
			else
			var topPaddingQuickTab = -40;
			
			var leftPaddingQuickTab = -16;

			if(BrowserDetect.browser != 'Opera'){	
				if(BrowserDetect.browser == 'Explorer') {
					if($('memberTabDMT'))
						$('addQuickTabCat').setStyle({ top : f[1]-topPaddingQuickTab - 25 + 'px' });
					else
						$('addQuickTabCat').setStyle({ top : f[1]-topPaddingQuickTab - 20 + 'px' });
					
					$('addQuickTabCat').setStyle({ left : f[0]-leftPaddingQuickTab - 16 + 'px' });
				}else{
					$('addQuickTabCat').setStyle({ marginTop : f[1]-topPaddingQuickTab  + 'px' });
					$('addQuickTabCat').setStyle({ marginLeft : f[0]-leftPaddingQuickTab + 'px' });
				}
			}else{
					$('addQuickTabCat').setStyle({ marginTop : f[1]-topPaddingQuickTab + 'px' });
					$('addQuickTabCat').setStyle({ marginLeft : f[0]-leftPaddingQuickTab + 'px' });
			}			
		}else{
			
			$('addQuickTabCat').style.display = 'none';
		}
		
	}

function ta_autoresize(txtbox)
{
    var cols = txtbox.cols ;
    var content = txtbox.value ;
    var lineCount = 1 ;
    var lastEOL = -1 ;
    do {
        var begin = lastEOL+1 ;
        lastEOL = content.indexOf("\n",lastEOL+1) ;
        var line = "" ;
        if(lastEOL != -1) {
            line = content.substring(begin,lastEOL) ;
        } else {
            line = content.substring(begin,content.length) ;
        }
        var rows_in_line = Math.floor(line.length/cols)+1 ;
        lineCount += rows_in_line
    } while (lastEOL != -1) ;
    txtbox.rows = lineCount ;
	//if(txtbox.identify() == 'talkTxtArea') limitChars(txtbox, 200)
}

function limitChars(textarea, limit)
{
	var text = textarea.value; 
	var textlength = text.length;
	 
	if(textlength > limit)
	{
		textarea.value = text.substr(0,limit);
		return false;
	}
	else
	{
		return true;
	}
}


/*********************   Marketing Class Starts    *******************************/

var marketing =Class.create();
marketing.prototype = {
	data: [],
	_type: '',
	initialize: function () {	
	
	},
	
 
	cmpEmail: function(type){
	
		var idTxt = "";
		var errDiv = "";
		var contentDiv = "";
		if(type == "sc1")
		{
			this._type = "sc1";
			idTxt="txtCmpEmail";
			errDiv = "sc1ErrMsg";
			contentDiv = "sc1CmpEmail";
		}
		else if(type == "sc2")
		{
			this._type = "sc2";
			idTxt="txtCmpEmailP";
			errDiv = "sc2ErrMsg";
			contentDiv = "sc2CmpEmail";
		}
		else if(type == "sc2b")
		{
			this._type = "sc2b";
			idTxt="txtCmpEmailSc2b";
			errDiv = "sc2bErrMsg";

			//contentDiv = "sc2CmpEmail";
		}

		var email = $(idTxt).value;
	
		if(email == "" || email == "your company email id")
		{
			$(errDiv).style.display = 'block';
			$(errDiv).innerHTML = "Please enter email address.";
			//alert("Please enter e-mail address.");
		}
		else if (!(email.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1))
		{
			$(errDiv).style.display = 'block';
			$(errDiv).innerHTML = "Please enter a valid email address.";
    		//alert("Please enter a valid e-mail address.");
		}
		else
		{			
			var resCmpEmail = this.resCmpEmail.bind(this);
			var url    = '/groups/groups/saveCompanyEmail.json';
			var rand   = Math.random(9999);
			var pars   = '?rand=' + rand + '&email=' + email;
			var myAjax = new Ajax.Request( url, {method: 'post',parameters: pars, onComplete: resCmpEmail} );
		}
	},
	resCmpEmail: function(res){
		if(res.responseText == "expired"){      
			window.location = MainUrl + "main.php?u=signout";return;
		}
		var errDiv = "";
		if(this._type == "sc1")
		{
			errDiv = "sc1ErrMsg";
		}
		else if(this._type == "sc2")
		{
			errDiv = "sc2ErrMsg";
		}
		else if(this._type == "sc2b")
		{
			errDiv = "sc2bErrMsg";
		}
		
		var resMsg = res.responseText;
		$(errDiv).style.display = 'block';
		if(resMsg == "free")
		{
			//alert("its a free domain");
			$(errDiv).innerHTML = "Please enter a valid company email id";
		}
		else if(resMsg == "exists")
		{
			//alert("already exists");
			$(errDiv).innerHTML = "E-mail ID Already Exists";
		}
		else if(resMsg == "success")
		{
			$(errDiv).className = "confirm";
			$(errDiv).innerHTML = "A verification email has been sent to you. Please verify your company email id.";
			
			if(this._type == "sc1")
				$('sc1CmpEmail').style.display = "none";
			else if(this._type == "sc2")
			{
				$('cmpyEmailPopup').fade({duration: 0.5});
				
				var type = $('type').value;
				var id = $('id').value;
				var url = "";
				
				if(type == 'P')
					url = "/groups/projects/view/"+id;
				else if(type == 'G')
					url = "./"+id;
					
				if(!id)
					url = "/groups/groups/";
					//url = "./view/";
					
				setTimeout('$("psuccess1").fade()',2000);
				setTimeout('window.location = "'+url+'";', 3000);
				$(element).style.display = 'none';
				$('psuccess1').appear({duration: 1.2})
			}
			else if(this._type == "sc2b")
			{
				$('sc2bCmpEmail').style.display = "none";
			}
		}	
	},
	
	emails: function(type){
	
		var errDiv = "";
		if(type == 'sc3a')
		{
			this._type = "sc3a";
			errDiv = "sc3aErrMsg";
			cntDiv = "sc3ainvite";
		}
		else if(type == "sc3b")
		{
			this._type = "sc3b";
			errDiv = "sc3bErrMsg";
		}
		var emails = document.getElementsByName('txtemail');
		var arrLength = emails.length;
		
		var emailArr = "";
		
		var isEmpty = 0;
		var isValid = 1;
		var j=0;
		for(i=0; i<arrLength; i++)
		{
			if(emails[i].value != "")
			{
				isEmpty = 1;
				var email=emails[i].value;
				var ext = 0;
				if(email.indexOf("@") != -1)
				{
					var dIndex = email.indexOf('@');
					var domain = email.slice(dIndex+1,email.length);
					ext = domain.slice((domain.lastIndexOf('.')+1),domain.length).length;
				}

				if(ext >=2 && ext <4)
				{
					isValid = 0;
					$(errDiv).style.display = 'block';
					$(errDiv).innerHTML = "Do not enter domain names as they are already appended";
					emails[i].focus();
				}
				else if(!(email.search(/^[\.\a-zA-Z0-9_-]*$/)!= -1))
				{
					isValid = 0;
					$(errDiv).style.display = 'block';
					$(errDiv).innerHTML = "This is not a valid email address";
					emails[i].focus();
				}
				else
				{
					if(j==0)
						emailArr=email;
					else
						emailArr = emailArr+"<>"+email;
					
					j++;
				}

			}
		}
		
		if(isEmpty == 0)
		{
			//alert("You need to enter an email address to invite someone");
			$(errDiv).style.display = 'block';
			$(errDiv).innerHTML = "You need to enter an email address to invite someone";
		}
		else if(isValid)
		{
			//alert(emailArr.length);
			var resInvt = this.resInvt.bind(this);
			var url    = '/groups/groups/inviteColleague.json';
			var rand   = Math.random(9999);
			var pars   = '?rand=' + rand + '&txtEmails=' + emailArr;
			var myAjax = new Ajax.Request( url, {method: 'post',parameters: pars, onComplete: resInvt} );

		}

	},
	
	resInvt: function(res)
	{
		if(res.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
		}
		
		if(this._type == 'sc3a')
		{
			$('sc3ainvite').style.display = 'none';
			$('sc3Msg').className = "confirm";
			$('sc3Msg').innerHTML = "Email(s) sent successfully.";
		}
		else if(this._type == "sc3b")
		{
			$('invtpopup').hide();			
			var type = $('type').value;
			var id = $('id').value;
			var url = "";
			if(type == 'P')
				url = "/groups/projects/view/"+id;
			else if(type == 'G')
				url = "./"+id;
			else if(!type && !id)
					url = "./";
			/*		
			setTimeout('document.getElementById("psuccess").style.display = "none";window.location = "'+url+'";', 2000);
			$(element).style.display = 'none';
			document.getElementById('psuccess').style.display = 'block';	
			*/
			
			setTimeout('$("psuccess").fade()',2000);
			setTimeout('window.location = "'+url+'";', 3000);				
			$(element).style.display = 'none';				
			$('psuccess').appear({duration: 1.2})
				
		}
		
	},
	
	showPopup: function(type)
	{
		this._type = type;
		
		if(type == "sc2")
		{
			$('bgPopCmpEmail').className = 'showwrap';
			$('bgPopCmpEmail').style.height = $('group').scrollHeight + 'px';
			//$('cmpyEmailPopup').style.display = 'block';
			$('cmpyEmailPopup').appear({duration: 1.3});
			
			//Clear the text box before showing
			$('txtCmpEmailP').value = "";
		}
		else if(type == "sc3b")
		{
			this._type = "sc3b";
			$('bgPopIvt').className = 'showwrap';
			$('bgPopIvt').style.height = $('group').scrollHeight + 'px';
			//$('invtpopup').style.display = 'block';
			$('invtpopup').appear({duration: 1.3});
			
			//Clear the text box before showing
			var inps=document.pcolleguesform.getElementsByTagName('input');
			var emlLt = inps.length;
			for(i=0;i<emlLt;i++)
				inps[i].value = "";
				
		}
		else if(type == "sc4")
		{
			if($('grpid'))
				this.groupInvites();
				
			var id = $('id').value;
			url = "./"+id;
			if(!id)
				url = "./";
			//window.location = url;
			setTimeout('window.location = "'+url+'";', 500);			
		}		
	},
		
	hidepopup: function(element)
	{
		//$(element).style.display = 'none';
		var type = $('type').value;
		var id = $('id').value;
		var url = "";
		if(type == 'P')
			url = "/groups/projects/view/"+id;
		else if(type == 'G')
			url = "./"+id;
		else if(!type && !id)
			url = "./";
		
		if(this._type == "sc2" && !id)
			url = "/groups/groups/";
		
		window.location = url;						
	}, 
	
	addBox :function(currElement,type)
	{
		var width = "";
		var inps = "";
		var domainId = "";
		
		if(type == 'popup')
		{
			domainId = "pdomain";
			inps=document.pcolleguesform.getElementsByTagName('input');
			width = "65%";
		}
		else
		{
			domainId = "npdomain";
			inps=document.colleguesform.getElementsByTagName('input');
			width = "65%";
		}
		var currBox = parseInt(currElement);
		
		var index = parseInt(currBox-2);
		var inpsLt = inps.length;
		var prevBoxVal = inps[index].value;
		var newBoxNo = parseInt(inps[inpsLt-1].getAttribute('v'))+1;
		var onFocusEvt = "mkt.addBox("+newBoxNo+",'"+type+"')";
		
		if(prevBoxVal != "" && (inpsLt == (currBox+1)))
		{
			var parTag = document.createElement('p');
			parTag.setAttribute('class','field');
			var newBox = document.createElement('input');
			newBox.setAttribute('type','text');
			//newBox.setAttribute('class','inputRight');
			newBox.setAttribute('name','txtemail');
			newBox.setAttribute('v',newBoxNo);
			newBox.setAttribute('onFocus',onFocusEvt);
			newBox.style.width = width;
			var domain = $(domainId).value;
			domain = "@"+domain;
			
			var span = document.createElement('span');
			span.innerHTML = domain;
			parTag.appendChild(newBox);
			parTag.appendChild(span);
			if(type == "popup")
			{
				/*
				document.pcolleguesform.appendChild(newBox);
				document.pcolleguesform.appendChild(span);
				*/
				document.pcolleguesform.appendChild(parTag);
			}
			else
			{
				/*
				document.colleguesform.appendChild(newBox);
				document.colleguesform.appendChild(span);
				*/
				document.colleguesform.appendChild(parTag);
			}
		}
				
	},
	
	groupInvites: function()
	{
		var userID = $('userID').value;
		var grpIDs = $('grpid').value;

		var url    = '/groups/groups/groupInvites.json';
		var rand   = Math.random(9999);
		var pars   = '?rand=' + rand + '&userID=' + userID + '&grpIDs=' + grpIDs;
		var myAjax = new Ajax.Request( url, {method: 'post',parameters: pars} );
	},
	
	goTogroups: function()
	{
		var url    = '/groups/groups/updDTM.json';
		var rand   = Math.random(9999);
		var pars   = '?rand=' + rand;
		var myAjax = new Ajax.Request( url, {method: 'post',parameters: pars,
											onSuccess:function(transport)
											{
												if(transport.responseText == "expired")
										        {      
										        	window.location = MainUrl + "main.php?u=signout";return;
										        }
										        else
										        {
													paginateData('/groups/groups/view.json','1');
										        }	
											}
		} );
	}
	
};

var mkt;
mkt = new marketing;



/*********************************** Marketing Class Ends ************************/

function revSidebar(self,scrollDiv,name,level,poster,showDel,type,oid,version){

	var levelStr = '';
	if(level == '1')levelStr = 'network';
	else if(level == '2')levelStr = 'group';//proj2Gr
	else if(level == '3')levelStr = 'subgroup';//proj2Gr
	$('sidebarRev').style.display = 'block';
	$('groupnameRev').innerHTML = name+' '+levelStr;
	$('usernameRev').innerHTML = poster;
	
	if(showDel == "1")
	{
		var delId = '';
		if(type == "MilestoneRHS"){
			delId = 'delLinkMilRHS'+version;
		}	
		else if(type == "TaskRHS")
		{
			delId = 'delLinkTaskRHS'+oid;
		}	
		
		$(delId).show();
	}	
	
	var f = findPosition(self);	
	var scrolled1 = document.getElementById("milestoneGroupScrollOverviewMy").scrollTop;			
	var scrolled2= document.getElementById("milestoneGroupScrollOverviewMyA").scrollTop;
	var scrolled = 0;
	if(scrollDiv == "milestoneGroupScrollOverviewMy")
		scrolled = f[1]-scrolled1-208;
		
	if(scrollDiv == "milestoneGroupScrollOverviewMyA")
		scrolled = f[1]-scrolled2-208;
		
	if(BrowserDetect.browser == 'Explorer'){
		if(scrollDiv == "milestoneGroupScrollOverviewMy"  && scrolled1 > 0)
		scrolled = f[1]-200;
		
		if(scrollDiv == "milestoneGroupScrollOverviewMyA"  && scrolled2 > 0)
		scrolled = f[1]-200;
	}
	
	/*
	if($('profileContainer') || ($('profileContainer') && $('profileContainer').style.display == 'none')){
	}else{
		//scrolled = scrolled - 126;	
	} 
	*/
	if($('sc4') && $('sc4').style.display != 'none')
	scrolled = scrolled - $('sc4').offsetHeight -22;
	if($('sc2') && $('sc2').style.display != 'none')
	scrolled = scrolled - $('sc2').offsetHeight -22;
	
	intRight = 	((windowWidth-1280)/2)+384;
	
	if((BrowserDetect.browser == 'Chrome' ||  BrowserDetect.browser == 'Opera') && windowWidth > 1280){
		intRight = intRight - 7;
	}
	if(windowWidth<=1100)
		intRight = 314;
	else if(windowWidth<=1280)
		intRight = 404;
	$('sidebarRev').setStyle({
		marginTop : scrolled + 'px',
		right:intRight + 'px' ///ie , ff , safari
	});
}



/****** Funtions From UTIL.JS **********************/

function browserWindowSize(level) {
var browserWinWidth = 0, browserWinHeight = 0;
switch(level)
{
	case 0:
			if( typeof( window.innerWidth ) == 'number' ) 
			{
				//Non-IE
				browserWinWidth = window.innerWidth;
				browserWinHeight = window.innerHeight;
			} 
			else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight) ) 
			{
				//IE 6+ in 'standards compliant mode'
				browserWinWidth = document.documentElement.clientWidth;
				browserWinHeight = document.documentElement.clientHeight;
			} 
			else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) 
			{
				//IE 4 compatible
				browserWinWidth = document.body.clientWidth;
				browserWinHeight = document.body.clientHeight;
			}
			break;
	 case 1:
			if( typeof( parent.window.innerWidth ) == 'number' ) 
			{
				//Non-IE
				browserWinWidth = parent.window.innerWidth;
				browserWinHeight = parent.window.innerHeight;
			} 
			else if( parent.window.document.documentElement && ( parent.window.document.documentElement.clientWidth || parent.window.document.documentElement.clientHeight ) ) 
			{
				//IE 6+ in 'standards compliant mode'
				browserWinWidth = parent.window.document.documentElement.clientWidth;
				browserWinHeight = parent.window.document.documentElement.clientHeight;
			} 
			else if( parent.window.document.body && ( parent.window.document.body.clientWidth || parent.window.document.body.clientHeight ) ) 
			{
				//IE 4 compatible
				browserWinWidth = parent.window.document.body.clientWidth;
				browserWinHeight = parent.window.document.body.clientHeight;
			}
			break;
	 case 2:
	 		if( typeof( parent.window.parent.window.innerWidth ) == 'number' ) 
			{
				//Non-IE
				browserWinWidth = parent.window.parent.window.innerWidth;
				browserWinHeight = parent.window.parent.window.innerHeight;
			} 
			else if( parent.window.parent.window.document.documentElement && ( parent.window.parent.window.document.documentElement.clientWidth || parent.window.parent.window.document.documentElement.clientHeight ) ) 
			{
				//IE 6+ in 'standards compliant mode'
				browserWinWidth = parent.window.parent.window.document.documentElement.clientWidth;
				browserWinHeight = parent.window.parent.window.document.documentElement.clientHeight;
			} 
			else if( parent.window.parent.window.document.body && ( parent.window.parent.window.document.body.clientWidth || parent.window.parent.window.document.body.clientHeight ) ) 
			{
				//IE 4 compatible
				browserWinWidth = parent.window.parent.window.document.body.clientWidth;
				browserWinHeight = parent.window.parent.window.document.body.clientHeight;
			}
			break;			 				
	 default:
	 		browserWinHeight = 0;
	 		browserWinWidth = 0;
} 		
return [browserWinHeight,browserWinWidth];
}

function sz(t) {
var tempVar = 3;
 var obj = t;
if ( obj == null ) {
    return false;
  }

  // NOTE: This is using regular expressions to count spaces and linefeeds
  var objText = obj.value;

  var linefeedsArray = objText.match(/[\n\r]/g);
  var linefeeds = ( linefeedsArray == null ? 0 : linefeedsArray.length);
  var extraRows = Math.round( objText.length / obj.cols );

  var newRows = linefeeds + extraRows;

  if ( newRows >= tempVar )  {
  	tempVar = parseInt(tempVar) + parseInt(3);
    obj.rows=newRows;
  }
  
/*
  a = t.value.split('\n');
  ///alert(a.length);
b=1;
for (x=0;x < a.length; x++) {
 if (a[x].length >= t.cols) b+= Math.floor(a[x].length/t.cols);
 }
b+= a.length;
if (b > t.rows) t.rows = b;
 */  
}

/****** Funtions From UTIL.JS ENDS**********************/

function returnFullUrl(strUrl)
	{
		
		var strTempUrl = new String(strUrl);
		if (strUrl.match("http://"))
		{
			var regEx1 = new RegExp ('http://', 'gi') ;
			var strTemp = strUrl.replace(regEx1, '');
			strTempUrl = "http://" + strTemp;
		}
		else if (strUrl.match("https://"))
		{
			var regEx2 = new RegExp ('https://', 'gi') ;
			var strTemp = strUrl.replace(regEx2, '');
			strTempUrl = "https://" + strTemp;
		}
		else
		{
			strTempUrl = "http://" + strUrl;
		}
		return strTempUrl;
}

function formattedProjectName(name)
{
	var str = name;
	var strLength = str.length;
	if(strLength > 25)
	{
		endSubStr = strLength-7;
		var subStr = str.substr(0,15)+'...'+str.substr(endSubStr,strLength);
		return subStr;
	}
	else
		return str;
}

function formattedName(name,len)
{
	var str = name;
	var strLength = str.length;
	if(strLength > len)
	{
		endSubStr = strLength-7;
		var subStr = str.substr(0,10)+'...'+str.substr(endSubStr,strLength);
		return subStr;
	}
	else
		return str;
}


function showQuikTab(para){
	if(para == '1'){
	$('navigate_groups').style.maxWidth = '1100px';
	$('moreTabTitleDiv').innerHTML = '<h1>Bookmark your groups</h1>';//proj2Gr
	$('moreTabDesc').innerHTML = 'Choose any group you want to bookmark from here. They will always appear on the dashboard so you can access them quickly. You can change bookmarks as often as you like from here but you can bookmark only upto 2 groups.';//proj2Gr
	$('quicktabContent').show();
	$('quicktabContentNav').hide();
	$('assignQuickTabDiv').hide();
	$('saveQuickTabDiv').show();
	}
	
	if(para == '0'){
		if($('noOfchangesInTab').value > 0){
			closeQuickTabAlert('0');
			return false;
		}
	$('navigate_groups').style.maxWidth = '900px';	
	$('moreTabTitleDiv').innerHTML = '<h1>Navigate Groups</h1>';//proj2Gr
	$('moreTabDesc').innerHTML = 'All your groups are listed here. To navigate to a group, click on its name.';//proj2Gr
	$('quicktabContent').hide();
	$('quicktabContentNav').show();
	$('assignQuickTabDiv').show();
	$('saveQuickTabDiv').hide();
	}
}
function closeQuickTabAlert(para){
	if(para == '0'){
		//$('quickTabOkButton').attributes["onclick"].value = 'resetQuikTab()';
		$('quickTabOkButton').onclick = function(){resetQuikTab();}
		$('quickTabErrOnNavPopup').show();	
	}
	
	if(para == '1'){
		if($('noOfchangesInTab').value > 0){
			//$('quickTabOkButton').attributes["onclick"].value = "document.getElementById('quickTabErrOnNavPopup').style.display='none';setHeaderMore('moreTabLink',0)";
			$('quickTabOkButton').onclick = function(){
				document.getElementById('quickTabErrOnNavPopup').style.display='none';
				setHeaderMore('moreTabLink',0);
			}
			
			$('quickTabErrOnNavPopup').show();	
		}else{
			setHeaderMore('moreTabLink',0);
		}
	}	
}

function resetQuikTab(){
	document.getElementById('quickTabErrOnNavPopup').style.display='none';	
	milestonesObject.getQuickTab('0');
}

function saveTodoLists()
{
	var doValidation;
	var errOccured;
	doValidation = 1;
	
	if ($('addtodo').innerHTML != '') 
	{
		doValidation = 0;
	} 
	else 
	{
		if ($('todo_title').value.strip() == '') {
			doValidation = 1;
		} else {
			doValidation = 0;
		}
	}
	
	if (doValidation == 1) 
	{
		//document.getElementById('add_result').style.display = 'block';
		//document.getElementById('add_result').innerHTML = '<div class="err">Please name your Task</div>';
		$('errTaskTitle').show()
		$('todo_title').addClassName('inpErr');
		//return false;
		errOccured = 1;
	}
	else
	{
		var ms = $('ms').value;
		if(ms != '') 
		{
	    	var todoinc = $('todoinc_'+ms).value;
        	$('todoinc').value = todoinc;
		}

		//document.getElementById('add_result').innerHTML = '';
		//document.getElementById('add_result').style.display = 'none';
		$('errTaskTitle').hide()
		$('todo_title').removeClassName('inpErr');
	}

	var tmp = document.getElementById('responsible').options[document.getElementById('responsible').selectedIndex].value
	if(tmp == '0')
	{
		//document.getElementById('add_result').style.display = 'block';
		//document.getElementById('add_result').innerHTML = '<div class="err">Please select who\'s responsible for the task</div>';
		$('errTaskUser').show()
		$('responsible').addClassName('inpErr');
		//return false;
		errOccured = 1;
	}else{
		$('errTaskUser').hide()
		$('responsible').removeClassName('inpErr');
	}
	
	if(errOccured) return false; 
	
	return true; 
}


var displayPeopleMTD = Class.create();
displayPeopleMTD.prototype = {
	data: [],
	
	requestCounter: 0,
	initialize: function () {
	},
	fillHolder: function () {
		var peopleInGroup = new Array();
		if (nothing != 'updateUser') { 
			var gorp = this.data.gorp;
			var id = this.data.id;
			var createdby = this.data.createdby;
			var nothing = this.data.updateUser;
			var loginuser = this.data.loginuser;
			var roles = this.data.roles;
			var userRole = this.data.userRole;
			var internalUsers = this.data.internalUsers;
			var externalUsers = this.data.externalUsers;
			var grpType = this.data.type;
			var grpName=this.data.grpName;
			// alert(roles['Administrator']['level']);
			window.adminUsersGroup=this.data.adminUsersGroup;
           var level=this.data.level;
            
			$('pplGrpType').value = grpType;
			if($('pplEmailMsg'))
				$('pplEmailMsg').innerHTML = "This is the e-mail that is sent to the people you are inviting to the "+ (grpType == 'G' ? 'group' : 'subgroup') +".  If you feel like writing one, great.  If not, we'll send a default message.<br> ";//proj2Gr
			var html = '';
			$('grpProjId').value = id;
			
			html = '';
			/*
			if (userRole >= roles['Moderator']['level']) {
				html +=	'<div>' 
					+'<div class="rightFloat" style="padding-bottom:10px">'
					+'<a href="javascript:void(0);" class="but" onclick="createSubmitted=0;showAddUserTab(' + id + ')">'
					+'<span class="lineHit30">Add new user</span>'
					+'</a></div>'					 
					+'<!-- All company and group -->';
			}
			*/
			html += '<div style="clear:right"></div>';
			
			var odd = true;
			var oldCompanyName = '';
			var disabled = '';
			var updateUser = 'updateUser';
			var companyName = '';
			internalUsers.each( function (internalUser) { 
				if (oldCompanyName != internalUser[0].companyname) {
					if (oldCompanyName != '') {
						html +=	'<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>		' +
								'	</div>';								
					}
                    //alert(internalUser.userpersonalinfo.username);
					oldCompanyName = internalUser[0].companyname;
					companyName = internalUser[0].companyname;
					companyName = companyName.toUpperCase(); 
					//<div class="grpTitle" ><h1><strong>' + companyName + '</strong></h1></div>
					html += '<div style="clear:left">&nbsp;</div>' 
							+'<div class="groupThreadContent11" style="padding-top:25px;>'
							+'<div class="peopleGrpRows" style="margin:0 0 20px;">';
				}

				if (gorp == 'group') {

					disabled = '';
					if (internalUser.groups_users.user_id == loginuser || internalUser.groups_users.user_id == createdby || userRole < roles['Moderator']['level']) {
						disabled = 'disabled';
					}					
                   // alert(internalUser.groups_users.user_id);
                    
				html += '<div class="people">'
					+'<div>'
						+'<div class="leftFloat"><img src="'+ internalUser[0].image +'" width="50" border="0" onmouseout="hideVCard(\''+internalUser.groups_users.user_id+'\');" onmouseover="showVCard(\''+internalUser.groups_users.user_id+'\',this,event);"></div>'
						+'<div class="peopleUsageData">'
							+'<h2><a onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\']; showProfilePage(obj,\'public\',\''+internalUser[0].encryptuserid+'\');" href="javascript:void(0);">' + internalUser.userpersonalinfo.username + '</a></h2>'
							+'<p class="blueColor">'+ emailIdWrap(internalUser.usercompanynetworkmst.emailid) +'</p>'
							+'<p><span>storage used:</span><span class="blueColor">' + internalUser[0].sizeusage + ' MB</span></p>';
				
				if(internalUser[0].LastLogin != '')
				{	
					html += '<p><span>last signed in:</span><span class="blueColor"> ' + internalUser[0].LastLogin + '</span></p>';
				}
				else
				{
					html += '<p> &nbsp; </p>';
				}
				
				var adminChecked = ''; var modChecked = ''; var userChecked = '';
				var adminClass = ''; var modClass = ''; var userClass = '';
				
				if(internalUser.groups_users.role_id == roles['Administrator']['id'])
				{
					adminChecked = 'checked '; adminClass = 'selected ';
				}
				else if(internalUser.groups_users.role_id == roles['Moderator']['id'])
				{
					modChecked = 'checked '; modClass = 'selected ';
				}
				else if(internalUser.groups_users.role_id == roles['User']['id'])
				{
					userChecked = 'checked '; userClass = 'selected ';
				}

               if(internalUser.groups_users.role_id == 3 && loginuser==internalUser.groups_users.user_id )
                   {
                   //alert('1');
				html +=	'</div>'
					+'</div>'
					+'<div class="borderTop paddingTopBottom">'
						+'<p class="leftFloat">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['User']['id'] + '^' + internalUser.groups_users.role_id + '" ' + userChecked + disabled + ' style="margin:2px"></p><p class="leftFloat '+ userClass +'">&nbsp;User</p><p class="leftFloat paddingLeft10">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Moderator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + modChecked + disabled + '></p><p class="leftFloat '+ modClass +'">&nbsp;Moderator</p><p class="leftFloat paddingLeft10">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + adminChecked + disabled + ' ></p><p class="leftFloat '+ adminClass +'">&nbsp;Manager</p><p class="leftFloat paddingLeft10">'
                            
					+'</div>'
				+'</div>';
                   }
                   else 
                       {
                          //  alert('2');
                         var Remove='SEPr';
                          html +=	'</div>'
					+'</div>'
					+'<div class="borderTop paddingTopBottom">'
						+'<p class="leftFloat">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['User']['id'] + '^' + internalUser.groups_users.role_id + '" ' + userChecked + disabled + ' style="margin:2px"></p><p class="leftFloat '+ userClass +'">&nbsp;User</p><p class="leftFloat paddingLeft10">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Moderator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + modChecked + disabled + '></p><p class="leftFloat '+ modClass +'">&nbsp;Moderator</p><p class="leftFloat paddingLeft10">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^' + internalUser.groups_users.role_id + '" ' + adminChecked + disabled + ' ></p><p class="leftFloat '+ adminClass +'">&nbsp;Manager</p><p class="leftFloat paddingLeft10">'
                           // +'<input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id+ '" value="'+ internalUser.groups_users.user_id+Remove + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^'+  '" ></p><p class="leftFloat">&nbsp;Remove</p>'
                            +'<input type="checkbox" class="borderNone" name="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id+ '^' + internalUser.groups_users.group_id+ '" value="'+ internalUser.groups_users.user_id+Remove + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '^'+internalUser.groups_users.role_id+'^'+internalUser.userpersonalinfo.username+ '@G'+grpName+ '" ></p><p class="leftFloat">&nbsp;Remove</p>'
                    +'</div>'
				+'</div>';
                       }
                       
					/*html += '<tr class="' + (odd ? 'alt' : '') + '">' +
						'<td>' + internalUser.userpersonalinfo.username + '</td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['User']['id'] + '" ' + (internalUser.groups_users.role_id == roles['User']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Moderator']['id'] + '" ' + (internalUser.groups_users.role_id == roles['Moderator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" id="radio^' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '" value="' + internalUser.groups_users.user_id + '^' + internalUser.groups_users.group_id + '^' + roles['Administrator']['id'] + '" ' + (internalUser.groups_users.role_id == roles['Administrator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td style="text-align: right">' + internalUser[0].sizeusage + ' MB</td>' +
						'<td>' + internalUser.usermst.lastlogin + '</td>' +
						'</tr>';*/
				} else {
					
					disabled = '';
					if (internalUser.projects_users.user_id == loginuser || internalUser.projects_users.user_id == createdby || userRole < roles['Moderator']['level']) {
						disabled = 'disabled';
					}	
					
					
				var adminChecked = ''; var modChecked = ''; var userChecked = '';
				var adminClass = ''; var modClass = ''; var userClass = '';
				
				if(internalUser.projects_users.role_id == roles['Administrator']['id'])
				{
					adminChecked = 'checked '; adminClass = 'selected ';
				}
				else if(internalUser.projects_users.role_id == roles['Moderator']['id'])
				{
					modChecked = 'checked '; modClass = 'selected ';
				}
				else if(internalUser.projects_users.role_id == roles['User']['id'])
				{
					userChecked = 'checked '; userClass = 'selected ';
				}
				
					html += '<div class="people">'
					+'<div>'
						+'<div class="leftFloat"><img src="../../img/pic.gif"></div>'
						+'<div style="margin-left:67px; padding-bottom:5px;">'
							+'<h2>' + internalUser.userpersonalinfo.username + '</h2>'
							+'<p class="blueColor">ben.jhonson@ uberdesign.com</p>'
							+'<p><span>storage used:</span><span class="blueColor">' + internalUser[0].sizeusage + ' MB</span></p>'
							+'<p><span>last signed in:</span><span class="blueColor"> ' + internalUser.usermst.lastlogin + '</span></p>'
						+'</div>'
					+'</div>'
					+'<div class="borderTop paddingTopBottom">'
						+'<p class="leftFloat pRadio">'
							+'<input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['User']['id'] + '" ' + userChecked + disabled + ' style="margin:2px"></p><p class="leftFloat '+ userClass +' pRadio" style="padding-top: 2px;">&nbsp;User</p><p class="leftFloat paddingLeft10 pRadio">'
							//+'<input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Moderator']['id'] + '" ' + modChecked + disabled + '></p><p class="leftFloat '+ modClass +'">&nbsp;Moderator</p><p class="leftFloat paddingLeft10">'
							+'<input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Administrator']['id'] + '" ' + adminChecked + disabled + ' ></p><p class="leftFloat '+ adminClass +' pRadio" style="padding-top: 2px;">&nbsp;Manager</p><p></p>'
					+'</div>'
				+'</div>';				

					/*html += '<tr class="' + (odd ? 'alt' : '') + '">' +
						'<td>' + internalUser.userpersonalinfo.username + '</td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['User']['id'] + '" ' + (internalUser.projects_users.role_id == roles['User']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Moderator']['id'] + '" ' + (internalUser.projects_users.role_id == roles['Moderator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td class="number"><input type="radio" class="borderNone" name="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" id="radio^' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '" value="' + internalUser.projects_users.user_id + '^' + internalUser.projects_users.project_id + '^' + roles['Administrator']['id'] + '" ' + (internalUser.projects_users.role_id == roles['Administrator']['id'] ? 'checked="checked"' : '') + disabled + ' /></td>' +
						'<td style="text-align: right">' + internalUser[0].sizeusage + ' MB</td>' +
						'<td>' + internalUser.usermst.lastlogin + '</td>' +
						'</tr>';*/

				}
							
						odd = !odd;
			}

    );

			if (oldCompanyName != '') {
				html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';
						
                
				if (userRole >= roles['Moderator']['level']) 
				{ 
					var url = '/groups/domains/saveUsers/'+userRole+'~~'+loginuser+'~~'+createdby;
					
					html += '<div style="width:600px;"><span id="errorMessageMem" style="display:none" class="err">Please select atleast 1 member to remove from the ' + companyName + ' intranet.</span></div>'
						+'<div style="padding:0px 20px 20px 0px;" >'
						//+'<div class="butRow"><a href="javascript:void(0);" class="but" onclick="micoxUpload(\'users\',\''+url+'\',\'add_loading_img\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;">'
						+'<div class="leftFloat" style="padding:10px 0;"><a href="javascript:void(0);" class="but" onclick="javascript:people_confirm_MTD(\'users\',\''+url+'\',\'add_loading_img\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;">'
                        +'<span>Update</span>'
						+'</a></div><div id="add_loading_img" class="leftFloat" style="padding-left:10px;padding-top:10px;" ></div><input type="hidden" name="userIdListAdmin" id="userIdListAdmin" value="">'							
						+'</div><div class="clear"></div>'
						+'</div>'
						+'<br style="clear: both;"/>';
				}
				else
				{
					html += '</div>';
				}
				
				

			}	//alert(gorp);
			
			oldCompanyName = '';

			externalUsers.each( function (externalUser) 
			{
				if(peopleInGroup[externalUser.groups_users.user_id] != undefined) return;	
				else peopleInGroup[externalUser.groups_users.user_id] = externalUser.groups_users.user_id;
				if (oldCompanyName != externalUser[0].companyname) 
				{
					if (oldCompanyName != '') 
					{ 			
						html += '</div>';
						html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';
						html += '<div>&nbsp;</div>';
					}				
				
					oldCompanyName = externalUser[0].companyname;
					var companyName = externalUser[0].companyname;
					companyName = companyName.toUpperCase(); 
					html += '<div class="grpTitle" ><h1><strong>' + companyName + '</strong></h1></div>' 
							+'<div class="groupThreadContent">'
							+'<div 	alert(gorp);class="peopleGrpRows">';		
				}
				
				html += '<div class="people">'
						+'<div>'
							+'<div class="leftFloat"><img onMouseOver="showVCard(\''+externalUser.groups_users.user_id+'\',this,event);" onMouseOut="hideVCard(\''+externalUser.groups_users.user_id+'\');" src="'+ externalUser[0].image +'" width="50" border="0"></div>'
						+'<div style="margin-left:67px; padding-bottom:5px;">'
							+'<h2><a href="javascript:void(0);" onclick="obj = Element.immediateDescendants($(\'navProfile\'))[\'0\']; showProfilePage(obj,\'public\',\''+externalUser[0].encryptuserid+'\');">' + externalUser.userpersonalinfo.username + '</a></h2>'
							+'<p class="blueColor">'+ (externalUser[0].emailid?externalUser[0].emailid:externalUser.usercompanynetworkmst.emailid) +'</p>';
				
				if(externalUser[0].LastLogin != '')
				{	
					html += '<p><span>last signed in:</span><span class="blueColor"> ' + externalUser[0].LastLogin + '</span></p>';
				}
				else
				{
					html += '<p> &nbsp; </p>';
				}
				
				html += '</div></div></div>';						
				
			}

    );
			
			html += '</div>';
			html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';
			html += '<div>&nbsp;</div>';

			
			html += '</div></div>';
			html += '<div style="clear:left; height:0px; font-size:1px; line-height:0px;">&nbsp;</div></div>';			
							
			$('leftPanel').innerHTML = html;
		}

		if(nothing == 'addUser') {
			$('successmessage').innerHTML = '<div class="success">Successfully added the people.</div>';
			$('addUserTab').style.display='none';
			$('successpopup').style.display = '';
			centerPos($('successpopup'), 1);
			setTimeout('$(\'successpopup\').style.display = \'none\'',2000);
		} else if (nothing == 'updateUser') {
			$('errorMessage').style.display = '';
			//if($('errorMessage').innerHTML != '<div class=err>You can not change higher level priviledge than yours.</div>')
			$('errorMessage').innerHTML = '<div class="confirm">Successfully made changes.</div>';
		//	parent.$('successpopup').style.display = '';
			setTimeout('$(\'errorMessage\').innerHTML = \'\';$(\'errorMessage\').style.display = \'none\';',2500);
			
		}
		createSubmitted = 0;
	},
	getPeopleData: function (gorp, id, updateUser,level) {
        

		var showPeopleData = this.showPeopleData.bind(this);
		var rand   = Math.random(99999);
		var url    = '/groups/peoples/getpeopledata.json';
		var userID = $('SessStrUserID').value;
        var level=level;
		var pars   = 'id=' + id + '&gorp=' + gorp + '&updateUser=' + updateUser + '&sessUID='+userID+ '&level='+level;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showPeopleData} );
	},
	showPeopleData: function (originalRequest) { //alert(originalRequest.responseText);       
		if(originalRequest.responseText == "expired")
        {      
        	window.location = MainUrl + "main.php?u=signout";return;
        }
        else
        {
			this.data=(eval('(' + originalRequest.responseText + ')'));		
			this.fillHolder();
			//positionFooter();
		}
	},
	getRemovePopupData: function (gorp, id, updateUser,level) {
        

		var showPeopleData = this.showPeopleData.bind(this);
		var rand   = Math.random(99999);
		var url    = '/groups/domains/getRemovePopupData';
		var userID = $('SessStrUserID').value;
        var level=level;
		var pars   = 'id=' + id + '&gorp=' + gorp + '&updateUser=' + updateUser + '&sessUID='+userID+ '&level='+level;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showPeopleData} );
	},
	showRemovePopupData: function (originalRequest) { //alert(originalRequest.responseText);
       // alert(originalRequest.responseText);
		if(originalRequest.responseText == "expired")
        {      
        	window.location = MainUrl + "main.php?u=signout";return;
        }
        else
        {        	
			this.data=(eval('(' + originalRequest.responseText + ')'));		
			this.fillHolder();
			//positionFooter();
		}
	},
	getPrimaryGroupUserInfo: function(groupId,additionalGroupUsersCnt,otherGroupUsersCnt){
		$('allusers').innerHTML = "<img src='/groups/img/loading.gif' class='loadingImgLeft' align='middle'/>";
		var setPrimaryGroupUserInfo = this.setPrimaryGroupUserInfo.bind(this);
		var url    = '/groups/groups/getPrimaryGroupUserInfo.json';
		var rand   = Math.random(9999);
		var userID = $('SessStrUserID').value;
		var pars   = '?groupId='+groupId+'&rand=' + rand+'&additionalGroupUsersCnt=' + additionalGroupUsersCnt+'&otherGroupUsersCnt=' + otherGroupUsersCnt + '&sessUID='+userID;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: setPrimaryGroupUserInfo} );
	},
	setPrimaryGroupUserInfo: function(originalRequest){
		if(originalRequest.responseText == "expired")
        {      
        	window.location = MainUrl + "main.php?u=signout";return;
        }
        else
        {
			this.data = (eval('(' + originalRequest.responseText + ')'));
			this.companyName = this.data.companyinfo[0][0].companyname;
			this.companyDomain = this.data.companyinfo[0].CN.companydomain;
			companyDomain = this.data.companyinfo[0].CN.companydomain;
			$('compUserCount').value = parseInt(this.data.groupuserinfo.length);
			$('companynames').value = '';
	
			var oldCompanyName = '';
			var tmpStrText = '';
			var hiddenCountText = '';
			var count = 0;
			var newCompanyName = '';
						
			var groupsLength = parseInt(this.data.groupuserinfo.length); 
			$('hidGroupLength').value = groupsLength;
			
			if(firstTime == 0) companyIndex = 0; 
			this.data.groupuserinfo.each(function(item,index){
				
				if (oldCompanyName != item['0'].companyname) {
					if (oldCompanyName != '') {
							tmpStrText +=	'		</div> <!-- addNameRow -->';
						if ((count % 3) != 0) {
							tmpStrText +=	'		</div> <!-- addNameRow -->' +
											'	</div> <!-- addName -->';
						}
						tmpStrText +=  hiddenCountText + count + '">';
					}
					companyIndex++;
					newCompanyName = item['0'].companyname;
					newCompanyName = newCompanyName.toUpperCase();
	
					hiddenCountText =	'<input type="hidden" name="selectedCompUserCount_' + companyIndex + '" id="selectedCompUserCount_' + companyIndex + '" value="0">' +
										'<input type="hidden" name="chkAllCompUserStatus_' + companyIndex + '" id="chkAllCompUserStatus_' + companyIndex + '" value="0">' +
										'<input type="hidden" name="compUserCount_' + companyIndex + '" id="compUserCount_' + companyIndex + '" value="';
					tmpStrText +=	'<div class="compnayGrp Grp">' +								
									'		<div id="companyName">' +
									'			<div class="leftFloat"><input name="chkAllCompUsers_' + companyIndex + '" id="chkAllCompUsers_' + companyIndex + '" type="checkbox" class="borderNone" onclick="javascript:objPeople.chkAllCompUsers(\'' + companyIndex + '\')"/></div>&nbsp;<div style="padding: 4px 0px 0px 25px;"><strong> ' + newCompanyName + '</strong></div>' +
									'			<div class="clearLeft"></div>' +
									'		</div>' +
									'</div>' +
									'<div class="addName1" id="companyGroupUserInfo">';
	
					//$('companynames').value = $('companynames').value + ',' + item['0'].companyname + '_' + companyIndex;
					
					oldCompanyName = item['0'].companyname;				
					count = 0;
				}
				
				$('hidCompanyIndex').value = companyIndex;
				
				firstTime = 0;
				
				if ((count % 3) == 0) {
					tmpStrText +=	'	<div class="addNameRow1">';
				}
	
				tmpStrText +=	'<div class="addNameCell1">' +							
								'<div class="leftFloat" style="padding-right:5px"><input name="currentGroupUsers_' + companyIndex + '_' + count + '" id="currentGroupUsers_' + companyIndex + '_' + count + '"' + ' type="checkbox" class="borderNone" value="' + item.users.userid + '~' + item.users.fullname + '" onclick="javascript:objPeople.chkEnableUser(\'' + companyIndex + '\', ' + count + ')"/></div>' + 
								ucwords(item.users.fullname) +						 
								'<div class="clearLeft"></div></div>';
				
				if ((count % 3) == 2) {
					tmpStrText +=	'		<span>&nbsp;</span>' + 
									'	</div><!-- addNameRow -->';
				}
	
				count++;
			});
			tmpStrText +=	'		</div> <!-- addNameRow -->' +
							'	</div> <!-- addName -->';
			tmpStrText +=  hiddenCountText + count + '">';
	
			$('companynames').value = $('companynames').value.replace(/^,/, '').replace(/,$/, '');
			//$('allusers').innerHTML = tmpStrText;
					
			if(groupsLength > 0 )
			{			
				$('allusers').innerHTML = tmpStrText;						
			}
			else
			{	
				$('invite_email').style.display = '';
				$('inviteUsers').style.display = 'none';
			}
			
			this.setAdditionalGroupUserInfo(this.companyDomain, this.data.additionalGroupUsersCnt);
	
			this.setOtherGroupUserInfo(this.data.otherGroupUsersCnt);
		}
	},
	getInvitationMessage:function(type){
		var setInvitationMessage = this.setInvitationMessage.bind(this);
		var url    = '/groups/groups/getinvitationmessage.json';
		var rand   = Math.random(9999);
		var userID = $('SessStrUserID').value;
		var pars   = '?type='+type+'&rand=' + rand + '&sessUID='+userID;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: setInvitationMessage} );		
	},
	setInvitationMessage:function(originalRequest){
		if(originalRequest.responseText == "expired")
        {      
        	window.location = MainUrl + "main.php?u=signout";return;
        }
        else
        {
			this.data = (eval('(' + originalRequest.responseText + ')'));
			if(trimAll(this.data[0].groupinvitationmessages.message) != "") {
				if($('invitationMessageText')) $('invitationMessageText').value = this.data[0].groupinvitationmessages.message;
			}
		}
	},
	setAdditionalGroupUserInfo: function(companyDomain,additionalGroupUsersCnt){
		var tmpStrText = "<div style='margin-right:30px;' class='Grp'><fieldset style='padding-top:0px'>"+
		"<div class='createGrpSubTitle'>INVITE PEOPLE FROM YOUR COMPANY</div>";
		for(i=1;i<=additionalGroupUsersCnt;i++)
		{
			tmpStrText+="<div class='field'"+tmpDisplay+"><div>"+
			"<input id='additionalGroupUser"+i+"' onkeypress='return barFormSubmit(event);' onblur=validateAddEmail(0,this.value,'additionalGroupUserValidate"+i+"','@"+companyDomain+"','additionalGroupUserRes"+i+"','additionalGroupUser',"+i+","+additionalGroupUsersCnt+");objPeople.validateAddUserMailId(this.value,'additionalGroupUserValidate"+i+"','@"+companyDomain+"','additionalGroupUserRes"+i+"'); type='text' size='40' class='rightAlign' />@"+
			companyDomain+"<span id='additionalGroupUserValidate"+i+"'></span><input type='hidden' value='0' name='additionalGroupUserRes"+i+"' id='additionalGroupUserRes"+i+"' /></div></div>";
		}
		tmpStrText+="</fieldset></div>";
		$('additionaGroupUserDIV').innerHTML = tmpStrText;
	},
	setOtherGroupUserInfo: function(otherGroupUsersCnt){
		var tmpStrText = "<div class='content fieldWidth3 Grp'><fieldset style='padding-top:10px'>"+
		"<div class='createGrpSubTitle'>INVITE PARTNERS TO JOIN YOUR "+ ($('pplGrpType').value == 'G' ? 'GROUP' : 'SUBGROUP') +"</div>";//proj2Gr
		tmpStrText += "<div class=\"paddingBottom10\">eg: sean@uberart.net, seansmith1989@gmail.com</div>";
		
		for(i=1;i<=otherGroupUsersCnt;i++)
		{
			tmpStrText+="<div class='field'><div>"+
			"<input id='outsideCompanyUser"+i+"' onkeypress='return barFormSubmit(event);' onblur=validateUserEmail(0,this.value,\'outsideCompanyUserValidate"+i+"\',\'outsideCompanyUserRes"+i+"\',\'outsideCompanyUser\',"+i+","+otherGroupUsersCnt+",\'\');objPeople.validateUserMailId(this.value,\'outsideCompanyUserValidate"+i+"\',\'outsideCompanyUserRes"+i+"\'); type='text' size='40' />"+
			"<span id='outsideCompanyUserValidate"+i+"'></span><input type='hidden' value='0' name='outsideCompanyUserRes"+i+"' id='outsideCompanyUserRes"+i+"' /></div></div>";
		}
		tmpStrText+="</fieldset></div>";
		$('otherGroupserDiv').innerHTML = tmpStrText;
	},
	chkAllCompUsers: function(companyIndexVal){
		if($('chkAllCompUserStatus_' + companyIndexVal).value == 0){
			var compUserCount = parseInt($('compUserCount_' + companyIndexVal).value);
			var selectedCompUserCount = 0;
			for(i=0;i<compUserCount;i++){
				$('currentGroupUsers_'+companyIndexVal+'_'+i).checked = true;	
				selectedCompUserCount += 1;
			}
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;			
			$('chkAllCompUserStatus_'+companyIndexVal).value = 1;
		}else
		{
			var compUserCount = parseInt($('compUserCount_'+companyIndexVal).value);
			var selectedCompUserCount = parseInt($('selectedCompUserCount_'+companyIndexVal).value);
			for(i=0;i<compUserCount;i++){
				$('currentGroupUsers_'+companyIndexVal+'_'+i).checked = false;	
				selectedCompUserCount -= 1;
			}
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;			
			$('chkAllCompUserStatus_'+companyIndexVal).value = 0;
		}
	},
	chkEnableUser: function(companyIndexVal, val){
		var compUserCount = parseInt($('compUserCount_' + companyIndexVal).value);
		var selectedCompUserCount = parseInt($('selectedCompUserCount_'+companyIndexVal).value);
		if($('currentGroupUsers_'+companyIndexVal+'_'+val).checked == true)
		{
			selectedCompUserCount+=1;
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;
		}else
		{
			selectedCompUserCount-=1;
			$('chkAllCompUsers_'+companyIndexVal).checked = false;
			$('chkAllCompUserStatus_'+companyIndexVal).value = 0;
			$('selectedCompUserCount_'+companyIndexVal).value = selectedCompUserCount;	
		}

		if(selectedCompUserCount == compUserCount)
		{
			$('chkAllCompUsers_'+companyIndexVal).checked = true;
			$('chkAllCompUserStatus_'+companyIndexVal).value = 1;
		} else {
			$('chkAllCompUsers_'+companyIndexVal).checked = false;
			$('chkAllCompUserStatus_'+companyIndexVal).value = 0;
		}
	},
	validateUserMailId :function(userMailId,errorElement,errorElementNumb) {
		userMailId = userMailId.replace(/^\s+|\s+$/g, '');		
		if($(errorElement).innerHTML == '&nbsp;&nbsp;<img src="/images/cross.gif" style="vertical-align: middle;">' || $(errorElement).innerHTML == '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">Please remove the duplicate E-mail Ids.</font>' || userMailId == '') {
		} else {
			this.chkMailIdWithDb(userMailId,errorElement,errorElementNumb);
		}
	},
	validateAddUserMailId :function(userMailId,errorElement,domainName,errorElementNumb) {
		userMailId = userMailId.replace(/^\s+|\s+$/g, '');
		if($(errorElement).innerHTML == '&nbsp;&nbsp;<img src="/images/cross.gif" style="vertical-align: middle;">' || $(errorElement).innerHTML == '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">Please remove the duplicate E-mail Ids.</font>' || userMailId == '') {
		} else {
			this.chkMailIdWithDb(userMailId+domainName,errorElement,errorElementNumb);
		}
	},
	chkMailIdWithDb: function(userMailId,errorElement,errorElementNumb) {
		var chkMailIdWithDbResponse = this.chkMailIdWithDbResponse.bind(this);
		var url = '/groups/groups/userEmailValid.json';
		var pars   = '?userMailId='+userMailId+'&errorElement='+errorElement+'&errorElementNumb='+errorElementNumb;
		this.requestCounter++;
		var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: chkMailIdWithDbResponse} );
	},
	chkMailIdWithDbResponse: function (originalRequest) {
		if(originalRequest.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
		}
		this.data1=(eval('(' + originalRequest.responseText + ')'));
		switch(parseInt(this.data1['strInvitationStatus'])) {
			/*case 0:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>You can not invite yourself.</font>";
					parent.$(this.data1['errorElementNumb']).value="0";
					break;
			case 1:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>New quick invite mail will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="1";
					break;
			case 3:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>Quick invite mail will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="3";
					break;
			case 4:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>No mail will be sent, as user is in DND list.</font>";
					parent.$(this.data1['errorElementNumb']).value="4";
					break;
			case 6:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>User Invited for Groups. User has blocked you.</font>";
					parent.$(this.data1['errorElementNumb']).value="6";
					break;
			case 7:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>New request will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="7";
					break;
			case 8:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>Request will be sent.</font>";
					parent.$(this.data1['errorElementNumb']).value="8";
					break;
			case 9:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>User will be invited.</font>";
					parent.$(this.data1['errorElementNumb']).value="9";
					break;
			default:
					parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/groups/img/tik.gif' style='vertical-align:middle;'>";
					parent.$(this.data1['errorElementNumb']).value="0";
					break;
			*/
			
			//please don't remove comments below as it is dynamically changing the innerHTML without validation
			//and also affecting in emailing process...
			case 0:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="0";
					break;
			case 1:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="1";
					break;
			case 3:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="3";
					break;
			case 4:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="4";
					break;
			case 6:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="6";
					break;
			case 7:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="7";
					break;
			case 8:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="8";
					break;
			case 9:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="9";
					break;
			default:
					//parent.$(this.data1['errorElement']).innerHTML = "&nbsp;&nbsp;<img src='/images/tick_new.gif' style='vertical-align:middle;'>";
					$(this.data1['errorElementNumb']).value="0";
					break;
	
		}
		this.requestCounter--;
	}
};

function people_confirm_MTD(form,url_action,id_element,html_show_loading,html_error_http)
{
    //alert(url_action);
    //alert(url_action);
    var count=0;
    $('errorMessageMem').style.display = 'none';
    var displayedPeople=new Array();
    $('rem_user_admin_name').innerHTML='is currently managing the following groups which will be turned over to you to manage or re-delegate.';
    $('rem_user_admin_name').innerHTML='';
    var userIdStr = '';
    var flag = 0;
    for(var i=0; i<document.users.elements.length; i++)
    {
       
     if(document.users.elements[i].checked == true && document.users.elements[i].value.match('SEPr'))
      {
        count++;
        flag = 1;
       // alert("The field name is: " + document.users.elements[i].name + " and itâ€™s value is: " + document.users.elements[i].value + ".<br />");
        
        //stringObject.substr(start,length);
        var peopleArray=document.users.elements[i].value.split('^');
        var peopleid=peopleArray[0].split('SEPr');
        peopleid=peopleid[0];
        peopleArray=peopleArray[4].split('@G');
        var people=peopleArray[0];
         var grpName=document.users.elements[i].value.substr(document.users.elements[i].value.lastIndexOf('@G')+2);
         var userroleArray=document.users.elements[i].value.split('^');
     	 var user_role= userroleArray[3];
     	
     	if(count > 1)
     	$('userIdListAdmin').value = $('userIdListAdmin').value +',"'+peopleid+'"'; 
     	else
     	$('userIdListAdmin').value = '"'+peopleid+'"';
     	
        if(count >= 2)
            {
              //  alert('2');
                //$('remove_confirm_popup').style.display = 'block';
                $('rem_people_list').innerHTML= $('rem_people_list').innerHTML+'<br>- '+people;

            }
            else
            {
                    //alert('1');
                   // $('remove_confirm_popup').style.display = 'block';
                    $('rem_people_list').innerHTML='- '+people;
            }
            /*
            if(user_role==3){
                                                  
              if(window.adminUsersGroup.lastIndexOf(peopleid)<0)
              {
              $('rem_user_admin_name').innerHTML= people+" is currently managing the following groups which will be turned over to you to manage or re-delegate";
             
               for(var j=0;j<(window.adminUsersGroup.length);j++)
                {
                if(window.adminUsersGroup[j]['groups_users']['user_id']==peopleid)
                	{
                	//alert(window.adminUsersGroup[j]['groups_users']['user_id']+"  "+j);
                	$('rem_user_admin_name').innerHTML=$('rem_user_admin_name').innerHTML+" <br>-"+window.adminUsersGroup[j]['groups']['name'];
                	}          	
                }
                
              }
            } */
        }
    }
    
    	if(flag == '1'){
			var url    = '/groups/domains/getUsersGroupAdmin.json';
			var rand   = Math.random(9999);
			var userID = $('SessStrUserID').value;
			var pars   = '?rand=' + rand+'&users=' + $('userIdListAdmin').value+'&sessUID='+userID;
			var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onSuccess: function(transport){
			if(transport.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			//alert(transport.responseText);
			$('rem_user_admin_name').innerHTML = transport.responseText;
			   $('remove_confirm_popup').style.display = 'block';
			   if(count!=0)
			   {
			       $('confirm_people').innerHTML='<a href="javascript:void(0);" class="butBlue" onclick=" document.getElementById(\'remove_confirm_popup\').style.display=\'none\';micoxUpload(\''+form+'\',\''+url_action+'\',\''+id_element+'\',\'<img src=/groups/img/loading.gif class=loadingImgLeft>\',\'Error\');return false;"><span class="lineHit30">Yes</span></a>';
			       $('nothingDone').innerHTML='<a href="javascript:void(0);" class="butBlue" onclick="document.getElementById(\'remove_confirm_popup\').style.display=\'none\';resetallChkBox();"><span class="lineHit30">No</span></a>';
			       
			      centerPos(document.getElementById('remove_confirm_popup'), 1);
			   }
			   else
			   {
			       
			       micoxUpload('users',url_action,id_element,'<img src=/groups/img/loading.gif class=loadingImgLeft>','Error');
			       return false;
			   }
			
			}} );
	   }
	   if(flag == '0'){$('errorMessageMem').style.display = 'block';}
   
}


function resetallChkBox(){
	for(var i=0; i<document.users.elements.length; i++)
    {
       
     if(document.users.elements[i].checked == true)
      {
      	document.users.elements[i].checked = false;
      }
    }
}

function resizeProfile()
{

	if(profileInterval=="")
	{
		$('iprofile').style.height = $('iprofile').contentWindow.document.body.scrollHeight + 'px';	
		profileInterval = setInterval('resizeIframe()',5);
	}else{
		if(profileInterval != "")
		{
			clearInterval(profileInterval);
			profileInterval = "";
		}
	}
}
/*
function resizeIframe()
{
	//if($('iprofile'))
	//{
		//$('iprofile').style.height = $('iprofile').contentWindow.document.body.scrollHeight + 'px';
	//}			
}
*/
function toggleMembers(self) {
	var nextSibling = self.parentNode.nextSibling;
	while(nextSibling && nextSibling.nodeType != 1)
		nextSibling = nextSibling.nextSibling;
	 
	Effect.toggle(nextSibling, 'slide', {duration: 0.5 });		
	
	if(self.className == 'imNetworkNameContract') self.className = 'imNetworkNameExpand';
	else self.className = 'imNetworkNameContract';
}

function rendercalendar(){
	if(document.all && !window.opera && !window.XMLHttpRequest){ ///for IE6
			parent.calendarframe.location = "/groups/groups/calendar";
		}else{
			parent.document.getElementById("calendarframe").src = "/groups/groups/calendar";
		}
}


function setTopbarDimension()
{
	intRight = 	(windowWidth/2)-365;
	if(windowWidth<=1100)
		intRight = 205;
	else if(windowWidth<1280)
		intRight = 295;
	if($('divtopbar')){			
		$('divtopbar').setStyle({		
			right: '35%'
		});
	}
	if(windowWidth >=1900)
	{
		$('allHeadPopup').setStyle({		
			right: '300px'
		});					
	}
	else
	{
		$('allHeadPopup').setStyle({		
			right: '2%'
		});	
	}	
}

function setQuickTabDim()
{
	if(windowWidth >=1900)
	{
		if($('navigate_groups'))
		{
			$('navigate_groups').setStyle({		
				left: '250px'
			});
		}
	}
	else if(windowWidth >1280 && windowWidth<1900)
	{
		if($('navigate_groups'))
		{
			$('navigate_groups').setStyle({		
				left: '150px'
			});
		}
	}
	else
	{				
		if($('navigate_groups'))
		{
			$('navigate_groups').setStyle({		
				left: '15px'
			});
		}
	}
}

function talkShowNormalBox()
{
	//if($('talkTxtAreaDefault').style.display == 'block') mosh
	//{
    	//show hide talk blurb
    	if($('talkProject').style.display == 'none')
    	{
    		$('talkBlurbShowHide').value = '1';
    	}
    	else if($('talkProject').style.display == 'block')
    	{
    		$('talkBlurbShowHide').value = '2';
    	}
    	
    	$('talkTxtAreaDefault').style.display = 'none';
    	$('talkTxtArea').style.display = 'block';
    	$('talkTxtArea').focus();
    	$('talkProject').style.display = 'block';
	//}
}

function talkHideNormalBox()
{
	talk_file_cnt=0;  
	$('talkTxtAreaDefault').show(); 
	$('talkStatus').hide(); 
	$('talkTxtArea').hide(); 
	$('talkLoading').hide();
	$('talkProject').hide(); 
	$('talk_file_para').innerHTML='';
}

function talkShowDefaultBox()
{
	if($('talkTxtArea').value=='')
	{
    	$('talkTxtArea').style.display = 'none';
    	$('talkTxtAreaDefault').style.display = 'block';
	}
}

/*************** Functions from groups.js ***********************/


var divIndex = 0;
var arrIndexes = new Array();


function showProfile()
{	
	//$('iprofile').style.width = windowWidth + 'px';
	//$('iprofile').style.height = $('iprofile').contentWindow.document.body.scrollHeight + 'px';	
	setTimeout('resizeIframe()',5);
}

function showhideInvite(obj)
{
	/*if (obj.style.display == 'none') 
		{
			obj.style.display = 'block';
		}
		else 
		{
			obj.style.display = 'none';
	}*/
	
	var num = document.getElementsByName('profileInfoDivName');
	var len = num.length;
	if(len == 0 && obj.id == 'profileInfoDiv'){
		createPersonalInfoDiv(obj);
	}
	if(len > 0 || (obj.id != 'profileInfoDiv'))
		Effect.toggle(obj, 'slide', {duration: 0.3 });
	else obj.style.display = 'block';
	
	if(obj.id == 'profileInfoDiv' && $('pname'+divIndex))
		$('pname'+divIndex).focus();
		
	$('displayTypeDetails').show();
}

function removeClient(obj){
	var objId = obj.id;
	var index = objId.substr(("rmClient").length);
	removeByElement(arrIndexes,index);
	
	group.changeStepThreeInfo(-1,index);
	
	var child = $('profileInfoDiv'+index);
    var parent = $('profileInfoDiv');
    parent.removeChild(child);
    if(divIndex == 1)divIndex = 0;
    
    if(arrIndexes.length > 0){
    	var lastIndex = arrIndexes[arrIndexes.length - 1];
   	 	document.getElementById("addClient"+lastIndex).style.display = 'block';
    }
    
   // Effect.toggle(child, 'slide', {duration: 0.3 });
}

function removeByElement(arrayName,arrayElement)
{
    for(var i=0; i<arrayName.length;i++ )
     { 
        if(arrayName[i]==arrayElement)
            arrayName.splice(i,1); 
     } 
}
  
function createPersonalInfoDiv(obj){
	
	var num = document.getElementsByName('profileInfoDivName');
	
	//if(group.validateStepOne()){
		document.getElementById('errorMessage').style.display = 'none';
		divIndex++;
		arrIndexes.push(divIndex);
		if(num.length != 0){
			var objId = obj.parentNode.id;
			var index = objId.substr(("addClient").length);
			document.getElementById('addClient' + index).style.display='none';
		}
		var str = $('clientField').innerHTML;
		str = str.replace(/sci/g, "sci"+divIndex);
		str = str.replace(/sclient/g, "sclient"+divIndex);
		var html = '';
		        
		html = html + '<div id="profileInfoDiv'+divIndex+'" name = "profileInfoDivName">' +
				'<div class="err" name = "errMessage'+divIndex+'" id="errorMessage'+divIndex+'" style="display:none;"></div>'+
				'<input type="hidden" name = "userid'+divIndex+'" id="userid'+divIndex+'">'+
				'<input type="hidden" id="photo'+divIndex+'">'+
				'<input type="hidden" id="clientId'+divIndex+'" name="clientId'+divIndex+'">'+
				'<input type="hidden" id="selected'+divIndex+'">'+
                '<div class="paddingTopBottom">' +
                	'<div class="rightFloat paddingTop10" style="width:35%">'+
                	'<div class="clearLeft">&nbsp;</div>'+
                	'<div id="rmClient'+divIndex+'" style="padding-top:15px;padding-bottom:25px;padding-left:15px;"> <a href ="javascript:void(0);" onClick="javascript:removeClient(this.parentNode);">Remove client / partner</a></div>'+	
                    '<div id ="selectPerson'+divIndex+'" class="selectPerson" style="display:none">'+
                      '<div style="padding:10px 10px 10px 5px;">Select the main contact person</div>'+
                      '<div id="listUsers'+divIndex+'"> </div>'+
                      '<div class="clearLeft">&nbsp;</div>'+
                    '</div>'+
                  '</div>'+
                '<fieldset id="profileInfo'+divIndex+'">';
				
		html = html + str;
		html = html +	'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>*</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Contact Person</label>'+
						  '</div>'+
						  '<div>'+
							'<input name="pname'+divIndex+'" id="pname'+divIndex+'" type="text" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+
							'<br /><span id="err_pname'+divIndex+'" class="inlineErr" style="display:none;margin-left:150px">Please enter contact person</span>'+			
						  '</div>'+
						'</div>'+ 
						'<div class="field">'+
						'<div class="fieldLabelMand">'+
							'<label>*</label>'+
						'</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Phone no.</label>'+
						  '</div>'+
						  '<div>'+
							'<input name="phoneno'+divIndex+'" id="phoneno'+divIndex+'" type="text" size="33" class="fieldWidth1"  onkeypress="return barFormSubmit(event);"/>'+
							'<br /><span id="err_phoneno'+divIndex+'" class="inlineErr" style="display:none;margin-left:150px">Please enter phone number</span>'+					
						  '</div>'+
						'</div>'+
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>*</label>'+
						'</div>'+
						'<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Email id</label>'+
						  '</div>'+
						  '<div>';
			html = html +	"<input name='email"+divIndex+"' id='email"+divIndex+"' size='15' type='text' class='fieldWidth1'  onchange=group.chkClientWithEmail("+divIndex+");group.chkMailIdWithDb(this.value,'','errNum_email"+ divIndex +"'); onkeypress='return barFormSubmit(event);'/>	";						
			
			html = html +	'<br /><span id="err_email'+divIndex+'" class="inlineErr" style="display:none;margin-left:150px">Please enter email id</span>'+
						  	'<span id="err1_email'+divIndex+'" class="inlineErr" style="display:none;margin-left:150px">This is not a valid email address</span>'+
						  	'<span id="err2_email'+divIndex+'" class="inlineErr" style="display:none;margin-left:150px">You have entered duplicate email ids</span>'+
						  	'<span id="err3_email'+divIndex+'" class="inlineErr" style="display:none;margin-left:150px">Email id does not belong to the client selected</span>'+
						  	'<input type="hidden" id="hid_email'+divIndex+'" value="-1">'+
						  	'<input type="hidden" id="errNum_email'+divIndex+'" name="errNum_email'+divIndex+'" value="0">'+
						  	
						  '</div>'+
						'</div>'+
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>&nbsp;</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Address 1</label>'+
						  '</div>'+
						  '<div>'+
							'<input name="address1'+divIndex+'" id="address1'+divIndex+'" type="text" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+							
						  '</div>'+
						'</div>'+
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>&nbsp;</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Address 2</label>'+
						  '</div>'+
						  '<div>' +
							'<input name="address2'+divIndex+'" id="address2'+divIndex+'" type="text" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+							
						  '</div>'+
						'</div>'+
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>&nbsp;</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Country</label>'+
						  '</div>'+
						  '<div>'+
							'<input name="country'+divIndex+'" id="country'+divIndex+'" type="text" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+					
						  '</div>'+
						'</div>'+
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>&nbsp;</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>State</label>'+
						  '</div>'+
						  '<div>' +
							'<input name="state'+divIndex+'" id="state'+divIndex+'" type="text" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+						
						  '</div>'+
						'</div>' +
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>&nbsp;</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>City</label>'+
						  '</div>' +
						  '<div>'+
							'<input name="city'+divIndex+'" id="city'+divIndex+'" type="text" size="33" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+							
						  '</div>'+
						'</div>' +
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>&nbsp;</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Zip</label>'+
						  '</div>'+
						  '<div>'+
							'<input name="zip'+divIndex+'" id="zip'+divIndex+'" type="text" size="33" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+							
						  '</div>'+
						'</div>'+
						'<div class="field">'+
						  '<div class="fieldLabelMand">'+
							'<label>&nbsp;</label>'+
						  '</div>'+
						  '<div class="fieldLabel fieldLabelWidthBig">'+
							'<label>Website</label>'+
						  '</div>'+
						  '<div>'+
							'<input name="website_url'+divIndex+'" id="website_url'+divIndex+'" type="text" size="33" class="fieldWidth1" onkeypress="return barFormSubmit(event);"/>'+							
						  '</div>'+
						'</div>'+
						'<div class="field">'+
							'<div class="fieldLabelMand">'+
							  '<label>&nbsp;</label>'+
						    '</div>'+
							'<div class="fieldLabel fieldLabelWidthBig">'+
							  '<label>&nbsp;</label>'+
							'</div>'+
							'<div id="addClient'+divIndex+'">'+
							'<a href ="javascript:void(0);" onClick="javascript:createPersonalInfoDiv(this);">'+
							'Add another client / partner'+
							'</a>'+
							'</div>'+
						'</div>'+		
                '</fieldset>'+
                '<div class="clearRight"></div>'+
                '</div>'+
               '</div>';
   			$('profileInfoDiv').insert ({
	         	'bottom'  : html
	  		})
	  		
	  		if(arrIndexes.length > 1 && $('pname'+divIndex))
	  				$('pname'+divIndex).focus();
	//}
}

function trimAll(strValue)
{
	var objRegExp = /^(\s*)$/;
    /* check for all spaces */
    if(objRegExp.test(strValue))
	{
       strValue = strValue.replace(objRegExp, '');
       if( strValue.length == 0)
          return strValue;
    }

	/* check for leading & trailing spaces */
	objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/;
	if(objRegExp.test(strValue))
   	{
		/* remove leading and trailing whitespace characters */
       strValue = strValue.replace(objRegExp, '$2');
    }
	/* Return formated string without leading and trailing spaces */
	return strValue;
}

function isEmailDONOTUSE(emailStr) 
{

	/* The following variable tells the rest of the function whether or not
	to verify that the address ends in a two-letter country or well-known
	TLD.  1 means check it, 0 means don't. */

	var bolVerify=1;

	/* The following is the list of known TLDs that an e-mail address must end with. */

	var strKnownDom=/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum)$/;

	/* The following pattern is used to check if the entered e-mail address
	fits the user@domain format.  It also is used to separate the username
	from the domain. */

	var strEmailPat=/^(.+)@(.+)$/;

	/* The following string represents the pattern for matching all special
	characters.  We don't want to allow special characters in the address. 
	These characters include ( ) < > @ , ; : \ " . [ ] */

	var specialChars="\\(\\)><@,;:\\\\\\\"\\.\\[\\]";

	/* The following string represents the range of characters allowed in a 
	username or domainname.  It really states which chars aren't allowed.*/

	var validChars="\[^\\s" + specialChars + "\]";

	/* The following pattern applies if the "user" is a quoted string (in
	which case, there are no rules about which characters are allowed
	and which aren't; anything goes).  E.g. "jiminy cricket"@disney.com
	is a legal e-mail address. */

	var quotedUser="(\"[^\"]*\")";

	/* The following pattern applies for domains that are IP addresses,
	rather than symbolic names.  E.g. joe@[123.124.233.4] is a legal
	e-mail address. NOTE: The square brackets are required. */

	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;

	/* The following string represents an atom (basically a series of non-special characters.) */

	var strAtom=validChars + '+';

	/* The following string represents one word in the typical username.
	For example, in john.doe@somewhere.com, john and doe are words.
	Basically, a word is either an atom or quoted string. */

	var strWord="(" + strAtom + "|" + quotedUser + ")";

	// The following pattern describes the structure of the user

	var userPat=new RegExp("^" + strWord + "(\\." + strWord + ")*$");

	/* The following pattern describes the structure of a normal symbolic
	domain, as opposed to ipDomainPat, shown above. */

	var domainPat=new RegExp("^" + strAtom + "(\\." + strAtom +")*$");

	emailStr = trimAll(emailStr);

	/* Finally, let's start trying to figure out if the supplied address is valid. */

	/* Begin with the coarse pattern to simply break up user@domain into
	different pieces that are easy to analyze. */

	var matchArray=emailStr.match(strEmailPat);

	if (matchArray==null)
	{
		/* Too many/few @'s or something; basically, this address doesn't
		even fit the general mould of a valid e-mail address. */
		return false;
	}

	var strUser = matchArray[1];
	var strDomain = matchArray[2];

	// Start by checking that only basic ASCII characters are in the strings (0-127).
	for ( cntUser=0; cntUser<strUser.length; cntUser++)
	{
		if (strUser.charCodeAt(cntUser)>127)
		{
			return false;
		}
	}

	for (cntDomain=0; cntDomain<strDomain.length; cntDomain++)
	{
		if (strDomain.charCodeAt(cntDomain)>127)
		{
			return false;
		}
	}

	// See if "user" is valid 

	if (strUser.match(userPat)==null)
	{
		// user is not valid
		return false;
	}

	/* if the e-mail address is at an IP address (as opposed to a symbolic
	host name) make sure the IP address is valid. */
	var IPArray=strDomain.match(ipDomainPat);

	if (IPArray!=null)
	{
	// this is an IP address
		for (var i=1;i<=4;i++)
		{
			if (IPArray[i]>255)
			{
				return false;
			}
		}
	}

	// Domain is symbolic name.  Check if it's valid.
	var atomPat = new RegExp("^" + strAtom + "$");
	var domArr = strDomain.split(".");
	var strLen = domArr.length;

	for (i=0;i<strLen;i++)
	{
		if (domArr[i].search(atomPat)==-1)
		{
			return false;
		}
	}

	/* domain name seems valid, but now make sure that it ends in a
	known top-level domain (like com, edu, gov) or a two-letter word,
	representing country (uk, nl), and that there's a hostname preceding 
	the domain or country. */

	if (bolVerify && domArr[domArr.length-1].length!=2 && domArr[domArr.length-1].search(strKnownDom)==-1)
	{
		return false;
	}

	// Make sure there's a host name preceding the domain.
	if (strLen<2) 
	{
		return false;
	}

	// If we've gotten this far, everything's valid!
	return true;
}


function isEmail(B){
	var A=1;
	var D=/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum)$/;
	var G=/^(.+)@(.+)$/;
	var S='\\(\\)><@,;:\\\\\\"\\.\\[\\]';
	var O="[^\\s"+S+"]";
	var F='("[^"]*")';
	var T=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
	var L=O+"+";var E="("+L+"|"+F+")";
	var P=new RegExp("^"+E+"(\\."+E+")*$");
	var I=new RegExp("^"+L+"(\\."+L+")*$");
	B=trimAll(B);
	B=B.toLowerCase();
	var C=B.match(G);

	if(C==null){return false}
	var K=C[1];
	var J=C[2];
	for(cntUser=0;cntUser<K.length;cntUser++){
		if((K.charCodeAt(cntUser)>96 && K.charCodeAt(cntUser)<123) || K.charCodeAt(cntUser)==46 || K.charCodeAt(cntUser)==45 || K.charCodeAt(cntUser)==95 || (K.charCodeAt(cntUser)>47 && K.charCodeAt(cntUser)<58)){}
		else{return false}
	}

	for(cntDomain=0;cntDomain<J.length;cntDomain++){
		if((J.charCodeAt(cntDomain)>96 && J.charCodeAt(cntDomain)<123) || J.charCodeAt(cntDomain)==46 || (J.charCodeAt(cntDomain)>47 && J.charCodeAt(cntDomain)<58)||J.charCodeAt(cntDomain)==45){} else{return false}
	}

	if(K.match(P)==null){return false}

	var N=J.match(T);

	if(N!=null){
		for(var R=1;R<=4;R++){
			if(N[R]>255){return false}
		}
	}

	var M=new RegExp("^"+L+"$");
	var H=J.split(".");
	var Q=H.length;
		
	for(R=0;R<Q;R++){
		if(H[R].search(M)==-1){return false}
	}

	if(A&&H[H.length-1].length!=2&&H[H.length-1].search(D)==-1){return false}
	if(Q<2){return false}

	return true
}

function validateAddEmail(dontChk, txtValue, objFormName, domainName, errorNumObj, objName, cnt, ttlCnt)
{
	txtValue = txtValue.replace(/^\s+|\s+$/g, '');
	$(errorNumObj).value = '0';
	if(txtValue != '') {
		validateUserEmail(dontChk, txtValue+domainName, objFormName, errorNumObj, objName, cnt, ttlCnt, domainName);
	} else {
		$(objFormName).innerHTML='';
	}
}

function validateUserEmail(dontChk, txtValue, objFormName, errorNumObj, objName, cnt, ttlCnt, domainName)
{						

	$(errorNumObj).value = '0';
	$(objFormName).innerHTML='';
	txtValue = txtValue.replace(/^\s+|\s+$/g, '');
	if( txtValue != "" ) {
		if(!isEmail(txtValue)) {
			$(objFormName).innerHTML='&nbsp;&nbsp;<img src="/images/cross_new.gif" style="vertical-align:middle;">';
		} else {
			if(dontChk == 1) {
				$(objFormName).innerHTML='&nbsp;&nbsp;<img src="/images/tick1.png" style="vertical-align:middle;">';
			} else {
				for(i=1;i<=ttlCnt;i++) {
					if(i==cnt)
						continue;
					txtValueOther = '';
					txtValueOther = trimAll($(objName+i).value);
					txtValueOther = txtValueOther+domainName;
					if(txtValueOther != '' && txtValueOther == txtValue) {
						$(objFormName).innerHTML='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">Please remove the duplicate E-mail Ids.</font>';
						break;
					} else {
						$(objFormName).innerHTML='&nbsp;&nbsp;<img src="/images/tick1.png" style="vertical-align:middle;">';
						
						if(cnt < (ttlCnt-1))
						{	
							var nxtFieldShowValue = cnt + 2;
							var nxtChkFieldShowValue = cnt + 1;
							
							if(objName == 'outsideCompanyUser' )
							{
								var nxtOthersFieldShowDiv = "invOthersField"+nxtFieldShowValue;
								var nxtOthersChkFieldShowDiv = "invOthersField"+nxtChkFieldShowValue;
								
								if($(nxtOthersChkFieldShowDiv))
									$(nxtOthersFieldShowDiv).show();
								else
									$(nxtOthersChkFieldShowDiv).show();
							} else {
							
								var nxtFieldShowDiv = "invField"+nxtFieldShowValue;
								var nxtChkFieldShowDiv = "invField"+nxtChkFieldShowValue;
								
						
								if($(nxtChkFieldShowDiv))
									$(nxtFieldShowDiv).show();
								else
									$(nxtChkFieldShowDiv).show();
							}
						}
					}
				}
			}
		}
	}
	else {
		$(objFormName).innerHTML='';
	}
}

function callFourthStep(waitTime) {
	setTimeout("group.createSubGroupStepFour();",waitTime);
}



/*		RESOURCES CALENDER STARTS	*/

/*	(P.S - DO NOT DELETE ANY COMMENTED PART)	*/

var globalResourceTemp = '';
var globalResourceCalled = false;
var globalResourceCurrentWeek = true;

function resourceOpenMilPopup(pos, d, which, which_id, whom) {

	if(d == '0') {		  	
	  	invalidPopup(pos);
	  	milestoneSubmitted=false;
	  	return false;
	}
	
	//$('add_milestone').style.display = "block";
	//centerPos('add_milestone', 1);
	$('calenderPopupArrow').hide();
	$('errorMsgTitleMilestone').hide(); 
	milestoneSubmitted=false; 
	showAddProfileForResource(d, which, which_id, whom);
	openStatus = 0;
	$('addTitle').value = '';
	$('isAddTaskList').checked = false;
	$('addMilestoneAutocomplete').checked = false;
}

function showDropDownResource(mainDDdiv,noofDivsForHeight,calHeight,innerDiv,inp,par,selId,allDivs,positionDiv){
	
	var loc= findPosLevel(inp,positionDiv);
	var lt = loc[0];
	var tp = loc[1];

	document.getElementById(mainDDdiv).style.left = lt+'px';
	document.getElementById(mainDDdiv).style.top = tp+'px';
	if(document.getElementById(mainDDdiv).style.display == 'none')
		document.getElementById(mainDDdiv).style.display = 'block';
	else
		document.getElementById(mainDDdiv).style.display = 'none';
	
	var divs = document.getElementById(noofDivsForHeight).value;
	divs = divs.split(',');	
	
	for(var i=0;i<divs.length;i++){	
		var hgt = document.getElementById(divs[i]).offsetHeight;
		document.getElementById(calHeight).value = parseInt(document.getElementById(calHeight).value) + parseInt(hgt)+ parseInt(4);
	}
	document.getElementById(innerDiv).style.height = document.getElementById(calHeight).value+'px';	
	document.getElementById(calHeight).value = "0";
}

function openResource(state, id) {
	var start_week = $('start_week').value;
	var first_timer_resource = $('first_timer_resource').value;
	var userID = $('userid_resource').value;
	var order = extractKeysFromArray(user_groups_resource);

	if(first_timer_resource == '1' || id == '')	id = 0;
	//else id = user_groups_resource[id];
	
	var cookiVal = readCookie('winDimension');
	var cookiValArr = readCookie('winDimension').split('x');
	var viewPortHeight = cookiValArr[0];
	var viewPortWidth = cookiValArr[1];
	res = cookiValArr[1]+"-"+cookiValArr[0];
	
	new Ajax.Request("/groups/groups/getResourceData.json", {
		method:"post", parameters:{"order":order, "start_week":start_week, "state":state, "id":id, "userID":userID, "res":res}, 
		onComplete:function(resp){
			if(resp.responseText == "expired"){      
				window.location = MainUrl + "main.php?u=signout";return;
			}
			var data = eval( "("+ resp.responseText +")" );

			globalResourceCalled = false;
			
			$("resources").innerHTML = data.content;
			$('resources').style.height = (viewPortHeight*0.87 - 105) + "px";
			$('resourceCal').style.width = (viewPortWidth*0.9 - 62) + "px";
			$('resources').scrollTop = 0;
			// set height of overall container
			hideMilestones($('red'));
			hideMilestones($('blue'));
			hideMilestones($('green'));	
			if($('first_timer_resource').value == '1')
				resetGroupDropdown(data.id);
				
			$('resource_loader').hide();
			$('resource_loader_1').hide();
		}
	});
}

function showWeekResource(self, id, state, th) {
	var classs = 'mouseover';
	if(state) {
		if(th) {
			self.childNodes[1].style.display = 'block';
			Element.extend(self);
			self.addClassName(classs);
		} else {
			var data = self.parentNode.parentNode.parentNode.firstChild.firstChild.childNodes[id]; 
			data.childNodes[1].style.display = 'block';
			Element.extend(data);
			data.addClassName(classs);
		}
	} else {
		if(th) {
			self.childNodes[1].style.display = 'none';
			Element.extend(self);
			self.removeClassName(classs);
		} else {
			var data = self.parentNode.parentNode.parentNode.firstChild.firstChild.childNodes[id]; 
			data.childNodes[1].style.display = 'none';
			Element.extend(data);			
			data.removeClassName(classs);
		}
	}
}

// New function
function checkUncheckResource(self, para, firstTimeTweak) {
	
	$('resource_loader_1').show();
	
	var chkArr = 'dropDownChk5[]';
	var chks =  document.getElementsByName(chkArr);
	for (var i = 0; i < chks.length; i++) {
		chks[i].checked = false;
		chks[i].parentNode.parentNode.className = '';
		chks[i].parentNode.parentNode.setAttribute('vx', 'tt');
	}
	
	self.checked = true;
	self.parentNode.parentNode.className = 'selOption';
	self.parentNode.parentNode.setAttribute('vx', 'ff');

	$('selectedIDs'+para).value = '';
	$('commonDropDOwn'+para).hide();
	
	getElementsByNameFun('dropDownChk'+para, 'selectedIDs'+para, 'selectedVal'+para); 
	$('first_timer_resource').value = 0; 
	$('userid_resource').value = 0;
	
	if(firstTimeTweak || $('start_week').value == '0') callResource(self, 'now', 'selectedIDs'+para, false);
	else callResource(self, 'stateless', 'selectedIDs'+para, false); 
	
}

function callResource(self, state, id, firstTime) {

	//alert(parent.isResourceTab);
	if(!parent.isResourceTab) return false; // proceed if call is from resource tab 

	if(!firstTime)
		var data = $(id).value.replace(/,/g, '-');
	else
		var data = 0;

	if(self != 'none') {
		if(self.checked) {
			self.parentNode.parentNode.className = 'selOption';
			self.parentNode.parentNode.setAttribute('vx', 'ff');
		} else {
			self.parentNode.parentNode.className = '';
			self.parentNode.parentNode.setAttribute('vx', 'tt');
		}
	}

	if(!globalResourceCalled) {
		globalResourceCalled = true; 
		openResource(state, data);
	}
}

function toggleClassResource(self, state){
	var vx = self.getAttribute('vx');
	if(state){
		if(vx == 'tt') {
			if(self.className == 'selOption')
				globalResourceTemp = self.id;
			else
				self.className = 'selOption';
		}

 		if(self.childNodes[1].className == 'listLvl0Disable')
			self.className = 'selOptionDisable';
		
	} else {
		if(vx == 'tt') {
 			if(globalResourceTemp == self.id)
				self.className = 'selOption';
			else
				self.className = ''
		}
			
		if(self.className == 'selOptionDisable')
			self.className = '';
	}
}

function resetGroupDropdown(group_id) {
	var groupIDarr = '';
	var groupID = '';
	var defaultSel = '';
	
	/*if(group_id == 'overviewResource' || group_id == '' || group_id == '0') {
		defaultSel = $('innerDiv5').firstChild.childNodes[1];
    	$('selectedVal5').value = defaultSel.childNodes[1].nodeValue;
    	defaultSel.className = 'selOption';
		defaultSel.setAttribute('vx', 'ff');
    	defaultSel.firstChild.firstChild.checked = true;
		return false;
	}*/

	group_id = String(group_id);
	if(group_id.match('-'))
		groupIDarr = group_id.split('-'); 
	
	var chkArr = 'dropDownChk5[]';
	var chks =  document.getElementsByName(chkArr);
	for (var i = 0; i < chks.length; i++) {
		chks[i].checked = false;
		chks[i].parentNode.parentNode.className = '';
    }
    
    if(groupIDarr != '') {
    	for(var i=0; i<groupIDarr.length; i++) {
    		groupID = String(groupIDarr[i]);
    		$(groupID).checked = true;
    		defaultSel = 'div'+groupID+'5ID';
    		$(defaultSel).className = 'selOption';
    		$(defaultSel).setAttribute('vx', 'ff');
    	}
    } else {
    	groupID = String(group_id);
    	$(groupID).checked = true;
    	defaultSel = 'div'+groupID+'5ID';
    	$(defaultSel).childNodes[0].childNodes[0].checked = true;
		$(defaultSel).className = 'selOption';
		$(defaultSel).setAttribute('vx', 'ff');
    }
	getElementsByNameFun('dropDownChk5','selectedIDs5','selectedVal5');
}

function extractNumber(text) {
	var numb = text.match(/\d/g);
	numb = numb.join("");
	return numb;
}

function extractKeysFromArray(input) {
    var tmp = '';
    var key = '';
    for (key in input) {
		tmp += key+'-';
	}
    return tmp;
}

function stopPropagation(eventHandle) {
	if (!eventHandle) var eventHandle = window.event;
	if (eventHandle) eventHandle.cancelBubble = true;
	if (eventHandle.stopPropagation) eventHandle.stopPropagation();
}

function currentWeekResource() {
	if(globalResourceCurrentWeek) {
		var calPos = findPos1('resource-overview-container');
		$('alreadyOnThePagePopup').show()
		$('alreadyOnThePagePopup').style.top = calPos[1]+30+'px';
		$('alreadyOnThePagePopup').style.left = calPos[2]-400+calPos[0]+'px';
		return false;
	} else {
		if(!globalResourceCalled) {
			$('resource_loader').show();
			callResource('none', 'now', 'selectedIDs5', false); 
			globalResourceCalled = true;
			globalResourceCurrentWeek = true;
		}
	}
}

function prevWeekResource() {
	if(!globalResourceCalled) {
		$('resource_loader').show();
		callResource('none', 'rewind', 'selectedIDs5', false); 
		globalResourceCalled = true;
		globalResourceCurrentWeek = false;
	}
}

function nextWeekResource() {
	if(!globalResourceCalled) {
		$('resource_loader').show();
		callResource('none', 'forward', 'selectedIDs5', false); 
		globalResourceCalled = true;
		globalResourceCurrentWeek = false;
	}
}
/*		RESOURCES CALENDER ENDS	*/








/*******************   it ends here           ***********************/

function submitTaskList(groupID) {
	todoSubmitted=false;
	if(formValidation()) {
		if(($('calenderLastInnerMainDiv') && $('calenderLastInnerMainDiv').style.display != 'none')  || $('currPageCat').value == "mlt")
			var tmp = document.getElementById('addSelect').options[document.getElementById('addSelect').selectedIndex].value
		else
			var tmp = document.getElementById('responsible').options[document.getElementById('responsible').selectedIndex].value
		
		if(tmp == '0') {
			$('add_result').style.display = 'block';		
			$('add_result').innerHTML = '<div class="err">Please select who\'s responsible for the task</div>';
			return false;
		}
		
		if (!todoSubmitted) {
			todoSubmitted = true; 
			if (micoxUpload('frmTodo','/groups/groups/addTaskLists/'+groupID,'add_loading_img','<img src=/groups/img/loading.gif class=loadingImgLeft>','Error') == false) {
				todoSubmitted = false;
			} else {
				parent.intCalendarEdit=1;
				if(document.getElementById('noTolist'))
					document.getElementById('noTolist').innerHTML = '';
			}
		}
		return false;
	}
}

function refreshTaskIconCalendar(date,grpId,milVersion,type){
	if($('calenderLastInnerMainDiv') && $('calenderLastInnerMainDiv').style.display != 'none') {
		var elem = $('datadiv'+milVersion);	
	}
	else {
		var elem = parent.window.frames["calendarframe"].$('datadiv'+milVersion);
	}
	
	date = date.strip();
	if(elem){
		if((type == 'add' && !elem.hasClassName('taskDetector')) || (type == 'remove' && elem.hasClassName('taskDetector'))){
			getNewCCD(date, '', grpId);
		}
		
		if(type == 'add'){
			elem.addClassName('taskDetector');
			if($('datadivRes'+milVersion))
				$('datadivRes'+milVersion).addClassName('taskDetector');
		}
		else if(type == 'remove') {
			elem.removeClassName('taskDetector');
			if($('datadivRes'+milVersion))
				$('datadivRes'+milVersion).removeClassName('taskDetector');
		}
	}
	else {
		getNewCCD(date, '', grpId);
	}
	
}

function setIntactDates(){

   	var url    = "/groups/groups/setIntactDates.json";
	var rand   = Math.random(9999);
	var pars   = "?rand=" +rand;
	var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: function (transport){
		if(transport.responseText == "expired"){      
			window.location = MainUrl + "main.php?u=signout";return;
		}
		var dateStr = transport.responseText.split('^^');
		$('start4prev').value = dateStr[0];
		$('start4today').value = dateStr[1];
		$('start4next').value = dateStr[2];
		$('start4prevOri').value = dateStr[0];
		$('start4nextOri').value = dateStr[2];
		$('div2Bmoved').value = dateStr[1];

	}} );	

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

function feedshareviatalk(feedid,userid,eventid)
{
	var url    = "/groups/groups/feedShareViaTalk.json";
	var rand   = Math.random(9999);
	var pars   = "rand=" +rand + '&feedid='+feedid+'&userid='+userid+'&eventid='+eventid;
	var myAjax = new Ajax.Request( url, {method: "post", parameters: pars,onSuccess:
								function(transport)
								{
									if(transport.responseText == "expired")
							        {      
							        	window.location = MainUrl + "main.php?u=signout";return;
							        }
								}
										
	} );
}

function emailIdWrap(email)
{
	var finalEmail = '';
	var fl = email.length;
	var lim = 20;
	if(fl > lim)
	{
		var femail = email.split('@');
		finalEmail = femail[0]+'@<br>'+femail[1];
		return finalEmail; 
	}
	else
	{
		return email;
	}
}

function wrapDescText(descText, lim)
{
	var textLen = descText.length;
	if(textLen > lim)
	{		
		tmp = descText.wordWrap(lim, "<br>", 2);		
		return tmp;
	}
	return descText;
}

function calTotPage(mdgrpid,totgrpid)
{
	var url    = "/groups/groups/calTotPage.json";
	var rand   = Math.random(9999);
	var pars   = "rand=" +rand + "&grpid=" + totgrpid + "&MDGrpid=" +mdgrpid;
	var myAjax = new Ajax.Request( url, {method: "post", parameters: pars,onSuccess:
								function(transport)
								{
									if(transport.responseText == "expired")
							        {      
							        	window.location = MainUrl + "main.php?u=signout";return;
							        }
							        else
							        {
							        	data = (eval('(' + transport.responseText + ')'));
							        	totalPage = data.totPage;
							        	$('hdnCurrPageNo').value=totalPage;
							        	paginateData('/groups/groups/view.json',totalPage);
							        }
								}
										
	} );	
}

function calTotPageFls(catId,grpid,section,cond)
{
	var rand   = Math.random(99999);
			new Ajax.Request('/groups/files/getTotPagefls.json', {
	  			parameters: { rand:rand,grpid:grpid, section:section,cond:cond},
	  			onSuccess: function(transport)
	  				{
	  					if(transport.responseText == "expired")
				        {      
				        	window.location = MainUrl + "main.php?u=signout";return;
				        }
				        else
				        {
				        	data = (eval('(' + transport.responseText + ')'));
				        	totPage = data.totFls;
				        	
				        	$('hdnCurrPageNo').value=totPage;
				        	if(section == "F")
				        		loadFiles('F',catId,0,totPage);
				        	else if(section == "M")
				        		loadMsgFiles('M',catId,0,totPage)
				        }	
	  				}
			});
}

function fileNameBreak(fileNameOrg,lim)
{
	var finalFileName = "";
	var fnArr = fileNameOrg.split('.');
	var flg = 1;
	var flExt = fnArr[fnArr.length-1];
	var fileName = fileNameOrg.substring(0,(fileNameOrg.length - flExt.length)-1);
	var fnLen = fileName.length;
	if(fnLen >= lim)
	{
		var flg=1;
		var cntLoop = parseInt(fnLen/lim);
		for(var i=1;cntLoop > i || cntLoop == i;i++)
		{
			var fName = fileName.substring(0,lim);
			finalFileName = finalFileName + fName + '<br>';
			fileName = fileName.substr(lim);
			if(lim > fileName.length)
			{
				if((lim+1) > (fileName+flExt).length)
					flg = 2;
			}
		}
		finalFileName = finalFileName + fileName;
		
		if(flg == 1)
			finalFileName = finalFileName + '<br>' + flExt;
		else if(flg == 2)
			finalFileName = finalFileName +'.'+ flExt;
			
		return finalFileName;
	}
	else
	{
		return fileNameOrg;
	}
}

function checkThreadDelete(tid,type,func)
{
	func = unescape(func);
	var errDiv="commentStatus"+tid,
		strType = "discussion";
		
	if(type == "TL")
	{
		errDiv = "tlkCommMsg"+tid;
		strType = "tweet";
	}	
	else if(type == "FL")
	{
		errDiv = "flsCommMsg"+tid;
		strType = "file";
	}	
	else if(type == "ML")
	{
		errDiv = "flsCommMsg"+tid;
		strType = "file";
	}
	else if(type == "TK")
	{
		errDiv = "flsCommMsg"+tid;
		strType = "file";
	}	
			
		
	var rand   = Math.random(99999);
	new Ajax.Request('/groups/delete/checkDelete.json', {
		parameters: { rand:rand,tid:tid,type:type},
		onSuccess: function(transport)
			{
				if(transport.responseText == "expired")
		        {      
		        	window.location = MainUrl + "main.php?u=signout";return;
		        }
		        else
		        {
		        	data = (eval('(' + transport.responseText + ')'));
		        	isDel = data.isDelete;
		        	
		        	if(isDel == "0")
		        	{
		        		eval(func);
		        	}	
		        	else
					{
						$(errDiv).innerHTML = "Your comment was not posted because this "+strType+" was deleted by its author.";
						$(errDiv).show();
						
						if(!$(errDiv).className.match('err'))
							$(errDiv).className = $(errDiv).className + ' err';
							
						setTimeout("$('"+errDiv+"').hide();",5000);	
					}
		        }	
			}
	});	
}

function updateDelPopup()
{
	var type = $('chkDelType').value;
	var rand   = Math.random(99999);
	new Ajax.Request('/groups/delete/updDeleteFlag.json', {
		parameters: { rand:rand,type:type},
		onSuccess: function(transport)
			{
				if(transport.responseText == "expired")
		        {      
		        	window.location = MainUrl + "main.php?u=signout";return;
		        }	
			}
	});
}

/*********************************************************************************************************/
/* blurb related functions*/

function showBlurb(type, selfid, event)
{	
	/*dont show blurb if user is not on dashboard*/
	if($('currGrpId') && ($('currGrpId').value != "ovr" || $('currPageCat').value != "ovr"))
	{
		return false;
	}
	else if(window.frames['mainframe'])
	{	
		if(window.frames['mainframe'].$('currGrpId') && (window.frames['mainframe'].$('currGrpId').value != "ovr" || window.frames['mainframe'].$('currPageCat').value != "ovr"))
		{
			return false;
		}
	}
	//alert(findPosElement(selfid));
	/*find position of the mouse pointer*/
	var f = findPosElement(selfid);
	
	var leftPar = parseInt(f[0]);
	var topPar = parseInt(f[1]);
	
	//alert(leftPar+'=='+topPar);
	
	/*if(type == 'cal')
	{
		if(window.frames['mainframe'].$('calFlag').value == '1')
		{
			leftParInt = leftPar + 50;
			if(BrowserDetect.browser == 'Explorer'){
				topParInt = topPar + 35;
			}
			else if(BrowserDetect.browser == 'Firefox'){
				topParInt = topPar + 38;
			}
			else{
				topParInt = topPar + 35;
			}
			window.frames['mainframe'].$('calender_blurb').setStyle({top : topParInt + 'px',left : leftParInt + 'px',zIndex : 107});
			setTimeout("window.frames['mainframe'].$('calender_blurb').style.display = 'block'",500);
		}	
		else
			return false;
	}*/
	if(type == 'profile')
	{
		if(document.getElementById('profileFlag').value == '1')
		{			
			if(BrowserDetect.browser == 'Explorer'){
				topParInt = topPar + 22;
				leftParInt = leftPar - 70;
			}
			else{
				topParInt = topPar + 27;
				leftParInt = leftPar - 70;
			}
			$('profile_blurb').setStyle({top : topParInt + 'px',left : leftParInt + 'px'});
			setTimeout("$('profile_blurb').style.display = 'block'",500);
		}	
		else
			return false;
	}
	else if(type == 'myNews')
	{
		if(document.getElementById('myNewsFlag').value == '1')
		{//proj2Gr			
			if(BrowserDetect.browser == 'Explorer' && BrowserDetect.version == 8){
				topParInt = topPar + 27;
				leftParInt = leftPar - 70;
			}
			else if(BrowserDetect.browser == 'Explorer' && BrowserDetect.version == 7){
				topParInt = topPar + 22;
				leftParInt = leftPar - 440;
			}
			else{
				topParInt = topPar + 30;
				leftParInt = leftPar - 68;
			}
			$('mynews_blurb').setStyle({top : topParInt + 'px',left : leftParInt + 'px'});			
			setTimeout("$('mynews_blurb').style.display = 'block'",500);
		}	
		else
			return false;
	}
	else if(type == 'weekView')
	{//alert(parent.window.frames['mainframe'].$('weekViewFlag'));
		if(parent.window.frames['mainframe'].$('weekViewFlag').value == '1')
		{
			leftParInt = leftPar - 83;
			if(BrowserDetect.browser == 'Explorer'){
				if(BrowserDetect.version == 8)
					topParInt = topPar - 8;
				else if(BrowserDetect.version == 7)
					topParInt = topPar - 18;
			}
			else if(BrowserDetect.browser == 'Firefox'){
				topParInt = topPar - 16;
			}
			else{
				topParInt = topPar - 21;
			}		
			$('weekView_blurb').setStyle({top : topParInt + 'px',left : leftParInt + 'px'});	
			setTimeout("$('weekView_blurb').style.display = 'block'",500);
		}	
		else
			return false;
	}
	else if(type == 'talk')
	{
		if($('talkFlag').value == '1' && $('talkBlurbShowHide').value == '1' && $('cntDropdown').value > '1')
		{
			//alert(selfid.style.width);
			leftParInt = leftPar + parseInt(selfid.style.width) + 50;
			topParInt = topPar-68;
			$('talk_blurb').setStyle({top : topParInt + 'px',left : leftParInt + 'px'});	
			setTimeout("$('talk_blurb').style.display = 'block'",500);
		}
	}
	else if(type == 'share')
	{
		if($('shareFlag').value == '1' && $('talkTxtAreaDefault').style.display != 'none')
		{
			if(BrowserDetect.browser == 'Explorer' && BrowserDetect.version == 8){
				topParInt = topPar + 26;
				leftParInt = leftPar - 50;
			}
			else if(BrowserDetect.browser == 'Explorer' && BrowserDetect.version == 7){
				topParInt = topPar + 40;
				leftParInt = leftPar - 50;
			}
			else if(BrowserDetect.browser == 'Firefox'){
				topParInt = topPar + 30;
				leftParInt = leftPar - 50;
			}
			else
			{
				topParInt = topPar + 30;
				leftParInt = leftPar - 45;
			}
			
			$('share_blurb').setStyle({top : topParInt + 'px',left : leftParInt + 'px'});	
			setTimeout("$('share_blurb').style.display = 'block'",500);
		}
	}
	else
		return false; 
}

/*function to hide a particular blurb for a particular user forever*/
function dontShowBlurb(type,user_id)
{
	/*if(type == 'cal')
	{
		$('calender_blurb').hide();
		$('calFlag').value = '2';	
	}*/
	if(type == 'profile')
	{
		$('profile_blurb').hide();
		$('profileFlag').value = '2';
	}
	else if(type == 'myNews')
	{
		$('mynews_blurb').hide();
		$('myNewsFlag').value = '2';
	}
	else if(type == 'weekView')
	{
		$('weekView_blurb').hide();
		parent.window.frames['mainframe'].$('weekViewFlag').value = '2';
	}
	else if(type == 'calCell')
	{
		$('calCell_blurb').hide();
		parent.window.frames['mainframe'].$('calCellFlag').value = '2';
	}
	else if(type == 'talk')
	{
		$('talk_blurb').hide();
		parent.window.frames['mainframe'].$('talkFlag').value = '2';
	}
	else if(type == 'share')
	{
		$('share_blurb').hide();
		parent.window.frames['mainframe'].$('shareFlag').value = '2';
	}
	else
		return false; 
	
	var url    = "/groups/groups/dontShowBlurb.json";
	var rand   = Math.random(9999);
	var pars   = "rand="+rand+"&user_id="+user_id+"&type="+type;
	var myAjax = new Ajax.Request( url, {method: "post", parameters: pars});
}

/*function to find the position of the element */
function findPosElement(obj)
{	
	for (var lx=0, ly=0;obj != null;lx += obj.offsetLeft, ly += obj.offsetTop, obj = obj.offsetParent);
    return [lx,ly];
}

function show_cal_blurb()
{
	var cal = findPosElement(parent.$('calendarOpenButton'));
	var leftParInt = 0;
	var topParInt = 0;
	
	/*for calender*/
	leftParInt = parseInt(cal[0]) + 50;
	if(BrowserDetect.browser == 'Explorer'){
		topParInt = parseInt(cal[1]) + 35;
	}
	else if(BrowserDetect.browser == 'Firefox'){
		topParInt = parseInt(cal[1]) + 38;
	}
	else{
		topParInt = parseInt(cal[1]) + 35;
	}
	if(parent.$('calendarOpenButton'))
	{
		parent.window.frames['mainframe'].$('calender_blurb').setStyle({top : topParInt + 'px',left : leftParInt + 'px',zIndex : 107});
		if(parent.window.frames['mainframe'].$('calFlag').value == 1)
		{
			parent.window.frames['mainframe'].$('calender_blurb').style.display = 'block';	
		}
		else if(parent.window.frames['mainframe'].$('calFlag').value == 2)
		{
			parent.window.frames['mainframe'].$('calender_blurb').style.display = 'none';
		}
	}
	else
	{
		window.setTimeout('show_cal_blurb()', 1000);
		//alert ('calender button not found');
	}
}

// blurb related function ends
/**************************************************************************************/

function changeBlueBg(elem){
	var strUnreadElems = 'div#'+elem+' div.unRead';
	var unReadElems = $$(strUnreadElems);
	var elemLen = unReadElems.length;
	for(var i=0;i<elemLen; i++)
	{
		if(unReadElems[i])
			unReadElems[i].className = unReadElems[i].className.replace('unRead','read');
	}
}

function hideShowButtonDivWithCond(id){

	var elems = $$('div#'+id+' div.butComment');
	var elemLen = elems.length;
	
	for(var i=0;i<elemLen; i++)
	{
		if(elems[i]){
			var toHide = 1;
			var exprElems = $$('div#'+elems[i].id+' span');
			var len = exprElems.length;
			
			for(var j=0;j<len; j++)
			{
				if(exprElems[j].style.display != 'none'){
					toHide = 0;
					break;			
				}
			}
			
			if(toHide == 0 && elems[i].id.indexOf('todoDiv') != 0)
				elems[i].show();
			else 
				elems[i].hide();
			
		}
	}	
}

function showOnMouseOver(id){
	var toHide = 1;
	var exprElems = $$('div#'+id+' span');
	var len = exprElems.length;
	
	for(var j=0;j<len; j++)
	{
		if(exprElems[j].style.display != 'none'){
			toHide = 0;
			break;			
		}
	}
	if(toHide == 0)
		$(id).show();
	else 
		$(id).hide();
}


function ucwords(str) {
    return (str + '').replace(/^(.)|\s(.)/g, function ($1) {
        return $1.toUpperCase();    });
}

/*
 *  type = Milestone/Task
 *  evt = close/reopen/edit/comment/view/dwld
 *  stat = 1 => Deleted / 2 => completed 
 */
function showUtlPopup(type,evt,stat)
{
	var msg = "";
	    		
	switch(evt)
	{
		case 'edit':
			if(stat == "1")
				msg = "You cannot edit this "+type+" because it has just been deleted by the author.";
			else
				msg = "You cannot edit this "+type+" because it is just marked as complete.";	
			break;
			
		case 'close':		
			if(stat == "1")
				msg = "You cannot complete this "+type+" because it has just been deleted by the author.";
			else
				msg = "You cannot complete this "+type+" because it is just marked as complete.";				
			break;
			
		case 'reopen':
			if(stat == "1")
				msg = "You cannot reopen this "+type+" because it has just been deleted by the author.";
			else
				msg = "You cannot reopen this "+type+" because it is just reopened.";				
			break;
			
		case 'view':
				msg = "This milestone is deleted.";							
			break;
		
		case 'viewTask':
				msg = "You cannot view tasks because it has just been deleted by the author.";							
			break;
				
		case 'comment':
			if(stat == "1")
				msg = "You cannot comment on this "+type+" because it has just been deleted by the author.";
			else
				msg = "You cannot comment on this "+type+" because it is just marked as complete.";				
			break;
			
		case 'dwld':
			
			msg = "This file has been deleted.";				
			break;	
			
		case 'addTL':
			if(stat == "1")
				msg = "You cannot create this tasklist because the milestone attached to it has just been deleted by the author.";
			else
				msg = "You cannot create this tasklist because the milestone attached to it is just marked as complete.";				
			break;				
	}
	
	$('spnUtlMsg').innerHTML = msg;
	$('utlPopup').show();			
	centerPos($('utlPopup')	, 1);
}


function checkFileExists(url,fileid)
{
	var rand   = Math.random(9999);
	var pars   = "rand="+rand+"&fileid="+fileid;
	new Ajax.Request( '/groups/files/checkFileStatus.json', {method: "post", parameters: pars,
										onSuccess:function(transport)
										{
											if(transport.responseText == "expired")
									        {      
									        	window.location = MainUrl + "main.php?u=signout";return;
									        }
									        
									        var data = (eval('(' + transport.responseText + ')'));
									        
									        if(data.stat == "0")
									        	showUtlPopup('file','dwld',1);
									        else
									        	window.location = url;
										}		
					});		
}	