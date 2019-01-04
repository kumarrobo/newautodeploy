<?php //echo "<pre>"; print_r($resultTransaction); echo "</pre>";?>
<div style="width:760px;"> 
    <div>       
        <div>
          <div class="box2" style="margin:0px;">
            <!-- <div class="header ie6Fix2">Active Packages</div> -->
            <div class="pack2" style="margin:0px 10px 15px 0px;" >
            	<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="Transactions" class="ListTable">
        			<caption class="header">
			        Transaction(s)
			        </caption>
			        <?php if (count($resultTransaction) > 0) { ?>
                    <thead>
			          <tr class="noAltRow">
			            <th style="width:20%">Date</th>
			            <th style="width:20%">Particulars</th>
			            <th style="width:13%" class='number'>Debit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			            <th style="width:13%" class='number'>Credit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			          </tr>
			        </thead>
                    <tbody>
                      <?php $i = 0; foreach($resultTransaction as $trans) { $i++;?>
			          <tr>
			            <td><?php echo $objGeneral->dateTimeFormat($trans['transactions']['timestamp']);?></td>
			            <?php if($trans['transactions']['type'] == TRANS_USER_CREDIT){ 
			            		echo "<td> Recharge </td>";
			            		echo "<td> - </td>";
			            		echo "<td>".$trans['transactions']['amount'] ."</td>";
			            	}
			            else if($trans['transactions']['type'] == TRANS_ADMIN_DEBIT){ 
			            	if($trans['transactions']['package_id'] != ''){
			            		echo "<td> Package Subscribed (".$trans['transactions']['name'].")</td>";
			            	}
			            	else if($trans['transactions']['app_id'] != ''){
			            		echo "<td> App Subscribed (".$trans['transactions']['name'].")</td>";
			            	}
			            	else {
			            		echo "<td> Message Sent </td>";
			            	}			            		
			            	echo "<td class='number'>".$trans['transactions']['amount'] ."</td>";
			            	echo "<td class='number'> - </td>";
			            }
			            else if($trans['transactions']['type'] == TRANS_ADMIN_UNSCR_CREDIT){ 
			            	if($trans['transactions']['package_id'] != ''){
		            			echo "<td> Money Refunded (Package: ".$trans['transactions']['name'].")</td>";
		            		}
		            		else if($trans['transactions']['package_id'] != ''){
		            			echo "<td> Money Refunded (App: ".$trans['transactions']['name'].")</td>";
		            		}
			            	echo "<td class='number'>-</td>";
			            	echo "<td class='number'> ".$trans['transactions']['amount']."</td>";
			            }
			            else if($trans['transactions']['type'] == TRANS_ADMIN_FREE_CREDIT){ 
			            	echo "<td> Free Credit </td>";
			            	echo "<td class='number'> - </td>";
			            	echo "<td class='number'> ".$trans['transactions']['amount'] ."</td>";
			            }
			            else if($trans['transactions']['type'] == TRANS_RETAIL){ 
			            		echo "<td> Recharge via retail card </td>";
			            		echo "<td class='number'> - </td>";
			            		echo "<td class='number'> ".$trans['transactions']['amount'] ."</td>";
			            }            
			            ?>
			          </tr>
			          <?php }?>
                    <tbody>
			        <?php } else { ?>
						<p class="blankState"> You have made no transaction yet.<br/>
						Transaction table shows you all the details related to your credit transactions. </p>
			 		<?php } ?>
			    </table>
			    <?php if(count($resultTransaction) == 10) {?>
			    	<div class="rightFloat"> <a href='javascript:void(0);' onclick='allTransactions();'> Click here to view all transactions</a></div><br class="clearRight" />
			    <?php }?>
          	</div>
        </div>
    </div>
</div>
<br class="clearRight" />
</div>
<script>$$('table.ListTable tr:nth-child(odd)').invoke("addClassName", "altRow");</script>