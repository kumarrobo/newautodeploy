<div style="width:750px;">
<?php echo $form->create('receipt'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Issue a receipt against <?php if(isset($data))echo $data['number']; else echo $number; ?></div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="cash">Cash Amount</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="1" type="text" id="cash" name="data[Receipt][cash_amount]"  value="<?php if(isset($data)) echo $data['Receipt']['cash_amount']; ?>"/>
                         </div>                     
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cheque">Cheque Number</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="2" type="text" id="cheque" name="data[Receipt][cheque_number]" value="<?php if(isset($data))echo $data['Receipt']['cheque_number']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="camount">Cheque Amount</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="3" type="text" id="camount" name="data[Receipt][cheque_amount]" value ="<?php if(isset($data))echo $data['Receipt']['cheque_amount']; ?>"/>
                         </div>                     
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Cheque Date</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="4" type="text" id="cdate" name="data[Receipt][cheque_date]" value="<?php if(isset($data))echo $data['Receipt']['cheque_date']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                     <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="tamount">Transfer Amount</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="5" type="text" id="tamount" name="data[Receipt][transfer_amount]" value ="<?php if(isset($data))echo $data['Receipt']['transfer_amount']; ?>"/>
                         </div>                     
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Reference ID</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="6" type="text" id="cdate" name="data[Receipt][transfer_ref_id]" value="<?php if(isset($data))echo $data['Receipt']['transfer_ref_id']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="bank">Bank Name</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="7" type="text" id="bank" name="data[Receipt][cheque_bank]" value ="<?php if(isset($data))echo $data['Receipt']['cheque_bank']; ?>"/>
                         </div>                     
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="cdate">Bank Branch</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="8" type="text" id="cdate" name="data[Receipt][cheque_branch]" value="<?php if(isset($data))echo $data['Receipt']['cheque_branch']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div class="field"  style="padding-top:20px">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Issue Receipt', array('id' => 'sub', 'tabindex'=>'9','url'=> array('controller'=>'shops', 'action'=>'issueReceipt'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'messagePopUpDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">                         
                         <div class="inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>	
            	 <input type="hidden" name="data[type]" value="<?php if(isset($data))echo $data['type']; else echo $type; ?>"/>
            	 <input type="hidden" name="data[id]" value="<?php if(isset($data))echo $data['id']; else echo $id; ?>"/>
            	 <input type="hidden" name="data[child]" value="<?php if(isset($data))echo $data['child']; else echo $child; ?>"/>
            	 <input type="hidden" name="data[number]" value="<?php if(isset($data))echo $data['number']; else echo $number; ?>"/>
		</fieldset>
<?php echo $form->end(); ?>
</div>