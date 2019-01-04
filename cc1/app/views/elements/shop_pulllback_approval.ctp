<?php echo $form->create('shop'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Pullback Approval </div>
				<div>
                                    <div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="trans_id">Enter Trans ID </label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="1" type="text" id="trans_id" name="trans_id" autocomplete="off"  value="<?php if(isset($data)) echo $data['trans_id'];?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shop"><span id="label">Approval Status </span></label></div>
                         <div class="fieldLabelSpace1">
                           	<select tabindex="2" id="shop_select" onChange="setStatus();"  >
                           		<!--<option value="0" >No PullBack</option>-->
                                        <option value="2" >Normal Pullback</option>
                                        <option value="3" >PullBack With Negative Balance</option>
                                        <option value="4" >Pullback Whatever Balance</option>
                                </select>
                                <input type="hidden" id="status_id" name="status_id"   value="<?php if(isset($status)) echo $status ;else echo 0;?>"/>
                         </div>
                    </div>
            	 </div>
                
            	<div class="field" style="padding-top:15px;">               		
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Approve', array('id' => 'sub', 'tabindex'=>'3','url'=> array('controller' => 'shops', 'action'=>'pullBackApproval'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <?php echo $this->Session->flash();?>
                
		</fieldset>
<?php echo $form->end(); ?>
<script>
    $('status_id').value  = $('shop_select').value;   
function setStatus(){ 
        $('status_id').value  = $('shop_select').value;  
}
</script>