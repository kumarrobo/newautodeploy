<?php echo $form->create('shop'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Create Credit/Debit Note</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="amount">Choose</label></div>
                         <div class="fieldLabelSpace1" style="padding-right:0px">
                         	<input <?php if((isset($data['note']) && $data['note'] == '0') || !isset($data['note'])) echo 'checked="checked"'; ?> type="radio" value="0" name="data[note]"><label>Credit Note</label><span class="padding1">&nbsp;</span><input <?php if(isset($data['note']) && $data['note'] == '1') echo 'checked="checked"'; ?> type="radio" value="1" name="data[note]"><label>Debit Note</label>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
				<div class="altRow">
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="amount">Enter Amount (<img src="/img/rs.gif" align="absmiddle">)</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="2" type="text" id="amount" name="data[amount]" autocomplete="off" value="<?php if(isset($data)) echo $data['amount'];?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){?>
                 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shop">Select Distributor</label></div>
                         <div class="fieldLabelSpace1">
                           	<select tabindex="3" id="shop" name="data[shop]" >
                           		<option value="0"></option>
								<?php foreach($distributors as $distributor) {?>
									<option value="<?php echo $distributor['Distributor']['id'];?>" <?php if(isset($data) && $data['shop'] == $distributor['Distributor']['id']) echo "selected";?>><?php echo $distributor['Distributor']['company'] . " - " . $distributor['Distributor']['id'] ; ?></option>
								<?php } ?>
							</select> 
                         </div>
                    </div>
            	 </div>
            	 <?php } else if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){ ?>
            	 <div class="field">
                     <div class="fieldDetail" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="shop">Select Retailer</label></div>
                        <div class="fieldLabelSpace1">
                           	<select tabindex="3" id="shop" name="data[shop]" >
                           		<option value="0"></option>
								<?php foreach($retailers as $retailer) {?>
									<option value="<?php echo $retailer['Retailer']['id'];?>" <?php if(isset($data) && $data['shop'] == $retailer['Retailer']['id']) echo "selected";?>><?php echo $retailer['Retailer']['shopname'] . " - " . $retailer['Retailer']['id'] ; ?></option>
								<?php } ?>
							</select> 
                         </div>
                    </div>
            	 </div>
            	 <?php } ?>         	 
            	 </div>
            	<div class="altRow">
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="description">Description</label></div>
                         <div class="fieldLabelSpace1" style="padding-right:0px">
                         	<textarea tabindex="4" id="description" name="data[description]" style="width:180px;height:55px;"><?php if(isset($data['description']))echo $data['description']; ?></textarea>
                         </div>                     
                 	</div>
            	 </div>
            	 </div> 
            	<div class="field" style="padding-top:15px;">               		
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Create', array('id' => 'sub', 'tabindex'=>'5','url'=> array('controller' => 'shops', 'action'=>'createNote'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <?php echo $this->Session->flash();?>
                
		</fieldset>
<?php echo $form->end(); ?>
<script>
if($('amount'))
	$('amount').focus();	
</script>