<?php if(count($retailers) > 0) {?>


<div class="appTitle" style="margin-bottom:20px;">Retailers in <?php echo $retailers['0']['locator_area']['name']; ?>, <?php echo $retailers['0']['Retailer']['city']; ?>, <?php echo $retailers['0']['Retailer']['state']; ?> </div>
<?php $i = 0;  
	foreach($retailers as $retailer) {
	echo '<div class="retailAddBox">';
?>
		<div class="leftFloat"><?php  echo (($page-1)*$limit + ($i+1)) . ")" ; ?></div>
		<div class="retailData">
			<div class="fntSz19"><strong><?php echo $retailer['Retailer']['shopname']; ?></strong></div>
			<div><?php echo $retailer['Retailer']['address']; ?></div>
			<div><?php echo $retailer['Retailer']['city']; ?> - <?php echo $retailer['Retailer']['pin']; ?></div>
			<div>Mobile: <?php echo $retailer['Retailer']['mobile']; ?></div>
		</div>
	</div>
<?php $i++; }?>
<br class="clearLeft" />
<div style="border-top:1px solid #5D5D5D;"></div>
<div class="ie6Fix2 pagination" style="padding-top:10px; float:right;"> 
  <ul style="padding:0px; margin:0px;">
  	<li><?php if($page == 1) { ?>
   		<span class="lightText"> &lt;&lt; Previous </span>
   		<?php } else { ?>
   		<span> <a class="noAffect" href="javascript:void(0);" onclick="getRetailers(<?php echo ($page - 1);?>,0);">&lt;&lt; Previous</a> </span> 
   		<?php } ?></li>
    <li class="paginationNo"><?php $tot = (int)($count/$limit);
   		 if($count%$limit != 0)
			$tot++;
			
		 for($i =1;$i<=$tot;$i++){
		 	if($i != $page){?>
		 		<span> <a class="" href="javascript:void(0);" onclick="getRetailers(<?php echo $i;?>,0);"><?php echo $i;?></a> </span>
		 <?php	} else { ?>
		 		<span class="current"><?php echo $i;?></span>
		 <?php }
		 	
		 }
   ?>  </li>
    <li class="lastElement"><?php if($page == $tot) { ?>
   		<span class="lightText"> Next &gt;&gt; </span>
   		<?php } else { ?>
   		<span> <a class="noAffect" href="javascript:void(0);" onclick="getRetailers(<?php echo ($page + 1);?>,0);">Next &gt;&gt;</a> </span> 
   		<?php }?></li>
  </ul>
  
  
</div>


<?php } else { ?>
<div class="appTitle" style="margin-bottom:20px;">Sorry, No retailers found</div>

<?php } ?>