<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Create Retailer       &nbsp;&nbsp;&nbsp;&nbsp;<small style="color:blue;">(OTP sent to your mobile number)</small></div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                        <div class="fieldLabel1 leftFloat compulsory"><label for="otp">OTP</label></div>
                            <div class="fieldLabelSpace1 strng">
                                <input tabindex="1" type="text" id="otp" maxlength="6" placeholder="OTP" name="data[Retailer][otp]" value =""/>
                            </div>  
                    </div>
                    <div class="fieldDetail leftFloat" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                            <div class="fieldLabelSpace1 strng">
                              <?php echo isset($data['Retailer']['name']) ? $data['Retailer']['name'] : "" ;?>
                            </div>  
                    </div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="shopname"> Shop Name </label></div>
                         <div class="fieldLabelSpace1 strng">
                       		<?php echo isset($data['Retailer']['shopname']) ? $data['Retailer']['shopname'] : "" ;?>
                         </div>                    
                 	</div>
                 	<!--<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="pan"> PAN Number </label></div>
                         <div class="fieldLabelSpace1 strng">
                         	 <?php echo isset($data['Retailer']['pan_number']) ? $data['Retailer']['pan_number'] : "" ;?>
                         </div>                    
                 	</div>-->
                 	<div class="clearLeft">&nbsp;</div> 
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile">Mobile</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo isset($data['Retailer']['mobile']) ? $data['Retailer']['mobile'] : "";?>&nbsp;
                         </div>               
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email" style="display:inline-block; padding-left:10px;">E-mail</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo isset($data['Retailer']['email']) ? $data['Retailer']['email'] : "";?>&nbsp;
                         </div>                     
                 	</div>

                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <!--<div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state"> State </label></div>
                    	<div class="fieldLabelSpace1 strng">
							<?php echo isset($state)?$state:"";?>
						</div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="city">City</label></div>
                        <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                        	<?php echo isset($city)?$city:"";?>
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
                       		<?php echo isset($area)?$area:"";?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="pin">Pin Code</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo isset($data['Retailer']['pin']) ? $data['Retailer']['pin'] : "";?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>-->
            	 <div>
              	 <div class="field">
                               	 
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="address">Address</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo isset($data['Retailer']['address']) ? $data['Retailer']['address'] : "" ;?>
                         </div>
                    </div>
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="login">Type</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php if(isset($data['Retailer']['rental_flag']) && $data['Retailer']['rental_flag'] == 0)echo "Kit"; else echo "Rental";?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                 <div class="altRow">            
                 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="Retailer Type">Retailer Type</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $retailer_type_label;?>
                         </div>               
                    </div>               
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="Location Type" style="display:inline-block; padding-left:10px;">Location Type</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                           <?php echo $location_type_label; ?>
                         </div>                     
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                 </div>
                 </div> 
                 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="Turnover Type">Turnover Type</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $turnover_type_label; ?>
                         </div>               
                    </div>               
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="Ownership Type" style="display:inline-block; padding-left:10px;">Ownership Type</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                           <?php echo $ownership_type_label; ?>
                         </div>                     
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                 </div>
                 
                <!--  <div>            
                 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile">Mobile</label></div>
                         <div class="fieldLabelSpace1 strng">
                            8983360629&nbsp;
                         </div>               
                    </div>               
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email" style="display:inline-block; padding-left:10px;">E-mail</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                            &nbsp;
                         </div>                     
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                 </div>
                 </div> -->
            	 <!--<div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="login">SMS Login Details</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php if(isset($data['login']) && $data['login'] == "on")echo "Yes"; else echo "No";?>
                         </div>
                    </div>
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Assign Slab</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         <?php //echo 'Retailer - ' . $slab;?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div style="padding-top:20px">-->
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Retailer', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'createRetailer'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backRetailer'), 'class' => 'retailBut disabledBut', 'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
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
<input type="hidden" name="data[Retailer][name]" value="<?php echo $data['Retailer']['name'];?>">
<input type="hidden" name="data[Retailer][mobile]" value="<?php echo $data['Retailer']['mobile'];?>">
<!--<input type="hidden" name="data[Retailer][slab_id]" value="<?php //echo $data['Retailer']['slab_id'];?>">-->
<input type="hidden" name="data[Retailer][city]" value="<?php echo isset($data['Retailer']['city']) ? $data['Retailer']['city'] : "" ;?>">
<input type="hidden" name="data[Retailer][pan_number]" value="<?php echo isset($data['Retailer']['pan_number']) ? $data['Retailer']['pan_number']: "" ;?>">
<input type="hidden" name="data[Retailer][state]" value="<?php echo isset($data['Retailer']['state']) ? $data['Retailer']['state'] : "" ;?>">
<input type="hidden" name="data[Retailer][address]" value="<?php echo isset($data['Retailer']['address']) ? $data['Retailer']['address'] : "";?>">
<input type="hidden" name="data[Retailer][rental_flag]" value="<?php echo isset($data['Retailer']['rental_flag']) ? $data['Retailer']['rental_flag'] : "" ;?>">
<!--<input type="hidden" name="data[login]" value="<?php if(isset($data['login']))echo $data['login'];?>">-->
<!--<input type="hidden" name="data[Retailer][area_id]" value="<?php echo $data['Retailer']['area_id'];?>">-->
<input type="hidden" name="data[Retailer][email]" value="<?php if(isset($data['Retailer']['email'])) echo $data['Retailer']['email'] ;?>">
<input type="hidden" name="data[Retailer][shopname]" value="<?php  if(isset($data['Retailer']['shopname'])) echo $data['Retailer']['shopname'];?>">
<!--<input type="hidden" name="data[Retailer][pin]" value="<?php  if(isset($data['Retailer']['pin'])) echo $data['Retailer']['pin'] ;?>">-->
<input type="hidden" name="data[Retailer][retailer_type]" value="<?php  if(isset($data['Retailer']['retailer_type'])) echo $data['Retailer']['retailer_type'];?>">
<input type="hidden" name="data[Retailer][location_type]" value="<?php  if(isset($data['Retailer']['location_type'])) echo $data['Retailer']['location_type'];?>">
<input type="hidden" name="data[Retailer][turnover_type]" value="<?php  if(isset($data['Retailer']['turnover_type'])) echo $data['Retailer']['turnover_type'];?>">
<input type="hidden" name="data[Retailer][ownership_type]" value="<?php  if(isset($data['Retailer']['ownership_type'])) echo $data['Retailer']['ownership_type'];?>">

<?php echo $form->end(); ?>