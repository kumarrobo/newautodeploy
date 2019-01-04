<?php foreach($data as $comment) {?>

<div class="appDataCont appDataContClr1">
	<div class="rightFloat">
		<span style="padding-top: 2px;" class="leftFloat"><?php echo $comment['comments']['created']; ?></span>
	</div>
	<span><a href="/groups/getUserInfo/<?php echo $comment['users']['mobile'];?>"><?php echo $comment['users']['mobile']; ?></a></span>
	<p class="appDataDesc"><?php echo $comment['comments']['comment']; ?></p>
</div>

<?php } ?>