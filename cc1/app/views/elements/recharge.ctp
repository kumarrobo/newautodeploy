<div class="appColRight1" style="font-size:0.9em">
	<div class="priceBox" style="padding:0px;">
		<div class="title4 fntSz15" style="padding:10px;"><span class="fntSz19 strng">Offline Payments</span> - Cheque / Drafts</div>
		<div class="info" style="padding:10px;">
			<p>All <span class="strng">cheques/demand drafts</span> should be drawn in favour of <span class="strng">"Mindsarray Technologies Pvt. Ltd."</span>.</p>
			<p>Deposit your cheques at your nearest <span class="strng">ICICI BANK.<br> Our account number : 019805004392</span></p>
			<span>Or You can courier the cheque/DD at the below mentioned address.<br></span><br>
			<div class="title5 strng">Address:</div>
			<p><span class="strng">Mindsarray Technologies Pvt. Ltd.</span><br>
				528, Raheja's Metroplex (IJMIMA),<br/>
				 Link Road, Malad (W),<br/> 
				Mumbai - 400064, Maharashtra.
			</p>
				
				<p><span class="strng">Contact No:</span> 09769597418 <br/></p><p><span class="strng">SMS:</span> PAY to 0922 317 8889</p><p>Email us your cheque details at <a href="mailto:billing@smstadka.com">billing@smstadka.com</a>. Mention your name(Account holder's name), registered mobile number, cheque amount, cheque date, bank details, cheque number in your email. We will recharge your account on successful clearance of your cheque.</p>
			<span>Thank You.</span>
		</div>
	</div>	
</div>
<div class="appColLeft1" id="appReminderAddDiv">
	<!-- Input box -->
	<div class="boxWithoutShadow">
		<div class="title6 fntSz15" style="padding:5px;"><span class="fntSz19 strng">Online Payments</span> - Credit Card / ATM (Debit) Card / Net Banking </div>
		<div style="padding:5px;">
			<div class="leftFloat leftCol6"><img title="DirectPay Logo" alt="DirectPay Logo" class="oSPos11 otherSprite" src="/img/spacer.gif">
    		</div>
    		<div id="recharge" class="rightCol6">
    			<div class="SampJokesTitle1 field">Choose recharge amount</div>
    			<div class="field strng">
    				<input checked="checked" type="radio" value="50" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>50.00</label><span class="padding1">&nbsp;</span><input type="radio" value="100" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>100.00</label><span class="padding1">&nbsp;</span><input type="radio" value="200" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>200.00</label><span class="padding1">&nbsp;</span><br><input type="radio" value="500" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>500.00</label><span class="padding1">&nbsp;</span><input type="radio" value="1000" name="amt" onclick="amtSelect(this.value);"><span><img class="rupee1" src="/img/rs.gif"></span><label>1000.00</label>
    			</div>
    		</div>
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
			<input type="image" onclick="encodeTxnRequest();" class="otherSprite oSPos7" src="/img/spacer.gif">
		</div>
	</div>
	<!-- Input box -->
	<div class="info">
		<p class="hints">
		<img src="/img/ssl_seal.png" class="rightFloat" >
		<i><span class="strng">Important Note:</span><br />
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


<input type="hidden" name="amountobepaid" id="amountobepaid" value="20">
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