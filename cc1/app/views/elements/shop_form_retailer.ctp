<?php echo $form->create(null, array('url' => array('controller' => 'shops', 'action' => 'buyKits'))); ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
    <div class="appTitle">New Retailer</div>
    <div>
        <div class="field" style="padding-top:5px;">
            <div class="fieldDetail leftFloat" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Mobile</label></div>
                <div class="fieldLabelSpace1">
                    <input tabindex="1" type="text" id="mobile" maxlength="10" name="data[Retailer][mobile]" value ="<?php if (isset($data)) echo $data['Retailer']['mobile']; ?>"/>
                </div>                     
            </div>  
            <div class="fieldDetail">
                <div class="fieldLabel1 leftFloat"><label for="shopname" class="compulsory"> Shop Name </label></div>
                <div class="fieldLabelSpace1">
                    <input tabindex="2" type="text" id="shopname" name="data[Retailer][shopname]" value="<?php if (isset($data))
    echo
    $data['Retailer']['shopname'];
?>"/>
                </div>                    
            </div><div class="clearLeft">&nbsp;</div>

            <div class="fieldDetail leftFloat" style="width:350px; ">
                <div class="fieldLabel1 leftFloat" style="padding-top: 10px;"><label for="RetailerTypes" class="compulsory">Retailer Type</label></div>
                <select name="data[Retailer][retailer_type]" style="width:168px;margin-top: 6px;height: 25px;border-radius: 5px;background-color: white;">
                    <option value="">Select Type</option>
                    <?php foreach ($imp_data as $key => $value) { ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>

            <div class="fieldDetail">
                <div class="fieldLabel1 leftFloat" style="padding-top: 10px;"><label for="locationTypes" class="compulsory">Location Type</label></div>
                <select name="data[Retailer][location_type]" style="width:168px;margin-top: 6px;height: 25px;border-radius: 5px;background-color: white;">
                    <option value="">Select Type</option>
                    <?php foreach ($location_data as $key => $value) { ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>


            <div class="fieldDetail leftFloat" style="width:350px;">
                <div class="fieldLabel1 leftFloat" style="padding-top: 10px;"><label for="TurnoverTypes" class="compulsory">Turnover Type</label>
                </div>

                <select name="data[Retailer][turnover_type]" style="width:168px;margin-top: 6px;height: 25px;border-radius: 5px;background-color: white;">
                    <option value="">Select Type</option>
                    <?php foreach ($turnover_data as $key => $value) { ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>

            <div class="fieldDetail">
                <div class="fieldLabel1 leftFloat" style="padding-top: 10px;"><label for="shopOwnershipTypes" class="compulsory">Shop Ownership Type
                    </label></div>
                <select name="data[Retailer][ownership_type]" style="width:168px;margin-top: 6px;height: 25px;border-radius: 5px;background-color: white;">
                    <option value="">Select Type</option>
                    <?php foreach ($ownership_data as $key => $value) { ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div> 

            <input type="hidden" name="data[Retailer][rental_flag]" value="1" />
            <div class="field">               		
                <div class="fieldDetail">
                    <div class="fieldLabel1 leftFloat">&nbsp;</div>
                    <div class="fieldLabelSpace1" id="sub_butt" style="padding-top: 20px; ">
<?php echo $ajax->submit('Create Retailer', array('id' => 'sub', 'tabindex' => '3', 'url' => array('controller' => 'shops', 'action' => 'createRetailer'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                    </div>                         
                </div>
                <div class="clearLeft">&nbsp;</div>
            </div>
            <div class="field">    
                <div class="fieldDetail">                         
                    <div>
<?php echo $this->Session->flash(); ?>
                    </div>   
                </div>
            </div>	
            </fieldset>
<?php echo $form->end(); ?>
