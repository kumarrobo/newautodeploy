<div>
<div class="tabs">
  <ul id='navTabs'>
  		<li>
  		<a href="/users/view">Dashboard</a>
		</li>
  		<li class="sel">
  		<a href="/users/paynow">Payment</a>  		
		</li>		
		<!-- <li id="UserBalance" class="balance" style="position:relative; top:-20px; right:5px;">Balance : <span><img class='rupee1' src='/img/rs.gif'/></span><?php echo number_format($objGeneral->getBalance($_SESSION['Auth']['User']['id']),2,'.','');?> </li> -->
		<br class="clearLeft" />
  </ul>
  <div class="clearLeft"></div>
</div>
</div> 
<div class="loginCont">
  <div>
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>                       
          	<li name='innerli'>
          		<a id="directPay" class="sel" href="/users/paynow" >Online Payment</a>          			
		  	</li>
          	<li name='innerli'>
          		<a id="cheque" href="javascript:void(0);" onclick="paymentTabs(this,'chequeDiv');">Cheque/DD</a>
		  	</li>
		  	<li name='innerli'>
				<a id="billingDet" href="javascript:void(0);" onclick="paymentTabs(this,'billingDiv');">Billing Details</a>
			</li>
		  	<li name='innerli'>
				<a id="custSupport" href="javascript:void(0);" onclick="paymentTabs(this,'custCareDiv');">Customer Support</a>
			</li>						                  
        </ul>
      </div>
    </div>
    
    <div style="margin-left:223px;" id="innerDiv">
    
    <!-- right hand side -->
    <!-- payment -->
    <div id="paymentDiv" style="display:block">
	<div class="appColRight1" style="font-size:0.9em">
		<div class="priceBox" style="padding:0px;">
			<div class="title4 fntSz15" style="padding:10px;"><span class="fntSz19 strng">Offline Payments</span></div>
			<div class="info" style="padding:10px;">
				<div>
					<div class="retailImageSmall1 rightFloat" style="padding-bottom:10px;width:110px;height:68px;">
						<img class="img_11" src="/img/spacer.gif">
					</div>
					<div style="padding-bottom:10px;">You could now find the SMSTadka Recharge card near your place.</div><br class="clearRight">
					<div style="padding-bottom:20px;">Go to any nearby <img src="/img/spacer.gif" class="pay4" align="absmiddle"> retailer, and ask for ST eRecharge Products. To locate your nearby shop<br><span class="strng">SMS: SHOP<pincode> to 0922 317 8889<br>OR<br>SMS: HELP<your query> to 09222 317 8889</span></div>
				</div>
				<div class="title5 strng">Cheque / Drafts:</div>
				<p>For <b>home delivery</b>* call us on<br><b> 022-2472 1133 <br> 09321 333 444</b><br> <span class="smallerFont">*Only in Mumbai, Navi Mumbai & Thane</span></p>
				<p>All <span class="strng">cheques/demand drafts</span> should be drawn in favour of <span class="strng">"Mindsarray Technologies Pvt. Ltd."</span>.</p>
				<p>Deposit your cheques at your nearest <span class="strng">ICICI BANK.<br> Our account number : 019805004392</span></p>
				<span>Or You can courier the cheque/DD at the below mentioned address.<br></span><br>
				<div class="title5 strng">Address:</div>
				<p><span class="strng">Mindsarray Technologies Pvt. Ltd.</span><br>
					528, Raheja's Metroplex (IJMIMA),<br/>
				 Link Road, Malad (W),<br/> 
				Mumbai - 400064, Maharashtra.</p>
					
					<p><span class="strng">Contact No:</span> 09769597418 <br/></p><p>Email us your cheque details at <a href="mailto:billing@smstadka.com">billing@smstadka.com</a>. Mention your name(Account holder's name), registered mobile number, cheque amount, cheque date, bank details, cheque number in your email. We will recharge your account on successful clearance of your cheque.</p>
				<span>Thank You.</span>
			</div>
		</div>
	</div>
	<div class="appColLeft1" id="appReminderAddDiv">
		<!-- Input box -->
		<div class="boxWithoutShadow">
			<div class="title6 fntSz15" style="padding:5px;"><span class="fntSz19 strng">Online Payments</span> - Credit / ATM(Debit) Card, Net Banking, Cash Card</div>
			<div style="padding:5px;">
				<div class="leftFloat leftCol6"><img title="DirectPay Logo" alt="DirectPay Logo" class="oSPos11 otherSprite" src="/img/spacer.gif">
	    		</div>
	    		<!-- direcPay Gateway-->
	    		<form action="https://www.timesofmoney.com/direcpay/secure/dpMerchantTransaction.jsp" method="post" id="ecom" name="ecom">
					<div id="paymentGatewayDiv">				
						<input type="hidden" name="custName" value="<?php echo $data['billing_user']['name'];?>">
						<input type="hidden" name="custAddress" value="<?php echo $data['billing_user']['address'];?>">
						<input type="hidden" name="custCity" value="<?php echo $data['billing_user']['city'];?>">
						<input type="hidden" name="custState" value="<?php echo $data['billing_user']['state'];?>">
						<input type="hidden" name="custPinCode" value="<?php echo $data['billing_user']['zip'];?>">
						<input type="hidden" name="custCountry" value="IN">
						<input type="hidden" name="custMobileNo" value="<?php echo $_SESSION['Auth']['User']['mobile'];?>">
						<input type="hidden" value="IN" name="custCountry">
						<input type="hidden" value="00" name="custPhoneNo1">
						<input type="hidden" value="00" name="custPhoneNo2">
						<input type="hidden" value="00" name="custPhoneNo3">
						<input type="hidden" name="custEmailId" value="<?php echo $data['billing_user']['email'];?>">
						<input type="hidden" name="otherNotes" value="<?php echo $data['billing_user']['other'];?>">
					</div>
				</form>
				<!-- ICC Gateway-->
				<form action="https://www.icashcard.in/pgGateway/ICCPaymentGateway.aspx" method="post" id="formPayICC" name="formPayICC">
					<div id="paymentGatewayDivICC"></div>
				</form>
				
				<!-- OSS Gateway-->
				<form action="http://www.osscard.com/PaymentGatewayASP/OSSCardPaymentGateway.asp" method="post" id="formPayOSS" name="formPayOSS">
					<div id="paymentGatewayDivOSS"></div>
				</form>
				
				
				<div id="recharge" class="rightCol6">
	    			<div class="SampJokesTitle1 field">Choose recharge amount</div>
	    			<div class="field strng">
    					<input checked="checked" type="radio" value="50" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>50.00</label><span class="padding1">&nbsp;</span><input type="radio" value="100" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>100.00</label><span class="padding1">&nbsp;</span><input type="radio" value="200" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>200.00</label><span class="padding1">&nbsp;</span><br><input type="radio" value="500" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>500.00</label><span class="padding1">&nbsp;</span><input type="radio" value="1000" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>1000.00</label>
    				</div><br>
    				<form name="paymentOptionForm">
    				<div class="SampJokesTitle1 field">Choose payment method</div>
    				<div><input checked="checked" type="radio" value="dp" name="card" id="c1"><label for="c1" style="display:inline-block;">Debit Card / Credit Card / Netbanking</label></div>
    				<div style="margin-left:20px;"><img src="/img/spacer.gif" class="pay1"></div>
    				<div><input type="radio" value="icc" name="card" id="c2"><label for="c2">I Cash Card - Smart Shop Cash Card</label></div>
    				<div style="margin-left:20px;"><img src="/img/spacer.gif" class="pay2"></div>
    				<div><input type="radio" value="oss" name="card" id="c3"><label for="c3">OSS Card - One Stop Shop Cash Card</div>
    				<div style="margin-left:20px;"><img src="/img/spacer.gif" class="pay3"></div></form>
    				<div id="paymentSubmit" style="padding:10px 0px;"><a href="javascript:void(0);" class="retailBut enabledBut strng" onclick="makePayment()">Pay Now</a></div>
	    		</div>
				
				<!-- <div id="paymentSubmit"><input type="image" onclick="encodeTxnRequest();" class="otherSprite oSPos7" src="/img/spacer.gif"></div> -->
			</div>
		</div>
		</br>
		<!-- Input box -->
		<div class="info">
			<p class="hints"><img src="/img/ssl_seal.png" class="rightFloat" ><i><span class="strng">Important Note:</span><br />
			1. This is not your prepaid mobile recharge.<br />
			2. This payment is only for SMSTadka services.<br />
			3. The payment facility is fully secure via 128 bit encryption.</i> </p>
			<div class="title5">Credit Card</div>
			<p><img src="/img/cc.gif" width="208" height="47" /></p>
		  <div class="title5">Visa / Master ATM (Debit) cards</div>
			<p><img src="/img/debit.jpg" width="459" height="101" /></p>
		  <div class="title5">Net Banking:</div>
			<p><img src="/img/netbanking.jpg" width="459" height="68" /></p>
		  <div class="title5">PayMate</div>
			<p><img src="/img/paymate.gif" width="91" height="42" /></p>
	  </div>
		
	</div>
	<div class="clearRight">&nbsp;</div>
	
	
	<input type="hidden" name="amountobepaid" id="amountobepaid" value="50">
	<script>
	$$('#recharge input').each(function(e) { e.observe('click', function(event) {
		//alert(e.type)
			if (e.type == 'radio')
			{
				if(e.value == 'other') {
					//$('urAmt').show();				
					//$('amount').value = '';
					}
				else {				
					//$('urAmt').hide();
				}
			}	
		});
	});
		
	</script>
	</div>
	<!-- payment ends -->
	<!-- check/dd -->
	<div class="field" id="chequeDiv" style="display:none">
	<fieldset>
	<div class="box2" style="margin-top:0px;">
		<div class="header">Cheque/DD</div>
	    <div class="Blanstate">&nbsp;</div>
	    <div style="padding:10px;">
	    	<p class="field">All cheques/demand drafts should be drawn in favour of  <span class="highlight">"Mindsarray Technologies Pvt. Ltd."</span>.</p>
			<p class="field">Deposit your cheques at your nearest ICICI BANK.<br> <b>Our account number : 019805004392</b></p>
			<p class="field">Email us your cheque details at <a href="mailto:billing@smstadka.com">billing@smstadka.com</a>. Mention your name(Account holder's name), registered mobile number, cheque amount, cheque date, bank details, cheque number in your email. We will recharge your account on successful clearance of your cheque.</p>
			<p>You can courier the cheque/DD at the below mentioned address.<br><br></p>
			
			<div class="title5 strng">Address:</div>
			<p><span class="strng">Mindsarray Technologies Pvt. Ltd.</span><br>
			528, Raheja's Metroplex (IJMIMA),<br/>
				 Link Road, Malad (W),<br/> 
				Mumbai - 400064, Maharashtra.</p><br>
			<p class="field">Thank You.</p>
			
	</div>
	</div>
	</fieldset>	
	</div>
	<!-- check/dd ends-->
	<!-- customer care -->
	<div class="field" id="custCareDiv" style="display:none">
	<fieldset>
	<div class="box2">
		<div class="header">Customer Support</div>
	    <div class="Blanstate">&nbsp;</div>
	    <div style="padding:10px;">
	    	<p class="field"><b>PROBLEM?</b><br>
	Contact our customer service at the following number between 10AM and 7PM:
	<br>
	<b>09769597418</b><br/>
	<b>09820595052</b><br/>
	<br><br>
	or write to us at:
	<br>
	<a href="mailto:help@smstadka.com">help@smstadka.com</a>
	<br><br>
	<b>Please note :</b> State your SMSTadka registered mobile number in all communications.
	    	</p>
			<p class="field">Thank You.</p>
	</div>
	</div>
	</fieldset>
	</div>
	<!-- customer care ends-->
	<!-- billing details -->
	<div id="billingDiv" style="display:none;">
	<?php echo $form->create('billdetails'); ?>
     	<fieldset class="fields">
			<div class="title3">Billing Details</div>
				<p class="fieldDetail" style="margin-top:5px;margin-bottom:15px;">Enter your billing details below for easy recharge process</p>
				<div class="field" style="padding-top:10px;">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="username"> Name </label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="1" type="text" id="username" name="custName" autocomplete="off" value="<?php if(isset($data['billing_user']['name']))echo $data['billing_user']['name']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
              
                 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="address">Billing Address</label></div>
                         <div class="fieldLabelSpace">
                            <textarea tabindex="2" id="address" name="custAddress" style="width:215px;height:55px;"><?php if(isset($data['billing_user']['address']))echo $data['billing_user']['address']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 
            	  <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="city">City</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="3" type="text" id="city" name="custCity" value="<?php if(isset($data['billing_user']['city']))echo $data['billing_user']['city']; ?>"/>
                            </div>
                    </div>
            	 </div>
            	 
            	  <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="state">State</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="4" type="text" id="state" name="custState" value="<?php if(isset($data['billing_user']['state']))echo $data['billing_user']['state']; ?>"/>
                            </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="zip">Pin Code</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="5" type="text" id="zip" name="custPinCode" value="<?php if(isset($data['billing_user']['zip']))echo $data['billing_user']['zip']; ?>"/>
                            </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="country">Country</label></div>
                         <div class="fieldLabelSpace">
                            <span id="country" name="custCountry"> India </span>
                         </div>
                    </div>
            	 </div>
            	 
            	 
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="email">Email Address</label></div>
                         <div class="fieldLabelSpace">
                             <input tabindex="6" type="text" id="email" name="custEmailId" value="<?php if(isset($data['billing_user']['email']))echo $data['billing_user']['email']; ?>"/>
                         </div>
                    </div>
            	 </div>
            	 
            	  <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="other">Other Notes or Instructions</label></div>
                         <div class="fieldLabelSpace">
                             <textarea tabindex="7" id="other" name="otherNotes" style="width:215px;height:55px;"><?php if(isset($data['billing_user']['other']))echo $data['billing_user']['other']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 
            	 
                 
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" id="sub_butt">
                            <?php echo $ajax->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'8','url'=> array('controller'=>'users', 'action'=>'changeBillDetails'),'class' => 'otherSprite oSPos7','after' => 'showLoader2("sub_butt");', 'update' => 'billingDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" style="color:#004B91">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>			
		</fieldset>
	<?php echo $form->end(); ?>
	<script>
	if($('username'))
		$('username').focus();	
	</script>
	</div>
	<!-- billing details end -->
	<!-- right hand side end-->
	</div>
    <br class="clearLeft" />
 </div>