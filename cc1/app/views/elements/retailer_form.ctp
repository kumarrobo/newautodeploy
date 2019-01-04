<?php echo $form->create('retailer',array('action' => 'add')); ?>
							     	<fieldset class="fields">
										<div class="title3">New Retailer</div>
											<div class="field" style="padding-top:10px;">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="username"> Name </label></div>
							                         <div class="fieldLabelSpace">
							                            <input tabindex="4" type="text" id="username" name="data[Retailer][name]"  />
							                         </div>                     
							                 	</div>
							            	 </div>
							            	 
							            	 <div class="field">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="mobile"> Mobile </label></div>
							                         <div class="fieldLabelSpace">
							                            <input tabindex="5" type="text" id="mobile" name="data[Retailer][mobile]" />
							                         </div>                     
							                 	</div>
							            	 </div>
							            	 
							            	 <div class="field">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="salesman"> Salesman </label></div>
							                         <div class="fieldLabelSpace">
							                         	<select tabindex="6" id="salesman" name="data[Retailer][salesman_id]">
															<option value="0"> Select </option>
															<?php foreach($salesmans as $salesman) {?>
																<option value="<?php echo $salesman['Salesman']['id'];?>"><?php echo $salesman['Salesman']['name'] . " - " . $salesman['Salesman']['area']; ?></option>
															<?php } ?>
														</select>
							                         </div>                     
							                 	</div>
							            	 </div>
							            	 
							            	 <div class="field">
							                    <div class="fieldDetail">
							                    	<div class="fieldLabel leftFloat"><label for="state"> State </label></div>
							                         <select tabindex="7" id="state" name="data[Retailer][state]" onchange="getCities(this.options[this.selectedIndex].value,'r')">
							                         	<option value="0">Select State</option>
														<?php foreach($states as $state) {?>
															<option value="<?php echo $state['locator_state']['id'];?>"><?php echo $state['locator_state']['name']; ?></option>
														<?php } ?>
													</select>                    
							                 	</div>
							            	 </div>
							            	 
					
							            	
							            	 <div class="field">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="city"> City </label></div>
							                         <select tabindex="8" id="city" name="data[Retailer][city]" onchange="getAreas(this.options[this.selectedIndex].value,'r')">
							                         	<option value="0">Select City</option>
														<?php foreach($cities as $city) {?>
															<option value="<?php echo $city['locator_city']['id'];?>"><?php echo $city['locator_city']['name']; ?></option>
														<?php } ?>
													</select>                    
							                 	</div>
							            	 </div>
							              
							              	<div class="field">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="area"> Area </label></div>
							                         <div class="fieldLabelSpace">
							                         	<select tabindex="9" id="area" name="data[Retailer][area_id]">
															<option value="0">Select Area</option>
															<?php foreach($areas as $area) {?>
																<option value="<?php echo $area['locator_area']['id'];?>"><?php echo $area['locator_area']['name']; ?></option>
															<?php } ?>
														</select>
							                         </div>                     
							                 	</div>
							            	 </div>
							            	 
							            	 <div class="field">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="shopname">Shop Name</label></div>
							                         <div class="fieldLabelSpace">
							                            <input tabindex="10" type="text" id="shopname" name="data[Retailer][shopname]" />
							                         </div>
							                    </div>
							            	 </div>
							            	 
							                 <div class="field">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="address">Address</label></div>
							                         <div class="fieldLabelSpace">
							                            <textarea tabindex="11" id="address" name="data[Retailer][address]" style="width:215px;height:55px;"></textarea>
							                         </div>
							                    </div>
							            	 </div>
							            	 
							            	<div class="field">
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat"><label for="pin">Pin</label></div>
							                         <div class="fieldLabelSpace">
							                            <input tabindex="12" type="text" id="pin" name="data[Retailer][pin]" />
							                         </div>
							                    </div>
							            	 </div>  
							            	 
							                 <div class="field">               		
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat">&nbsp;</div>
							                         <div class="fieldLabelSpace" id="sub_butt">
							                            <?php echo $form->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'13','class' => 'otherSprite oSPos7')); ?>
							                         </div>                         
							                    </div>
							                </div>
							                <div class="field">    
							                    <div class="fieldDetail">
							                         <div class="fieldLabel leftFloat">&nbsp;</div>
							                         <div class="fieldLabelSpace" style="color:#004B91">
							                            <?php echo $this->Session->flash();?>
							                         </div>   
							                    </div>
							            	 </div>	
									</fieldset>
							<?php echo $form->end(); ?>