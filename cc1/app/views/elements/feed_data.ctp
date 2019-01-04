<?php 
$arr = explode(",",AUTOMATED_PACKS);
if(in_array($packid,$arr)) {
	foreach($feedData as $feed){
?>
<div id="div_<?php echo $feed['rss_feeds']['id'];?>">
<span><input id="rss_<?php echo $feed['rss_feeds']['id']; ?>" type="checkbox" onclick="read(this,<?php echo $feed['rss_feeds']['id']; ?>);" value="<?php echo $feed['rss_feeds']['id']; ?>"> </span>
<span> <a id="data_<?php echo $feed['rss_feeds']['id'];?>" href="javascript:void(0);" onclick="showHide(<?php echo $feed['rss_feeds']['id']; ?>);"><?php echo $feed['rss_feeds']['title']; ?></a>&nbsp;-&nbsp;</span> 
<span style="font-size:9px;margin-right:5px;"><?php echo $feed['rss_feeds']['created']; ?></span>
<span style="font-size:10px;"><a target="_blank" href="<?php echo $feed['rss_feeds']['link']; ?>"><?php echo $feed['rss_feeds']['link']; ?> </a> </span>
</div>
<div id="desc_<?php echo $feed['rss_feeds']['id'];?>" style="display:none;"><?php echo $feed['rss_feeds']['description']; ?> </div>
<?php } ?>
<input type="button" value="Add" onclick="addFeeds()">
<input type="button" value="Remove" onclick="removeFeeds(<?php echo $packid; ?>)">
<?php } ?>