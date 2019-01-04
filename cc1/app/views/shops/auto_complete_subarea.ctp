<ul>
 <?php foreach($subarea as $s){
 		$id = $s['subarea']['id'];
 ?>
 	<li id="<?php echo $id; ?>"><?php echo $s['subarea']['name'];?></li>
<?php } ?>
</ul>