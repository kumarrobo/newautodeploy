<!-- data: Landing page or Login page -->
<!-- Two column row one -->
<div style="padding-top:15px;">
	<!-- Left column -->
	<div class="mainColumnLeft" style="width:100%">
		<div class="landingPgTabPos">
		<div class="adsFreePos"><img src="/img/spacer.gif" class="otherSprite oSPos2" alt="100% Safe | Spam Free | Ads Free" title="100% Safe | Spam Free | Ads Free"/></div>
        	<ul id="landingPgTabs">
				<li class="tab1"><a href="javascript:void(0);" class="sel" onclick="changeIndexTab(this)";>Get Started</a></li>
				<li class="tab2"><a href="javascript:void(0);" onclick="changeIndexTab(this)";>What is SMSTadka?</a></li>
				<li class="tab3"><a href="javascript:void(0);" onclick="changeIndexTab(this)";>How it Works?</a></li>
				<li class="tab4"><a href="javascript:void(0);" onclick="changeIndexTab(this)";>SMSTadka Shops</a></li>
			</ul>
		</div>
		<!-- Get started cont > Package boxes -->
		<div id="landingPgTabCont">
            <!-- get started -->
			<div id="tab1">
				<p class="fntSz17 strng color3">Explore the following sections and subscribe to your favourite packages.</p>
				<div>
					<?php echo $this->element('categories_login');?>
					<div class="clearLeft">&nbsp;</div>          
    			</div>
			</div>
	        <!-- get started ends -->
	        
	        <!-- what is SMSTadka? -->
			<div id="tab2" class="fntSz15 color2" style="display:none;">
			  	<p class="strng color7">With SMSTadka, get messages every day on your favourite topics.</p>
			  	<p style="padding-top:3px;">SMSTadka offers you SMS subscription service for your mobile. Subscribe to packages (100+) from a wide
