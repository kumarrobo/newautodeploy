  <?php 
/*  echo '<pre>';
  print_r($upcomingRem);
  echo '</pre>';
 */	$upcomingRemCnt = 0; $upcomingPag =0;
 	
  	foreach($upcomingRem as $UR){
  	$alertType = '';
  	if(trim($UR['Reminder']['day']) != ''){
  		$alertType = '<img src="/img/spacer.gif" class="otherSprite oSPos21" align="absmiddle" title="Repeat Daily" alt"Repeat Daily">';
  	}else if(trim($UR['Reminder']['week']) != ''){
  		$alertType = '<img src="/img/spacer.gif" class="otherSprite oSPos22" align="absmiddle" title="Repeat Weekly" alt"Repeat Weekly">';
  	}else if(trim($UR['Reminder']['month']) != ''){
  		$alertType = '<img src="/img/spacer.gif" class="otherSprite oSPos23" align="absmiddle" title="Repeat Monthly" alt"Repeat Monthly">';
  	}else if(trim($UR['Reminder']['year']) != ''){
  		$alertType = '<img src="/img/spacer.gif" class="otherSprite oSPos24" align="absmiddle" title="Repeat Yearly" alt"Repeat Yearly">';
  	}else{
		$alertType = '<img src="/img/spacer.gif" style="height:21px;width:21px" align="absmiddle">';
	}
  	$id = $objMd5->encrypt($UR['Reminder']['id'], encKey);    	 
  ?>
  <div class="appDataCont appDataContClr1" id="<?php echo $id; ?>">
	<div class="leftFloat" style="padding-top:2px;"><?php echo $objGeneral->dateTimeFormat($UR['Reminder']['date']." ".$UR['Reminder']['time']); ?></div>
    <div class="rightFloat">
		
		<?php echo $alertType; ?>
		<a href="javascript:void(0);" onclick="confirmDelReminder('<?php echo $id; ?>')"><img src="/img/spacer.gif" class="otherSprite oSPos18" align="absmiddle" title="Delete Reminder" alt"Delete Reminder"></a>
	</div>
	<p class="appDataDesc" style="clear:both;height:100%;"><?php echo stripslashes($UR['Reminder']['message']); ?></p>
	<div style="margin-bottom:2px;">
	
	<ul class="gList">
		<?php $remForCnt = count($UR['Reminder']['reminder_for']); 
		$show = APP_REM_DEF_CNT;
		$showClose = 0;
		$showCnt = 0;
		foreach($UR['Reminder']['reminder_for'] as $k => $v){ ?>
		<li id="<?php echo $k.'_'.$id; ?>"><span><?php echo $v; ?></span><?php if($remForCnt> 1) { ?><span class="gRemove"><a href="javascript:void(0);" onclick="delRemRec('<?php echo $id; ?>','<?php echo $k; ?>')">X</a></span><?php } ?></li>
		<?php $showCnt++;
			if($showCnt == $show){
				$showClose = 1;
				echo "<div id='".$id."_seealldiv' style='display:none;'>";
			}
		 } 
			if($showClose == 1)
			echo '</div>';
			?>
		</ul>
            <?php
			if($remForCnt > $show){
		?>
		<div style="background:none" class="" id="<?php echo $id.'_seeall'; ?>"><a href="javascript:void(0);" onclick="$('<?php echo $id; ?>_seealldiv').show();$('<?php echo $id; ?>_seeall').remove();" class="rightFloat">See all</a></div>
		<?php } ?>
	
	
	<div class="clearBoth">&nbsp;</div>
	</div>
  </div>
  <!-- <div class="appDataCont" style="padding:0px;">
  	<div style="border:1px solid #000; padding:2px;">
  		<div>Add data to grp2</div>
  		<table cellspacing="0" cellpadding="0" width="100%">
  			<tr>
  				<th width="10%"><input type="checkbox"></th>
  				<th width="55%">Name</th>
  				<th width="35%">Mobile No.</th>
  			</tr>
  			<tr>
  				<td><input type="checkbox"></td>
  				<th>Arya</th>
  				<th>99999999</th>
  			</tr>
  		</table>
  	</div>
  </div> -->
  <div style="display:none;border: 1px solid rgb(0, 0, 0); padding:0px 0px 10px 10px;" id="appRemCnfDelBox<?php echo $id; ?>">
      <div>
		<div class="rightFloat appClose"><a href="javascript:void(0);" onclick="closeRemDelBox('<?php echo $id; ?>');">x</a></div>
		<div id="appDelRem<?php echo $id; ?>" style="padding:10px 10px 0px 0px;">						
		</div>
	  </div>
  </div>
  
  
  <?php $upcomingRemCnt=1; $upcomingPag = $UR['Reminder']['id']; } ?>
  <script>$('remAppUpcomingPagCnt').value = <?php echo $upcomingRemCnt; ?>;</script>
  <script>$('remAppUpcomingPag').value = <?php echo $upcomingPag; ?></script>
  <?php if($totalUpcomingRem > APP_REM_REC){ ?>
  <script>$('UpcomingRemVMDiv').innerHTML = '<a href="javascript:void(0);" onclick="appRemPagination();">View More</a>';</script>
  <?php }else{ ?>
  <script>$('UpcomingRemVMDiv').innerHTML = '';</script>
  <?php } ?>
  <?php if($totalUpcomingRem < 1){ ?>
  <script>$('UpcomingRemBSDiv').innerHTML = 'None of your alerts are active, at present.';</script>
  <?php }else{ ?>
  <script>$('UpcomingRemBSDiv').innerHTML = '';</script>
  <?php } ?>