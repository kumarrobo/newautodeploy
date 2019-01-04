<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Create Salesman   &nbsp;&nbsp;&nbsp;&nbsp;<small style="color:blue;">(OTP sent to your mobile number)</small></div>
				<div>
				<div class="field" style="padding-top:5px;">
                    
                    <div class="fieldDetail leftFloat" style="width:350px;">
                        <div class="fieldLabel1 leftFloat compulsory"><label for="otp">OTP</label></div>
                        <div class="fieldLabelSpace1 strng">
                            <input tabindex="1" type="text" id="otp" maxlength="6" placeholder="OTP" name="data[Salesman][otp]"  value=""/>
                        </div>        
                    </div>
                                    
                                    
                    <div class="fieldDetail leftFloat" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                        <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Salesman']['name'];?>
                        </div>        
                    </div>
                 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile">Mobile</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Salesman']['mobile'];?>&nbsp;
                         </div>               
                 	</div>
                 	
                 	
                 	<div class="clearLeft">&nbsp;</div> 
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <?php if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) { ?>
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="limit">Transaction Limit</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Salesman']['tran_limit'];?>&nbsp;
                         </div>               
                 	</div>            	 
                    <?php } ?>
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="extra" style="display:inline-block; padding-left:10px;">Extra</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $data['Salesman']['extra'];?>&nbsp;
                         </div>                     
                 	</div>
                 	
                 	<br>
                 	<div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="subarea">SubArea</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['subArea1'];?>&nbsp;
                         </div>               
                 	</div>
                 	
                 	
                 	
                 	
                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 </div style="padding-top:20px">
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Salesman', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'createSalesman'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backSalesman'), 'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
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
<input type="hidden" name="data[Salesman][name]" value="<?php echo $data['Salesman']['name'];?>">
<input type="hidden" name="data[Salesman][mobile]" value="<?php echo $data['Salesman']['mobile'];?>">
<input type="hidden" name="data[Salesman][tran_limit]" value="<?php echo $data['Salesman']['tran_limit'];?>">
<input type="hidden" name="data[Salesman][extra]" value="<?php echo $data['Salesman']['extra'];?>">
<input type="hidden" name="data[subArea1]" value="<?php echo $data['subArea1'];?>">
<?php echo $form->end(); ?>