<?php echo $form->create('signature'); ?>
	<fieldset class="fields1" style="border:0px;margin:0px;">
		<div class="field">
            <div class="fieldDetail">
                 <div class="fieldLabel1 leftFloat"><label for="login">Create Signature</label></div>
                 <div class="fieldLabelSpace1">
                    <input type="checkbox" tabindex="1" id="signature" name="data[signature]" <?php if(isset($data['signature']) && $data['signature'] == 'on') echo "checked";?> onclick="showHideSignature()">
                 </div>
            </div>
    	</div>
    	<div id="sign">
    	<div class="field">
            <div class="fieldDetail">
                 <div class="fieldLabel1 leftFloat"><label for="login">Your Signature</label></div>
                 <div class="fieldLabelSpace1">
                    <textarea tabindex='2' id='textContent' name='data[text]' class="editSMS" onKeyUp="countCharacters('textContent',<?php echo SIGNATURE_LIMIT; ?>,'charCount');" <?php if(isset($data['signature']) && $data['signature'] == 'on') echo ""; else echo "disabled"; ?>><?php if(isset($data['text']) && !empty($data['text'])) echo $data['text'];?></textarea>
             		<span id='charCount' class="hints">Upto <?php echo SIGNATURE_LIMIT; ?> chars</span>
                 </div>
            </div>
    	</div>
    	<div class="field"  style="padding-top:20px">               		
            <div class="fieldDetail">
                 <div class="fieldLabel1 leftFloat">&nbsp;</div>
                 <div class="fieldLabelSpace1" id="sub_butt">
                 	<?php echo $ajax->submit('Submit', array('id' => 'sub_butt', 'tabindex'=>'3','url'=> array('controller'=>'shops', 'action'=>'saveSignature'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                 </div>                         
            </div>
        </div>
        <div class="field">    
            <div class="fieldDetail">                         
                 <div class="" style="font-size:1.0em">
                    <?php echo $this->Session->flash();?>
                 </div>   
            </div>
    	 </div>
    	</div>
	</fieldset>
<?php echo $form->end(); ?>
				
<script>
	function countCharacters(id,limit,out){
		var str = $(id).value.length;
		
		$(out).innerHTML=str + '/' + limit + " chars";
		if(str > limit){
			alert('Message limit: '+limit+' chars only');
			$(id).value = $(id).value.substring(0,limit);
			str = $(id).value.length;
			$(out).innerHTML=str + '/' + limit + " chars";
			return false;
		}	
	}
	
	function showHideSignature(){
		if($('signature').checked){
			$('textContent').disabled = false;
		}
		else {
			$('textContent').disabled = true;
		}
	}
</script>