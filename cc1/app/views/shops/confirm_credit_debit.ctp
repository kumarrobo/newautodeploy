<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Create Credit/Debit Note</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Choose</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if($data['note'] == 0) echo "Credit Note"; else echo "Debit Note";?>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
				<div class="altRow">
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Transfer Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['amount'];?>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div>         	 
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile"><?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR)echo "Retailer"; else echo "Distributor";?></label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) echo $data['shopData']['shopname']; else echo $data['shopData']['company']; echo " - " . $data['shopData']['id'];?>
                         </div>               
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="description">Description</label></div>
                         <div class="fieldLabelSpace1 strng"><?php echo $data['description'];?></div>             
                 	</div>
            	 </div>
            	 </div>
                 <div class="field" style="padding-top:15px;">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:10px;" id="sub_butt">
							<?php echo $ajax->submit('Create Note', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'createNote'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backCreditDebit'), 'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
						</div>                       
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>	
		</fieldset>

<input type="hidden" name="data[confirm]" value="1">
<input type="hidden" name="data[amount]" autocomplete="off" value="<?php echo $data['amount'];?>"/>
<input type="hidden" name="data[description]" autocomplete="off" value="<?php echo $data['description'];?>"/>
<input type="hidden" name="data[shop]" autocomplete="off" value="<?php echo $data['shop'];?>"/>
<input type="hidden" name="data[note]" autocomplete="off" value="<?php echo $data['note'];?>"/>
<?php echo $form->end(); ?>