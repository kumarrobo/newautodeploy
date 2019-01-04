<div class="pack2" style="margin-left:0px;">
<table border="0" cellpadding="0" cellspacing="0" summary="List Users" width="100%">
	<caption class="header">
    <?php echo $type . " (Total: " . count($data) . ")"; ?>
    </caption>
	  <tr>
	  	<?php foreach ($data[0] as $in) {?>
	  	<?php foreach ($in as $k=>$v) {?>
	  	<th><?php echo $k; ?></th>
	  	<?php }}?>  
	  </tr>
	  	          
<?php foreach ($data as $user) { ?>
		<tr>
		<?php foreach ($user as $in) {
		foreach ($in as $k=>$v) {
?>
	  		<td>
	  		<?php 
	  			if($k != 'mobile') echo $v;
	  			else echo '<a href="/groups/getUserInfo/'.$v.'">'.$v.'</a>'; 
	  		?>
	  		</td>
<?php }}?>
	</tr>
	<?php } ?>
</table>
</div>