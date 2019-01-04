<div class="title2 color4 strng">Explore your favourite category</div>
<div>
  	<ul class="points2">
  		<?php $i = 0; $j = 0; foreach($ocatData as $category) { $i++; if($i%4 == 1) { $j++;} if($i > 4) $i = 1; ?>
  		<li style="<?php if($j == 2 && $i == 4) echo "margin-right:0px;";?>"><a class="smCat<?php echo $j;?>" alt="<?php echo strtoupper($category['Category']['name']); ?>" title="<?php echo strtoupper($category['Category']['name']); ?>" href="/categories/view/<?php echo $objGeneral->nameToUrl($category['Category']['name']);?>">
  			<span class="smCatImg">
  				<img class="smCatImg<?php echo $category['Category']['id'];?>" src="/img/spacer.gif" title="SMS Alerts for <?php echo ucfirst($category['Category']['name']); ?>" alt="SMS Alerts for <?php echo ucfirst($category['Category']['name']); ?>">
  			</span>
  			<span class="smCatName"><?php echo strtoupper($category['Category']['name']); ?></span>
  			</a>
  		</li>
  		<?php } ?>
  	</ul>
  	<div class="clearLeft"></div>
  </div>
 <!-- <div class="clearLeft"> </div> -->