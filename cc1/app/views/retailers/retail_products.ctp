<div class="appColRight2">
	<div class="appColLeftBox" style="margin-bottom:10px;">
		<div class="appTitle">Locate your nearby shop</div>
		<div>To locate your nearest SMSTadka shop, click the below button</div>
		
		<div class="field" id="sendButt" style="margin-top:10px;">
         	<a onclick="$('locate').simulate('click');" href="javascript:void(0);"><img class="otherSprite oSPos32" src="/img/spacer.gif"></a>  
       	</div>       	
		<div style="margin-top:10px;"><strong>For Home Delivery* Call us on</strong><br>022 - 2472 1133 <br>09321 333 444 <br><span class="smallerFont">* Only in Mumbai, Navi Mumbai & Thane.</span></div>
		<div style="margin-top:10px;">Or <strong>SMS: SHOP to 0922 317 8889</strong></div>
				
	</div>
	<div class="appColLeftBox" style="margin-bottom:10px;">
		<div class="appTitle">Customer Support</div>
		<div style="margin-bottom:4px"><span class="strng">Mob.:</span> 0976 959 7418</div>
        <div><span class="strng">SMS:</span> HELP to 0922 317 8889</div>
	</div>
	<div class="appColLeftBox" style="margin-bottom:10px;">
		<div class="appTitle">Become a Business Partner</div>
		<div>To become a Business Partner click on the button 'Become a Partner'</div>
		
		<div class="field" id="sendButt" style="margin-top:10px;">
         	<a onclick="window.scrollTo(0,0);$('becomeRetailer').simulate('click');" href="javascript:void(0);"><img class="otherSprite oSPos31" src="/img/spacer.gif"></a>  
       	</div>       	
		<div>Or <strong>Call us on 09769597418</strong></div>
	</div>
</div>
<div class="appColLeft2">
	<?php foreach($products as $prod){ ?>
		<div class="retailImage inIEretailImage">
			<img class="img_<?php echo $prod['Product']['id']; ?>" src="/img/spacer.gif">
		</div>
	<?php } ?>
</div>
<div class="clearRight">&nbsp;</div>