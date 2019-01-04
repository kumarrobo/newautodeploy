<?php echo $form->create('shop'); if($this->data['confirm_flag'] == 1) { unset($this->data); } ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
    <?php if(!isset($confirm_flag)) { ?>
    <div class="appTitle">Debit System</div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Select Debit Type</label></div>
                <div class="fieldLabelSpace1">
                    <select tabindex="2" id="shop" name="data[shop]" onchange="typeRetrieve(this.value);">
                        <option value="0" <?php if($this->data['shop'] == 0) { echo "selected"; } ?> >Select Type</option>
                        <option value="1" <?php if($this->data['shop'] == 1) { echo "selected"; } ?> >Kit Charge</option>
                        <option value="2" <?php if($this->data['shop'] == 2) { echo "selected"; } ?> >Security Deposit</option>
                        <option value="3" <?php if($this->data['shop'] == 3) { echo "selected"; } ?> >One Time Charge/Booster Pack</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="ds">
    </div>
    <?php } else { ?>
    <div class="appTitle">Confirm Transfer</div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Debit Type</label></div>
                <div class="fieldLabelSpace1">
                    <?php $debit_type = array(1=>'Kit Charge', 2=>'Security Deposit',3=>'One Time Charge'); ?>
                    <?php echo $debit_type[$this->data['shop']]; ?>
                    <input type="hidden" name="data[shop]" value="<?php echo $this->data['shop']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:500px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Distributors</label></div>
                <div class="fieldLabelSpace1">
                    <?php echo $distributors[$this->data['distributor']]['Distributor']['company'] . " - " . $this->data['distributor']; ?>
                    <input type="hidden" name="data[distributor]" value="<?php echo $this->data['distributor']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Service</label></div>
                <div class="fieldLabelSpace1">
                <?php
                    $ser = array();
                    foreach($services as $service) {
                        $ser[$service['services']['id']] = $service['services']['name'];
                    }
                    echo $ser[$this->data['service']];
                ?>
                <input type="hidden" name="data[service]" value="<?php echo $this->data['service']; ?>">
                </div>
            </div>
        </div>
    </div>
    <?php if($this->data['shop'] == 1) { ?>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">No of Kits</label></div>
                <div class="fieldLabelSpace1">
                    <?php echo $this->data['kits']; ?>
                    <input type="hidden" name="data[kits]" value="<?php echo $this->data['kits']; ?>">
                    <input type="hidden" name="data[per_kit]" value="<?php echo $this->data['per_kit']; ?>">
                    <input type="hidden" name="data[discount_per_kit]" value="<?php echo $this->data['discount_per_kit']; ?>">
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Amount</label></div>
                <div class="fieldLabelSpace1">
                    <?php echo $this->data['shop'] == 1 ? $this->data['kits'] * ($this->data['per_kit'] - $this->data['discount_per_kit']) : $this->data['amount']; ?>
                    <input type="hidden" name="data[amount]" value="<?php echo $this->data['shop'] == 1 ? $this->data['kits'] * ($this->data['per_kit'] - $this->data['discount_per_kit']) : $this->data['amount']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:600px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Note</label></div>
                <div class="fieldLabelSpace1">
                    <?php echo $this->data['note']; ?>
                    <input type="hidden" name="data[note]" value="<?php echo $this->data['note']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:600px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Password</label></div>
                <div class="fieldLabelSpace1">
                    <input type="password" name="data[password]">
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <input type="hidden" name="data[confirm_flag]" value="<?php echo !isset($confirm_flag) ? 0 : 1; ?>">
    <div class="field" style="padding-top:15px;">               		
        <div class="fieldDetail" style="width:350px;">
            <div class="fieldLabel1 leftFloat">&nbsp;</div>
            <div class="fieldLabelSpace1" id="sub_butt">
                <?php $button = !isset($confirm_flag) ? 'Transfer Kits' : 'Confirm'; ?>
                <?php echo $ajax->submit($button, array('id' => 'sub', 'tabindex' => '7', 'url' => array('controller' => 'shops', 'action' => 'debitSystem'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
            </div>                         
        </div>
    </div>
    <?php echo $this->Session->flash(); ?>
</fieldset>
<?php echo $form->end(); ?>

<script>
    
    <?php if (isset($this->data)) { ?>
            typeRetrieve(<?php echo $this->data['shop']; ?>);
            <?php if($this->data['shop'] == 1) { ?>
                calculateAmount();
            <?php } ?>
    <?php } ?>
    
    function typeRetrieve(val) {
        
            var data = "";
            
            if(val != 0) {
                    data = "<div class='altRow'>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'><label for='shop'>Select Distributor</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<select tabindex='2' id='distributor' name='data[distributor]'>";
                    data += "<option value='0'>---Select Distributor---</option>";
                    <?php foreach ($distributors as $distributor) { ?>
                    data += "<option value='<?php echo $distributor['Distributor']['id']; ?>' <?php if($this->data['distributor'] == $distributor['Distributor']['id']) { echo "selected"; } ?> ><?php echo $distributor['Distributor']['company'] . ' - ' . $distributor['Distributor']['id']; ?></option>";
                    <?php } ?>
                    data += "</select>"; 
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";

                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'><label for='shop'>Select Service</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<select tabindex='3' id='service' name='data[service]'>";
                    data += "<option value='0'>---Select Service---</option>";
                    <?php foreach ($services as $service) { ?>
                    data += "<option value='<?php echo $service['services']['id']; ?>' <?php if($this->data['service'] == $service['services']['id']) { echo "selected"; } ?> ><?php echo $service['services']['name']; ?></option>";
                    <?php } ?>
                    data += "</select>"; 
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";

                    if (val == 1) {
                            data += "<div class='altRow'>";
                            data += "<div class='field'>";
                            data += "<div class='fieldDetail' style='width:350px;'>";
                            data += "<div class='fieldLabel1 leftFloat'><label for='kit'>No of Kits</label></div>";
                            data += "<div class='fieldLabelSpace1'>";
                            data += "<input tabindex='4' type='text' id='kits' onchange='calculateAmount();' name='data[kits]' value='<?php echo $this->data['kits']; ?>' autocomplete='off'/>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";

                            data += "<div>";
                            data += "<div class='field'>";
                            data += "<div class='fieldDetail' style='width:350px;'>";
                            data += "<div class='fieldLabel1 leftFloat'><label for='kit'>Price / Kit</label></div>";
                            data += "<div class='fieldLabelSpace1'>";
                            data += "<input tabindex='5' type='text' id='per_kit' onchange='calculateAmount();' name='data[per_kit]' value='<?php echo $this->data['per_kit']; ?>' autocomplete='off'/>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";

                            data += "<div>";
                            data += "<div class='field'>";
                            data += "<div class='fieldDetail' style='width:350px;'>";
                            data += "<div class='fieldLabel1 leftFloat'><label for='kit'>Discount / Kit</label></div>";
                            data += "<div class='fieldLabelSpace1'>";
                            data += "<input tabindex='6' type='text' id='discount_per_kit' onchange='calculateAmount();' name='data[discount_per_kit]' value='<?php echo $this->data['discount_per_kit']; ?>' autocomplete='off'/>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                    }

                    if(val == 1) { var readonly = "readonly"; }
                    data += "<div class='altRow'>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'><label for='kit'>Total Amount</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<input tabindex='7' type='text' id='amount' name='data[amount]' value='<?php echo $this->data['amount']; ?>' "+readonly+" autocomplete='off'/>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    
                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'><label for='kit'>Note</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<textarea tabindex='8' id='note' name='data[note]'><?php echo $this->data['note']; ?></textarea>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
            }
            
            $('ds').innerHTML = data;
    }
    
    function calculateAmount() {
            var kits = $('kits').value;
            var per_kit = $('per_kit').value;
            var discount_per_kit = $('discount_per_kit').value;
            
            $('amount').value = kits * (per_kit - discount_per_kit);
    }
</script>