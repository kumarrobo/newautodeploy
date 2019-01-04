  <?php  	
  	$upcomingRemCnt = 0; $upcomingPag =0;  
  	foreach($archivedRem as $AR){
  	$id = $objMd5->encrypt($AR['AppsLog']['id'], encKey); 
  ?>
  <!--<div class="appDataCont appDataContClr2">
	<div class="rightFloat">
		<span class="leftFloat" style="padding-top:2px;"><?php echo $objGeneral->dateFormat($AR['AppsLog']['timestamp']); ?></span>
		<a href="javascript:void(0);" onclick="delArchived('<?php echo $id; ?>')"><img src="/img/spacer.gif" class="otherSprite oSPos18" align="absmiddle"></a>
	</div>
	<span><?php echo ($AR['friendlists']['nickname'] != "")?$AR['friendlists']['nickname']:(($AR['AppsLog']['mobile'] == $_SESSION['Auth']['User']['mobile'])?'Me':$AR['AppsLog']['mobile']); ?></span>
	<p class="appDataDesc"><?php echo stripslashes($AR['AppsLog']['content']); ?></p>
  </div>-->
  
  <div class="appDataCont appDataContClr2">
	<div class="leftFloat" style="padding-top:2px;"><?php echo $objGeneral->dateFormat($AR['AppsLog']['timestamp']); ?></div>
    <div class="rightFloat">		
		<a href="javascript:void(0);" onclick="delArchived('<?php echo $id; ?>')"><img src="/img/spacer.gif" class="otherSprite oSPos18" align="absmiddle"></a>
	</div>
	<p class="appDataDesc" style="clear:both"><?php echo stripslashes($AR['AppsLog']['content']); ?></p>
	<div style="margin-bottom:2px;">
	
	<ul class="gList">
		<?php $remForCnt = count($AR['0']['rec']); 
		$show = APP_REM_DEF_CNT;
		$showClose = 0;
		$showCnt = 0;
		foreach($AR['0']['rec'] as $k => $v){ ?>
		<li id="<?php echo $k.'_'.$id; ?>"><span><?php echo $v; ?></span><?php if($remForCnt> 1) { ?><?php } ?></li>
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
  <?php $upcomingRemCnt=1; $upcomingPag = $AR['AppsLog']['id']; } ?>
  <script>$('remAppArcPagCnt').value = <?php echo $upcomingRemCnt; ?>;</script>
  <script>$('remAppArcPag').value = <?php echo $upcomingPag; ?></script>
  <?php if($totalArchivedRem > APP_REM_REC){ ?>
  <script>$('archivedRemVMDiv').innerHTML = '<a href="javascript:void(0);" onclick="appRemArcPagination();">View More</a>';</script>
  <?php }else{ ?>
  <script>$('archivedRemVMDiv').innerHTML = '';</script>
  <?php } ?>
 