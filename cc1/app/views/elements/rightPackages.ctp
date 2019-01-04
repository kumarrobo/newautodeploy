<!-- previous_button_disabled -->
<?php
if(count($packData) > 0)
{
?>    
    	<div class="rightCol rightFloat">
    		<div style="margin-top:10px;margin-bottom:20px;">
    			<script type="text/javascript"><!--
google_ad_client = "pub-6426318916029686";
/* 200x90, created 7/29/11 */
google_ad_slot = "8077108498";
google_ad_width = 200;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
    		</div>
	        <div class="rightColSpace">
	          <div class="title4"><?php if(isset($header)) echo $header; else echo "Related Packages";?></div>
	          <div class="color4 fntSz15">Get more fresh messages on your mobile daily. Subscribe Now.</div>
	          <div style="padding:15px 0px;">
	          <a id="VCPButt" href="javascript:void(0)" onClick="VC('previous')" class="previous_button1">
	          	<img width="16px" height="16px;" src="/img/spacer.gif">
	          </a>
	          <div style="overflow:hidden">
	          	<?php
	          	$i = 0;
	          	$j = 1;
	          	?>	          	
              	<div id="VC_<?php echo $j; ?>" style="background:#fff;overflow:visible;">
              		<?php foreach($packData as $othPackage) {
              			$i++;
              		?>
          			<div <?php if ($i%3 == 0) echo 'style="padding-bottom:0px;"'; else echo 'style="margin-bottom:20px;padding-bottom:0px;"'; ?> >
	          			<div style="padding-top:3px;">	            
							<div class="box3Width2">
								<div class="leftCol3 thumbImage">
									<a title="<?php echo $othPackage['Package']['name']; ?>" alt="<?php echo $othPackage['Package']['name']; ?>" href="/packages/view/<?php echo $othPackage['Package']['url']; ?>"><img border="0" class="package_<?php echo $othPackage['Package']['id']; ?>" src="/img/spacer.gif"></a></div>
									<div class="thumbDesc1 fntSz15" style="display: block; text-align: left; color: rgb(0, 0, 0);">
									<div class="fntSz17 strng color1" style="text-align: left;"><a title="<?php echo $othPackage['Package']['name']; ?>" alt="<?php echo $othPackage['Package']['name']; ?>" href="/packages/view/<?php echo $othPackage['Package']['url']; ?>"><?php echo $othPackage['Package']['name']; ?></a></div>
									<div>
										<span class="strng">Price - <span><img class="rupee2" style="margin-right: 1px;" src="/img/rs.gif"></span><?php echo round($othPackage['Package']['price']); ?></span><span> for <?php echo $othPackage['Package']['validity']; ?> days</span>
									</div>
									<div>Frequency - <?php echo $objGeneral->getFrequency($othPackage['Package']['frequency']);?></div>
									<div><input type="button" value="Subscribe" class="css3But2" onclick="subPackage('<?php echo $objMd5->encrypt($othPackage['Package']['id'],encKey) ?>','sub');">
									<?php if($othPackage['Package']['trial_days'] > 0) {?>
									<a onclick="subPackageTrial('<?php echo $objMd5->encrypt($othPackage['Package']['id'],encKey) ?>','sub',this);" style="font-size: 11px; display: inline-block; margin-left: 5px; text-decoration: underline;" href="javascript:void(0);">Try FREE</a>
									<?php } ?>
									</div>
								</div>
								<div class="clearLeft">&nbsp;</div>
							</div>
						</div>
					</div>
              		<?php
              			if ($i%3 == 0 && $i != count($packData)) 
              			{ 
              				$j++;
              				echo '</div><div id="VC_'.$j.'" style="background:#fff;overflow:hidden;display:none">';
              			}
              		}
              		?>
              	</div>
              </div>
              <a id="VCNButt" href="javascript:void(0)" onClick="VC('next')" class="next_button1">
	          	<img width="16px" height="16px;" src="/img/spacer.gif">
	          </a>
              </div>
	          <!-- Ie6 fix1 -->
	          <?php if(isset($categoryName)) {?>
	          <div class="title4">Other <?php echo $categoryName; ?></div>
	          <ul class="ulStyle">
	          	<?php foreach($otherCatData as $otherCat) {?>
            		<li><?php
	            		echo $this->Html->link(__($otherCat['Category']['name'], true), array('action' => 'view',$objGeneral->nameToUrl($categoryName), $objGeneral->nameToUrl($otherCat['Category']['name'])));
            		?></li>
          		<?php } ?>
	          </ul>
	          
	          
	          <?php } ?>
	        </div>
	        
	        <?php 
	        	if($this->params['controller'] != 'messages') echo $this->element('tagCloud',array('tagCloud' => $tagCloud)); 
	        ?>
      	</div> 
<script>
var VCCurr = 1;
var MaxVCCurr = <?php echo $j; ?>;
var MinVCCurr = 1;
function VC(dir)
{	
	if (dir == 'previous' && VCCurr > MinVCCurr)
	{		
		//Effect.Shrink('VC_'+VCCurr, { queue: 'end' });
		new Effect.BlindUp($('VC_'+VCCurr),{duration:2});
		if (VCCurr == MinVCCurr) { VCCurr = MaxVCCurr; } else { VCCurr -= 1; };
		new Effect.BlindDown($('VC_'+VCCurr),{duration:2});
		//Effect.Grow('VC_'+ VCCurr);
	}
	else if(dir == 'next' && VCCurr < MaxVCCurr)
	{	
		//Effect.Shrink('VC_'+VCCurr, { queue: 'end' });
		new Effect.BlindUp($('VC_'+VCCurr),{duration:2});		
		if (VCCurr == MaxVCCurr) { VCCurr = MinVCCurr; } else { VCCurr += 1; };
		new Effect.BlindDown($('VC_'+VCCurr),{duration:2});	
		//new Effect.toggle($('VC_'+VCCurr),'SlideDown', {duration:3});
	}		
} 
</script>
<?php
}
?>

<div class="box6L">
	              <div class="box6R">
	                <div class="box6B">
	                  <div class="box6BL">
	                    <div class="box6BR">
	                      <div class="box6TR">
	                        <div class="box6TL">
	                        
	                        
	                        
	                        </div>
	                      </div>
	                    </div>
	                  </div>
	                </div>
	              </div>
	            </div>


    