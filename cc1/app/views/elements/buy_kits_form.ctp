<script src="//code.jquery.com/jquery-1.10.2.js"></script>

<?php echo $form->create(null, array('url' => array('controller' => 'shops', 'action' => 'buyKits'))); ?>
<?php if($this->data['confirm_flag'] == 1) { unset($this->data); } ?>
<fieldset class="fields1" style="border:0px;margin:0px;">
<?php echo $this->Session->flash(); echo "<br/>"; ?>
    <?php if(!isset($confirm_flag)) { ?>
    <div class="appTitle">Buy Kits</div>
    <div style="width:60%; float:left">
        <div>
                <div>
                    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="service">Select Service</label></div>
                            <div class="fieldLabelSpace1">
                                <select class='form-control fix-width services' tabindex='2' id='shop' name='data[service]' onchange='changeService();'>
                                    <option value='0'>---Select Service---</option>
                                    <?php foreach ($services as $service) { ?>
                                        <option value='<?php echo $service['services']['id']; ?>' <?php if ($this->data['service'] == $service['services']['id']) {
                                    echo "selected";
                                } ?> ><?php echo $service['services']['name']; ?></option>
                            <?php } ?>
                                </select>
                            </div>                     
                        </div>
                    </div>
                </div>
                <div>
                    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="plan">Select Plan</label></div>
                            <div class="fieldLabelSpace1">
                                <select class='form-control fix-width services' tabindex='2' id='plans' name='data[plan]' onchange='changePlanOrKits();'>
                                    <option value=''>---Select Plan---</option>
                                    <?php $planlist = json_decode($serviceplans, 1);
                                    if (array_key_exists($this->data['service'], $planlist)) {
                                        ?>
                                        <?php foreach ($planlist[$this->data['service']] as $key => $plan) { ?>
                                            data += "<option value='<?php echo $plan['id']; ?>' <?php if ($this->data['plan'] == $plan['id']) { echo "selected";
                                } ?> ><?php echo $plan['plan_name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                                </select>
                            </div>                     
                        </div>
                    </div>
                </div>
            <div>
                <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="kits">No of Kits</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex='4' class='form-control fix-width' type='text' id='kits' onkeyup='changePlanOrKits();' name='data[kits]' value='<?php echo $this->data['kits']; ?>' autocomplete='off'/>
                            </div>
                        </div>
                    </div>
                    <input type='hidden' id='setup_amt' name='data[setup_amt]' value='<?php echo $this->data['setup_amt']; ?>' autocomplete='off'/>
                    <input type='hidden' id='is_visible' name='data[is_visible]' value='<?php echo $this->data['is_visible']; ?>' autocomplete='off'/>
            </div>
            <div>
                <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="discount">Discount / Kit</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex='6' class='form-control fix-width' type='text' id='discount_per_kit' onkeyup='changePlanOrKits();' name='data[discount_per_kit]' value='<?php echo $this->data['discount_per_kit']; ?>' autocomplete='off' readonly="readonly"/>
                            </div>
                        </div>
                    </div>
            </div>
            <div>
                    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="amount">Total Amount</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex='7' class='form-control fix-width'  type='text' id='amount' name='data[amount]' value='<?php echo $this->data['amount']; ?>' readonly="readonly" autocomplete='off'/>
                            </div>
                        </div>
                    </div>
                </div>
            
            <div>                
                    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="amount">Note</label></div>
                            <div class="fieldLabelSpace1">
                                <textarea tabindex='8' class='form-control fix-width' id='note' name='data[note]'><?php echo $this->data['note']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
  <?php  } else {  ?>
    <div class="appTitle">Confirm Transfer</div>
    
    <input type="hidden" name="data[distributor]" value="<?php echo $this->data['distributor']; ?>">
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
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="plans">Plan</label></div>
                <div class="fieldLabelSpace1">
                <?php 
                $planlist = json_decode($serviceplans, 1);
                    $pln = array();
                    foreach($planlist[$this->data['service']] as $key => $plan){
                        $pln[$plan['id']] = $plan['plan_name'];
                    }
                    echo $pln[$this->data['plan']];
                ?>
                <input type="hidden" name="data[plan]" value="<?php echo $this->data['plan']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">No of Kits</label></div>
                <div class="fieldLabelSpace1">
                    <?php echo $this->data['kits']; ?>
                    <input type="hidden" name="data[kits]" value="<?php echo $this->data['kits']; ?>">
                    <input type="hidden" name="data[setup_amt]" value="<?php echo $this->data['setup_amt']; ?>">
                    <input type="hidden" name="data[discount_per_kit]" value="<?php echo $this->data['discount_per_kit']; ?>">
                    <input type="hidden" name="data[is_visible]" value="<?php echo $this->data['is_visible']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat"><label for="shop">Amount</label></div>
                <div class="fieldLabelSpace1">
                    <?php echo ($this->data['kits'] * $this->data['setup_amt']) - ($this->data['kits'] * $this->data['discount_per_kit']); ?>
                    <input type="hidden" name="data[amount]" value="<?php echo ($this->data['kits'] * $this->data['setup_amt']) - ($this->data['kits'] * $this->data['discount_per_kit']); ?>">
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
<?php } ?>

        <input type="hidden" name="data[confirm_flag]" value="<?php echo !isset($confirm_flag) ? 0 : 1; ?>">
        
        <div class="field">               		
            <div class="field">               		
                <div class="fieldDetail">
                    <div class="fieldLabel1 leftFloat">&nbsp;</div>
                    <div class="fieldLabelSpace1" id="sub_butt" style="padding-top: 20px; ">
                        <?php $button = !isset($confirm_flag) ? 'Transfer' : 'Confirm'; ?>
                        <button id="tfr_kits sub" type="submit" class="btn btn-primary retailBut"><?php echo $button ?></button>
                        <?php if (isset($confirm_flag)) { ?>
                            <a href="/shops/buyKits" id="backbtn" class="btn btn-primary retailBut">Back</a>
                        <?php } ?>
                    </div>                         
                </div>
                <div class="clearLeft">&nbsp;</div>
            </div>
            <div class="clearLeft">&nbsp;</div>
        </div>
<?php echo $this->Session->flash(); ?>
    </div>

</fieldset>
<?php echo $form->end(); ?>
<script>
    var allplans = JSON.parse('<?php echo $serviceplans; ?>');
    function changeService(){
        jQuery('#plans').html('<option value="">---Select Plan---</option>');
        var selected_service_id = jQuery('.services').val();
        var options = '';
        if(typeof(allplans[selected_service_id]) != "undefined" ){
            jQuery.each(allplans[selected_service_id], function (key, plan)
            {
                if(plan.is_visible != '0'){
                    options += '<option value="'+plan.id+'">'+plan.plan_name+'</option>';
                }
            });
            jQuery('#plans').html(options);
        }   
    }
    function changePlanOrKits(){
        var selected_plan_id = jQuery('#plans').val();
        var no_of_kits = jQuery('#kits').val();
        var selected_service_id = jQuery('.services').val();
        
        if(selected_plan_id && selected_service_id){
            jQuery.each(allplans[selected_service_id], function (key, plan){
                if(selected_plan_id == plan.id){
                    jQuery('#discount_per_kit').val(plan.dist_commission);
                    var discount_per_kit = jQuery('#discount_per_kit').val();
                    jQuery('#is_visible').val(plan.is_visible);
                    jQuery('#setup_amt').val(plan.setup_amt);
                    jQuery('#amount').val((no_of_kits * plan.setup_amt) - (no_of_kits * discount_per_kit));
                }
            });
        }
    }
</script>
<script>jQuery.noConflict();</script>