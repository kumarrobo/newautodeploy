<?php echo $form->create('shop');

	foreach($editData as $data){
	
?>

     	<fieldset class="fields1" style="border:0px;margin:0px;">
			

<?php if($type == 'r'){ ?>
<input  type="hidden" id="username" name="data[Retailer][id]"  value="<?php if(isset($data))echo $data['Retailer']['id']; ?>"/>
			<div class="appTitle">Edit Retailer<span style="float:right"><a href="/shops/allRetailer"><< back</a></span></div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username" class="compulsory">Name</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="1" type="text" id="username" name="data[Retailer][name]"  value="<?php if(isset($data))echo $data['Retailer']['name']; ?>"/>
                         </div>                     
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="pan" class="compulsory"> PAN Number </label></div>
                         <div class="fieldLabelSpace1">
                         	 <input tabindex="2" type="text" id="pan" name="data[Retailer][pan_number]" value="<?php if(isset($data))echo $data['Retailer']['pan_number']; ?>"/>
                         </div>                    
                 	</div>         
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Mobile</label></div>
                         <div class="fieldLabelSpace1">
                            <?php echo $data['users']['mobile']; ?>
                            <input tabindex="3" type="hidden" id="mobile" name="data[users][mobile]" value ="<?php if(isset($data))echo $data['users']['mobile']; ?>"/>
                         </div>                     
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="4" type="text" id="email" name="data[Retailer][email]" value="<?php if(isset($data))echo $data['Retailer']['email']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state" class="compulsory"> State </label></div>
                    	<div class="fieldLabelSpace1">
                         <select tabindex="5" id="state" name="data[Retailer][state]" onchange="getCities(this.options[this.selectedIndex].value,'r')" style="width:148px">
                         	<option value="0">Select State</option>
							<?php if(count($states)>0){foreach($states as $state) {?>
								<option value="<?php echo $state['locator_state']['id'];?>" <?php if(isset($data) && ($data['Retailer']['state'] ==  $state['locator_state']['name'] || $retState ==  $state['locator_state']['id'] )) echo "selected"; ?>><?php echo $state['locator_state']['name']; ?></option>
                            <?php }} ?>
						</select>
						</div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="city" class="compulsory">City</label></div>
                        <div class="fieldLabelSpace1" id="cityDD">
                        <select tabindex="6" id="city" name="data[Retailer][city]" onchange="getAreas(this.options[this.selectedIndex].value,'r')" style="width:148px">
                        	<option value="0">Select City</option>
							<?php foreach($cities as $city) {?>
								<option value="<?php echo $city['locator_city']['id'];?>" <?php if(isset($data) && ($data['Retailer']['city'] ==  $city['locator_city']['name'] || $retCity ==  $city['locator_city']['id'])) echo "selected";  ?>><?php echo $city['locator_city']['name']; ?></option>
							<?php } ?>
						</select>
						</div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="area" class="compulsory"> Area </label></div>
                         <div class="fieldLabelSpace1" id="areaDD">
                         	<select tabindex="7" id="area" name="data[Retailer][area_id]" style="width:148px">
                         		<option value="0">Select Area</option>
								<?php foreach($areas as $area) {?>
									<option value="<?php echo $area['locator_area']['id'];?>" <?php if(isset($data) && $data['Retailer']['area_id'] ==  $area['locator_area']['id']) echo "selected"; ?>><?php echo $area['locator_area']['name']; ?></option>
								<?php } ?>
							</select>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="pin" class="compulsory">Pin Code</label></div>
                         <div class="fieldLabelSpace1"">
                         	<input tabindex="8" type="text" id="pin" name="data[Retailer][pin]" value ="<?php if(isset($data))echo $data['Retailer']['pin']; ?>"/>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shopname" class="compulsory"> Shop Name </label></div>
                         <div class="fieldLabelSpace1">
                         	 <input tabindex="9" type="text" id="shopname" name="data[Retailer][shopname]" value="<?php if(isset($data))echo $data['Retailer']['shopname']; else echo $edata[$modName]['shopname']; ?>"/>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address" class="compulsory">Address</label></div>
                         <div class="fieldLabelSpace1"">
                            <textarea tabindex="10" id="address" name="data[Retailer][address]" style="width:180px;height:55px;"><?php if(isset($data))echo $data['Retailer']['address'];?></textarea>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                   <div class="fieldDetail leftFloat" style="width:350px;">
                   		<div class="fieldLabel1 leftFloat"><label for="slab" class="compulsory">Assign Slab</label></div>
                         <div class="fieldLabelSpace1">
                            <select tabindex="11" id="slab" name="data[Retailer][slab_id]" >
							<?php foreach($slabs as $slab) {?>
								<option value="<?php echo $slab['Slab']['id'];?>" <?php if(isset($data) && $slab['Slab']['id'] == $data['Retailer']['slab_id']) echo "selected"; ?>><?php echo $slab['Slab']['name']; ?></option>
							<?php } ?>
							</select>
                         </div>                                           
                 	</div>         	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="salesman" class="compulsory"> Salesman</label></div>
                         <div class="fieldLabelSpace1">
                         	 <select tabindex="12" id="area" name="data[Retailer][salesman]" style="width:148px">
                         		<option value="0">Select Salesman</option>
								<?php foreach($sMen as $sm) {?>
									<option value="<?php echo $sm['salesmen']['id'];?>" <?php if(isset($data) && $data['Retailer']['salesman'] ==  $sm['salesmen']['id']) echo "selected"; ?>><?php echo $sm['salesmen']['name']." (".$sm['salesmen']['mobile'].")"; ?></option>
								<?php } ?>
							</select>                         	 
                         </div>
                    </div>
            	 </div>
            	 </div>            	 
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="setup"> Kit/Rental </label></div>
                         <div class="fieldLabelSpace1">
                         	<?php if($data['Retailer']['rental_flag'] == 0) echo "Kit"; else echo "Rental"; ?>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="kyc">KYC Docs</label></div>
                         <div class="fieldLabelSpace1">
                            <textarea tabindex="13" id="address" name="data[Retailer][kyc]" style="width:180px;height:55px;"><?php if(isset($data))echo $data['Retailer']['kyc'];?></textarea>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 
            	 <div class="altRow">
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="phoneinfo" class="compulsory"> Mobile Phone Info</label></div>
                         <div class="fieldLabelSpace1">
                         	 <input tabindex="14" type="text" id="shopname" name="data[Retailer][mobile_info]" value="<?php if(isset($data))echo $data['Retailer']['mobile_info']; else echo $edata[$modName]['mobile_info']; ?>"/>
                         </div>                    
                    </div>           	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="App Type" class="compulsory">App Type</label></div>
                         <div class="fieldLabelSpace1""> <?php $aArr = explode(",",$data['Retailer']['app_type']); ?>
                             <select tabindex="15" id="slab" name="data[Retailer][app_type][]" multiple="multiple">
								<option <?php if(isset($data) && in_array(APP_JAVA,$aArr)) echo "selected"; ?> value="<?php echo APP_JAVA; ?>" >Java</option>
								<option <?php if(isset($data) && in_array(APP_ANDROID,$aArr)) echo "selected"; ?> value="<?php echo APP_ANDROID; ?>" >Android</option>
								<option <?php if(isset($data) && in_array(APP_SMS,$aArr)) echo "selected"; ?> value="<?php echo APP_SMS; ?>" >SMS</option>
								<option <?php if(isset($data) && in_array(APP_USSD,$aArr)) echo "selected"; ?> value="<?php echo APP_USSD; ?>" >USSD</option>
							</select>
                         </div>
                    </div>
            	 </div>
            	 </div>
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Next >>', array('id' => 'sub', 'tabindex'=>'12','url'=> array('controller'=>'shops', 'action'=>'editRetValidation'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">
                         
                         <div class="inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	
<?php }else if($type == 'd'){  ?>
<input  type="hidden" id="username" name="data[Distributor][id]"  value="<?php if(isset($data))echo $data['Distributor']['id']; ?>"/>
			<div class="appTitle">Edit Distributor<span style="float:right"><a href="/shops/allDistributor"><< back</a></span></div>
			
                        <div>
		    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="map_lat" class="compulsory">Lat</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex="1" type="text" id="map_lat" name="data[Distributor][map_lat]"  value="<?php if(isset($data))echo $data['Distributor']['map_lat']; ?>"/>
                            </div>                     
                        </div>
                        <div class="fieldDetail">
                             <div class="fieldLabel1 leftFloat"><label for="map_long" class="compulsory">Long</label></div>
                             <div class="fieldLabelSpace1">
                                <input tabindex="2" type="text" id="map_long" name="data[Distributor][map_long]" value="<?php if(isset($data))echo $data['Distributor']['map_long']; ?>"/>
                              <input tabindex="3" type="button" value="Show Location" onclick="Initialize()">
                             </div> 
                             
                        </div>
                    </div>
            	   </div>
                        
                        
                        <div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username" class="compulsory">Name</label></div>
                         <div class="fieldLabelSpace1">
                            <input  type="text" id="username" name="data[Distributor][name]"  value="<?php if(isset($data))echo $data['Distributor']['name']; ?>"/>
                         </div>                     
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="company" class="compulsory">Company Name</label></div>
                         <div class="fieldLabelSpace1">
                            <input  type="text" id="company" name="data[Distributor][company]" value="<?php if(isset($data))echo $data['Distributor']['company']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Mobile</label></div>
                         <div class="fieldLabelSpace1">
                            <?php echo $data['users']['mobile']; ?>
                            <input  type="hidden" id="mobile" name="data[users][mobile]" value ="<?php if(isset($data))echo $data['users']['mobile']; ?>"/>
                         </div>                     
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1">
                            <input  type="text" id="email" name="data[Distributor][email]" value="<?php if(isset($data))echo $data['Distributor']['email'];?>"/>
                         &nbsp;&nbsp;<label for="dob" class="compulsory">DOB </label>
                            <input type="text" name="data[Distributor][dob]" id="data[Distributor][dob]"  onmouseover="fnInitCalendar(this, 'data[Distributor][dob]','close=true')" value="<?php $dob = $data[Distributor][dob]; if(isset($dob))echo $dob;?>" />
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state" class="compulsory"> State </label></div>
                    	<div class="fieldLabelSpace1">
                        <input  type="text" id="state" name="data[Distributor][state]" value="<?php if(isset($data))echo $data['Distributor']['state'];?>" readonly/>
			</div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="city" class="compulsory">City</label></div>
                        <div class="fieldLabelSpace1" id="cityDD">
                        <input  type="text" id="city" name="data[Distributor][city]" value="<?php if(isset($data))echo $data['Distributor']['city'];?>" readonly/>
			</div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="area" class="compulsory"> Area Range </label></div>
                         <div class="fieldLabelSpace1">
                         	 <input  type="text" id="area" name="data[Distributor][area_range]" value="<?php if(isset($data))echo $data['Distributor']['area_range'];  ?>"/>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address" class="compulsory">Company Address</label></div>
                         <div class="fieldLabelSpace1">
                            <textarea  id="address" name="data[Distributor][address]" style="width:180px;height:55px;"><?php if(isset($data))echo $data['Distributor']['address']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="pan" class="compulsory"> PAN Number </label></div>
                         <div class="fieldLabelSpace1">
                         	 <input  type="text" id="pan" name="data[Distributor][pan_number]" value="<?php if(isset($data))echo $data['Distributor']['pan_number']; ?>"/>
                         </div>                    
                 	</div>
                    <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){?> 
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab" class="compulsory">Assign Slab</label></div>
                         <div class="fieldLabelSpace1">
                            <select  id="slab" name="data[Distributor][slab_id]" >
                            <?php foreach($slabs as $slab) {?>
                                    <option value="<?php echo $slab['Slab']['id'];?>" <?php if(isset($data) && $slab['Slab']['id'] == $data['Distributor']['slab_id']) echo "selected";?>><?php echo $slab['Slab']['name']; ?></option>
                            <?php } ?>
                            </select>
                         </div>
                    </div>
                    <?php }else{
                        ?>
                        <!--input type="hidden" id="slab" name="data[Distributor][slab_id]" value="<?php echo $_SESSION['Auth']['slab_id'];?>"-->
                        <?php
                        } ?> 
            	 </div>
            	 </div>
                
                <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){ ?> 
                 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Target Amt</label></div>
                         <div class="fieldLabelSpace1">
                            
                          <select  id="slab" name="data[Distributor][target_amount]" >                            
                                    <option value="-1" <?php if(isset($data) && $data['Distributor']['target_amount'] == -1) echo "selected";?>>
                                    No Limit
                                    </option>
                                     <option value="10000" <?php if(isset($data) && $data['Distributor']['target_amount'] == 10000) echo "selected";?>>
                                    10000
                                    </option>
                                    <option value="15000" <?php if(isset($data) && $data['Distributor']['target_amount'] == 15000) echo "selected";?>>
                                    15000
                                    </option>
                                    <option value="25000" <?php if(isset($data) && $data['Distributor']['target_amount'] == 25000) echo "selected";?>>
                                    25000
                                    </option>
                          </select>
                         </div>                    
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="rental_amount">Rental Amt</label></div>
                         <div class="fieldLabelSpace1">
                            
                             <select id="slab" name="data[Distributor][rental_amount]" >                            
                                    <option value="50" <?php if(isset($data) && $data['Distributor']['rental_amount'] == 50) echo "selected";?>>
                                    50
                                    </option>
                                    <option value="30" <?php if(isset($data) && $data['Distributor']['rental_amount'] == 30) echo "selected";?>>
                                    30
                                    </option>
                                    
                          </select>
                         </div>                     
                    </div>
            	 </div>
            	 </div>

                 <div >         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="margin" class="compulsory">Margin</label></div>
                         <div class="fieldLabelSpace1">
                            <input type="text" id="email" name="data[Distributor][margin]" value="<?php if(isset($data))echo $data['Distributor']['margin'];?>"/>
                         </div>                    
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="active_flag">Active Flag</label></div>
                         <div class="fieldLabelSpace1">
                           
                            <select id="active_flag" name="data[Distributor][active_flag]" >                            
                                    <option value="0" <?php if(isset($data) && $data['Distributor']['active_flag'] == 0) echo "selected";?>>
                                    Close
                                    </option>
                                    <option value="1" <?php if(isset($data) && $data['Distributor']['active_flag'] == 1) echo "selected";?>>
                                    Open
                                    </option>
                                    
                          </select>
                         </div>                     
                    </div>
            	 </div>
            	 </div>
                 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="sd_amt" class="compulsory">Security Deposit</label></div>
                         <div class="fieldLabelSpace1">
<!--                            <input  type="text" id="email" name="data[Distributor][sd_amt]" value="<?php if(isset($data))echo $data['Distributor']['sd_amt'];?>"/>-->
                            
                              <select  id="slab" name="data[Distributor][sd_amt]" >                            
                                    <option value="0" <?php if(isset($data) && $data['Distributor']['sd_amt'] == -1) echo "selected";?>>
                                    None
                                    </option>
                                     <option value="5000" <?php if(isset($data) && $data['Distributor']['sd_amt'] == 5000) echo "selected";?>>
                                    5000
                                    </option>
                                    <option value="10000" <?php if(isset($data) && $data['Distributor']['sd_amt'] == 10000) echo "selected";?>>
                                    10000
                                    </option>
                                    <option value="15000" <?php if(isset($data) && $data['Distributor']['sd_amt'] == 15000) echo "selected";?>>
                                    15000
                                    </option>
									<option value="20000" <?php if(isset($data) && $data['Distributor']['sd_amt'] == 20000) echo "selected";?>>
                                    20000
                                    </option>
                                    <option value="25000" <?php if(isset($data) && $data['Distributor']['sd_amt'] == 25000) echo "selected";?>>
                                    25000
                                    </option>
                                    <option value="50000" <?php if(isset($data) && $data['Distributor']['sd_amt'] == 50000) echo "selected";?>>
                                    50000
                                    </option>
                              </select>
                         
                         
                         </div>                    
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="sd_date">Deposit Date</label></div>
                         <div class="fieldLabelSpace1">
                            <input  onmouseover="fnInitCalendar(this, 'sdDate','restrict=true,open=true')" type="date" id="sdDate" name="data[Distributor][sd_date]" value="<?php if(isset($data))echo $data['Distributor']['sd_date'];?>"/>
                         </div>                     
                    </div>
            	 </div>
            	 </div>  
                 <?php
                    }
                    ?>
                 <div >
              	 <div class="field">
                  <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){?> 
                    <div class="fieldDetail leftFloat" style="width:350px;">
                       <div class="fieldLabel1 leftFloat"><label for="pan" class="compulsory"> Relationship Manager (RM) </label></div>
                       <div class="fieldLabelSpace1">
                       	 <select  id="city" name="data[Distributor][rm_id]" >
                                <option value="0">Select RM</option>
			 	  <?php foreach($rm_list as $rm) {?>
				<option value="<?php echo $rm['rm']['id'];?>" <?php if(isset($data) && $data['Distributor']['rm_id'] ==  strtolower(trim($rm['rm']['id']))){ echo "selected";} ?>><?php echo $rm['rm']['name']; ?></option>
				<?php } ?>
			 </select>
                       </div>         
                    </div>
                     <?php } ?> 
                    
                     <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="retailer_limit">Retailer Limit</label></div>
                         <div class="fieldLabelSpace1">
                            <input type="text" id="email" name="data[Distributor][retailer_limit]" value="<?php if(isset($data))echo $data['Distributor']['retailer_limit'];?>"/>
                         </div>                     
                    </div>
            	 </div>
            	 </div>   
                 <div class="altRow">         	 
                     <div class="field">
                        <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){?> 
                        <div class="fieldDetail leftFloat" style="width:350px;">
                             <div class="fieldLabel1 leftFloat"><label for="sd_withdraw_date">Deposit Withdraw Date</label></div>
                             <div class="fieldLabelSpace1">
                                <input onmouseover="fnInitCalendar(this, 'sdWithdrawDate','restrict=true,open=true')" type="date" id="sdWithdrawDate" name="data[Distributor][sd_withdraw_date]" value="<?php if(isset($data))echo $data['Distributor']['sd_withdraw_date'];?>"/>
                             </div>                     
                        </div>
                         <?php } ?> 
                 
                        <div class="fieldDetail">
                             <div class="fieldLabel1 leftFloat"><label for="alternate_mob">Alternate Mobile No</label></div>
                             <div class="fieldLabelSpace1">
                                <input type="text" id="alternate_mob" name="data[Distributor][alternate_mob]" value="<?php if(isset($data))echo $data['Distributor']['alternate_number'];?>"/>
                             </div>                     
                        </div>
                     </div>
            	 </div>
                <div style="padding-top:5px;">       

                    <?php if($this->Session->read('Auth.commission_type') == 2) { ?>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="DistReference" class="compulsory">Commission Type</label></div>
                            <div class="fieldLabelSpace1">
                                <label><input type="radio" onclick="return commissionType(0);" id="ct1" class="ct" name="data[Distributor][commission_type]" value="0" <?php if($data['Distributor']['commission_type'] == 0) { echo "checked"; } ?> >Primary</label>
                                <label><input type="radio" id="ct2" class="ct" name="data[Distributor][commission_type]" value="1" <?php if($data['Distributor']['commission_type'] == 1) { echo "checked"; } ?> >Tertiary</label>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="DistReference" class="compulsory">GST No</label></div>
                            <div class="fieldLabelSpace1">
                                <input type="text" name="data[Distributor][gst_no]" value="<?php echo $data['Distributor']['gst_no']; ?>" style="margin-top: -5px;" />
                            </div>
                        </div>
                    </div>
            	</div><br/>

                <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>
                <div>  
                    
                 <div class="field">
                     <div class="fieldDetail leftFloat" style="width:350px;">
                      <div class="fieldLabel1 leftFloat"><label for="distributor_type" class="compulsory">Distributor Type</label></div>
                       <div class="fieldLabelSpace1">
                         <select class="form-control" id="dist_type" name="data[Distributor][dist_type]">
                           <option value="0" <?php if(isset($data) && $data['Distributor']['dist_type'] == 0) echo "selected";?>>
                            Replacement
                            </option>
                            <option value="1" <?php if(isset($data) && $data['Distributor']['dist_type'] == 1) echo "selected";?>>
                            New
                           </option>
                         
                         </select>
                       </div>                     
                     </div>
                 </div>
                      <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">  
                        <div class="fieldLabel1 leftFloat"><label for="lead_type" class="compulsory">Lead Source</label></div>
                        <div class="fieldLabelSpace1">
                        <select class="form-control" id="lead_type" name="data[Distributor][lead_type]">
                           <option value="0" <?php if(isset($data) && $data['Distributor']['lead_type'] == 0) echo "selected";?>>
                            Offline
                            </option>
                            <option value="1" <?php if(isset($data) && $data['Distributor']['lead_type'] == 1) echo "selected";?>>
                            Online
                           </option>
                        </select>
                        </div>                     
                        </div>                           
                 </div>
                 </div><br/>
                <?php 
                }
                if($this->Session->read('Auth.commission_type') == 2) { ?>
                <div class="altRow">
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="DistReference">Services</label></div>
                            <div class="fieldLabelSpace1">
                                <select id="demo" multiple="multiple" style="width:200px;">
                                    <?php foreach($services  as $service) { ?>
                                    <option onclick="updateServices(<?php echo $service['id']; ?>)" <?php if(in_array($service['id'], explode(',', $data['Distributor']['active_services']))) { echo "selected"; } ?>><?php echo $service['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" id="active_services" name="data[Distributor][active_services]" value="<?php echo $data['Distributor']['active_services']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="commission_per">Commission %</label></div>
                            <div class="fieldLabelSpace1">
                                <input type="text" name="data[Distributor][margin]" value="<?php echo $data['Distributor']['margin']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	</div>
                <?php } ?>
            	 <!--<div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="login" class="compulsory">SMS Login Details</label></div>
                         <div class="fieldLabelSpace1">
                            <input type="checkbox" tabindex="9" id="login" name="data[login]" <?php if(isset($data['login']) && $data['login'] == 'on') echo "checked";?>>
                         </div>
                         
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab" class="compulsory">Assign Slab</label></div>
                         <div class="fieldLabelSpace1">
                            <select tabindex="10" id="slab" name="data[Distributor][slab_id]" >
							<?php foreach($slabs as $slab) {?>
								<option value="<?php echo $slab['Slab']['id'];?>" <?php if(isset($data) && $slab['Slab']['id'] == $data['Distributor']['slab_id']) echo "selected";?>><?php echo $slab['Slab']['name']; ?></option>
							<?php } ?>
							</select>
                         </div>
                    </div>
            	 </div>
            	 </div>-->
                 <div class="field"  style="padding-top:20px">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Next >>', array('id' => 'sub', 'tabindex'=>'11','url'=> array('controller'=>'shops', 'action'=>'editDistValidation'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">   
                         <!-- <div class="fieldLabel leftFloat">&nbsp;</div> -->
                         <div class="inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
<?php } ?>
            	 </div>
                 
                <div class="col-sm-6">
                <input type="button" value="Show Address" onclick="ShowAddress()">
                <div id="googleMap" style="width:1000px;height:200px;"></div></div>    
            </div> 
                 
		</fieldset>
<?php } echo $form->end(); ?>

