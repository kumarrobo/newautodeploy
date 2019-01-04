<div class="rightCol5 rightFloat">
    <div class="rightColSpace">
    <?php 
  if(count($recentMsgs) > 0)
  {
  ?>
      	<div id="recentMsgs" style="position:relative">
      	  <div class="antina" style=""></div>                  
          <div class="SampJokesTitle">SAMPLE MESSAGES(SMS)</div>
          <div id="" style="height:415px;">
            <div class="container" style="width:280px;">
              <?php if(!in_array($packageData['Package']['id'],explode(",",DONT_SHOW_MSG))) { ?>		 
              <input type="hidden" id="msgNum" value="1"/>
              <ul class="recentMsgsCont" style="height:345px; overflow:hidden;">
              <?php $i = 1; foreach($recentMsgs as $msg) {?>
                <li class="sampleSMS" id="sampSMS<?php echo $i;?>" style="<?php if($i > 1) echo 'display:none'; ?>">
                  <p><?php echo $msg['Log']['content']; ?></p>
                </li>
                <?php $i++;} ?>
              </ul>
              
              <div style="margin-left:40%; padding:10px 0px;">
                <ul class="sampleNo">                  
                  <li><a href="javascript:void(0)" onclick="showSample('pre')">&lt;</a>                  
                  <li><a href="javascript:void(0)" onclick="showSample('next')">&gt;</a></li>
                </ul>
                <br class="clearLeft" />
              </div>
              <?php
              	if(isset($_SESSION['Auth']['User']) && $_SESSION['Auth']['User']['group_id'] == 2)
              	{
              ?>
              		<div align="center"><a href="/groups/allRecentMsgs/<?php echo $packageData['Package']['id']; ?>" target="_blank">View All</a></div>
              <?php
              	}
              ?>
              <?php } else { ?>
              	<ul class="recentMsgsCont" style="height:345px; overflow:hidden;">
              	<li style="padding-top:125px;font-weight:bold;font-size:1.3em;text-align:center">
              	Content cannot be displayed !!
              	</li>
              	</ul>
              <?php } ?>
            </div>
          </div>
		</div>
  <?php
  }
  ?>
  		<!--<br>
  		<iframe src="http://www.facebook.com/plugins/recommendations.php?site=www.smstadka.com&amp;width=284&amp;height=300&amp;header=true&amp;colorscheme=light&amp;font&amp;border_color" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:300px;" allowTransparency="true"></iframe>
  		<br><br>-->
      </div>
  </div>