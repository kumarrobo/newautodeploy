    <fieldset>

     
        <legend>Api Balances</legend>
        
           <div class="row">
            
               <div class="col-lg-12" style="margin-bottom: 14px;">
                  
                      
 
                           <?php if(!empty($apiVendors)):  ?>
                           <?php foreach($apiVendors as $api): ?>
                            <div class="col-lg-3 divblocklastsuccess">
                           <ul class="divblockapi">
                           <li><?php echo $api['shortform']; ?></li>
                           <li><?php echo number_format($api['balance'],2);  ?></li>
                            </ul>
                                  </div>
                           <?php endforeach; ?>
                         
                           <?php endif; ?>
                           
                      
                
               </div>
               
           </div>
    </fieldset>