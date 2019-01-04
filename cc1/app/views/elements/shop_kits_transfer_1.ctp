<?php echo $form->create('shop'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Add Kits</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="amount">Enter Amount (<img src="/img/rs.gif" align="absmiddle">)</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="1" type="text" id="amount" name="data[amount]" autocomplete="off" value="<?php if(isset($data)) echo $data['amount'];?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shop">Select Distributor</label></div>
                         <div class="fieldLabelSpace1">
                           	<select tabindex="2" id="shop" name="data[shop]" >
                           		<option value="0">---Select Distributor---</option>
								<?php foreach($distributors as $distributor) {?>
									<option value="<?php echo $distributor['Distributor']['id'];?>" <?php if(isset($data) && $data['shop'] == $distributor['Distributor']['id']) echo "selected";?>><?php echo $distributor['Distributor']['company'] . " - " . $distributor['Distributor']['id'] ; ?></option>
								<?php } ?>
							</select> 
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="kit">No. of kits</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="3" type="text" id="kit" name="data[kit]" autocomplete="off" value="<?php if(isset($data)) echo $data['kit'];?>"/>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	  <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="commission_flag">Add commission?</label></div>
                         <div class="fieldLabelSpace1">
                            <input type="checkbox" onclick="showHide('hide_commission');" tabindex="4" id="commission_flag" name="data[commission_flag]" <?php if(isset($data['commission_flag']) && $data['commission_flag'] == 'on') echo "checked";?>>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div id="hide_commission" style="<?php if(isset($data['commission_flag']) && $data['commission_flag']=='on') echo 'display:block'; else echo 'display:none';?>">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="kit_comm">Commission per kit</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="5" type="text" id="kit_comm" name="data[kit_commission]" autocomplete="off" value="<?php if(isset($data)) echo $data['kit_commission'];?>"/>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="note">Note</label></div>
                         <div class="fieldLabelSpace1">
                            <textarea tabindex="6" id="note" name="data[note]" style="width:180px;height:55px;"><?php if(isset($data['note']))echo $data['note']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	<div class="field" style="padding-top:15px;">               		
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Transfer Kits', array('id' => 'sub', 'tabindex'=>'7','url'=> array('controller' => 'shops', 'action'=>'transferKits'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <?php echo $this->Session->flash();?>
                
		</fieldset>
<?php echo $form->end(); ?>
<script>
if($('amount'))
	$('amount').focus();
	
function showHide(){
	if($('hide_commission').style.display=='block'){
		$('hide_commission').style.display='none';
	}
	else if($('hide_commission').style.display=='none'){
		$('hide_commission').style.display='block';
	}
}
</script>