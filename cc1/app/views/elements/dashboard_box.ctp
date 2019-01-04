<?php if(count($data) > 0) { foreach($data as $data){ ?>
  <li onmouseover="dashMouseOver(this);" onmouseout="dashMouseOut(this);" class="box6Cont2" style="background:none; border-top:1px solid #f3f2f2;border-bottom:1px solid #f3f2f2;">
    <div class="packageDetail">
      <div class="leftFloat packageImg thumbImage1" style="height:100%; border:0px;"> <a href="/packages/view/<?php echo $data['packages']['url']; ?>" > <img border="0" href="javascript:void(0);" class="package_<?php echo $data['Log']['package_id']; ?>" title="Santa Banta Jokes" alt="Santa Banta Jokes" src="/img/spacer.gif"> </a> </div>
      <div style="margin-left:70px; width:725px;">
        <div class="packName" style="padding-bottom:5px;"> <a href="/packages/view/<?php echo $data['packages']['url']; ?>"><?php echo $data['packages']['name']; ?></a><span class="smallerFont" style="color:#888; font-size:0.7em; font-weight:normal;"> <?php echo $objGeneral->dateTimeFormat($data['Log']['timestamp']);?></span></div>
        <div class="packDesc oldSMS"> <?php echo ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target=\"_blank\" rel=\"nofollow\">\\0</a>", $data['Log']['content']); ?>
        </div>
        <div style="margin-top:2px; width:555px;" >            	
          <div style="float: right; font-size:0.75em; display:none;" class="SampJokesLinks actionLinks2">
              <ul>
                <li><a href="javascript:void(0)" onclick="sendMessage(<?php echo $data['Log']['id'];?>,'frnds','log');">Send to friends</a></li>
                <li class="lastElement"><a href="javascript:void(0)" onclick='subPackage("<?php echo $objMd5->encrypt($data['Log']['package_id'],encKey); ?>","sub");''>Subscribe to <?php echo $data['packages']['name']; ?></a></li>
              </ul>
              <br class="clearLeft">
          </div>
          <div>&nbsp;</div>
        </div>
        <div class="clearRight"></div>
      </div>
      <div class="clearLeft"></div>
    </div>        
  </li>
<?php }?>
<input type="hidden" name="dashMoreVal" id="dashMoreVal" value="<?php echo $upper; ?>"/>
<?php }else{ ?>
0
<?php } ?>
