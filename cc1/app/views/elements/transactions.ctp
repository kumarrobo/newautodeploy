<div class="box2" style="width:670px;">
            
            <div class="pack2" style="margin-left:0px;min-height:100px;max-height:600px;overflow-y:auto;">
            	<table border="0" cellpadding="0" cellspacing="0" style="width:650px;" summary="Transactions">
        			<caption class="header">
			        <span class="balance">Balance : <span><img class='rupee1' src='/img/rs.gif'/></span><?php echo number_format($objGeneral->getBalance($_SESSION['Auth']['User']['id']),2,'.','') ?> </span>Transaction(s)
			        </caption>
			          <tr>
			            <th style="width:30%">Date</th>
			            <th style="width:40%">Particulars</th>
			            <th style="width:15%" class='number'>Debit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			            <th style="width:15%" class='number'>Credit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			          </tr>
			          <?php $i = 0; foreach($resultTransaction as $trans) { $i++;?>
			          <tr class="<?php // if($i%2 == 1) echo 'altRow'; ?>">
			            <td><?php echo $objGeneral->dateTimeFormat($trans['transactions']['timestamp']);?></td>
			            <?php if($trans['transactions']['type'] == TRANS_USER_CREDIT){ 
			            		echo "<td> Recharge </td>";
			            		echo "<td class='number'> - </td>";
			            		echo "<td class='number'> ".$trans['transactions']['amount'] ."</td>";
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
			            		
			            		echo "<td class='number'> ".$trans['transactions']['amount'] ."</td>";
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
			            	else if($trans['transactions']['type'] == TRANS_ADMIN_FREE_CREDIT) { 
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
			          <?php } ?>
			          
			    </table>
            </div>
          </div>
       