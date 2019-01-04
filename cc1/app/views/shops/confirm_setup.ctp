<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Set-up Fees</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Retailer</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $retailer;?>
                         </div>        
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="pan">Salesman</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	 <?php echo $salesman;?>
                         </div>                    
                 	</div>
                 	<div class="clearLeft">&nbsp;</div> 
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile">Amount</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['SalesmanTransaction']['amount'];?>&nbsp;
                         </div>               
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email" style="display:inline-block; padding-left:10px;">Payment Mode</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php
                         		if($data['SalesmanTransaction']['payment_mode'] ==  1)
                         		echo 'Cash';
                         		else if($data['SalesmanTransaction']['payment_mode'] ==  2)
                         		echo 'Cheque';
                         		else if($data['SalesmanTransaction']['payment_mode'] ==  3)
                         		echo 'NEFT';
                         		else if($data['SalesmanTransaction']['payment_mode'] ==  4)
                         		echo 'DD';                         		
                         	?>&nbsp;
                         </div>                     
                 	</div>
                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state"> Details </label></div>
                    	<div class="fieldLabelSpace1 strng">
							<?php echo $data['SalesmanTransaction']['details'];?>&nbsp;
						</div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="city">Collection Date</label></div>
                        <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                        	<?php echo $data['SalesmanTransaction']['collection_date'];?>&nbsp;
						</div>                    
                 	</div>
                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	                        	
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Payment', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'addSetUpFee'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backSetup'), 'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
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
<input type="hidden" name="data[SalesmanTransaction][retailer]" value="<?php echo $data['SalesmanTransaction']['retailer'];?>">
<input type="hidden" name="data[SalesmanTransaction][salesman]" value="<?php echo $data['SalesmanTransaction']['salesman'];?>">
<input type="hidden" name="data[SalesmanTransaction][amount]" value="<?php echo $data['SalesmanTransaction']['amount'];?>">
<input type="hidden" name="data[SalesmanTransaction][payment_mode]" value="<?php echo $data['SalesmanTransaction']['payment_mode'];?>">
<input type="hidden" name="data[SalesmanTransaction][details]" value="<?php echo $data['SalesmanTransaction']['details'];?>">
<input type="hidden" name="data[SalesmanTransaction][collection_date]" value="<?php echo $data['SalesmanTransaction']['collection_date'];?>">
<?php echo $form->end(); ?>