
      <ul class="indexCat" style="">
      
        <?php $i = 0; $j = 0; foreach($ocatData as $category) { $i++; if($i%4 == 1) { $j++;} if($i > 4) $i = 1; ?>
  		<li class="imageGrp<?php echo $j;?>" style="<?php if($i == 4) echo "margin-right:0px;";?>">
  			<div>
  				<span class="catImg">
  				<a alt="<?php echo strtoupper($category['Category']['name']); ?>" title="<?php echo strtoupper($category['Category']['name']); ?>" href="/categories/view/<?php echo $objGeneral->nameToUrl($category['Category']['name']);?>"><img class="catImg<?php echo $category['Category']['id'];?>" src="/img/spacer.gif" title="SMS Alerts for <?php echo ucfirst($category['Category']['name']); ?>" alt="SMS Alerts for <?php echo ucfirst($category['Category']['name']); ?>"></a>
  				</span>
  				<span class="catName"><a alt="<?php echo strtoupper($category['Category']['name']); ?>" title="<?php echo strtoupper($category['Category']['name']); ?>" href="/categories/view/<?php echo $objGeneral->nameToUrl($category['Category']['name']);?>"><?php echo strtoupper($category['Category']['name']); ?></a></span>
  			</div>
  		</li>
  		<?php } ?>
      </ul>          
 