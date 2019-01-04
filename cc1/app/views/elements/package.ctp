

<div class="loginCont">
    <div class="leftFloat dashboardPack" style="margin-right:5px;">
      <div class="catList">
        <ul id='innerul'>
          <?php echo $this->element('sideNav',array('resultCategories' => $resultCategories,'category' => $category,'count' => $count));?>
		</ul>
      </div>
    </div>
    <input type="hidden" id="packageCount" value=<?php echo $count;?>>
    <input type="hidden" id="category" value="<?php echo $category; ?>">
    <input type="hidden" id="pageNum" value="<?php echo $page;?>">
    <div style="margin-left:210px;" id="filterPackages">
    	<?php if(isset($package) && !empty($package)) {
    			echo "<script>getPackage('$package');</script>";	
    		}
    		else 
    			echo "<script> $('category').value = '".$category."';</script>";
				echo $this->element('packages',array('packData' => $packData, 'page' => $page,'count' => $count,'limit' => $limit,'layout' => $layout));?>
    </div>
    <br class="clearLeft" />
 </div>