range of interesting choices & also use Personal Alert services to create alerts for yourself and others.
<br>SMSTadka helps you stay updated with the latest news/score on the move, gives you giggling
moments by sending high-voltage jokes on your mobile, helps you track your favourite stocks, gives you a chance to follow your favourite stars
and a lot lot more.</p>
			  	<div style="padding-top:20px;">
			  	<p class="strng color7 leftFloat" style="width:49%;">What is Personal Alerts?</p>
			  	<p class="strng color7" style="margin-left:51%;">What is a Package?</p>
			  	</div>
			  	<div class="leftFloat" style="width:49%; padding-top:3px; border-right:1px solid #249111;">			  		
			  		<p>This is a unique service offered by SMSTadka, where you can configure your alerts.</p>
			  		<p style="padding-top:20px;">For example,</p>
			  		<ol>
			  			<li>Alerts for a specific Stock's Price and its news.</li>
 						<li>Alert for your PNR number.</li>
						<li>Reminders for yourself and friends.</li>
			  		</ol>
			  	</div>
			  	<div style="margin-left:51%; padding-top:3px">			  		
			  		<p>Package is an SMS Subscription Channel (Topic), to which you subscribe to get SMS alerts on that topic.</p>
			  		<p style="padding-top:20px;">For example,</p>
			  		<ol>
			  			<li>Daily News</li>
 						<li>Santa-Banta Jokes</li>
						<li>Beauty tips and a lot more</li>
			  		</ol>
			  	</div>
			  	<div class="clearLeft">&nbsp;</div>
			</div>
			<!-- what is SMSTadka? ends -->
			
			<!-- How it works -->
			<div id="tab3" class="fntSz15 color2" style="display:none;">
				<div class="fntSz12" style="padding-bottom:12px;">
					<div class="leftFloat" style="width:35%; margin-right:22px;"><div class="HIWpoints leftFloat">1</div><span class="fntSz24 color1 strng">Register</span><div style="padding-top:4px;">Register by entering your mobile No. & get password on SMS.</div></div>
					<div class="leftFloat" style="width:26%; margin-right:22px;"><div class="HIWpoints leftFloat">2</div><span class="fntSz24 color1 strng">Login</span><div style="padding-top:4px;">Login using the password received on your mobile.</div></div>
					<div class="leftFloat" style="width:30%;"><div class="HIWpoints leftFloat">3</div><span class="fntSz24 color1 strng">Subscribe</span><div style="padding-top:4px;">Browse & Subscribe to your favorite packages and services.</div></div>
					<div class="clearLeft"></div>
				</div>
				<div class="fntSz15 color2 rowDivider1" style="padding-bottom:12px;">
					<div class="leftFloat" style="width:49%; padding-top:3px">
						<div class="color1 strng">Can I try this service for Free?</div>
						<p>Yes, SMSTadka offers you free package trials. You can try different packages by just clicking on the 'Try FREE' link below them.</p>
						<div class="color1 strng" style="padding-top:10px;">Will you charge from my mobile balance?</div>
						<p><span class="color8 strng">NO,</span> We will not charge you from your mobile Balance or Bill. SMSTadka account balance is separate from mobile balance.</p>
					</div>
					<div style="margin-left:51%; padding-top:3px">
						<div class="color1 strng">Is SMSTadka a paid service?</div>
						<p>Yes, once your free trial for the package is over, you will have to recharge your SMSTadka account.</p>
						<div class="color1 strng" style="padding-top:10px;">What are the various payment options? </div>
						<p>You can pay either online (credit card, debit card, netbanking) or offline (cheque drop, cash deposit, retail outlets).</p>
					</div>
					<div class="clearLeft">&nbsp;</div>
				</div>
				<div class="fntSz15 color2 rowDivider1">
					<img src="/img/banks.png">
				</div>
			</div>
			<!-- How it works ends -->
			
			<!-- SMSTadka Shops -->
			<div id="tab4" class="fntSz15" style="display:none;">
				<div style="padding-bottom:0px;">
					<div class="strng" style="color:#b20562;">Retail Products:</div>
					<div style="padding-top: 1px;"><a href="/retailers/all/products"><img src="/img/spacer.gif" style="width:520px; height:102px;background:url(/img/homeShop.gif)"></a></div>
				</div>
				
				<div style="padding-bottom:6px;">
					<!--<div class="strng" style="color:#b20562;">Shop Locator:</div>-->
					<div style="padding-top: 3px;">We have a wide range of products. <a href="/retailers/all/products" class="strng">Click here</a> to view all products.</div>
				</div>
				
				<div style="padding-bottom:6px;">
					<div class="strng" style="color:#b20562;">Shop Locator:</div>
					<div style="padding-top: 2px;">Now find all SMSTadka retail products in your nearby shops! SMS <strong>SHOP to 0922 317 8889</strong> or <a href="/retailers/all" class="strng">click here to locate a Shop</a>. Ask for ST e-Recharge for SMSTadka online balance.</div>
				</div>
				<div style="padding-bottom:6px;">
					<div class="strng" style="color:#b20562;">SMSTadka Retail Channel Partner</div>
					<div style="padding-top: 3px;"><img src="/img/spacer.gif" style="float:left;width:110px;height:40px;background:url(/img/homeShop.gif) left -112px no-repeat;margin-right:10px;">Now OneStopShop(OSS) is our new Retail Channel Partner. Visit your nearest OSS  shop to buy SMSTadka products.</div>
				</div>
				<div>
					<div class="strng" style="color:#b20562;">SMSTadka Business Opportunity</div>
					<div style="padding-top: 1px;">To become our channel partner/distributor/retailer, <a href="mailto:business@smstadka.com" class="strng">email us</a> or <a href="/retailers/all/bRetailer" class="strng">apply here</a></div>
				</div>				
			</div>
			<!-- SMSTadka Shops ends -->
			<!-- Login  Box -->
            <div style="margin-left:585px; position:relative">
                <div>			
                    <ul class="fntSz17 color1 strng ulStyle" >
                        <li>Subscribe Daily SMS packages</li>
                        <li class="last">Stock News, Group Messages & PNR Alerts</li>
                    </ul>
            	</div>
                <div class="curve1 shadow1 boxes1 adjust">
                    <div class="logBoxCont signUp">          
                        <div id="loginDiv"> <?php echo $this->element('captcha_check');?> </div>		          
                    </div>
                </div>
            </div>
            <!-- End of Login Box -->
		</div>
		<!-- Get started cont > Package box ends -->	
       	
	</div>
	<!-- Left column ends -->
	<!-- Right column -->
	<div class="mainColumnRight" style="position:relative">
		
		
		<!-- Old code kept for ie compatiblity -->
		<!-- <div>
		    <div class="logBoxTL">
		      <div class="logBoxTR">
		        <div class="logBoxT">&nbsp;</div>
		      </div>
		    </div>
		    <div class="logBoxL" style="height:100%">
		      <div class="logBoxR">
		        <div class="logBoxCont signUp">          
		          <div id="loginDiv" style="padding:14px 10px 4px;"> <?php // echo $this->element('captcha_check');?> </div>		          
		        </div>
		      </div>
		    </div>
		    <div class="logBoxBL">
		      <div class="logBoxBR">
		        <div class="logBoxB">&nbsp;</div>
		      </div>
		    </div>    
		</div> -->		
		<!-- Old code kept for ie compatiblity ends -->
	</div>
	<!-- Right column ends -->
	<div class="clearLeft">&nbsp;</div>
</div>
<!-- Two column row one ends -->
	
<?php echo $this->element('horizontal_bar');?>
<div>&nbsp;</div>