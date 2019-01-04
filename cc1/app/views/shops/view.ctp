<script>
	function pnrNoValidate(y){
	  var errMsg = '';
	  if(y == ''){
		  errMsg = 'Mention 10 digit PNR No.';
	  }else if(isNaN(y)||y.indexOf(" ")!=-1){
		  errMsg = 'PNR No. should contain numeric values.';
	   }else if (y.length != 10){
		  errMsg = 'PNR No. should be a 10 digit number.';
	   }
	   
	  return errMsg;
	}
	
	function showSubscribe(id,name,outside_flag,price){
		closeSubscribe(1);
		if(outside_flag == 0){
			$('extra').hide();
		}
		else {
			if(id == PNR_PRODUCT || id == PNR_HAPPY_JOURNEY || id == PNR_SIGNATURE){
				$('extrafieldName').innerHTML = 'PNR Number';
				$('extra').show();
			}
		}
		$('enterNo').show();
		centerPos('subscribePopup');
		$('prodTitle').innerHTML = name+' - <span><img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif" class="rupee1"></span>'+price;
		$('retProdId').value = id;
		$('subscribePopup').show();
		$('mobileNo').focus();
		/* Vinit - bg */
		$('bg').show(); // = 'showwrap';//add class to display the div
		if (window.innerHeight && window.scrollMaxY) {// Firefox
		yWithScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		yWithScroll = document.body.scrollHeight;
		} else { // works in Explorer 6 Strict, Mozilla (not FF) and Safari
		yWithScroll = document.body.offsetHeight;
		}
		$('bg').style.height = yWithScroll + 'px';
	}
	
	function closeSubscribe(hideFlag){
		$('successPopupMsg').innerHTML = '';
		$('successPopup').hide();
		$('subscribeForm').reset();
		$('activateErr').innerHTML = '';
		$('confirmErr').innerHTML = '';
		$('confirmPnrNo').innerHTML = '';
		$('pnrConfirm').hide();	
		$('enterNo').hide();
		$('extra').hide();
		$('confirmSub').hide()
		$('subscribePopup').hide()
		
		if(hideFlag == 1)
		$('bg').hide();
	}
	
	function confirmSubscribe(mobileNo,pnrNo){
		$('activateErr').innerHTML = '';
		$('confirmErr').innerHTML = '';
		var err = appMobileValidate(mobileNo.value);
		if(err == ''){
			if($('extra').style.display != 'none'){
				var err1 = pnrNoValidate(pnrNo.value);
				if(err1 == ''){
					$('enterNo').hide();
					$('confirmNo').innerHTML = mobileNo.value;
					
					$('confirmPnrNo').innerHTML = pnrNo.value;
					$('pnrConfirm').show();
					
					$('confirmSub').show();
				}else{
					$('activateErr').innerHTML = '<div class="error_class">'+err1+'</div>';
					return false;
				}	
			}else{// others
				$('enterNo').hide();
				$('confirmNo').innerHTML = mobileNo.value;
				$('confirmSub').show();
			}			
		}else{
			$('activateErr').innerHTML = '<div class="error_class">'+err+'</div>';
			return false;
		}
	}
	
	function backConfirm(){
			$('activateErr').innerHTML = '';
			$('confirmSub').hide();
			$('enterNo').show();			
	}
	
	function subPackage(){
		showLoader2("sub_butt1");
		var url = '/shops/retailerProdActivation';
		var pars   = {mobNo: $('mobileNo').value,pnrNo: $('extrafield').value,prodId: $('retProdId').value};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
			onSuccess:function(transport)
			{
				$('sub_butt1').innerHTML = '<a href="javascript:void(0);" class="retailBut enabledBut" onclick="subPackage()">Ok</a>';
				var res = transport.responseText;
				res.evalScripts();				
				if(res.strip() != '1'){	
					closeSubscribe(0);
					$('successPopupMsg').innerHTML = res;
					centerPos('successPopup');
					$('successPopup').show();							
				}else{
					$('confirmErr').innerHTML = "<div class='error_class'>You don't have sufficient balance. Please contact your distributor.</div>";
				}
				
			}
		});			
	}

	function changeField(){
		//alert($('mobileNo').value.length);
		if($('extrafield')){
			var charLimit = 8;
			if(navigator.userAgent.toLowerCase().indexOf("msie") > -1 || navigator.userAgent.toLowerCase().indexOf("firefox") > -1)
			charLimit = 9;
			
			if(navigator.userAgent.toLowerCase().indexOf("firefox") > -1){
				if($('mobileNo').value.length == charLimit){
					$('extrafield').focus();
				}
			}else{
				if($('mobileNo').value.length > charLimit){
					$('extrafield').focus();
				}				
			}			
		}
	}
	
	function cf(){
		if($('extrafield')){
			var charLimit = 9;
			if($('mobileNo').value.length == charLimit){
				$('extrafield').focus();
			}
		}
	}
</script>

