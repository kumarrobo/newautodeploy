<?php $selectedDate=0;$todayDate=strtotime('today');$selectedDate=!empty($this->params['url']['searchbydate'])?strtotime($this->params['url']['searchbydate']):$todayDate;?>    
<?php  $previousDay=($todayDate-$selectedDate>0)?true:false;?>
<script>
jQuery(document).ready(function(){
    
    jQuery('table#operator_view_table td.level1').on('click',function(){
        //console.log(jQuery(this).attr('data-operator-id'));
        currentelement=jQuery(this);
        
        jQuery('tr.modem_'+jQuery(this).attr('data-operator-id')).toggle('fast',function(){
        
                if($(this).is(':visible'))
                {
                    $(currentelement).find('i.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                }
                else
                {
                      $(currentelement).find('i.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                }
      
        });
    });
});
</script>  
<div id="operators_view" class="tab-pane fade in">

      <div class="row">
          
 
          <table class="table table-condensed table-hover" id="operator_view_table">
           <thead>
               <tr>
                   <th>&nbsp;</th>
                   <th>Sims</th>
                   <th>Requests/min</th>
                   <th>Block/Stop</th>
                   <th>Cur Balance</th>
                   <th>Opening</th>
                   <th>Closing</th>
                   <th>Incoming</th>
                   <th>Sale</th>
                   <th>Diff</th>
                   <th>Blocked Balance</th>
                </tr>
           </thead>
           
           <tbody>
               
               <?php foreach($operators as $key=>$value): ?>
               
               <?php $modem_class=$value['info']['id']; ?>
               <tr>
                   <td class="level1" data-operator-id="<?php echo $modem_class; ?>"><i  class="glyphicon glyphicon-plus"></i><?php echo $key;  ?></td>
                   <td><?php echo $value['info']['totalActiveSims']."/".$value['info']['totalSims'];  ?></td>
                   <td>
                       <?php 
                     $operatorwiseTotalReq=0; 
                     $operatorwiseTotalSuccessReq=0; 
                     
                      if(isset($requests['operatorview'][$value['info']['id']])): 
                           
                           foreach($requests['operatorview'][$value['info']['id']] as $operatorreq):
                                $operatorwiseTotalReq+=$operatorreq['totalrequests'];
                                $operatorwiseTotalSuccessReq+=$operatorreq['successrequests'];
                           endforeach;
                       
                      endif;
                      
                      echo $operatorwiseTotalReq."/".$operatorwiseTotalSuccessReq;
                       ?>
                   </td>
                   <td><?php echo $value['info']['totalBlockedSims']."/".$value['info']['totalStoppedSims'];  ?></td>
                   <td><?php echo number_format($value['info']['totalBalance'],2);  ?></td>
                   <td><?php echo number_format($value['info']['totalOpening'],2);  ?></td>
                   <td><?php echo number_format($value['info']['totalClosing'],2);  ?></td>
                   <td><?php echo number_format($value['info']['totalIncoming'],2);  ?></td>
                   <td><?php echo number_format($value['info']['totalSale'],2);  ?></td>
                   <td>
                       <?php
                    //Current Day
                     if (!$previousDay):
                        echo number_format(-($value['info']['totalOpening'] - $value['info']['totalBalance'] + $value['info']['totalIncoming'] - $value['info']['totalSale'] + $value['info']['totalIncomingClo']), 2);
                    else:
                        //Previous Day       
                        echo number_format(-($value['info']['totalOpening'] - $value['info']['totalClosing'] + $value['info']['totalIncoming'] - $value['info']['totalSale'] + $value['info']['totalIncomingClo']), 2);
                    endif;
                    ?>
                   </td>
                    <td><?php echo number_format($value['info']['totalBlockedBalance'],2);  ?></td>
               </tr>
               
                            <?php foreach ($value['modems'] as $modemkey=>$modemvalue): ?>

                               <tr style="display: none;" class="modem_<?php echo $modem_class; ?>">
                                   <td><div class="level2"><i class="glyphicon glyphicon-plus"></i><?php echo $modemvalue['company'];  ?></div></td>
                                <td><?php echo $modemvalue['totalActiveSims']."/".$modemvalue['totalSims'];  ?></td>
                                <td>
                                    <?php  
                                    if(isset($requests['operatorview'][$value['info']['id']][$modemkey])):  
                                        echo $requests['operatorview'][$value['info']['id']][$modemkey]['successrequests']."/".$requests['operatorview'][$value['info']['id']][$modemkey]['totalrequests'];
                                    else:
                                        echo "NA";
                                    endif;
                                    ?>
                                </td>
                                <td><?php echo $modemvalue['totalBlockedSims']."/".$modemvalue['totalStoppedSims'];  ?></td>
                                <td><?php echo number_format($modemvalue['totalBalance'],2);  ?></td>
                                <td><?php echo number_format($modemvalue['totalOpening'],2);  ?></td>
                                <td><?php echo number_format($modemvalue['totalClosing'],2);  ?></td>
                                <td><?php echo number_format($modemvalue['totalIncoming'],2);  ?></td>
                                <td><?php echo number_format($modemvalue['totalSale'],2);  ?></td>
                                 <td>
                                <?php
                                // Not an api vendor
                                if($modemvalue['update_flag']!=0):
                                            //Current Day 
                                            if (!$previousDay):
                                             echo number_format(-($modemvalue['totalOpening'] - $modemvalue['totalBalance'] + $modemvalue['totalIncoming'] - $modemvalue['totalSale'] + $modemvalue['totalIncomingClo']), 2);
                                            else:
                                             //Previous Day       
                                             echo number_format(-($modemvalue['totalOpening'] - $modemvalue['totalClosing'] + $modemvalue['totalIncoming'] - $modemvalue['totalSale'] + $modemvalue['totalIncomingClo']), 2);
                                            endif;
                                 else:
                                            echo "0.00";
                                 endif;           
                                ?>
                                </td>
                                <td><?php echo number_format($modemvalue['totalBlockedBalance'],2);  ?></td>
                           </tr>

                           
                           <tr style="display:none;" >
                                                    <td colspan="12" >

                                                        <div>
                                                            <table  class="table table-condensed table-hover table-bordered">
                                                                <thead>
                                                                <th>S-D/M/P</th>
                                                                <th>Vendor</th> 
                                                                <th>Number</th>
                                                                <th>Margin</th>
                                                                <th>Balance</th>
                                                                <th>Opening</th>
                                                                <th>Closing</th>
                                                                <th>Incoming</th>
                                                                <th>Sale</th>
                                                                <th>H.Sale</th>
                                                                <th>R.Sale</th>
                                                                <th>Inc</th>
                                                                <th>Diff</th>
                                                                <th>Succ %</th>
                                                                 <th>Prcs time</th>
                                                                <th>L Succ</th>
                                                                <th></th>
                                                                <th>Actions</th>
                                                            
                                                           
                                                             
                                                                </thead>
                                                                <tbody>
                                    <?php foreach($modemvalue['sims'] as $s): ?>
                                                                    
                                                                    <?php foreach($s as $sim):  ?>
 <tr  data-sale-flag="1" style="background-color:<?php echo $this->Sims->setColor($sim); ?>"  data-mobile-no="<?php echo $sim->mobile; ?>"  data-saleamt="<?php echo $sim->sale; ?>"  >
                                                                            <td><?php echo $sim->signal; ?>-<?php echo $sim->id; ?>/<?php echo $sim->machine_id; ?>/<?php echo $sim->device_num; ?></td>
                                                                            <td><?php echo $sim->vendor; ?></td>
                                                                            <td><?php echo $sim->mobile; ?></td>
                                                                            <td><?php echo $sim->commission; ?></td>
                                                                            <td><?php echo $sim->balance; ?></td>
                                                                            <td><?php echo $sim->opening; ?></td>
                                                                            <td><?php echo isset($sim->closing)?$sim->closing:""; ?></td>
                                                                            <td><?php echo $sim->tfr; ?> </td>
                                                                            <td><?php echo $sim->sale; ?></td>
                                                                            <td><?php echo ($sim->sale-$sim->roaming_today); ?></td>
                                                                            <td><?php echo $sim->roaming_today;; ?>/<?php echo $sim->roaming_limit; ?></td>
                                                                            <td><?php echo $sim->inc; ?></td>
                                                                                    <?php
                                                                                     if (!$previousDay):
                                                                                       $diff=-($sim->opening - $sim->balance + $sim->tfr - $sim->sale+$sim->inc);
                                                                                   else:
                                                                                      $diff=-($sim->opening - $sim->closing + $sim->tfr + $sim->inc - $sim->sale);
                                                                                   endif;
                                                                                   ?>
                                                                            <td><?php  echo number_format($diff,2); ?></td>
                                                                            <td><?php  if($sim->success>0): echo $sim->success."%"; endif; ?></td>
                                                                             <td><?php echo $sim->process_time; ?>  secs</td>
                                                                             
                                                                             <?php $datelink=isset($this->params['url']['searchbydate'])?$this->params['url']['searchbydate']:date('Y-m-d'); ?>
                                                                             <td><a href="/sims/lastModemSMSes/<?php echo $modemkey;  ?>/<?php echo $sim->id;  ?>/1/1500/ <?php echo $datelink;?>" target="_blank"><?php echo !empty($sim->last)?$sim->last:"Last Sms"; ?></a></td>
                                                                             <td <?php if($sim->stop_flag): ?>style="background-color: red;"<?php endif;?>><a  onclick="stopDevice('<?php echo $sim->id;  ?>','<?php echo !$sim->stop_flag?1:0; ?>','<?php echo $modemkey;  ?>',this);"><?php echo $sim->stop_flag?"Start":"Stop"; ?></a></td>  
                                                                              <td><?php echo $this->element('simAction',array('sim'=>$sim,'vendor_id'=>$modemkey)); ?></td>   
                                                                          </tr>
                                                                      <?php endforeach; ?>
  
                                     <?php endforeach; ?>
                                                                     </tbody>

                                                            </table>

                                                        </div>

                                                    </td>
                                                </tr>
                           
                           
                           <?php endforeach; ?>
               
               
               <?php endforeach; ?>
               
              
               
               
           </tbody>
           
      </table>
          
               </div>

</div>