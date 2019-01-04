
<div id="subscribe" class="calenderPopInner" style="width:430px;">

	<div class="field"> <?php if($trial) { ?> 
		<?php if($_SESSION['Auth']['User']['dnd_flag'] == 0){?>
	    	You are subscribing to <i><?php echo $trial_days; ?> day trial package</i> of <b><?php echo $packageName; ?></b><br>Click "OK" to proceed. 
		<?php } else { ?>
			Your number is in DND, so you are not allowed to take free trials. You can subscribe to monthly packages
		<?php }} else {?>
		You are subscribing to the package, <b><?php echo $packageName; ?></b><br>Click "OK" to proceed. 
		<?php } ?>
	</div>

	<?php if($_SESSION['Auth']['User']['dnd_flag'] == 0 || !$trial){?>
    <div class="field">
      
       	<input id="packageName" type="hidden" name="data[Package][name]" value="<?php echo $objMd5->encrypt($packageUrl,encKey);?>">
       	<input id="trialPack" type="hidden" name="data[Package][trial]" value="<?php echo $trial;?>">
       	
        <div id="subOkButt"><a href="javascript:void(0);" onclick="submitPackage();" class="buttSprite leftFloat" style="margin-top: 5px;"><img src="/img/spacer.gif" class="butOk"></a>
        </div>
	</div>	
    <?php }?>    
</div>