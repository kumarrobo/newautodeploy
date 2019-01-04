<?php echo $form->create('shop'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Set-up Fees</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="retailer" class="compulsory">Retailer</label></div>
                         <div class="fieldLabelSpace1">
                            <select tabindex="1" id="retailer" name="data[SalesmanTransaction][retailer]" style="width:148px">
                         		<option value="0">Select Retailer</option>
								<?php foreach($records as $ret) {
									if($fees[$ret['Retailer']['id']] >= SETUP_FEE_AMT) continue;
									?>							
									<option value="<?php echo $ret['Retailer']['id'];?>" <?php if(isset($data) && $data['SalesmanTransaction']['retailer'] ==  $ret['Retailer']['id']) echo "selected";?>><?php echo $ret['Retailer']['shopname'].' ('.$ret['Retailer']['mobile'].')'; ?></option>
								<?php } ?>
							</select>
                         </div>			                        
                 	</div>
                 	<div class="fieldDetail leftFloat" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="shopname" class="compulsory"> Salesman</label></div>
                         <div class="fieldLabelSpace1">
                         	 <select tabindex="2" id="salesman" name="data[SalesmanTransaction][salesman]" style="width:148px">
                         		<option value="0">Select Salesman</option>
								<?php foreach($sMen as $sm) {?>
									<option value="<?php echo $sm['salesmen']['id'];?>" <?php if(isset($data) && $data['SalesmanTransaction']['salesman'] ==  $sm['salesmen']['id']) echo "selected"; ?>><?php echo $sm['salesmen']['name']." (".$sm['salesmen']['mobile'].")"; ?></option>
								<?php } ?>
							</select>                         	 
                         </div>                     
                 	</div> <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shopname" class="compulsory">Amount</label></div>
                         <div class="fieldLabelSpace1">
                         	 <input tabindex="3" type="text" id="shopname" name="data[SalesmanTransaction][amount]" value="<?php if(isset($data))echo $data['SalesmanTransaction']['amount']; ?>"/>                       	 
                         </div>
                 	</div>
                 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shopname" class="compulsory">Payment Mode</label></div>
                         <div class="fieldLabelSpace1">
                         	 <select tabindex="4" id="p_mode" name="data[SalesmanTransaction][payment_mode]" style="width:148px">
                         	 	<option value="0" <?php if(isset($data) && $data['SalesmanTransaction']['payment_mode'] ==  0) echo "selected"; ?>>Select</option>			                         		
                         		<option value="1" <?php if(isset($data) && $data['SalesmanTransaction']['payment_mode'] ==  1) echo "selected"; ?>>Cash</option>
                         		<option value="2" <?php if(isset($data) && $data['SalesmanTransaction']['payment_mode'] ==  2) echo "selected"; ?>>Cheque</option>
                         		<option value="3" <?php if(isset($data) && $data['SalesmanTransaction']['payment_mode'] ==  3) echo "selected"; ?>>NEFT</option>											
                         		<option value="4" <?php if(isset($data) && $data['SalesmanTransaction']['payment_mode'] ==  4) echo "selected"; ?>>DD</option>
							</select>                         	 
                         </div>                     
                 	</div> <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div >         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="address">Details</label></div>
                         <div class="fieldLabelSpace1"">
                            <textarea tabindex="5" id="address" name="data[SalesmanTransaction][details]" style="width:180px;height:55px;"><?php if(isset($data))echo $data['SalesmanTransaction']['details']; ?></textarea>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="date" class="compulsory">Collection Date</label></div>
                         <div class="fieldLabelSpace1">
                            <input type="text" tabindex="6" value="<?php if(isset($data))echo $data['SalesmanTransaction']['collection_date']; ?>" name="data[SalesmanTransaction][collection_date]" id="collectionDate" onmouseover="fnInitCalendar(this, 'collectionDate','restrict=true,open=true')" maxlength="10" style="width: 100px; cursor: pointer;">
                         </div>
                    </div><div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Add Payment', array('id' => 'sub', 'tabindex'=>'7','url'=> array('controller'=>'shops', 'action'=>'addSetUpFee'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">                         
                         <div>
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>	
		</fieldset>
<?php echo $form->end(); ?>
<script>
if($('username'))
	$('username').focus();	
</script>