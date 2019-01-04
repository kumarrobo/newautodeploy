<?php echo $form->create('confirm'); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Edit Distributor</div>
                        
                        <div>
		            <div class="field" style="padding-top:5px;">
                             <div class="fieldDetail leftFloat" style="width:350px;">
                                <div class="fieldLabel1 leftFloat"><label for="map_lat">Lat</label></div>
                                <div class="fieldLabelSpace1 strng">
                                  <?php echo $data['Distributor']['map_lat'];?>
                                </div>                     
                             </div>
                            <div class="fieldDetail">
                                <div class="fieldLabel1 leftFloat"><label for="map_long">Long</label></div>
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
                            <?php echo $data['users']['mobile'];?>&nbsp;
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
                         <div class="fieldLabel1 leftFloat"><label for="login">PAN Number</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $data['Distributor']['pan_number'];?>
                         </div>
                    </div> 
                    <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>           	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Assign Slab</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo 'Distributor - ' . $slab;?>
                         </div>
                    </div>
                    <?php
}
                ?>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                     <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>   
                         <div class="altRow">
              	 <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="pan"> Target Amt </label></div>
                         <div class="fieldLabelSpace1 strng">
                         	 <?php echo ( $data['Distributor']['target_amount'] == -1 ? "No Limit" : $data['Distributor']['target_amount'] );?>
                         </div>                    
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="tds">Rental Amt</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Distributor']['rental_amount'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                        <div>
              	 <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="pan">Commission %</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	 <?php echo $data['Distributor']['margin'];?>
                         </div>                    
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="tds">Active Flag</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo ( $data['Distributor']['active_flag'] == 1 ? "Open" : "Close" );?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                        <div class="altRow">
              	 <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="sd_amt"> Security Deposit </label></div>
                         <div class="fieldLabelSpace1 strng">
                         	 <?php echo $data['Distributor']['sd_amt'];?>
                         </div>                    
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="sd_date">Security Deposit Date</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Distributor']['sd_date'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                      <?php
}
                ?>  
                        
                 
                        
                        <div >   
            	 <div class="field">
                    <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="login">RM Name</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $rm_name;?>
                         </div>
                    </div>
                    <?php
}
                ?>
                     <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="tds">Retailer Limit</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Distributor']['retailer_limit'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                 <div class="altRow">   
            	 <div class="field">
                 <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="login">Security Deposit Withdraw Date: </label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $data['Distributor']['sd_withdraw_date'];?>
                         </div>
                    </div>
                    <?php
}
                ?>
                         <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="tds">Alternate Mobile No</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['Distributor']['alternate_mob'];?>
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
                <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                             <div class="fieldLabel1 leftFloat"><label for="login">Commission Type</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo $commission[$commission_type]; ?>
                             </div>
                        </div>
                    </div>
                    <?php
}
                ?>
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
                <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>
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
                <?php
}
                ?>
<!--                <div style="margin-top: 10px;">
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px; margin-top: -10px;">
                             <div class="fieldLabel1 leftFloat"><label for="margin">Commission %</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo isset($data['Distributor']['margin']) ? $data['Distributor']['margin'] : 0.5; ?>
                             </div>
                        </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	</div>-->
                <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>
                <?php $distarr = array('0'=>'Replacement','1' => 'New');?> 
                <?php $leadarr = array('0'=>'Offline','1' => 'Online');?> 
               <div class="altRow">   
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="dist_type">Distributor Type</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $distarr[$data['Distributor']['dist_type']];?>
                         </div>
                    </div>
                         <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="lead_type">Lead Type</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $leadarr[$data['Distributor']['lead_type']];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
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
							<?php echo $ajax->submit('Confirm Edit', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'editDistValidation'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backDistEdit','d'), 'class' => 'retailBut disabledBut',  'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
						</div>                       
                    </div>                    
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
		</fieldset>
<input type="hidden" name="data[confirm]" value="1">
<input type="hidden" name="data[Distributor][id]" value="<?php echo $data['Distributor']['id'];?>">
<input type="hidden" name="data[Distributor][map_lat]" value="<?php echo $data['Distributor']['map_lat'];?>">
<input type="hidden" name="data[Distributor][map_long]" value="<?php echo $data['Distributor']['map_long'];?>">
<input type="hidden" name="data[Distributor][name]" value="<?php echo $data['Distributor']['name'];?>">
<input type="hidden" name="data[Distributor][pan_number]" value="<?php echo $data['Distributor']['pan_number'];?>">
<input type="hidden" name="data[users][mobile]" value="<?php echo $data['users']['mobile'];?>">
<input type="hidden" name="data[Distributor][slab_id]" value="<?php echo $data['Distributor']['slab_id'];?>">
<input type="hidden" name="data[Distributor][city]" value="<?php echo $data['Distributor']['city'];?>">
<input type="hidden" name="data[Distributor][state]" value="<?php echo $data['Distributor']['state'];?>">
<input type="hidden" name="data[Distributor][company]" value="<?php echo $data['Distributor']['company'];?>">
<input type="hidden" name="data[Distributor][address]" value="<?php echo $data['Distributor']['address'];?>">
<input type="hidden" name="data[Distributor][area_range]" value="<?php echo $data['Distributor']['area_range'];?>">
<input type="hidden" name="data[Distributor][email]" value="<?php echo $data['Distributor']['email'];?>">
<input type="hidden" name="data[Distributor][dob]" value="<?php echo $data['Distributor']['dob'];?>">
<input type="hidden" name="data[Distributor][rm_id]" value="<?php echo $data['Distributor']['rm_id'];?>">
<input type="hidden" name="data[trans_type]" value="<?php echo $type;?>">


<input type="hidden" name="data[Distributor][target_amount]" value="<?php echo $data['Distributor']['target_amount'];?>">
<input type="hidden" name="data[Distributor][rental_amount]" value="<?php echo $data['Distributor']['rental_amount'];?>">
<input type="hidden" name="data[Distributor][margin]" value="<?php echo $data['Distributor']['margin'];?>">
<input type="hidden" name="data[Distributor][active_flag]" value="<?php echo $data['Distributor']['active_flag'];?>">
<input type="hidden" name="data[Distributor][retailer_limit]" value="<?php echo $data['Distributor']['retailer_limit'];?>">
<input type="hidden" name="data[Distributor][sd_amt]" value="<?php echo $data['Distributor']['sd_amt'];?>">
<input type="hidden" name="data[Distributor][sd_date]" value="<?php echo $data['Distributor']['sd_date'];?>">
<input type="hidden" name="data[Distributor][sd_withdraw_date]" value="<?php echo $data['Distributor']['sd_withdraw_date'];?>">
<input type="hidden" name="data[Distributor][alternate_mob]" value="<?php echo $data['Distributor']['alternate_mob'];?>">
<?php if($this->Session->read('Auth.commission_type') == 2) { ?>
<input type="hidden" name="data[Distributor][commission_type]" value="<?php echo $data['Distributor']['commission_type'];?>">
<input type="hidden" name="data[Distributor][active_services]" value="<?php echo implode(array_map('current', $active_services), ',');?>">
<input type="hidden" name="data[Distributor][margin]" value="<?php echo $data['Distributor']['margin'];?>">
<?php } ?>
<input type="hidden" name="data[Distributor][gst_no]" value="<?php echo $data['Distributor']['gst_no']; ?>">
<input type="hidden" name="data[Distributor][dist_type]" value="<?php echo $data['Distributor']['dist_type']; ?>">
<input type="hidden" name="data[Distributor][lead_type]" value="<?php echo $data['Distributor']['lead_type']; ?>">

<?php echo $form->end(); ?>