<div class="rightCol5 rightFloat">
    <div class="rightColSpace">
      <div class="ie6Fix1">
        <div class="box6L">
          <div class="box6R">
            <div class="box6B">
              <div class="box6BL">
                <div class="box6BR">
                  <div style="background: url(/img/box5_t.gif) repeat-x; height:2px;">&nbsp;</div>
                  <div style="border-bottom:1px dashed #000; padding:5px 12px;" class="SampJokesTitle">Recent Message</div>
                  <div id="" style="height:400px;">
                    <!-- vertical_carousel -->
                    <div class="container" style="width:280px;">
                      <input type="hidden" id="msgNum" value="1"/>	
                      <ul class="recentMsgsCont" style="height:340px; overflow:hidden;">
                      <?php $i = 0; foreach($logData as $log) { $i++;?>
                        <li class="sampleSMS" id="sampSMS<?php echo $i;?>" style="<?php if($i > 1) echo 'display:none'; ?>">
                          <p>Time : <?php echo date('jS M, Y g:i A',strtotime($log['Log']['timestamp'])); ?> <br><br><?php echo $log['Log']['content'];?> </p>
                        </li>
                      <?php }?>
                      </ul>
                      <br />
                      <div style="margin-left:40%; padding:10px 0px;">
		                <ul class="sampleNo">                  
		                  <li><a href="javascript:void(0)" onclick="showSample('pre')">&lt;</a>                  
		                  <li><a href="javascript:void(0)" onclick="showSample('next')">&gt;</a></li>
		                </ul>
		                <br class="clearLeft" />
		              </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>