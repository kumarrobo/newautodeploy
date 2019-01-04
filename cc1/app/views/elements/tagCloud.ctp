<div style="margin-top:20px;">
<div class="title4">Most Popular Links</div>
<?php $i=0; $j=0; foreach($tagCloud as $tag) {  
if($tag['0']['counts'] > 800) $fontSize = 19;
else if ($tag['0']['counts'] > 600) $fontSize = 16;
else if ($tag['0']['counts'] > 400) $fontSize = 13;
else if ($tag['0']['counts'] > 200) $fontSize = 10;
else $fontSize = 9;
?>
<?php if($i % 4 == 0) { ?>
<a href="/packages/view/<?php echo $popularPacks[$j]['Package']['url']; ?>"><span style="margin-right:5px;font-size:15pt;color:#EC724A"><?php echo $popularPacks[$j]['Package']['name']; ?></span></a>
<?php $j++;} ?>
<a href="/tags/view/<?php echo $tag['tags']['url']; ?>"><span style="margin-right:5px;font-size:<?php echo $fontSize;?>pt"><?php echo $tag['tags']['name']; ?></span></a>
<?php $i++;} ?>
</div>