<script>
//if($('username'))
//	$('username').focus();	

if($('map_lat'))
	$('map_lat').focus();
    
        function Initialize()
        {
          var map_lat = document.getElementById('map_lat').value;  
          var map_long = document.getElementById('map_long').value;  
          
          var mapProp = {
            center: new google.maps.LatLng(map_lat,map_long),
            zoom:10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };
          
          var latlng = new google.maps.LatLng(map_lat, map_long);
            
          var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
          var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title:'Click to zoom'
            });
            marker.setMap(map); 
            
          google.maps.event.addListener(map, 'click', function(event){
          marker.setPosition(event.latLng);
          var event_latlng = event.latLng;
            document.getElementById('map_lat').value = event_latlng.lat().toFixed(6);
            document.getElementById('map_long').value = event_latlng.lng().toFixed(6); 
          });
         
        }

        function loadScript()
        {
          var script = document.createElement("script");
          script.type = "text/javascript";
          script.src = "http://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>&sensor=false&callback=Initialize";
          document.body.appendChild(script);
        }

        window.onload = loadScript;
   
        function httpGet(theUrl){
            var xmlHttp = null;
            xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", theUrl, false );
            xmlHttp.send( null );
            return xmlHttp.responseText;
        }
        
        function ShowAddress(){
            var lat_lng = document.getElementById('map_lat').value+'/'+document.getElementById('map_long').value;
//            19.167911/72.840986
            new Ajax.Request("/apis/getAreaUsingLatLong/"+lat_lng,
            {
                method: 'GET',
                dataType: 'json',
                onFailure: function(data) {
                    console.log('Fail Data -- '+data.responseText);
                },
                onSuccess: function(data) {
                    var JSONObject = JSON.parse(data.responseText);
//                    console.log(JSONObject); // Dump all data of the Object in the console
                    document.getElementById('area').value=  JSONObject["area_name"]; 
                    document.getElementById('city').value=  JSONObject["city_name"];
                    document.getElementById('state').value= JSONObject["state_name"];
                }
            });
        }
        
        function commissionType(val) {
                var a = document.getElementById('active_services').value.split(',');
                var primary_services = JSON.parse('<?php echo json_encode($primary_services); ?>');
                
                for (var ps in primary_services) {
                        var index = a.indexOf(String(primary_services[ps])); if(index != -1) { a.splice(index,1); }
                }
                
                if(a.length != 0) { alert('Services selected are only supported in TERTIARY commission type'); return false; }
        }
        
        function updateServices(val) {
                if(val > 10 && document.getElementsByClassName('ct')['ct1'].checked == true) {
                        alert('SHIFTING distributor to TERTIARY commission type, as this service is available only in TERTIARY');
                        document.getElementsByClassName('ct')['ct2'].checked = true;
                }
                document.getElementById("demo").onclick = function (e) {
                        var active_services = document.getElementById('active_services').value == '' || !e.ctrlKey ? [] : document.getElementById('active_services').value.split(",");
                        var in_object = active_services.indexOf(String(val));
                        if(in_object == -1){
                                active_services.push(val);
                                active_services.sort(function (a, b) { return a - b; });
                        } else {
                                active_services.splice(in_object, 1);
                        }
                        document.getElementById('active_services').value = active_services.join();
                }
        }

</script>