<div id="bg" class="popOutline" style="position: absolute; z-index: 989; top:0;left:0;display:none;width:98%;">
</div>
<div id="successPopup" class="popOutline" style="display:none;position: absolute; z-index: 990; ">
	<div class="popCont popContWidth">
		<div class="rightFloat popClose"><a onclick="closeSubscribe(1);" href="javascript:void(0);">x</a></div>
		<div>&nbsp;</div>
		<div id="successPopupMsg">asdkasd asdkd asdkhd asdka shkd asbdka dakshda sdahsdk asdk has</div>
	</div>	
</div>
<div id="subscribePopup" class="popOutline" style="display:none;position: absolute; z-index: 990; width: 460px; top: 201px; left: 272px;">
 <form name="subscribeForm" id="subscribeForm">	
	<div class="popCont popContWidth">
		<div style="" class="rightFloat popClose"><a onclick="closeSubscribe(1);" href="javascript:void(0);">x</a></div>
		<div>&nbsp;</div>
		<div class="appTitle" id="prodTitle"></div>

		<div id="enterNo" style="display:none;">		
			<div class="field">
				<div class="fieldDetail">
			         <div class="fieldLabel1 leftFloat"><label for="amount">Mobile No.</label></div>
			         <div class="fieldLabelSpace1">
			            <input type="text" maxlength="10" value="" autocomplete="off" name="mobileNo" id="mobileNo" tabindex="1" onkeypress="changeField();">
			            <input type="hidden" name="retProdId" id="retProdId" value="">			            
			         </div>		                              
			 	</div>
			 	
	     	</div>
	     	<div class="field" id="extra" style="display:none">
	     		<div class="fieldDetail">
			         <div class="fieldLabel1 leftFloat"><label for="extrafield" id="extrafieldName"></label></div>
			         <div class="fieldLabelSpace1">
			            <input type="text" value="" autocomplete="off" name="extrafield" id="extrafield" tabindex="2">			            
			         </div>		                              
			 	</div>
			</div> 	
	     	<div class="field">
				<div class="fieldDetail">
			         <div class="fieldLabel1 leftFloat">&nbsp;</div>
			         <div class="fieldLabelSpace1">
			            <div id="sub_butt">
							<a href="javascript:void(0);" class="retailBut enabledBut" onclick="confirmSubscribe($('mobileNo'),$('extrafield'))">Activate</a>
						</div>
			         </div>		                              
			 	</div>
	     	</div>
	     	<div id="activateErr"></div>
     	</div>

     	<div id="confirmSub" style="display:none;">
			<div class="field">
				<div class="fieldDetail">
			         <div class="fieldLabel1 leftFloat"><label for="amount">Mobile No.</label></div>
			         <div class="fieldLabelSpace1" id="confirmNo" style="width:148px;"></div>			                                 
			 	</div>
	     	</div>
	     	
	     	<div class="field" id="pnrConfirm" style="display:none;">
				<div class="fieldDetail">
			         <div class="fieldLabel1 leftFloat"><label for="amount">PNR No.</label></div>
			         <div class="fieldLabelSpace1" id="confirmPnrNo"></div>			                                 
			 	</div>
	     	</div>
	     	<div>Are you sure you want to activate?</div>
	     	<div class="field">
				<div class="fieldDetail">			        		         
			        <div id="sub_butt1" class="leftFloat" style="margin-right:10px;">			        
			        	<a href="javascript:void(0);" class="retailBut enabledBut" onclick="subPackage()">Ok</a>
						<?php //echo $ajax->submit('Ok', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'allotRetailCards'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>						
					</div>                            
					<div>				
						<a href="javascript:void(0);" class="retailBut enabledBut" onclick="backConfirm()">Back</a>												
					</div>
			 	</div>
	     	</div>
	     	<div id="confirmErr"></div>
     	</div>
	</div>
</form>	
</div>
<div style="min-height:500px;">
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'home'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont" style="padding-left:1px;">
    		<?php if($_SESSION['Auth']['User']['group_id'] == RETAILER) { ?>
    		
    		<div style="margin:0 auto;width:998px;">
    		<?php foreach($products as $prod){ 
    			if($prod['Product']['id'] == PNR_PRODUCT && $info['signature_flag'] == 1){
    				$prod = $pnr_sign;
    			}
    		?>
				<div class="retailImage inIEretailImage">
					<a href="javascript:void(0);" onclick="showSubscribe('<?php echo $prod['Product']['id']; ?>','<?php echo $prod['Product']['name']; ?>','<?php echo $prod['Product']['outside_flag']; ?>','<?php echo $prod['Product']['price']; ?>')"><img class="img_<?php echo $prod['Product']['id']; ?>" src="/img/spacer.gif"></a><br>
					<div id="sub_butt">
						<a href="javascript:void(0);" class="retailBut enabledBut" onclick="showSubscribe('<?php echo $prod['Product']['id']; ?>','<?php echo $prod['Product']['name']; ?>','<?php echo $prod['Product']['outside_flag']; ?>','<?php echo $prod['Product']['price']; ?>')">Activate</a>						
					</div>
				</div>
			<?php } ?>
			</div>
			<?php } ?>
    	</div>
    </div>
 </div>
<br class="clearRight" />
</div>