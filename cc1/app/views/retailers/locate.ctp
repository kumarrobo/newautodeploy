<div class="appColRight2">
	<div style="margin-bottom:10px;">
		<div class="appTitle">Other ways to shop</div>
		<div style="margin-top:10px;"><strong>For Home Delivery* Call us on</strong><br>022 - 2472 1133 <br>09321 333 444 <br><span class="smallerFont">* Only in Mumbai, Navi Mumbai & Thane.</span></div>
		<div style="margin-top:10px;">Or <strong>SMS: SHOP to 0922 317 8889</strong></div>
	</div>
	<div>
		<div class="appTitle">Our top selling products</div>
		<div class="retailImage"><img src="/img/spacer.gif" class="img_2"></div>
		<div class="retailImage"><img src="/img/spacer.gif" class="img_1"></div>
		<div class="retailImage"><img src="/img/spacer.gif" class="img_8"></div>
	</div>
</div>

<div class="appColLeft2">
	<div class="appColLeftBox">
	<div class="appTitle" style="margin-bottom:10px;">Locate your nearby SMSTadka Shop</div>
	<div class="field leftFloat" style="margin-right:20px;">
	    <div class="fieldDetail">
	    	<div class="fieldLabel leftFloat" style="width:100px;"><label for="state">State: </label></div>
	         <select tabindex="7" id="state" name="data[Retailer][state]" onchange="getCities(this.options[this.selectedIndex].value,'u')">
	                <option value="0">Select State</option> 
				<?php foreach($states as $state) {?>
					<option value="<?php echo $state['locator_state']['id'];?>"><?php echo $state['locator_state']['name']; ?></option>
				<?php } ?>
			</select>                    
	 	</div>
	</div>					            	
	<div class="field">
	    <div class="fieldDetail">
	         <div class="fieldLabel leftFloat" style="width:100px;"><label for="city">City: </label></div>
	         <div id="cityDD">
	         <select tabindex="8" id="city" name="data[Retailer][city]" onchange="getAreas(this.options[this.selectedIndex].value,'u')">
	         	<option value="0">Select City</option>
				<?php foreach($cities as $city) {?>
					<option value="<?php echo $city['locator_city']['id'];?>"><?php echo $city['locator_city']['name']; ?></option>
				<?php } ?>
			</select>
			</div>                  
	 	</div>
	</div>							              
  	<div class="field">
        <div class="fieldDetail">
             <div class="fieldLabel leftFloat" style="width:100px;"><label for="area">Area: </label></div>
             <div id="areaDD" class="">
             	<select tabindex="9" id="area" name="data[Retailer][area_id]">
             		<option value="0">Select Area</option>
					<?php foreach($areas as $area) {?>
						<option value="<?php echo $area['locator_area']['id'];?>"><?php echo $area['locator_area']['name']; ?></option>
					<?php } ?>
				</select>
             </div>                     
     	</div>
	</div>
	<div class="field" style="margin-top:10px">
        <div class="fieldDetail">
        	 <div class="fieldLabel leftFloat" style="width:100px;">&nbsp;</div>
             <div class="">
			 	<div id="sendButt" style="margin-left:100px;">
		         	<a onclick="getRetailers(1,1)" href="javascript:void(0);"><img class="otherSprite oSPos32" src="/img/spacer.gif"></a>  
		     	</div>
		     	<div id="locateErr" style="margin-left:100px;" class="inlineErr1">		         	
		     	</div>
		     </div>
		</div>
	</div>
	</div>							            	 
	<div id="retailersData" style="margin-top:20px;">
		<!-- <h2 class="title2">Retailers</h2> -->
	</div>   
</div>
<div class="clearRight">&nbsp;</div>