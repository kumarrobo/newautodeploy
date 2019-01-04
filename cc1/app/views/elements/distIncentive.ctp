<form class="field" style="border:0px;margin:0px;width:1070px">
    <div class="appTitle alignCenter"><?php echo date('F'). " Month Target";?></div>
    
    <?php if(!empty($scheme)) {$i = 0; ?>
                <?php foreach ($scheme['target'] as $key => $details) {  $i++;?>
            
            
            <fieldset style="width:500px; float: left;border:1px solid grey;padding: 5px;">
                        
                        <div>
                            <div class="field LabelSpace1 alignCenter" style="background-color: #efefef;margin: 5px">
                            <p><b><?php echo "Scheme $i";?></b></p>
                            </div>                     
                        </div>
                        
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="accountname">Recharges: </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo "Achieve total of Rs ".$details['recharge']." in recharges"; ?></p>
                            </div>                     
                        </div>
                        
                        <?php if($details['mpos'] > 0) { ?>
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="mpos">Mpos: </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo "Activate ".$details['mpos']." devices for Pay1 Swipe (Mpos)";?></p>
                            </div>                     
                        </div>
                        <?php } ?>
                        
                        <?php if($details['smartbuy'] > 0) { ?>
                        <div>
                            <div class="fieldLabel1 leftFloat"><label for="smartbuy">Smartbuy: </label></div>
                            <div class="field LabelSpace1">
                                <p><?php echo "Bill smartbuy products of Rs ".$details['smartbuy']." to retailers";?></p>
                            </div>                     
                        </div>
                        <?php } ?>
                        
                        <div>
                            <div class="field LabelSpace1 alignCenter" style="background-color: #4caf50;margin: 5px">
                            <p><b><?php echo "Earnings";?></b></p>
                            </div>                     
                        </div>
                        
                        <div>
                        	<div class="field LabelSpace1">
                        	 <?php if($details['incentive'] == $details['incentive_ex']){ ?>
                                <p><?php echo "Earn Rs.  ".$details['incentive']." as incentive (+0.05% extra on online limit transfer)";?></p>
                            <?php } else { 
                            $inc_pos = strpos($details['incentive'], '%');
            				$percent = substr($details['incentive'],0,$inc_pos);
            				$total = 0.55 + $percent;
                            ?>
                            	<p><?php echo "Earn upto  ".$total."% of total recharge value (including 0.05% on online transfer)";?></p>
                            <?php } ?>
                            </div>
                        </div>
                        
                         <?php if($details['mpos'] > 0) { 
                         $total_mpos = 250 * $details['mpos'];
                         ?>
                        <div>
                        	<div class="field LabelSpace1">
                        	 
                                <p><?php echo "Earn Rs. ".$total_mpos." for ".$details['mpos']." Mpos devices";?></p>
                           
                            </div>
                        </div>
                        <?php } ?>
                        
                        <?php if($details['smartbuy'] > 0) { 
                         ?>
                        <div>
                        	<div class="field LabelSpace1">
                        	 
                                <p><?php echo "Earn 0.5% for Smartbuy billing value ";?></p>
                           
                            </div>
                        </div>
                        <?php } ?>
                    </fieldset>
                    
                    <?php } }?>
       
</form>

