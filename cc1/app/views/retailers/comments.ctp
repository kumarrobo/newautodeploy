<?php foreach($data as $comment) {?>

<div class="appDataCont appDataContClr1">
	<div class="rightFloat">
		<span style="padding-top: 2px;" class="leftFloat"><?php echo $comment['comments']['created']; ?></span>
	</div>
	<span><a href="/retailers/index/<?php echo $comment['retailers']['id'];?>">Retailer: <?php echo $comment['retailers']['name']; ?></a></span>
	<p class="appDataDesc"><?php echo $comment['comments']['comment']; ?></p>
</div>

<?php } ?>