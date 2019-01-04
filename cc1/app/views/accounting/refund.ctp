<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style> 
     .fix-width {
        width: 300px!important;
    }
    
</style>
<div id='innerDiv'>
<?php echo $form->create(null, array('url' => array('controller' => 'accounting', 'action' => 'refund')));
?>
<fieldset class="fields1" style="border:0px;margin:0px;">
   
    <div class="appTitle">Refund System</div>
    <?php echo $this->Session->flash(); ?>
        <div class="submit rightFloat" ><a href="/accounting/debitSystem"><input class="retailBut enabledBut btn btn-primary" style="padding: 0px 5px 3px;" value="Back" type="button"></a></div>
    <div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat" style="float:left; margin-top: 5px; width: 135px;"><label for="shop">Select Refund Type</label></div>
                <div  class="fieldLabelSpace1">
                    <select class="form-control fix-width" tabindex="2" id="shop" name="data[shop]" onchange="typeRetrieve(this.value);">
                        <option value="0" <?php if($this->data['shop'] == 0) { echo "selected"; } ?> >Select Type</option>
                        <option value="1" <?php //if($this->data['shop'] == 1) { echo "selected"; } ?> >Kit Refund</option>
                        <option value="2" <?php //if($this->data['shop'] == 2) { echo "selected"; } ?> >Security Deposit Refund</option>
                        <option value="3" <?php //if($this->data['shop'] == 3) { echo "selected"; } ?> >One Time Charge/Booster Pack Refund</option>
                    </select>
         
                </div>
                
            </div>
        </div>
    </div>
    <div id="ds">
    </div>

    <input type="hidden" name="data[confirm_flag]" value="<?php echo !isset($confirm_flag) ? 0 : 1; ?>">
    <div class="field " style="padding-top:15px;">
        <div class="fieldDetail" style="width:350px;">
            <div class="fieldLabel1 leftFloat">&nbsp;</div>
            <div class="fieldLabelSpace1" id="sub_butt">
                <button id="refund_kits" type="submit" class="btn btn-primary">Refund</button>
                <?php // echo $ajax->submit('Refund', array('url' => array('controller' => 'accounting', 'action' => 'refund'), 'class' => 'btn btn-primary enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
            </div>                         
        </div>
    </div>
   
</fieldset>
<?php echo $form->end(); ?>
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>jQuery.noConflict();</script>
<script>

    
    function typeRetrieve(val) {
        
            var data = "";
            
            if(val != 0) {

                    data = "<div class='altRow'>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail ' style='width:350px;'>";
                    data +="<div class='fieldLabel1 leftFloat' style='float: left; width:135px; margin-top: 5px; '><label for='shop'>Select Distributor</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += '<input type="text" class="form-control autocomplete" style="width:300px;" id="distributor" placeholder="Search by ID / Name / Mobile" onkeyup="showResult(this.value)"autocomplete="off" /><div id="livesearch"></div>';
                    data += '<input type="hidden" class="form-control autocomplete" id="distributor-id" name="data[distributor]">';
                    data += '<div id="countryList"></div>';
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";

                    if (val == 1) {
                            
                            data += "<div>";
                            data += "<div class='field'>";
                            data += "<div class='fieldDetail' style='width:350px;'>";
                            data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='shop'>Select Service</label></div>";
                            data += "<div class='fieldLabelSpace1'>";
                            data += "<select class='form-control fix-width' tabindex='3' id='service' name='data[service]' onchange='changeService();changePlanOrKits();'>";
                            data += "<option value='0'>---Select Service---</option>";
                            <?php foreach ($services as $service) { ?>
                            data += "<option value='<?php echo $service['services']['id']; ?>' <?php if($this->data['service'] == $service['services']['id']) { echo "selected"; } ?> ><?php echo $service['services']['name']; ?></option>";
                            <?php } ?>
                            data += "</select>"; 
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                            
                            data += "<div>";
                            data += "<div class='field'>";
                            data += "<div class='fieldDetail' style='width:350px;'>";
                            data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='shop'>Select Plan</label></div>";
                            data += "<div class='fieldLabelSpace1'>";
                            data += "<select class='form-control fix-width services' tabindex='2' id='plans' name='data[plan]' onchange='changePlanOrKits();'>";
                            data += "<option value=''>---Select Plan---</option>";
                            <?php $planlist = json_decode($serviceplans, 1);
                            if(array_key_exists($this->data['service'], $planlist)){ ?>
                                <?php foreach($planlist[$this->data['service']] as $key => $plan){  ?>
                                    data += "<option value='<?php echo $plan['id']; ?>' <?php if($this->data['plan'] == $plan['id']) { echo "selected"; } ?> ><?php echo $plan['plan_name']; ?></option>";
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
                            data += "<input class='form-control fix-width' tabindex='4' type='text' id='kits' name='data[kits]' onkeyup='changePlanOrKits();' autocomplete='off'/>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                            data += "</div>";
                    }

                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='kit'>Total Amount</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<input class='form-control fix-width' tabindex='7' type='text' id='amount' name='data[amount]' autocomplete='off'/>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    
                    
                    data += "<div>";
                    data += "<div class='altRow'>";
                    data += "<div class='field'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='note'>Note</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<textarea tabindex='8' class='form-control fix-width' id='note' name='data[note]'><?php echo $this->data['note']; ?></textarea>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
            }
            
            $('ds').innerHTML = data;


    }
    var allplans = JSON.parse('<?php echo $serviceplans; ?>');
    function changeService(){
        jQuery('#plans').html('<option value="">---Select Plan---</option>');
        var selected_service_id = jQuery('#service').val();
        var plan = jQuery('#plans').val();
        var options = '';
        if(typeof(allplans[selected_service_id]) != "undefined" ){
            jQuery.each(allplans[selected_service_id], function (key, plan)
            {
                options += '<option value="'+plan.id+'">'+plan.plan_name+'</option>';
            });
            jQuery('#plans').html(options);
        }   
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
                                    list +=  "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ v.id +",\""+ v.name +"\");'>"+ v.name +"</a></div>"; 
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
    
    function changePlanOrKits(){
        var selected_service_id = jQuery('#service').val();
        var selected_plan_id = jQuery('#plans').val();
        
        if(typeof(allplans[selected_service_id] != "undefined")){
            jQuery.each(allplans[selected_service_id], function(key, plan){
                if(selected_plan_id && (plan.id == selected_plan_id)){
                    jQuery('#amount').val(plan.setup_amt);
                }
            });
        } 
    }
        
</script>
<script>jQuery.noConflict();</script>
</div>
