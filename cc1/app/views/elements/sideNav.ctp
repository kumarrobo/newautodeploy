<!-- My Code -->
	<?php if(isset($_SESSION['Auth']['User'])) {?>
		  <li class="hList" name='innerli'>
          	<a class="<?php if($category == 'pop') echo 'sel';?>" href="javascript:void(0);" onclick="fetchPackages(this,'pop')">
          	<span><img src="/img/popular.png" algin="absmiddle"></span>     	
          	<p id="cat_pop" style="display:inline;">Most Popular</p>  </a>           				
		  </li>
	<?php } ?>
	<?php foreach($resultCategories as $packages) {?> 
		  <li>
          	<a class="hList" href="javascript:void(0);"><span><img src="/img/icon_<?php echo $packages['Category']['id'] ?>.png?1" algin="absmiddle"></span>          	
          	<p id="cat_<?php echo $packages['Category']['id'];?>"><?php echo $packages['Category']['name'];?></p></a>
          	<div class="sublist"><ul>
          	<?php $i = 0; $ids = ""; while(!empty($packages['Category'][$i])) {  $ids .= $packages['Category'][$i]['id'] . ",";?>
          		
          		<li name='innerli'>
          			<?php 
          				$href = "javascript:void(0);";
          				$onclick = "";
          				if(isset($_SESSION['Auth']['User'])) {
          					$onclick = "fetchPackages(this,".$packages['Category'][$i]['id'].")";
          				}
          				else {
          					$href = "/categories/view/".$objGeneral->nameToUrl($packages['Category']['name'])."/".$objGeneral->nameToUrl($packages['Category'][$i]['name']);
          				}
          			?>
          			<a id="cat_<?php echo $packages['Category'][$i]['id'];?>" href="<?php echo $href; ?>" class="<?php if($category == $packages['Category'][$i]['id']) echo 'sel';?>" onclick="<?php echo $onclick; ?>">
          				<?php echo $packages['Category'][$i]['name']; ?>
          			</a>
          		</li>
          	<?php $i++; } ?>	          		
          	</ul></div><input type="hidden" class="ie6Hide" id="hidcat_<?php echo $packages['Category']['id'];?>" value=<?php echo $ids; ?>>             				
		  </li>
	<?php } ?>
		  
<!-- My Code -->