<?php echo $form->create('confirm', array(
'id' => 'form_confirm_sd_edit')); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Update Super Distributor </div>
                        <div>
			    <div class="field" style="padding-top:5px;">
                   
            
                            <div class="fieldDetail leftFloat" style="width:350px;">
                                <div class="fieldLabel1 leftFloat"><label for="map_lat">Latitude</label></div>
                                <div class="fieldLabelSpace1 strng">
                                   <?php echo $data['SuperDistributor']['map_lat'];?>
                                </div>                     
                 	    </div>
                            <div class="fieldDetail">
                                <div class="fieldLabel1 leftFloat"><label for="map_long">Longitude</label></div>
                                <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                                       <?php echo $data['SuperDistributor']['map_long'];?>&nbsp;
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
                            <?php echo $data['SuperDistributor']['name'];?>
                         </div>                     
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="company">Company Name</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['SuperDistributor']['company'];?>&nbsp;
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
                            <?php echo $data['SuperDistributor']['mobile'];?>&nbsp;
                         </div>               
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                         	<?php echo $data['SuperDistributor']['email'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <label for="dob">DOB</label>&nbsp;&nbsp;&nbsp;<?php echo $data['SuperDistributor']['dob'];?>&nbsp;     
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
                         <div class="fieldLabel1 leftFloat"><label for="address">Address</label></div>
                         <div class="fieldLabelSpace1 strng" >
                         	<?php echo $data['SuperDistributor']['address'];?>
                         </div>
                    </div>
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="no_distributors_limit">No. of dist limit</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                            <?php echo $data['SuperDistributor']['no_distributors_limit'];?>
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
                         	 <?php echo $data['SuperDistributor']['pan_number'];?>
                         </div>                    
                 	</div>
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="pan"> Assigned RM </label></div>
                         <div class="fieldLabelSpace1 strng">
                             <?php echo $data['SuperDistributor']['rm_name'];?>
                         </div>                    
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">           	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Assign Slab</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo 'Super Distributor - ' . $slab[0]['slabs']['name'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                
                <div style="padding-bottom: 5px;">
                    
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px; margin-top: -10px;">
                             <div class="fieldLabel1 leftFloat"><label for="login">GST No</label></div>
                             <div class="fieldLabelSpace1 strng">
                                    <?php echo $data['SuperDistributor']['gst_no']; ?>
                             </div>
                        </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	</div>
                
                
                       
                       
            	 <div style="padding-top:20px">
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="leftFloat" style="margin-right:20px;" id="sub_butt">
							<?php //echo $ajax->submit('Confirm Super Distributor', array('id' => 'sub', 'tabindex'=>'1','url'=> array('controller'=>'shops','action'=>'editSuperDistributorValidation'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                            <input class="retailBut enabledBut" value="Confirm Super Distributor" onclick="confirm_super_distributor_edit();" type="button">
						</div>
						<div class="fieldLabelSpace" id="sub_butt1">
							<?php echo $ajax->submit('Go Back', array('id' => 'sub1', 'tabindex'=>'2','url'=> array('controller'=>'shops','action'=>'backSuperDistributorEdit'), 'class' => 'retailBut disabledBut',  'after' => 'showLoader2("sub_butt1");', 'update' => 'innerDiv')); ?>
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
<input type="hidden" name="data[SuperDistributor][map_lat]" id="map_lat" value="<?php echo $data['SuperDistributor']['map_lat'];?>">
<input type="hidden" name="data[SuperDistributor][map_long]" id= "map_long" value="<?php echo $data['SuperDistributor']['map_long'];?>">
<input type="hidden" name="data[SuperDistributor][name]" id="name" value="<?php echo $data['SuperDistributor']['name'];?>">
<input type="hidden" name="data[SuperDistributor][mobile]" id="mobile" value="<?php echo $data['SuperDistributor']['mobile'];?>">
<input type="hidden" name="data[SuperDistributor][dob]" id="dob" value="<?php echo $data['SuperDistributor']['dob'];?>">
<input type="hidden" name="data[SuperDistributor][slab_id]" id="slab_id" value="<?php echo $data['SuperDistributor']['slab_id'];?>">
<input type="hidden" name="data[SuperDistributor][city]" id="city" value="<?php echo $data['SuperDistributor']['city'];?>">
<input type="hidden" name="data[SuperDistributor][state]" id="state" value="<?php echo $data['SuperDistributor']['state'];?>">
<input type="hidden" name="data[SuperDistributor][pan_number]" id="pan_number" value="<?php echo $data['SuperDistributor']['pan_number'];?>">
<input type="hidden" name="data[SuperDistributor][company]" id="company" value="<?php echo $data['SuperDistributor']['company'];?>">
<input type="hidden" name="data[SuperDistributor][address]" id="address" value="<?php echo $data['SuperDistributor']['address'];?>">
<input type="hidden" name="data[SuperDistributor][no_distributors_limit]" id="no_distributors_limit" value="<?php echo $data['SuperDistributor']['no_distributors_limit'];?>">
<input type="hidden" name="data[login]" id="login" value="<?php if(isset($data['login']))echo $data['login'];?>">
<input type="hidden" name="data[SuperDistributor][email]" id="email" value="<?php echo $data['SuperDistributor']['email'];?>">

<input type="hidden" name="data[SuperDistributor][gst_no]" id="gst_no" value="<?php echo $data['SuperDistributor']['gst_no'];?>">
<input type="hidden" name="data[SuperDistributor][rm_id]" id="rm_id" value="<?php echo $data['SuperDistributor']['rm_id'];?>">
<input type="hidden" name="data[SuperDistributor][rm_name]" id="rm_name" value="<?php echo $data['SuperDistributor']['rm_name'];?>">
<input type="hidden" name="data[SuperDistributor][id]" id="sd_id" value="<?php echo $data['SuperDistributor']['id']; ?>">
<?php echo $form->end(); ?>

<!-- function confirm_super_distributor_edit(){
  alert($("data[SuperDistributor][map_lat]").val());
    var data  = {
        "data[confirm]" : 1,
        "data[SuperDistributor][map_lat]" : $("data[SuperDistributor][map_lat]").val(),
    }


     $.ajax({
            type: 'POST',
            url: '/shops/editSuperDistributorValidation',
            data: data,
            cache: false,
            dataType: 'HTML',
            success: function (data){
                if(data == "success"){
                      window.location = "/shops/allSuperDistributor"
                  }else{
                      $('#innerDiv').val(data);
                  }
                
            }
        });
} -->