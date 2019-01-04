<div class="appColRight2">
	<div class="appTitle">Our top selling products</div>
	<div class="retailImage"><img src="/img/spacer.gif" class="img_2"></div>
	<div class="retailImage"><img src="/img/spacer.gif" class="img_1"></div>
	<div class="retailImage"><img src="/img/spacer.gif" class="img_8"></div>
</div>
<div class="appColLeft2">
	<div class="appColLeftBox" style="margin-bottom:10px;">
	<div class="appTitle" style="margin-bottom:10px;">Become a Business Partner</div>
	<?php echo $form->create('become'); ?>
	<fieldset>
	<div class="field leftFloat" style="margin-right:20px;">
	    <div class="fieldDetail">
	    	<div class="fieldLabel leftFloat" style="width:100px;"><label for="state">Name:</label></div>
	         <input tabindex="4" type="text" name="data[RetailerInfo][name]" style="width:110px;">                    
	 	</div>
	</div>					            	
	<div class="field">
	    <div class="fieldDetail">
	         <div class="fieldLabel leftFloat" style="width:100px;"><label for="city">Mobile:</label></div>
	         <input tabindex="5" type="text" style="width:110px;" name="data[RetailerInfo][mobile]">                    
	 	</div>
	</div>							              
	<div class="field leftFloat" style="margin-right:20px;">
	    <div class="fieldDetail">
	    	<div class="fieldLabel leftFloat" style="width:100px;"><label for="state">State: </label></div>
	        <input tabindex="6" type="text" style="width:110px;" name="data[RetailerInfo][state]">                    
	 	</div>
	</div>					            	
	<div class="field">
	    <div class="fieldDetail">
	        <div class="fieldLabel leftFloat" style="width:100px;"><label for="city">City: </label></div>
	        <input tabindex="7" type="text" style="width:110px;" name="data[RetailerInfo][city]">                    
	 	</div>
	</div>
	<div class="field leftFloat" style="margin-right:20px;">
	    <div class="fieldDetail">
	    	<div class="fieldLabel leftFloat" style="width:100px;"><label for="Address">Address: </label></div>
	        <textarea tabindex="8" style="width:110px;" name="data[RetailerInfo][address]"></textarea>                    
	 	</div>
	</div>					            	
	<div class="field">
	    <div class="fieldDetail">
	        <div class="fieldLabel leftFloat" style="width:100px;"><label for="Pincode">Pincode: </label></div>
	        <input tabindex="9" type="text" style="width:110px;" name="data[RetailerInfo][pin]">                    
	 	</div>
	</div><div class="clearLeft">&nbsp;</div>
	<div class="field leftFloat" style="margin-right:20px;">
	    <div class="fieldDetail">
	    	<div class="fieldLabel leftFloat" style="width:100px;"><label>Products interested in: </label></div>
	        <textarea tabindex="10" style="width:110px;" name="data[RetailerInfo][products]"></textarea>                    
	 	</div>
	</div>					            	
	<div class="field">
	    <div class="fieldDetail">
	        <div class="fieldLabel leftFloat" style="width:100px;"><label for="Comments">Comments: </label></div>
	        <textarea tabindex="11" style="width:110px;" name="data[RetailerInfo][comments]"></textarea>                    
	 	</div>
	</div>
	<div class="field" style="margin-top:10px">
	    <div class="fieldDetail">
	        <div class="fieldLabel leftFloat" style="width:100px;">&nbsp;</div>
	        <div class="">
			 	<div id="sendButt">
			 	    <?php echo $ajax->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'12','url'=> array('controller'=>'retailers', 'action'=>'become'), 'class' => 'otherSprite oSPos31', 'after' => 'showLoader2("sendButt");', 'update' => 'innerDiv')); ?>  
		     	</div>
		     </div>        
	 	</div>
	</div>
	<div class="field">    
        <div class="fieldDetail">
        	<div class="fieldLabel leftFloat" style="width:100px;">&nbsp;</div>
            <div style="color:#004B91">
                <?php echo $this->Session->flash();?>
             </div>   
        </div>
	 </div>
	</fieldset>
	<?php echo $form->end(); ?>
	</div>
	<div style="margin-top:20px">
		<p>OR Contact us via below details</p>
		<p style="margin-top:5px">Call us on <span class="color3"><strong>09769597418</strong></span> | Email us at <a href="mailto:business@smstadka.com"><strong>business@smstadka.com</strong></a></p>
	</div>
</div>
<div class="clearRight">&nbsp;</div>