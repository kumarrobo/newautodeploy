<div id="horizontal_carousel<?php echo $num; ?>"  class="horizontal_carousel" style="width:600px;">
      <div class="previous_button"><img src="/img/spacer.gif" ></div>
      <!-- <div class="leftFloat carousalTitle"><img src="/img/spacer.gif" class="image<?php // echo $num; ?>" /></div> -->  
      <div class="container" style="width:549px;">
        <ul>
        	<li class="packegImage">        	
       		<?php
       		
       			$cnt = 0;
       			$totalPkg = count($packs);
       			$pkgCnt = 1;
       			foreach($packs as $package) {
       				$cnt += 1; 
       		?>
       		<div class="leftFloat" style="padding-top:3px;margin:0px 25px 20px 0px;">                        	
	            <div class="box3Width" style="display:block;">                          
	              	<span class="leftCol3 thumbImage posRlative">                      	
	                  		<a href="/packages/view/<?php echo $package['Package']['url'];?>"><img border="0" class="package_<?php echo $package['Package']['id'];?>" title="<?php echo $package['Package']['name'];?>" alt="<?php echo $package['Package']['name'];?>" src="/img/spacer.gif"></a>
	                  		<?php if(in_array($package['Package']['id'],$moneyBack)){  ?>
	                  		<img height="5px" width="5px" class="day7Smaller" src="/img/spacer.gif">                      		
	                  		<?php } ?>                      	                          		
	         		</span>
	         		<a href="/packages/view/<?php echo $package['Package']['url'];?>">
	                <span class="thumbDesc color1 fntSz15" style="display:block">
	                	<span class="strng"><?php echo "<span><img class='rupee2' style='margin-right:1px;' src='/img/rs.gif'/></span>".floor($package['Package']['price']); ?></span><span> for <br /> <?php echo $package['Package']['validity'];?> days</span>
	                </span>
	                <span class="clearLeft thumbTitle fntSz15 strng ie71" style="display:block;padding-top:2px"><?php echo $package['Package']['name']; // echo $this->Html->link(__($package['Package']['name'], true), array('controller' => 'packages', 'action' => 'view',$package['Package']['url'])); ?></span>
	                </a>                          
	            </div>
            </div>
            <?php
            		
            		if (($cnt % 3)== 0) // For 3 rows
            			echo "<br class='clearLeft' />";
            		if ($cnt >= 9)
            		{
            		if($pkgCnt < $totalPkg)
            		echo "</li><li class='packegImage'>";
            		$cnt = 0;
            		}
            		$pkgCnt++;
            	 } 
            ?>
           	</li>
        </ul>
      </div>
      <div class="next_button"><img src="/img/spacer.gif" ></div>      
    </div>
<script type="text/javascript">
  function runTest<?php echo $num; ?>() {
    new UI.Carousel("horizontal_carousel<?php echo $num; ?>");
  }
  Event.observe(window, "load", runTest<?php echo $num; ?>);
</script>