<div style="width:760px;"> 
    <div>       
        <div>
          <div class="box2" style="margin:0px;">
            <!-- <div class="header ie6Fix2">Active Packages</div> -->
            <div class="pack2" style="margin:0px 10px 15px 0px;" >
            	<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="Transactions" class="ListTable">
        			<caption class="header">
			        	Expired Packages
			        </caption>
			        <?php if (count($resultExpired) > 0) { ?>
                    <thead>
			          <tr class="noAltRow">			            
			            <th style="width:30%">Package Name</th>			            
                        <th style="width:17%">Subscribed Date</th>
			            <th style="width:17%">Expiry Date</th>
                        <th style="width:16%" class='number'>Amount (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                        <th style="width:10%">&nbsp;</th>
			          </tr>
                    </thead>
                    <tbody>
                    	<?php foreach($resultExpired as $deactive) {?>
                    	<tr>
                        	<td>
                        	<?php if($deactive['PackagesUser']['trial_flag'] == 0) {
                            	echo $this->Html->link(__($deactive['packages']['name'], true), array('controller' => 'packages','action' => 'view',$deactive['packages']['url']));
                            }
                            else {
                            	echo $this->Html->link(__($deactive['packages']['name'] . " (Trial)", true), array('controller' => 'packages','action' => 'view',$deactive['packages']['url']));
                            }
                            ?>
                            </td>
                            <td><?php echo $objGeneral->dateFormat($deactive['0']['start']);?></td>
                            <td><?php echo $objGeneral->dateFormat($deactive['0']['end']);?></td>
                            <td class="number">
                            	<?php if($deactive['PackagesUser']['trial_flag'] == 0) {
                            			echo $deactive['packages']['price']; 
                            		}
                            		else {
                            			echo "FREE";
                            		}
                            		
                            	?>	
                            </td>
                            <td>
                            	<?php if($deactive['PackagesUser']['trial_flag'] == 0) { ?>
                            	<a class='buttSprite1' href='javascript:void(0);' onclick='subPackage("<?php echo $objMd5->encrypt($deactive['PackagesUser']['package_id'],encKey); ?>","sub");'><img class='butSubscribe1' src='/img/spacer.gif' /></a>
                            	<?php } ?>
                            </td>
                        </tr>
                    	<?php } ?> 
              		</tbody>                                    
					<?php } else { ?>
                	<tbody><tr class="noAltRow"><td>
						<p class="blankState"> There seems to be no expired package in your account. <br>
Expired packages section displays the list of packages which were subscribed by you and are presently inactive. </p></td></tr></tbody>
			 		<?php } ?>
				</table>
				<?php if(count($resultExpired) == 5) {?>
              		<div class="rightFloat"> <a href='javascript:void(0);' onclick='allExpiredPacks();'>  Click here to view all expired packages</a></div><br class="clearRight" />
              	<?php } ?>
          	</div>
        </div>
    </div>
</div>
<br class="clearRight" />
</div>
<script>$$('table.ListTable tr:nth-child(odd)').invoke("addClassName", "altRow");</script>