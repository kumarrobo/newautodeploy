    <ul class="pagination">

        <li class="<?php if($page <= 1) echo "disabled" ?>">
            <?php 
            if($page <= 1){ ?>
                <a href="#">&laquo;</a>
            <?php } else { ?>
                <a href="javascript:goToPage(<?php echo 1 ?>);">&laquo;</a>
            <?php }
            ?>
            
        </li>
        <li class="<?php if($page <= 1) echo "disabled" ?>">
            <?php 
            if($page <= 1){ ?>
                <a href="#">Previous</a>
            <?php } else { ?>
                <a href="javascript:goToPage(<?php echo $page-1  ?>);">Previous</a>
            <?php }
            ?>
        </li>
		<?php if($total_pages < 5 || $page < 3){
			$iterations = min($total_pages, 5);
			for($i = 1; $i <= $iterations; $i++){
				echo "<li ";
				if($i == $page){
					echo "class='active'";
				}
				echo "><a href='javascript:goToPage(".$i.");'>".$i."</a></li>";
			}
		}
                else if($total_pages - $page < 2){
                    $diff = $total_pages - $page;
                    for($i = 4; $i >= 0; $i--){
                        echo "<li ";
                        if($page + $diff - $i == $page){
                            echo "class='active'";
			}
                        echo "><a href='javascript:goToPage(".($page + $diff - $i).");'>".($page + $diff - $i)."</a></li>";
                    }
                }
		else {
			echo "<li><a href='javascript:goToPage(".($page - 2).");'>".($page - 2)."</a></li>";
			echo "<li><a href='javascript:goToPage(".($page - 1).");'>".($page - 1)."</a></li>";
			echo "<li class='active'><a href='javascript:goToPage(".$page.");'>".$page."</a></li>";
			echo "<li><a href='javascript:goToPage(".($page + 1).");'>".($page + 1)."</a></li>";
			echo "<li><a href='javascript:goToPage(".($page + 2).");'>".($page + 2)."</a></li>";
		} 					
		?>
        <li class="<?php if($page >= $total_pages) echo "disabled" ?>">
            <?php 
            if($page >= $total_pages){ ?>
                <a href="#">Next</a>
            <?php } else { ?>
                <a href="javascript:goToPage(<?php echo $page+1 ?>);">Next</a>
            <?php }
            ?>
                
        </li>
        <li class="<?php if($page >= $total_pages) echo "disabled" ?>">
            <?php 
            if($page >= $total_pages){ ?>
                <a href="#">&raquo;</a>
            <?php } else { ?>
                <a href="javascript:goToPage(<?php echo $total_pages ?>);">&raquo;</a>
            <?php }
            ?>
            
        </li>

    </ul>