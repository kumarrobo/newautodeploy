<?php foreach($friendList as $friend){ ?>
<?php if(!$afteradd){ ?><div id="<?php echo 'main_'.$friend['Friendlist']['id']; ?>"> <?php } ?>
<div class="appDataCont appDataContClr1">
	<div>  				
		<div class="rightFloat">					
			<img align="absmiddle" style="height: 21px; width: 21px;" src="/img/spacer.gif">
			<a href="javascript:void(0);" onclick="addToContact('<?php echo $friend['Friendlist']['id']; ?>')"><img align="absmiddle" alt="Add this Contact to Groups" title="Add this Contact to Groups" class="otherSprite oSPos29" src="/img/spacer.gif"></a>							
			<a href="javascript:void(0);" onclick="confirmDelRemCon('<?php echo $friend['Friendlist']['mobile']; ?>','<?php echo $friend['Friendlist']['id']; ?>');">
				<img align="absmiddle" alt="" class="otherSprite oSPos18" src="/img/spacer.gif">
			</a>
		</div>
			<span>
				<table cellpadding="0px" cellspacing="0px" border="0" >                    	
                  	<tr>                       
                    	<td width="150px"><?php echo $friend['Friendlist']['nickname']; ?></td>
                    	<td width="100px"><?php echo $friend['Friendlist']['mobile']; ?></td>
                  	</tr>                     	
                </table>
			</span>
		<div class="clearRight">&nbsp;</div>
	</div>
	<div>
		<ul class="gList">
			<?php 
				$grpCnt = count($friend['Grouplist']); 
				$show = APP_REM_DEF_CNT;
				$showClose = 0;
				$showCnt = 0;
				foreach($friend['Grouplist'] as $gl){ ?>
				<li id="<?php echo "gfl_".$gl['GrouplistsFriendlist']['id']; ?>">
					<span><?php echo $gl['name']; ?></span>
					<span class="gRemove">
						<a href="javascript:void(0);" onclick="delFrndGrp('<?php echo $gl['GrouplistsFriendlist']['id']; ?>','<?php echo $friend['Friendlist']['id']; ?>')">X</a>
					</span>
				</li>
				<?php 
					$showCnt++;
					if($showCnt == $show){
						$showClose = 1;
						echo "<div id='".$friend['Friendlist']['id']."_seealldiv' style='display:none;'>";
					}
				 } 
					if($showClose == 1)
					echo '</div>';
					
					if($grpCnt > $show){
				?>
				<li style="background:none" id="<?php echo $friend['Friendlist']['id'].'_seeall'; ?>">
					<a href="javascript:void(0);" onclick="$('<?php echo $friend['Friendlist']['id']; ?>_seealldiv').show();$('<?php echo $friend['Friendlist']['id']; ?>_seeall').remove();" class="rightFloat">See all</a></li>
				<?php } ?>
		</ul>	
        <div class="clearBoth">&nbsp;</div>
	</div>
</div>
<!-- Group List -->
<div style="padding:0px;">	  				
  	<div id="addToCons<?php echo $friend['Friendlist']['id']; ?>" style="border:1px solid #000; padding:0 0px 2px 5px;display:none;"></div>
</div>
<!-- Group List Ends -->
<div style="display:none;border: 1px solid rgb(0, 0, 0); margin-top: 1px;" id="appRemConCnfDelBox<?php echo $friend['Friendlist']['id']; ?>">
  <div>
  	<div class="appClose rightFloat"><a href="javascript:void(0);" onclick="closeRemConDelBox('<?php echo $friend['Friendlist']['id']; ?>');" >X</a></div>					
	<div id="appDelRemCon<?php echo $friend['Friendlist']['id']; ?>" style="padding: 10px;"></div>
  </div>
</div>
<?php if(!$afteradd){ ?></div><?php } ?>
<?php } ?>