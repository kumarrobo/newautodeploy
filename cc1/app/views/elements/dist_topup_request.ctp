<?php
if(isset($msg)) {
    echo $msg;
} else {
?>
<div id="pay1_top_request">
<form id="shopDistTopUpRequestForm" enctype="multipart/form-data" method="post" action="/apis/sendTopUpRequest" accept-charset="utf-8">
<fieldset style="padding:0px;border:0px;margin:0px;">
   
    <div class="field" style="padding-top:5px;">
        <div class="fieldDetail leftFloat" style="width:350px;">
            <div class="fieldLabel1 leftFloat"><label for="bank_acc_id" class="compulsory">Bank Acc No. : </label></div>
            <div class="fieldLabelSpace1">
                <input  tabindex="2" type="hidden" id="mobile" name="mobile" value ="<?php echo $mobile;?>"/>
                 
                 
                 <select id="bank_acc_id" name="bank_acc_id" title="Pay1 bank account no in which amount is deposited .">
                    <option value="">-- Select Bank --</option>
                    
                     <?php foreach($banks as $bval){ ?>
                     <option value ="<?php echo $bval['bank_details']['bank_name']; ?>"><?php echo $bval['bank_details']['bank_name']; ?></option>
                     <?php } ?>
                </select>
            </div>                     
        </div> 
        <div class="fieldDetail leftFloat" style="margin-left: 20px;width:350px;">
            <div class="fieldLabel1 leftFloat"><label for="amount" class="compulsory">Amount :</label></div>
            <div class="fieldLabelSpace1">
                <input  tabindex="2" type="text" id="mobile" name="amount" value =""/>
            </div>                     
        </div> 
        <div class="clearLeft">&nbsp;</div>

    </div>
    <div class="field" style="padding-top:5px;">
        <div class="fieldDetail leftFloat" style="width:350px;">
            <div class="fieldLabel1 leftFloat"><label for="trans_type_id" class="compulsory">Transfer Type</label></div>
            <div class="fieldLabelSpace1">
                <select id="trans_type_id" name="trans_type_id">
                    <option value="">-- Select Type --</option>
                    <option value="NEFT-RTGS">NEFT/RTGS</option>
                    <option value="ATM-Transfer">ATM-Transfer</option>
                    <option value="CASH">CASH</option>
                    <option value="Cheque">Cheque</option>
                </select>
            </div>                  
        </div> 
        <div class="fieldDetail leftFloat" style="margin-left: 20px;width:350px;">
            <div class="fieldLabel1 leftFloat"><label for="mobile" >Bank Trans ID</label></div>
            <div class="fieldLabelSpace1">
                <input title="Bank transaciont id ." tabindex="2" type="text" id="bank_trans_id" name="bank_trans_id" value =""/>
            </div>                     
        </div>
        <div class="clearLeft">&nbsp;</div>
    </div>
    <div class="field" style="padding-top:5px;">
        <div class="fieldDetail leftFloat" style="width:350px;">
            <div class="fieldLabel1 leftFloat"><label for="branch_name" >Branch Name</label></div>
            <div class="fieldLabelSpace1">
                <input title="Branch Name" tabindex="2" type="text" id="branch_name" name="branch_name" value =""/>
            </div>                     
        </div> 
        <div class="fieldDetail leftFloat" style="margin-left: 20px;width:350px;">
            <div class="fieldLabel1 leftFloat"><label for="branch_code" >Branch Code</label></div>
            <div class="fieldLabelSpace1">
                <input title="Branch Code" tabindex="2" type="text" id="branch_code" name="branch_code" value =""/>
            </div>                     
        </div> 
        <div class="clearLeft">&nbsp;</div>
    </div>
    <div class="field" style="padding-top:5px;">
        <div class="fieldDetail leftFloat" style="width:350px;">
            <div class="fieldLabel1 leftFloat"><label for="bank_slip" >Bank Slip Image</label></div>
            <div class="fieldLabelSpace1" style="width: 100%">
                <input type="file" id="bank_slip" name="bank_slip" /><div style="color: red; margin-top: 5px;">* Image size should not be more than 5 MB</div>
            </div>
        </div> 
        <div class="clearLeft">&nbsp;</div>
    </div>
    
    <div class="field">               		
        <div class="fieldDetail">
            <div class="fieldLabel1 ">&nbsp;</div> 
            <div class="fieldLabelSpace1" id="sub_butt">
                <div class="submit">
                    <input id="send_top_up_req" class="retailBut enabledBut" value="Send" type="submit">
                </div>
            </div>                         
        </div>
    </div>
</fieldset>
</form>
</div>
<?php } ?>