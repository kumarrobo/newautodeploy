<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php if($type == 'r') { 
    				echo $this->element('shop_side_activities',array('side_tab' => 'allretailer'));
    			}
    			else if($type == 'd') {
    				echo $this->element('shop_side_activities',array('side_tab' => 'retailerList'));
    			} else if($type == 'sd'){
                    echo $this->element('shop_side_activities',array('side_tab' => 'allsuperdistributor'));
                }
    		?>
    		<div id="innerDiv">
<?php foreach($editData as $data){if($type == 'r'){ ?>
	<fieldset class="fields1" style="border:0px;margin:0px;">
			<?php if(!isset($dist)) { ?>
			<div class="appTitle">Retailer Details<span style="float:right"><a href="/shops/allRetailer"><< back</a></span></div>
			<?php } else { ?>
			<div class="appTitle">Retailer Details<span style="float:right"><a href="/shops/retailerListing/<?php echo $dist; ?>"><< back</a></span></div>
			<?php } ?>	
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Retailer']['name'];?>
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
                         <div class="fieldLabelSpace1 strng">
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
                        <div class="fieldLabelSpace1 strng">
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
                         <div class="fieldLabelSpace1 strng">
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
                         <div class="fieldLabel1 leftFloat"><label for="pan">PAN Number</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php if(!empty($data['Retailer']['pan_number']))echo $data['Retailer']['pan_number']; else echo "&nbsp;";?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="address">Address</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $data['Retailer']['address'];?>
                         </div>
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Slab</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo 'Retailer - ' . $slab;?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
                 
		</fieldset>
<?php } ?>

<?php if($type == 'd'){ ?>
	<fieldset class="fields1" style="border:0px;margin:0px;">
			<div class="appTitle">Distributor Details<span style="float:right"><a href="/shops/allRetailer"><< back</a></span></div>
				<div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['Distributor']['name'];?>
                         </div>                     
                 	</div>
                 	<div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="company" >Company Name</label></div>
                         <div class="fieldLabelSpace1 strng">
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
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $data['Distributor']['email'];?>&nbsp;
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
                        <div class="fieldLabelSpace1 strng">
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
                          <div class="fieldLabel1 leftFloat"><label for="pan">PAN Number</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php echo $data['Distributor']['pan_number'];?>
                         </div>
                    </div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Slab</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo 'Distributor - ' . $slab;?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                          <div class="fieldLabel1 leftFloat"><label for="tds">TDS Authorized</label></div>
                         <div class="fieldLabelSpace1 strng">
                         	<?php if($data['Distributor']['tds_flag'] == "1") echo "Yes"; else echo "No";?>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div style="padding-top:20px">                 
            	 </div>
		</fieldset>
<?php } ?>

<?php if($type == 'sd'){ ?>
    <fieldset class="fields1" style="border:0px;margin:0px;">
            <div class="appTitle">Super Distributor Details<span style="float:right"><a href="/shops/allSuperDistributor"><< back</a></span></div>
                <div>
                <div class="field" style="padding-top:5px;">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="username">Name</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['SuperDistributor']['name'];?>
                         </div>                     
                    </div>
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="company" >Company Name</label></div>
                         <div class="fieldLabelSpace1 strng">
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
                            <?php echo $data['users']['mobile'];?>&nbsp;
                         </div>               
                    </div>               
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['SuperDistributor']['email'];?>&nbsp;
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
                        <div class="fieldLabelSpace1 strng">
                            <?php echo $city;?>
                        </div>                    
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                 </div>
                 </div>
                 <div class="altRow">
                 <div class="field">               
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address">Address</label></div>
                         <div class="fieldLabelSpace1 strng" style="margin-left: 500px;">
                            <?php echo $data['SuperDistributor']['address'];?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                 </div>
                 </div>
                 <div>
                 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                          <div class="fieldLabel1 leftFloat"><label for="pan">PAN Number</label></div>
                         <div class="fieldLabelSpace1 strng">
                            <?php echo $data['SuperDistributor']['pan_number'];?>
                         </div>
                    </div>               
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab">Slab</label></div>
                         <div class="fieldLabelSpace1 strng">
                         <?php echo 'SuperDistributor - ' . $slab;?>
                         </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                 </div>
                 </div>
                 <div style="padding-top:20px">                 
                 </div>
        </fieldset>
<?php }} ?>
</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>