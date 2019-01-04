<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Edit Retailer</div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Retailer']['name'];?>
                         </div>                     
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="username">PAN Number</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Retailer']['pan_number'];?>
                         </div>                     
                 	</div>
                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile">Mobile</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['users']['mobile'];?>&nbsp;
                         </div>               
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Retailer']['email'];?>&nbsp;
                         </div>                     
                 	</div>
                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state"> State </label></div>
                    	<div class="fieldLabelSpace1 strng">
							<?php echo $state;?>
						</div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="city">City</label></div>
                        <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                        	<?php echo $city;?>
						</div>                    
                 	</div>
                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="area"> Area </label></div>
                         <div class="fieldLabelSpace1 strng">
                       		<?php echo $area;?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="pin">Pin Code</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Retailer']['pin'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shopname"> Shop Name </label></div>
                         <div class="fieldLabelSpace1 strng">
                       		<?php echo $data['Retailer']['shopname'];?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address">Address</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Retailer']['address'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="slab">Assign Slab</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo 'Retailer - ' . $slab;?>
                         </div>                         
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Salesman</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo $salesman;?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shopname"> KYC Docs </label></div>
                         <div class="fieldLabelSpace1 strng">
                       		<?php echo $data['Retailer']['kyc']; ?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address">Mobile Phone Info</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Retailer']['mobile_info'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">                         
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">App Type</label></div>
                         <div class="fieldLabelSpace1 strng">                         
                         <?php 
                         $appArr = explode(",",$appType);
                         if(in_array(APP_JAVA,$appArr)) echo "Java, ";
                         if(in_array(APP_ANDROID,$appArr)) echo "Android, ";
                         if(in_array(APP_SMS,$appArr)) echo "SMS, ";
                         if(in_array(APP_USSD,$appArr)) echo "USSD, ";
                         ?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat" >&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Edit', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'editRetValidation'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backDistEdit','r'),  'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
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
<input type="hidden" name="data[Retailer][id]" value="<?php echo $data['Retailer']['id'];?>">
<input type="hidden" name="data[Retailer][name]" value="<?php echo $data['Retailer']['name'];?>">
<input type="hidden" name="data[Retailer][pan_number]" value="<?php echo $data['Retailer']['pan_number'];?>">
<input type="hidden" name="data[users][mobile]" value="<?php echo $data['users']['mobile'];?>">
<input type="hidden" name="data[Retailer][slab_id]" value="<?php echo $data['Retailer']['slab_id'];?>">
<input type="hidden" name="data[Retailer][city]" value="<?php echo $data['Retailer']['city'];?>">
<input type="hidden" name="data[Retailer][state]" value="<?php echo $data['Retailer']['state'];?>">
<input type="hidden" name="data[Retailer][address]" value="<?php echo $data['Retailer']['address'];?>">
<input type="hidden" name="data[Retailer][area_id]" value="<?php echo $data['Retailer']['area_id'];?>">
<input type="hidden" name="data[Retailer][email]" value="<?php echo $data['Retailer']['email'];?>">
<input type="hidden" name="data[Retailer][shopname]" value="<?php echo $data['Retailer']['shopname'];?>">
<input type="hidden" name="data[Retailer][pin]" value="<?php echo $data['Retailer']['pin'];?>">
<input type="hidden" name="data[Retailer][salesman]" value="<?php echo $data['Retailer']['salesman'];?>">
<input type="hidden" name="data[Retailer][kyc]" value="<?php echo $data['Retailer']['kyc'];?>">
<input type="hidden" name="data[Retailer][mobile_info]" value="<?php echo $data['Retailer']['mobile_info'];?>">
<input type="hidden" name="data[Retailer][app_type]" value="<?php echo $appType;?>">

<?php echo $form->end(); ?>