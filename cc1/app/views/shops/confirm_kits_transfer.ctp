<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Add Kits</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['amount'];?>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile">Distributor</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['shopData']['company'];?>
                         </div>
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="kit">No. of kits</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['kit'];?>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="commission_flag">Add commission?</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if($data['commission_flag'] == 'on')echo "Yes"; else echo "No";?>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <?php if($data['commission_flag'] == 'on'){?>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="kit">Discount per kit</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['kit_commission'];?>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <?php } ?>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="commission_flag">Note</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['note'];?>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 
                 <div class="field" style="padding-top:15px;">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:10px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Transfer', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'transferKits'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backKitTransfer'), 'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
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
<input type="hidden" name="data[shop]" autocomplete="off" value="<?php echo $data['shop'];?>"/>
<input type="hidden" name="data[kit]" autocomplete="off" value="<?php echo $data['kit'];?>"/>
<input type="hidden" name="data[commission_flag]" autocomplete="off" value="<?php echo $data['commission_flag'];?>"/>
<input type="hidden" name="data[kit_commission]" autocomplete="off" value="<?php echo $data['kit_commission'];?>"/>
<input type="hidden" name="data[note]" autocomplete="off" value="<?php echo $data['note'];?>"/>
<?php echo $form->end(); ?>