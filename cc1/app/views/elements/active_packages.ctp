<div style="width:760px;"> 
    <div>       
        <div>
          <div class="box2" style="margin:0px;">
            <!-- <div class="header ie6Fix2">Active Packages</div> -->
            <div class="pack2" style="margin:0px 10px 15px 0px;" >
            	<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="Transactions" class="ListTable">
        			<caption class="header">
			        Active Packages
			        </caption>
			        <?php if (count($resultActive) > 0) { ?>
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
                    <?php foreach($resultActive as $active) {?>
                    	<tr>
                        	<td>
                        		<?php if($active['PackagesUser']['trial_flag'] == 0) {
                            		echo $this->Html->link(__($active['packages']['name'], true), array('controller' => 'packages','action' => 'view',$active['packages']['url'])); 
                            	}
                            	else {
                            		echo $this->Html->link(__($active['packages']['name'] . " (Trial)", true), array('controller' => 'packages','action' => 'view',$active['packages']['url']));
                            	}
                            	?>
                            	
                            </td>
                            <td><?php echo $objGeneral->dateFormat($active['PackagesUser']['start']);?></td>
                            <td><?php echo $objGeneral->dateFormat($active['PackagesUser']['end']);?></td>
                            <td class="number">
                            	<?php if($active['PackagesUser']['trial_flag'] == 0) {
                            			echo $active['packages']['price']; 
                            		}
                            		else {
                           				echo "FREE"; 			
                            		}
                            	?>	
                            </td>
                            <td>
                            	<?php if($active['PackagesUser']['trial_flag'] == 0) { ?>
                            	<a href="javascript:void(0);" onclick="subPackage('<?php echo $objMd5->encrypt($active['PackagesUser']['package_id'],encKey);?>','unsub');"> Unsubscribe </a>
                            	<?php } else { ?>
                            	
                            	<?php }?>
                            </td>
                        </tr>
                    <?php } ?> 
              		</tbody>                                    
					<?php } else { ?>
            	 	<tbody><tr class="noAltRow"><td>
						<p class="blankState">
							You have not subscribed to any of the packages. <a href="/users/view">Click here to choose your favorite package from the various categories.</a>  <br> This section will show you all the active packages of yours.</p></td></tr></tbody>
			 		<?php } ?>
				</table>
          	</div>
        </div>
    </div>
</div>
<br class="clearRight" />
</div>
<script>$$('table.ListTable tr:nth-child(odd)').invoke("addClassName", "altRow");</script>