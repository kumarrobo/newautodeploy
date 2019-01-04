<ul id="recentMessege" class="recentMessege">			
  <?php foreach($recentMessages as $message) { ?>
  <li class="rcntMssg box6Cont2" style="margin-bottom:3px;" onmouseout="dashMouseOut(this,<?php echo $message['Log']['id']; ?>);" onmouseover="dashMouseOver(this,<?php echo $message['Log']['id']; ?>);" id="">
    <div class="packageDetail">
      <div style="border: 0px;" class="leftFloat packageImg thumbImage1"><a href="/packages/view/<?php echo $message['packages']['url']; ?>"> <img border="0" src="/img/spacer.gif" alt="<?php echo $message['packages']['name']; ?>" title="<?php echo $message['packages']['name']; ?>" class="package_<?php echo $message['Log']['package_id']; ?>" href="javascript:void(0);"></a></div>
      <div class="adjust1">
        <div style="padding-bottom: 5px;" class="packName"> <a href="/packages/view/<?php echo $message['packages']['url']; ?>"><?php echo $message['packages']['name']; ?></a><span style="color: rgb(136, 136, 136); font-size: 0.7em; font-weight: normal; padding-left:5px" class="smallerFont"><?php echo $objGeneral->dateTimeFormat($message['Log']['timestamp']);?></span></div>
        <div class="packDesc oldSMS"><?php echo ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target=\"_blank\" rel=\"nofollow\">\\0</a>", $message['Log']['content']); ?>      
		</div>
      </div>
      <div class="clearLeft"></div>
      <div style="height:23px;">
    	<div id="hidMsg<?php echo $message['Log']['id']; ?>" style="float: right; font-size:0.75em; display:none;" class="SampJokesLinks actionLinks2">
      		<ul style="padding-top:5px;margin-left:0px;margin-right:0px; padding:0px; padding-top:3px;">
        		<li style="background:#f2f1f1;"><a href="javascript:void(0)" onclick="sendMessage(<?php echo $message['Log']['id'];?>,'frnds','log');">Send to friends</a></li>
        		<li style="background:#f2f1f1;" class="lastElement"><a href="javascript:void(0)" onclick='subPackage("<?php echo $objMd5->encrypt($message['Log']['package_id'],encKey); ?>","sub");''>Subscribe to <?php echo $message['packages']['name']; ?></a></li>
      		</ul>
      		<br class="clearLeft">
  		</div>
      </div>
    </div>    			            
  </li>
  <?php } ?>
</ul>