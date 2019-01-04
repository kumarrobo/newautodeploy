<div style="width:720px;">
<?php echo $form->create('receipt'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Issue a receipt against <?php echo $data['number']; ?></div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="cash">Cash Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if(!empty($data['Receipt']['cash_amount'])) echo '<img src="/img/rs.gif" class="rupeeBkt">' . sprintf('%.2f', $data['Receipt']['cash_amount']);?>
                         </div>                   
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cheque">Cheque Number</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Receipt']['cheque_number'];?>&nbsp;
                         </div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="camount">Cheque Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php if(!empty($data['Receipt']['cheque_amount'])) echo '<img src="/img/rs.gif" class="rupeeBkt">' . sprintf('%.2f', $data['Receipt']['cheque_amount']);?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Cheque Date</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Receipt']['cheque_date'];?>&nbsp;
                         </div>                   
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                     <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="tamount">Transfer Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                             <?php if(!empty($data['Receipt']['transfer_amount'])) echo '<img src="/img/rs.gif" class="rupeeBkt">' . sprintf('%.2f', $data['Receipt']['transfer_amount']);?>
                         </div>                    
                 	</div>       	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Reference ID</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Receipt']['transfer_ref_id'];?>&nbsp;
                         </div>                   
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">
              	 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="tamount">Total</label></div>
                         <div class="fieldLabelSpace1 strng">
                             <img src="/img/rs.gif" class="rupeeBkt"><?php echo  sprintf('%.2f', $total);?>
                         </div>                    
                 	</div>       	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Bank Name</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Receipt']['cheque_bank'];?>&nbsp;
                         </div>                   
                 	</div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
              	 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="tamount">Outstanding Balance</label></div>
                         <div class="fieldLabelSpace1 strng">
                             <img src="/img/rs.gif" class="rupeeBkt"><?php echo  sprintf('%.2f', $outstanding);?>
                         </div>                    
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Bank Branch</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Receipt']['cheque_branch'];?>&nbsp;
                         </div>                   
                 	</div>      	 
            	 </div>
            	 </div>
            	 <div style="padding-top:20px">
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Receipt', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'issueReceipt'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'messagePopUpDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backReceipt'), 'class' => 'retailBut disabledBut',  'after' => 'showLoader2("sub_butt1");', 'update' => 'messagePopUpDiv')); ?>
						</div>                       
                    </div>                    
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>
            	 </div>
            	 <input type="hidden" name="data[type]" value="<?php if(isset($data))echo $data['type']; else echo $type; ?>"/>
            	 <input type="hidden" name="data[id]" value="<?php if(isset($data))echo $data['id']; else echo $id; ?>"/>
            	 <input type="hidden" name="data[child]" value="<?php if(isset($data))echo $data['child']; else echo $child; ?>"/>
		</fieldset>
<input type="hidden" name="data[confirm]" value="1">
<input type="hidden" name="data[Receipt][cash_amount]" value="<?php echo $data['Receipt']['cash_amount'];?>">
<input type="hidden" name="data[Receipt][cheque_number]" value="<?php echo $data['Receipt']['cheque_number'];?>">
<input type="hidden" name="data[Receipt][cheque_amount]" value="<?php echo $data['Receipt']['cheque_amount'];?>">
<input type="hidden" name="data[Receipt][cheque_date]" value="<?php echo $data['Receipt']['cheque_date'];?>">
<input type="hidden" name="data[Receipt][transfer_amount]" value="<?php echo $data['Receipt']['transfer_amount'];?>">
<input type="hidden" name="data[Receipt][transfer_ref_id]" value="<?php echo $data['Receipt']['transfer_ref_id'];?>">
<input type="hidden" name="data[Receipt][cheque_bank]" value="<?php echo $data['Receipt']['cheque_bank'];?>">
<input type="hidden" name="data[Receipt][cheque_branch]" value="<?php echo $data['Receipt']['cheque_branch'];?>">
<input type="hidden" name="data[type]" value="<?php echo $data['type'];?>">
<input type="hidden" name="data[id]" value="<?php echo $data['id'];?>">
<input type="hidden" name="data[child]" value="<?php echo $data['child'];?>">
<input type="hidden" name="data[number]" value="<?php echo $data['number'];?>">
<?php echo $form->end(); ?>
</div>