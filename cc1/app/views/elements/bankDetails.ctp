<form class="field" style="border:0px;margin:0px;width:1070px">
    <div class="appTitle alignCenter">Bank Details</div>
    
            <?php if(!empty($bankdetails)):?>
                <?php foreach ($bankdetails as $details):?>
                    
                    <fieldset style="width:500px; float: left;border:1px solid grey;padding: 5px;">
                        
                        <div>
                            <div class="field LabelSpace1 alignCenter" style="background-color: #efefef;margin: 5px">
                            <p><b><?php echo $details['bank_details']['bank'];?></b></p>
                            </div>                     
                        </div>
                        
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="accountname">Account Name : </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo $details['bank_details']['account_name'];?></p>
                            </div>                     
                        </div>
                        
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="accountno">A/c No : </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo $details['bank_details']['account_no'];?></p>
                            </div>                     
                        </div>
                        
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="ifsc"> IFSC Code : </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo $details['bank_details']['ifsc'];?></p>
                            </div>                     
                        </div>
                        
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="branch">Branch : </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo $details['bank_details']['branch'];?></p>
                            </div>                     
                        </div>
                       
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="accounttype">Type of A/c : </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo $details['bank_details']['account_type'];?></p>
                            </div>
                        </div>
                        
                    </fieldset>
                <?php endforeach;?>                   
            <?php endif;?>
       
</form>

