<div id="innerBlock" class="ie6Fix2">
	<?php foreach($allApps as $app) { ?>
		<div class="appBox">
            <div class="appBoxSpace"> <img title="<?php echo $app['SMSApp']['name']; ?>" src="/img/spacer.gif" class="appImages appImg<?php echo $app['SMSApp']['id'];?> rightFloat" />
              <div class="appBoxTitle"><a href="javascript:void(0);" onclick="subApp('<?php echo $app['SMSApp']['controller_name']; ?>',1);"><?php echo $app['SMSApp']['name']; ?></a></div>
              <div class="appBoxCont">
                <div class="strng">Price: <?php echo $objGeneral->getPrice($app['SMSApp']['basic_price']) . " " . $app['SMSApp']['price_tag']; ?></div>
                <ul class="appBoxPoint">
                  <?php $desc = explode("\n",$app['SMSApp']['description']);
                  foreach($desc as $msg) { 
                  echo "<li>".$msg."</li>";
                 } ?>
                </ul>
                <div class="rightFloat">
                  <input type="button" class="css3But2" onclick="subApp('<?php echo $app['SMSApp']['controller_name']; ?>',0);" value="<?php echo $app['SMSApp']['call_to_action']; ?>" >
                </div>                
              </div>
              <div class="clearRight">&nbsp;</div>
            </div>
          </div>
        <?php } ?>
</div>
<div class="clearLeft">&nbsp;</div>	