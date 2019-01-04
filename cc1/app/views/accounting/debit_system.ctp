<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>

<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet"/>

<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">    
  </body>
<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab button:hover {
        background-color: #428bca;
        color: #fff;
    }

    .tab button.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }

    .fix-width {
        width: 300px!important;
    }
    .autocomplete-suggestions {
        border: 1px solid #999;
        background: #fff;
        cursor: default;
        overflow: auto;
    }

    .autocomplete-suggestion {
        padding: 10px 5px;
        font-size: 1.0em;
        white-space: nowrap;    
        overflow: hidden;
    }

    .autocomplete-selected {
        background: #f0f0f0;
    }

    .autocomplete-suggestions strong {
        font-weight: normal;
        color: #3399ff;
    }
</style>
<div class="innerDiv">
    <div class="tab">
        <ul class="nav nav-tabs">
            <li><button class="tablinks" onclick="window.location = '/accounting/txnUpload'">File Upload</button></li>
            <li><button class="tablinks" onclick="window.location = '/accounting/autoUpload'">Txn Entry</button></li>
            <li><button class="tablinks" onclick="window.location = '/accounting/bankTxnListing'">Txn Listing</button></li>
            <li><button class="tablinks dropdown-toggle" onclick="window.location = '/accounting/closingBalanceReport'">Closing Balance Report</button></li>
            <!--<li><button class="tablinks" onclick="window.location='/accounting/limitReconsilationReport'">Limit Reconsilation Report</button></li>-->
            <li><button class="tablinks" onclick="window.location = '/accounting/bankStatements'">Bank Statements</button></li>
            <li><button class="tablinks" onclick="window.location = '/accounting/ledger'">Ledger</button></li>
            <li><button class="tablinks active" onclick="window.location = '/accounting/debitSystem'">Debit System</button></li>
        </ul>
    </div>
    <br/><br/>

    <?php echo $form->create(null, array('url' => array('controller' => 'accounting', 'action' => 'debitSystem')));
    if ($this->data['confirm_flag'] == 1) {
        unset($this->data);
    } ?>
    <fieldset class="fields1" style="border:0px;margin:0px;">

            <?php if (!isset($confirm_flag)) { ?>
            <div class="appTitle">Debit System</div>
                <?php $message = $this->Session->flash(); ?>
                <?php if (!empty($message) && preg_match('/Errors/', $message)): ?>
                <div class="alert alert-danger">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($message) && preg_match('/Success/', $message)): ?>
                <div class="alert alert-success">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
            <div class="submit rightFloat" ><a href="/accounting/refund"><input class="retailBut enabledBut btn btn-primary" style="padding: 0px 5px 3px;" value="Refund" type="button"></a></div>
            <div>
                <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                        <div class="fieldLabel1 leftFloat" style="float:left; margin-top: 5px; width: 135px;"><label for="shop">Select Debit Type</label></div>
                        <div  class="fieldLabelSpace1">
                            <select class="form-control fix-width debittype" tabindex="2" id="shop" name="data[shop]" onchange="typeRetrieve(this.value);">
                                <option value="0" <?php if ($this->data['shop'] == 0) {  echo "selected";} ?> >Select Type</option>
                                <option value="1" <?php //if($this->data['shop'] == 1) { echo "selected"; }  ?> >Kit Charge</option>
                                <option value="2" <?php //if($this->data['shop'] == 2) { echo "selected"; }  ?> >Security Deposit</option>
                                <option value="3" <?php //if($this->data['shop'] == 3) { echo "selected"; }  ?> >One Time Charge/Booster Pack</option>
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
                            <?php $debit_type = array(1 => 'Kit Charge', 2 => 'Security Deposit', 3 => 'One Time Charge'); ?>
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
                            <?php echo $distributors_data[$this->data['distributor']]['imp']['shop_est_name'] . " - " . $this->data['distributor']; ?>
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
                            foreach ($services as $service) {
                                $ser[$service['services']['id']] = $service['services']['name'];
                            }
                            echo $ser[$this->data['service']];?>
                            <input type="hidden" name="data[service]" value="<?php echo $this->data['service']; ?>">
                        </div>
                    </div>
                </div>
            </div>
                 <?php if ($this->data['shop'] == 1) { ?>
                 <div>
                <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="plans">Plan</label></div>
                        <div class="fieldLabelSpace1">
                            <?php
                            $planlist = json_decode($serviceplans, 1);
                            $pln = array();
                            foreach ($planlist[$this->data['service']] as $key => $plan) {
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
                 <?php } ?>

            <div>
                <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="shop">Amount</label></div>
                        <div class="fieldLabelSpace1">
                            <?php echo $this->data['shop'] == 1 ? (($this->data['kits'] * $this->data['setup_amt']) - ($this->data['kits'] * $this->data['discount_per_kit'])) : $this->data['amount']; ?>
                            <input type="hidden" name="data[amount]" value="<?php echo $this->data['shop'] == 1 ? (($this->data['kits'] * $this->data['setup_amt']) - ($this->data['kits'] * $this->data['discount_per_kit'])) : $this->data['amount']; ?>">
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
        <input type="hidden" name="data[confirm_flag]" value="<?php echo!isset($confirm_flag) ? 0 : 1; ?>">
        <div class="field" style="padding-top:15px;">               		
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat">&nbsp;</div>
                <div class="fieldLabelSpace1" id="sub_butt">
                    <?php $button = !isset($confirm_flag) ? 'Transfer' : 'Confirm'; ?>
                    <button id="tfr_kits" type="submit" class="btn btn-primary"><?php echo $button ?></button>
                    <?php if (isset($confirm_flag)) { ?>
                        <a href="/accounting/debitSystem" id="backbtn" class="btn btn-primary">Back</a>
                    <?php } ?>
                </div>                         
            </div>
        </div>

    </fieldset>
<?php echo $form->end(); ?>

    <script>

<?php
if (!empty($message) && preg_match('/Success/', $message)) {
    ?>
            setTimeout(function() {
                window.location = "/accounting/debitSystem";
            }, 2000);
    <?php
}
?>

<?php if (isset($this->data)) { ?>
            typeRetrieve(<?php echo $this->data['shop']; ?>);
    <?php if ($this->data['shop'] == 1) { ?>
                changePlanOrKits();
    <?php } ?>
<?php } ?>

        function typeRetrieve(val) {

            var data = "";

            if (val != 0) {

                data = "<div class='altRow'>";
                data += "<div class='field'>";
                data += "<div class='fieldDetail' style='width:350px;'>";
                data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='shop'>Distributor</label></div>";
                data += "<div class='fieldLabelSpace1'>";
                data += '<input type="text" class="form-control autocomplete" style="width:300px;" id="distributor" placeholder="Search by ID / Name / Mobile" onkeyup="showResult(this.value)"autocomplete="off" /><div id="livesearch"></div>';
                data += '<input type="hidden" class="form-control autocomplete" id="distributor-id" name="data[distributor]">';
                data += '<div id="countryList"></div>';
                data += "</div>";
                data += "</div>";
                data += "</div>";
                data += "</div>";

                data += "<div>";
                data += "<div class='field'>";
                data += "<div class='fieldDetail' style='width:350px;'>";
                data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='shop'>Select Service</label></div>";
                data += "<div class='fieldLabelSpace1'>";
                data += "<select class='form-control fix-width services' tabindex='2' id='shop' name='data[service]' onchange='changeService();'>";
                data += "<option value='0'>---Select Service---</option>";
                <?php foreach ($services as $service) { ?>
                                    data += "<option value='<?php echo $service['services']['id']; ?>' <?php if ($this->data['service'] == $service['services']['id']) {
                        echo "selected";
                    } ?> ><?php echo $service['services']['name']; ?></option>";
                <?php } ?>
                data += "</select>";
                data += "</div>";
                data += "</div>";
                data += "</div>";
                data += "</div>";

                if (val == 1) {

                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='shop'>Select Plan</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<select class='form-control fix-width services' tabindex='2' id='plans' name='data[plan]' onchange='changePlanOrKits();'>";
                    data += "<option value=''>---Select Plan---</option>";
                    <?php $planlist = json_decode($serviceplans, 1);
                    if (array_key_exists($this->data['service'], $planlist)) {
                        ?>
                        <?php foreach ($planlist[$this->data['service']] as $key => $plan) { ?>
                                                data += "<option value='<?php echo $plan['id']; ?>' <?php if ($this->data['plan'] == $plan['id']) {
                                echo "selected";
                            } ?> ><?php echo $plan['plan_name']; ?></option>";
                        <?php } ?>
                    <?php } ?>
                    data += "</select>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";

                    data += "<div class='altRow'>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='kit'>No of Kits</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<input tabindex='4' class='form-control fix-width' type='text' id='kits' onkeyup='changePlanOrKits();' name='data[kits]' value='<?php echo $this->data['kits']; ?>' autocomplete='off'/>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";

                    data += "<div>";
                    data += "<div class='altRow'>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='discount'>Discount / Kit</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<input tabindex='6' class='form-control fix-width discount' type='text' id='discount_per_kit' onkeyup='changePlanOrKits(\"discount_per_kit\");' name='data[discount_per_kit]' value='<?php echo $this->data['discount_per_kit']; ?>' autocomplete='off'/>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "<input type='hidden' id='setup_amt' name='data[setup_amt]' value='<?php echo $this->data['setup_amt']; ?>' autocomplete='off'/>";
                    data += "<input type='hidden' id='is_visible' name='data[is_visible]' value='<?php echo $this->data['is_visible']; ?>' autocomplete='off'/>";
                }

                if (val == 1) {
                    var readonly = "readonly";
                }
                data += "<div>";
                data += "<div class='field'>";
                data += "<div class='fieldDetail' style='width:350px;'>";
                data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='amount'>Total Amount </label></div>";
                data += "<div class='fieldLabelSpace1'>";
                data += "<input tabindex='7' class='form-control fix-width'  type='text' id='amount' name='data[amount]' value='<?php echo $this->data['amount']; ?>' " + readonly + " autocomplete='off'/>";
                data += "</div>";
                data += "</div>";
                data += "</div>";
                data += "</div>";

                data += "<div>";
                data += "<div class='altRow'>";
                data += "<div class='field'>";
                data += "<div class='fieldDetail' style='width:350px;'>";
                data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='note'>Note</label></div>";
                data += "<div class='fieldLabelSpace1'>";
                data += "<textarea tabindex='8' class='form-control fix-width' id='note' name='data[note]'><?php echo $this->data['note']; ?></textarea>";
                data += "</div>";
                data += "</div>";
                data += "</div>";
                data += "</div>";
                data += "</div>";
            }

            $('ds').innerHTML = data;
        }

        var allplans = JSON.parse('<?php echo $serviceplans; ?>');
        function changeService() {
            jQuery('#plans').html('<option value="">---Select Plan---</option>');
            var selected_service_id = jQuery('.services').val();
            var options = '';
            if (typeof (allplans[selected_service_id]) != "undefined") {
                jQuery.each(allplans[selected_service_id], function(key, plan)
                {
                    if(plan.is_visible != '0'){
                        options += '<option value="' + plan.id + '">' + plan.plan_name + '</option>';
                    }
                });
                jQuery('#plans').html(options);
            }
            changePlanOrKits();
        }
        window.onclick = function() {
            clearOptions();
        }

        function showResult(str){
            if(str == ''){
                clearOptions();
            }
            var list = "";
            if (str.length > 1) {
                jQuery('#livesearch').html("Loading ...");
                jQuery('#livesearch').css({'border':'1px solid #A5ACB2','width':'50px'});
                jQuery.ajax({
                            type : 'POST',
                            url : '/accounting/distList',
                            dataType : "json",
                            data : {
                                search : str
                            },
                            success: function(data){
                                jQuery.each(data, function(k, v) {
                                    list +=  "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ v.id +",\""+ (v.name).replace("'", "") +"\");'>"+ (v.name).replace("'", "") +"</a></div>"; 
                                });
                                jQuery('#livesearch').html(list);
                                jQuery('#livesearch').css({'width':'500px'});
                            }
                });
            }
        }
        
        function selectType (id, name) {
            jQuery('#distributor-id').val(id);
            jQuery('#distributor').val(name);

            clearOptions();
        }
        function clearOptions () {
            jQuery('#livesearch').html('');
            jQuery('#livesearch').css({'border':'0px'});
        }

        function changePlanOrKits(mode) {
            var selected_plan_id = jQuery('#plans').val();
            var no_of_kits = jQuery('#kits').val();
            var selected_service_id = jQuery('.services').val();

            if (selected_plan_id && selected_service_id) {
                jQuery.each(allplans[selected_service_id], function(key, plan) {
                    if (selected_plan_id == plan.id) {
                        if(!mode){
                            jQuery("#discount_per_kit").val(plan.dist_commission);
                        }
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
</div>
