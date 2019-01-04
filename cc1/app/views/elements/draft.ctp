<div style="width:760px;"> 
    <div>       
        <div>
          <div class="box2" style="margin:0px;">
            <!-- <div class="header ie6Fix2">Active Packages</div> -->
            <div class="pack2" style="margin:0px 10px 15px 0px;" >
            	<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="Transactions">
        <caption class="header">
      Pending Actions
        </caption>
        <?php if (count($resultTransaction) > 0) { ?>
          <tr>
            <th align="left" style="width:16%">Date</th>
            <th align="left" style="width:20%">Type</th>
            <th align="left" style="width:36%">Description</th>
            <th align="left" style="width:10%">Amount</th>
            <th align="left" style="width:14%">Option</th>
          </tr>
          
          <?php $i = 0; foreach($resultTransaction as $trans) { $i++;?>
          <?php	$draft_date = $objGeneral->dateFormat($trans['Draft']['draftedOn']); ?>
          <tr id="draft_<?php echo $trans['Draft']['id'];?>" class="<?php // if($i%2 == 1) echo 'altRow'; ?>">
            <td><?php echo $draft_date;?></td>
            <?php if($trans['Draft']['type'] == 'pck'){ 
            		echo "<td> Package</td>";
            		}else if($trans['Draft']['type'] == 'me'){
            		echo "<td> Message to me</td>";
            		}else if($trans['Draft']['type'] == 'frd'){
            		echo "<td> Message to friends : ".$trans['Draft']['nicknames']."</td>";
            		}
            		
            	
            		echo "<td>".$trans['Draft']['content']."</td>";
            		echo "<td>".$trans['Draft']['amount']."</td>";
					echo "<td style='padding-bottom:10px;'>";
            		if($trans['Draft']['type'] == 'pck'){ 
            		echo "<a class='buttSprite1' href='javascript:void(0);' onclick='subPackage(\"".$objMd5->encrypt($trans['Draft']['refid'],encKey)."\",\"sub\");'><img class='butSubscribe1' src='/img/spacer.gif' />";
            		}else if($trans['Draft']['type'] == 'me'){
            		echo "<a class='buttSprite1' href='javascript:void(0);' onclick='sendMessageDraft(\"".$objMd5->encrypt($trans['Draft']['id'],encKey)."\");' ><img class='butSend' src='/img/spacer.gif' />";
            		}else if($trans['Draft']['type'] == 'frd'){
            		echo "<a class='buttSprite1' href='javascript:void(0);' onclick='sendMessageDraft(\"".$objMd5->encrypt($trans['Draft']['id'],encKey)."\");'><img class='butSend' src='/img/spacer.gif' />";
            		}
					echo "</a><a class='buttSprite1' href='javascript:void(0);' onclick='deleteDraft(".$trans['Draft']['id'].");'><img class='butDelete' src='/img/spacer.gif' /></a>";
					echo "</td>";
					
            ?>
            
          </tr>
          <?php }?>
           <?php } else {?>
           <tr class="noAltRow"><td>
           	<p class="blankState"> You have no pending actions. <br /> This section shows you the history (Record) of all the actions pending because of insufficient balance in your account at the time of sending a message or subscribing a package. You can come here and complete your pending actions at any point of time.</p>
           	</td></tr>
           <?php }?>
        </table>
          	</div>
        </div>
    </div>
</div>
<br class="clearRight" />
</div>