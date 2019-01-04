<div class="category">
    <div class="footerDivider" >&nbsp;</div>
<?php 
	$k=0;$cnt = count($catData); 
	foreach($catData as $category) {
		$k++;									
?>					
  <div class="leftFloat box1" <?php if ($k == $cnt) echo "style='margin-right:0px;width:236px;'";?>>
    <div class="header">
    	<?php echo $this->Html->link(__($category['Category']['name'], true), array('controller' => 'categories','action' => 'view',$objGeneral->nameToUrl($category['Category']['name']))) ?>
    </div>
    <div class="cont">
      <ul>
    <?php $i = 0; while(!empty($category['Category'][$i])) { if($i < 5) { ?>  
        <li>
        	<?php  echo $this->Html->link(__($category['Category'][$i]['name'], true), array('controller' => 'categories','action' => 'view',$objGeneral->nameToUrl($category['Category']['name']), $objGeneral->nameToUrl($category['Category'][$i]['name']))) ?>
        </li>
	<?php
	} $i++; }
	if($i < 5)
	{
		while($i<5)
		{
	 ?> 
     	<li>&nbsp;</li> 
          <?php
		  $i++;
		 }
	 }
	  ?>      	            
      </ul>
      <div class="moreLink">
      	<?php echo $this->Html->link(__('more...', true), array('controller' => 'categories','action' => 'view',$objGeneral->nameToUrl($category['Category']['name']))) ?>
      </div>
    </div>
    <div class="box1Footer"></div>
  </div>
  <?php } ?>
   <br class="clearLeft" />
</div>