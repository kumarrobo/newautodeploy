<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>

<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> 

<div id='innerDiv'>
<?php echo $form->create(null, array('url' => array('controller' => 'accounting', 'action' => 'distBankMapping')));
?>
<fieldset class="fields1" style="border:0px;margin:0px;">
    <div class="appTitle">Distributor-Bank Card Mapping</div>
    <?php echo $this->Session->flash(); ?>
<div>
        <div class="field">
            <div class="fieldDetail" style="width:350px;">
                <div class="fieldLabel1 leftFloat" style="float:left; margin-top: 5px; width: 135px;"><label for="distributor">Distributors</label></div>
                <div  class="fieldLabelSpace1">
                    <input type="text" class="form-control autocomplete" style="width:300px;" id="distributor" placeholder="Search by ID / Company Name" onkeyup="showResult(this.value)" autocomplete="off" onchange="typeRetrieve(this.value);changeCardNo();" /><div id="livesearch"></div>
                    <input type="hidden" class="form-control autocomplete" id="distributor_id" name="data[distributor]">
                </div>
                
            </div>
        </div>
    <div id="ds">
    </div>
    </div>
    <div class="field " style="padding-top:15px;">
        <div class="fieldDetail" style="width:350px;">
            <div class="fieldLabel1 leftFloat">&nbsp;</div>
            <div class="fieldLabelSpace1" id="sub_butt">
                <button id="submit" type="submit" class="btn btn-primary">Submit</button>
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
    
    $("#alert").fadeOut(8000);
    function typeRetrieve(val) {
        var data = "";
        
        if(val != 0) {
                    
                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='distributor'>Contact Number</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<input class='form-control fix-width' tabindex='7' type='text' id='contact' name='data[contact]' readonly='readonly' autocomplete='off' style='width: 300px;'/>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    
                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='distributor'>Bank Name</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<select class='form-control fix-width' tabindex='3' id='bank' name='data[bank]'style='width: 300px;' onchange='changeCardNo();'>";
                    data += "<option value='0'>---Select Bank---</option>";
                    <?php
                    foreach ($bank_details as $bank_detail) { ?>
                    data += "<option value='<?php echo $bank_detail['bank_details']['id']; ?>' <?php if($this->data['bank_details'] == $bank_detail['bank_details']['id']) { echo "selected"; } ?> ><?php echo $bank_detail['bank_details']['bank_name']; ?></option>";
                    <?php } ?>
                    data += "</select>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    
                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='distributor'>Card Number</label></div>";
                    data += "<div class='fieldLabelSpace1'>";
                    data += "<input class='form-control fix-width' tabindex='7' type='text' id='card' name='data[card]' autocomplete='off' style='width: 300px;'/>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    
                    data += "<div>";
                    data += "<div class='field'>";
                    data += "<div class='fieldDetail' style='width:350px;'>";
                    data += "<div class='fieldLabel1 leftFloat'  style='float:left; margin-top: 5px; width: 135px;'><label for='distributor'>Type </label></div>";
                    data += "<div class='fieldLabelSpace1 custom-control form-check'>";
                    data += "<input class='form-check-input' type='radio' name='data[optradio]' id='optradio1' value='1' ><label class='form-check-label'>Auto</label> <input class='form-check-input' type='radio' name='data[optradio]' id='optradio2' value='0' style = 'margin-left:35px;'><label class='form-check-label'>Manual</label>"
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                    data += "</div>";
                        }
                        
                 jQuery('#ds').html(data);
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
                            url : '/accounting/distributorList',
                            dataType : "json",
                            data : {
                                search : str
                            },
                            success: function(data){
                                jQuery.each(data, function(k, v) {
                                    list +=  "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ v.id +",\""+ v.name +"\",\""+ v.dist_name +"\",\""+ v.mobile +"\");'>"+ v.name +"</a></div>"; 
                                });
                                jQuery('#livesearch').html(list);
                                jQuery('#livesearch').css({'width':'500px'});
                            }
                });
            }
        }
          function selectType (id, name,dist_name,mobile) {
            jQuery('#distributor_id').val(id);
            jQuery('#distributor').val(name);
            jQuery('#name').val(dist_name);
            jQuery('#contact').val(mobile);

        }
        function clearOptions () {
            jQuery('#livesearch').html('');
            jQuery('#livesearch').css({'border':'0px'});
        }
        
        function changeCardNo() {
            var bank = jQuery('#bank').val();
            var dist = jQuery('#distributor_id').val();
            jQuery.ajax({
                            type : 'POST',
                            url : '/accounting/banklist',
                            dataType : "json",
                            data : {
                                'bank_id' :bank,
                                'dist_id' :dist
                            },
                            success: function(response){
                                jQuery('#card').val(response.card_no);
                                if(response.type==1){
                                    jQuery("#optradio1").prop("checked", true);
                                }else{
                                    jQuery("#optradio2").prop("checked", true);
                                }
                            }
                        });

        }
        
    
        </script>
</div>