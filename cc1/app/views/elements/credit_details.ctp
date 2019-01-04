<?php //echo "<pre>"; print_r($resultTransaction); echo "</pre>";?>
<div style="width:760px;"> 
    <div>       
        <div>
          <div class="box2" style="margin:0px;">
            <!-- <div class="header ie6Fix2">Active Packages</div> -->
            <div class="pack2" style="margin:0px 10px 15px 0px;" >
            	<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="Transactions" class="ListTable">
        			<caption class="header">
			        Earned Credits
			        </caption>
			        <?php if ((count($resultTransaction)+count($resultTransaction1))  > 0) { ?>
                    <thead>
			          <tr class="noAltRow">
			            <th style="width:10%">Sr. No.</th>
			            <th style="width:30%">Redeem Date</th>
			            <th style="width:20%">Mobile No.</th>
			            <th style="width:20%">Type</th>
			            <th style="width:10%">Credits</th>
			          </tr>
			        </thead>
                    <tbody>
                      <?php $i = 0; foreach($resultTransaction as $trans) { $i++;?>
			          <tr>
			            <td><?php echo $i;?></td>
			            <td><?php echo $objGeneral->dateTimeFormat($trans['users_redeemed']['created']);?></td>
			            <td><?php echo substr($trans['users']['mobile'],0,6)."XXXX";?></td>
			            <td><?php echo 'Redeemed';?></td>
			            <td><?php echo REDEEM_AMT;?></td>			           			            
			          </tr>
			          <?php } ?>
			          <?php foreach($resultTransaction1 as $trans) { $i++;?>
			          <tr>
			            <td><?php echo $i;?></td>
			            <td><?php echo $objGeneral->dateTimeFormat($trans['users_redeemed']['created']);?></td>
			            <td><?php echo substr($trans['users']['mobile'],0,6)."XXXX"; ?></td>
			            <td><?php echo 'Earned';?></td>
			            <td><?php echo SHARER_CREDIT;?></td>			           			            
			          </tr>
			          <?php } ?>
			          </tbody>			        
			    </table>
			    <table>
			    	<tbody>	
			          <tr class="noAltRow">
			          <td colspan="5">
			          <br/><b>Earn More Credits</b>
			          <br/>Create your own coupon code and share it with your friends, family, workmates or anybody on the internet. If people redeem the coupon code shared by you, on every redemption, you get Rs.<?php echo SHARER_CREDIT; ?> instantly in your account. <br/><a href="/messages/refCode" class="retailBut enabledBut strng" style="color:white !important">Start sharing</a> or <a href="javascript:void(0);" onclick="$('hiw').simulate('click');">Click here to know more</a>.
			          <td>
			          </tr>
			         </tbody>			        
			    </table>
			          <?php } else { ?>
			    <table>
			    	<tbody>      
			          	<tr class="noAltRow"><td>
							<br/>Now take the advantage of SMSTadka FREE credits.
		           			<br/><br/>SMSTadka is offering FREE credits to its users. If you have an SMSTadka free coupon code, you can <a href="javascript:void(0);" onclick="$('drafts').simulate('click');">redeeem it</a> and earn Rs.<?php echo REDEEM_AMT; ?> credits! <a href="javascript:void(0);" onclick="$('hiw').simulate('click');">Click here to know more</a>.
		           			<br/><br/>And also you can create your own coupon code and share it with your friends, family, workmates or anybody on the internet. If people redeem the coupon code shared by you, on every redemption, you get Rs.<?php echo SHARER_CREDIT; ?> instantly in your account. <br/><a href="/messages/refCode" class="retailBut enabledBut strng"  style="color:white !important">Start sharing</a> or <a href="javascript:void(0);" onclick="$('hiw').simulate('click');">Click here to know more</a>.																			
						</td></tr>
						<tbody>			        
			    </table>
			 		<?php } ?>                    			   
          	</div>
        </div>
    </div>
</div>
<br class="clearRight" />
</div>
<script>$$('table.ListTable tr:nth-child(odd)').invoke("addClassName", "altRow");</script>