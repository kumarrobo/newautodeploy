<div class="btn-group">
   <button type="button" class="btn btn-primary dropdown-toggle btn-xs"  data-toggle="dropdown">
      Action <span class="caret"></span>
   </button>
   <ul class="dropdown-menu" role="menu">
<!--       <li><a href="#" onclick="stopDevice('<?php echo $sim->id;  ?>','<?php echo !$sim->stop_flag?1:0; ?>','<?php echo $vendor_id;  ?>');">Stop/Start</a></li>
       <li><a href="/sims/lastModemSMSes/<?php echo $vendor_id;  ?>/<?php echo $sim->id;  ?>/1" target="_blank">Last Sms</a></li>
      <li><a href="/sims/lastModemTransactions/<?php echo $vendor_id;  ?>/<?php echo $sim->id;  ?>/1">Last Txns</a></li>-->
      
      <li><a class="open-smsmodal" href="#sendSmsModal" data-toggle="modal" data-vendorid="<?php echo $vendor_id; ?>" data-simid="<?php echo $sim->id; ?>" >SMS</a></li>
      <li><a class="open-ussdmodal" href="#sendUssdModal" data-toggle="modal" data-vendorid="<?php echo $vendor_id; ?>" data-simid="<?php echo $sim->id; ?>">USSD</a></li>
    <?php if(!$isDistributer): ?>      
        <li><a onclick="sendBlockSms('<?php echo $sim->inv_supplier_id; ?>','<?php echo $sim->mobile; ?>','<?php echo $sim->vendor; ?>','<?php echo $sim->operator; ?>','<?php echo $sim->balance; ?>')">SendBlockSms</a></li>
    
    <li id='sim_remove_<?php echo $sim->id . '_' . $vendor_id; ?>><div'><a onclick="removeSim('<?php echo $sim->id;  ?>','<?php echo $vendor_id;  ?>')">Sim Remove</a></li>
    <li><a class="open-rtmodal" href="#rechargeType" data-toggle="modal" data-vendorid="<?php echo $vendor_id; ?>" data-simid="<?php echo $sim->id; ?>" data-operatorid="<?php echo $sim->opr_id; ?>" data-rt="<?php echo $sim->recharge_method; ?>">Recharge Type</a></li>
    <?php endif; ?>
<!--      <li><a onclick="runShowHide('<?php echo  $sim->id;  ?>','<?php echo  $vendor_id; ?>','0')">Hide</a></li>-->
       <li class="divider"></li>
      
       <li><a onclick="checkBalance('<?php echo $sim->id; ?>','<?php echo $vendor_id; ?>','<?php echo  $sim->opr_id;  ?>')">CheckBalance</a></li>
        <?php if(!$isDistributer): ?>
        <li><a onclick="runShowHide('<?php echo  $sim->id;  ?>','<?php echo  $vendor_id; ?>','<?php echo  $sim->opr_id;  ?>','<?php echo  $sim->balance;  ?>','0')">Hide</a></li>
        <?php endif; ?>
<!--      <li><a class="open-atmodal" href="#sendAtModal" data-toggle="modal" data-vendorid="<?php echo $vendor_id; ?>" data-simid="<?php echo $sim->id; ?>">At</a></li>
      <li><a class="open-resetmodal" href="#sendResetModal" data-toggle="modal" data-vendorid="<?php echo $vendor_id; ?>" data-simid="<?php echo $sim->id; ?>">Reset</a></li>-->
      </ul>
</div>

