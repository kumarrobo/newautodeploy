<ul>
	<li><a href="javascript:void(0);" onclick="sendMessage(<?php echo $id;?>,'me','msg');"> SMS Me </a></li>
    <li><a href="javascript:void(0);" onclick="sendMessage(<?php echo $id;?>,'frnds','msg');"> SMS to Friends </a></li>
    <li><a href="javascript:void(0);" onclick="openPopup('<?php echo $url; ?>')"><img src="/img/spacer.gif" class="otherSprite oSPos15" alt="Twitter icon" title="Twitter icon" class="twitter" /> Tweet it</a> </li>
    <li><a name="fb_share" type="button_count" share_url="<?php echo '/messages/facebook/'.$url; ?>"></a></li>
</ul>			