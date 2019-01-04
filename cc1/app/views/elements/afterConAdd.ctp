<?php foreach($groupList as $gl){ ?>
<?php if(!$afteradd){ ?><div id="<?php echo 'main_'.$gl['Grouplist']['id']; ?>"> <?php } ?>
<div class="appDataCont appDataContClr1">
	<div>  				
		<div class="rightFloat">					
			<img align="absmiddle" style="height: 21px; width: 21px;" src="/img/spacer.gif">
			<a href="javascript:void(0);" onclick="addToGroup('<?php echo $gl['Grouplist']['id']; ?>')"><img align="absmiddle" alt="Add Contacts to this Group" title="Add Contacts to this Group" class="otherSprite oSPos29" src="/img/spacer.gif"></a>							
			<a href="javascript:void(0);" onclick="confirmDelRemGroup('<?php echo $gl['Grouplist']['id']; ?>')">
				<img align="absmiddle"  alt="" class="otherSprite oSPos18" src="/img/spacer.gif">
			</a>
		</div><span><?php echo $gl['Grouplist']['name'];?></span><div class="clearRight">&nbsp;</div>
	</div>
	<div>
		
		<ul class="gList">
			<?php $grpFrndCnt = count($gl['Friendlist']); 
					$show = APP_REM_DEF_CNT;
					$showClose = 0;
					$showCnt = 0;
					foreach($gl['Friendlist'] as $glf){ ?>
					<li id="<?php echo $glf['id'].'_'.$gl['Grouplist']['id']; ?>"><span><?php echo $glf['nickname']; ?></span><span class="gRemove"><a href="javascript:void(0);" onclick="delGrpFrnd('<?php echo $gl['Grouplist']['id']; ?>','<?php echo $glf['id']; ?>')">X</a></span></li>
					<?php $showCnt++;
						if($showCnt == $show){
							$showClose = 1;
							echo "<div id='".$gl['Grouplist']['id']."_seealldiv' style='display:none;'>";
						}
					 } 
						if($showClose == 1)
						echo '</div>';
						
						if($grpFrndCnt > $show){
					?>
					<li style="background:none" class="" id="<?php echo $gl['Grouplist']['id'].'_seeall'; ?>"><a href="javascript:void(0);" onclick="$('<?php echo $gl['Grouplist']['id']; ?>_seealldiv').show();$('<?php echo $gl['Grouplist']['id']; ?>_seeall').remove();" class="rightFloat">See all</a></li>
					<?php } ?>
		</ul>
		<div class="clearLeft">&nbsp;</div>
	</div>
</div>
<!-- Contact List -->
<div style="padding:0px;">	  				
  	<div id="addToGrps<?php echo $gl['Grouplist']['id']; ?>" style="border:1px solid #000; padding:0 0px 2px 5px;display:none;"></div>
</div>
<!-- Contact List Ends -->
<div style="display:none;border: 1px solid rgb(0, 0, 0); margin-top: 1px;" id="appRemGrpCnfDelBox<?php echo $gl['Grouplist']['id']; ?>">
  <div>
  	<div class="appClose rightFloat"><a href="javascript:void(0);" onclick="closeRemGrpDelBox('<?php echo $gl['Grouplist']['id']; ?>');">X</a></div>					
	<div id="appDelRemGrp<?php echo $gl['Grouplist']['id']; ?>" style="padding: 10px;"></div>
  </div>
</div>
<?php if(!$afteradd){ ?></div><?php } ?>
<?php } ?>