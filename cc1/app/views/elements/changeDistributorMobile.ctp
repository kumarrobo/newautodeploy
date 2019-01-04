

<?php  
       // echo '<pre>'; print_r($response); die;
       // echo '<pre>'; print_r($data); die;
       // echo '<pre>'; print_r($otp_flag); die; 
?>

    <form role="form"  method="post" >
        
        <fieldset class="fields">
            
            <div class="title3" style="color:blue;" > 
                
                Distributor Mobile Number Change
            
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                
                <?php if(isset($response['success'])) { ?> 
                
                   <small style="color:blue;"> <?php echo $response['success']['msg'];  ?> </small> 
                    
                <?php }else if(isset($response['errors'])){ ?>
                
                   <small style="color:red;"> <?php echo $response['errors']['msg'];?> </small>
                   
                <?php }else if(isset($verify_response)){ ?> 
                   
                   <small style="color:red;"> <?php echo $verify_response['errors']['msg'];?> </small>
                   
                <?php } ?>   
                
            </div>
            
            
            <?php if(($data) && !isset($response)) { ?> 
            
            <div class="field" style="padding-top:10px;">
                    
                <div class="fieldDetail">
                        
                    <div class="fieldLabel leftFloat"><label for="otp"> OTP </label></div>
                    <div class="fieldLabelSpace">
                        <input tabindex="4" type="text" id="otp" maxlength="6" name="otp" autocomplete="off" />
                    </div>                     
                    
                </div>
            </div>
            
             <div class="field">
                
                <div class="fieldDetail">
                    
                    <!--<div class="fieldLabel leftFloat"><label for="old_mobile"> Old Mobile Number </label></div>-->
                     
                        <div class="fieldLabelSpace">
                            <input tabindex="5" type="hidden" id="old_mob_num" name="old_mob_num" value="<?php if(isset($data))echo $data['old_mob_num']; ?>" />
                        </div>                     
                </div>
            </div>
            
            <div class="field">
                
                <div class="fieldDetail">
                    
                    <!--<div class="fieldLabel leftFloat"><label for="new_mobile"> New Mobile Number </label></div>-->
                     
                        <div class="fieldLabelSpace">
                            <input tabindex="5" type="hidden" id="new_mob_num" name="new_mob_num" value="<?php if(isset($data))echo $data['new_mob_num']; ?>" />
                        </div>                     
                </div>
            </div>
            
             <div class="field">
                
                <div class="fieldDetail">
                    
                    <!--<div class="fieldLabel leftFloat"><label for="sup_dist_mob"> Super Dist Mobile Number </label></div>-->
                     
                        <div class="fieldLabelSpace">
                            <input tabindex="5" type="hidden" id="master_dist_mob" name="master_dist_mob" value="<?php if(isset($data))echo $data['master_dist_mob']; ?>" />
                        </div>                     
                </div>
            </div>
            
            <?php }else { ?>
            
            
            <div class="field">
                
                <div class="fieldDetail">
                    
                    <div class="fieldLabel leftFloat"><label for="old_mobile"> Old Mobile Number </label></div>
                     
                        <div class="fieldLabelSpace">
                            <input tabindex="5" type="text" id="old_mob_num" maxlength="10" name="old_mob_num" autocomplete="off" />
                        </div>                     
                </div>
            </div>
            
            <div class="field">
                
                <div class="fieldDetail">
                    
                    <div class="fieldLabel leftFloat"><label for="new_mobile"> New Mobile Number </label></div>
                     
                        <div class="fieldLabelSpace">
                            <input tabindex="5" type="text" id="new_mob_num" maxlength="10" name="new_mob_num" autocomplete="off" />
                        </div>                     
                </div>
            </div>
            
            <?php } ?>
            
            <div class="field">
                
                <div class="fieldDetail">
                    
                        <div class="fieldLabelSpace">
                            
                            <button type="submit" class="btn btn-default">Submit</button>
                            
                        </div>                     
                </div>
            </div>
            
             
            
    </form>
  </fieldset>
        
