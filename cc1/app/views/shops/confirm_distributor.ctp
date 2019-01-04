<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Create Distributor &nbsp;&nbsp;&nbsp;&nbsp;<small style="color:blue;">(OTP sent to your mobile number)</small></div>
                        <div>
			    <div class="field" style="padding-top:5px;">
                    
                    <div class="fieldDetail leftFloat" style="width:350px;">
                        <div class="fieldLabel1 leftFloat compulsory"><label for="otp">OTP</label></div>
                        <div class="fieldLabelSpace1 strng">
                            <input tabindex="1" type="text" id="otp" maxlength="6" placeholder="OTP" name="data[Distributor][otp]"  value=""/>
                        </div>        
                    </div>
            
                            <div class="fieldDetail leftFloat" style="width:350px;">
                                <div class="fieldLabel1 leftFloat"><label for="map_lat">Latitude</label></div>
                                <div class="fieldLabelSpace1 strng">
                                   <?php echo $data['Distributor']['map_lat'];?>
                                </div>                     
                 	    </div>
                            <div class="fieldDetail">
                                <div class="fieldLabel1 leftFloat"><label for="map_long">Longitude</label></div>
                                <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                                       <?php echo $data['Distributor']['map_long'];?>&nbsp;
                                </div>                     
                            </div>
                 	    <div class="clearLeft">&nbsp;</div>
            	            </div>
            	        </div>
                        
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Distributor']['name'];?>
                         </div>                     
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="company">Company Name</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Distributor']['company'];?>&nbsp;
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
                            <?php echo $data['Distributor']['mobile'];?>&nbsp;
                         </div>               
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Distributor']['email'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <label for="dob">DOB</label>&nbsp;&nbsp;&nbsp;<?php echo $data['Distributor']['dob'];?>&nbsp;     
                         </div>                     
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                 <?php
                    if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                    ?>
                 <div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="DistReference"> Dist Reference </label></div>
                    	<div class="fieldLabelSpace1 strng">
                                <?php echo $data['Distributor']['dist_reference'];?>
                        </div>                    
                 	</div>
                    <?php if(!empty($data['Distributor']['dist_reference_code'])){ ?> 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="DistReferenceCode">Reference Code</label></div>
                        <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                        	<?php echo $data['Distributor']['dist_reference_code'];?>
                        </div>                    
                 	</div>
                    <?php  }?> 
                 	<div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>        
                   <?php
                    }
                   ?>     
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
                         <div class="fieldLabel1 leftFloat"><label for="area"> Area Range </label></div>
                         <div class="fieldLabelSpace1 strng">
                       		<?php echo $data['Distributor']['area_range'];?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address">Address</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Distributor']['address'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="pan"> PAN Number </label></div>
                         <div class="fieldLabelSpace1 strng">
                         	 <?php echo $data['Distributor']['pan_number'];?>
                         </div>                    
                 	</div>
                 	<!--div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="tds">TDS Authorized</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php if(isset($data['Distributor']['tds_flag']) && $data['Distributor']['tds_flag'] == '1') echo "Yes"; else echo "No";?>
                         </div>
                    </div-->
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="login">SMS Login Details</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php if(isset($data['login']) && $data['login'] == "on")echo "Yes"; else echo "No";?>
                         </div>
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Assign Slab</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo 'Distributor - ' . $slab[0]['slabs']['name'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                <?php 
                    $commission = array('0'=>'Primary','1'=>'Tertiary');
                    $commission_type = $this->Session->read('Auth.commission_type') == 2 ? $data['Distributor']['commission_type'] : $this->Session->read('Auth.commission_type');
                ?>
                <div style="padding-bottom: 5px;">
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                             <div class="fieldLabel1 leftFloat"><label for="login">Commission Type</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo $commission[$commission_type]; ?>
                             </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px; margin-top: -10px;">
                             <div class="fieldLabel1 leftFloat"><label for="login">GST No</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo $data['Distributor']['gst_no']; ?>
                             </div>
                        </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	</div>
                <div class="altRow" style="padding-bottom: 5px;">
                    <div class="field">
                        <div class="fieldDetail leftFloat">
                             <div class="fieldLabel1 leftFloat"><label for="login">Active Services</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo implode(array_map('next', $active_services), ', '); ?>
                             </div>
                        </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                </div>
                <div style="margin-top: 10px;">
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px; margin-top: -10px;">
                             <div class="fieldLabel1 leftFloat"><label for="margin">Commission %</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo isset($data['Distributor']['margin']) ? $data['Distributor']['margin'] : 0.5; ?>
                             </div>
                        </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	</div>
                <?php
                    if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                    ?>

                       <?php $distarr = array('0'=>'Replacement','1' => 'New');?> 
                       <?php $leadarr = array('0'=>'Offline','1' => 'Online');?> 
                    <div style="padding-bottom: 5px;">
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                             <div class="fieldLabel1 leftFloat"><label for="dist_type">Distributor Type</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo $distarr[$data['Distributor']['dist_type']]; ?>
                             </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px; margin-top: -10px;">
                             <div class="fieldLabel1 leftFloat"><label for="lead_type">Lead Type</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo $leadarr[$data['Distributor']['lead_type']]; ?>
                             </div>
                        </div>
                    </div>
                    
            	</div>
                <?php
                }
                ?>
                       
            	 <div style="padding-top:20px">
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php echo $ajax->submit('Confirm Distributor', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'createDistributor'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backDistributor'), 'class' => 'retailBut disabledBut',  'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
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
		</fieldset>

<input type="hidden" name="data[confirm]" value="1">
<input type="hidden" name="data[Distributor][map_lat]" value="<?php echo $data['Distributor']['map_lat'];?>">
<input type="hidden" name="data[Distributor][map_long]" value="<?php echo $data['Distributor']['map_long'];?>">
<input type="hidden" name="data[Distributor][name]" value="<?php echo $data['Distributor']['name'];?>">
<input type="hidden" name="data[Distributor][mobile]" value="<?php echo $data['Distributor']['mobile'];?>">
<input type="hidden" name="data[Distributor][dob]" value="<?php echo $data['Distributor']['dob'];?>">
<input type="hidden" name="data[Distributor][reference]" value="<?php echo $data['Distributor']['dist_reference'];?>">
<input type="hidden" name="data[Distributor][reference_code]" value="<?php echo $data['Distributor']['dist_reference_code'];?>">
<input type="hidden" name="data[Distributor][slab_id]" value="<?php echo $data['Distributor']['slab_id'];?>">
<input type="hidden" name="data[Distributor][city]" value="<?php echo $data['Distributor']['city'];?>">
<input type="hidden" name="data[Distributor][state]" value="<?php echo $data['Distributor']['state'];?>">
<input type="hidden" name="data[Distributor][pan_number]" value="<?php echo $data['Distributor']['pan_number'];?>">
<input type="hidden" name="data[Distributor][tds_flag]" value="<?php if(isset($data['Distributor']['tds_flag']) && $data['Distributor']['tds_flag'] == "1")echo "on";?>">
<input type="hidden" name="data[Distributor][company]" value="<?php echo $data['Distributor']['company'];?>">
<input type="hidden" name="data[Distributor][address]" value="<?php echo $data['Distributor']['address'];?>">
<input type="hidden" name="data[login]" value="<?php if(isset($data['login']))echo $data['login'];?>">
<input type="hidden" name="data[Distributor][area_range]" value="<?php echo $data['Distributor']['area_range'];?>">
<input type="hidden" name="data[Distributor][email]" value="<?php echo $data['Distributor']['email'];?>">
<?php if($this->Session->read('Auth.commission_type') == 2) { ?>
<input type="hidden" name="data[Distributor][commission_type]" value="<?php echo $data['Distributor']['commission_type'];?>">
<input type="hidden" name="data[Distributor][active_services]" value="<?php echo implode(array_map('current', $active_services), ',');?>">
<input type="hidden" name="data[Distributor][margin]" value="<?php echo $data['Distributor']['margin']; ?>">
<?php } ?>
<input type="hidden" name="data[Distributor][gst_no]" value="<?php echo $data['Distributor']['gst_no'];?>">
<input type="hidden" name="data[Distributor][dist_type]" value="<?php echo $data['Distributor']['dist_type'];?>">
<input type="hidden" name="data[Distributor][lead_type]" value="<?php echo $data['Distributor']['lead_type'];?>">
<?php echo $form->end(); ?>