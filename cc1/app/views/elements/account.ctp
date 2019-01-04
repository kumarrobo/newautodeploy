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
                      <tbody>            <?php foreach($resultActive as $active) {?>
             
                                      
                                      <tr>
                                      	
                                       <td>
                                      <?php echo $this->Html->link(__($active['packages']['name'], true), array('controller' => 'packages','action' => 'view',$active['packages']['url'])) ?>
                                      	</td>
                                        <td><?php echo $objGeneral->dateFormat($active['PackagesUser']['start']);?></td>
                                        <td><?php echo $objGeneral->dateFormat($active['PackagesUser']['end']);?></td>
                                        <td class="number"><?php echo $active['packages']['price'];?></td>
                                        <td>
                                        	<a href="javascript:void(0);" onclick="subPackage('<?php echo $objMd5->encrypt($active['PackagesUser']['package_id'],encKey);?>','unsub');"> Unsubscribe </a>
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
              <?php if(count($resultActive) == 5) {?>
              <div class="rightFloat field"> <a href='javascript:void(0);' onclick='allActivePacks();'> Click here to view all active packages</a></div><br class="clearRight" />
              <?php }?>
          </div>
            </div>
            
         
          
         
          <div class="box2" style="margin:0px;">            
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
                                      <?php echo $this->Html->link(__($deactive['packages']['name'], true), array('controller' => 'packages','action' => 'view',$deactive['packages']['url'])) ?>
                                      	</td>
                                        <td><?php echo $objGeneral->dateFormat($deactive['PackagesUser']['start']);?></td>
                                        <td><?php echo $objGeneral->dateFormat($deactive['PackagesUser']['end']);?></td>
                                        <td class="number"><?php echo $deactive['packages']['price'];?></td>
                                        <td>
                                        	<a class='buttSprite1' href='javascript:void(0);' onclick='subPackage("<?php echo $objMd5->encrypt($deactive['PackagesUser']['package_id'],encKey); ?>","sub");'><img class='butSubscribe1' src='/img/spacer.gif' /></a>
                                        
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
         
          <div class="box2" style="margin:0px;">
            
            <div class="pack2" style="margin:0px 10px 15px 0px;">
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
			            <td><?php echo $objGeneral->dateTimeFormat($trans['Transaction']['timestamp']);?></td>
			            <?php if($trans['Transaction']['type'] == TRANS_USER_CREDIT){ 
			            		echo "<td> Recharge </td>";
			            		echo "<td> - </td>";
			            		echo "<td>".$trans['Transaction']['amount'] ."</td>";
			            	}
			            	else if($trans['Transaction']['type'] == TRANS_ADMIN_DEBIT){ 
			            		if($trans['Transaction']['package_id'] != ''){
			            			echo "<td> Package Subscribed (".$trans['packages']['name'].")</td>";
			            		}
			            		else {
			            			echo "<td> Message Sent </td>";
			            		}			            		
			            		echo "<td class='number'>".$trans['Transaction']['amount'] ."</td>";
			            		echo "<td class='number'> - </td>";
			            	}
			            	else if($trans['Transaction']['type'] == TRANS_ADMIN_FREE_CREDIT){ 
			            		echo "<td> Free Credit </td>";
			            		echo "<td class='number'> - </td>";
			            		echo "<td class='number'> ".$trans['Transaction']['amount'] ."</td>";
			            	}
			            	else if($trans['Transaction']['type'] == TRANS_RETAIL){ 
			            		echo "<td> Recharge via retail card </td>";
			            		echo "<td class='number'> - </td>";
			            		echo "<td class='number'> ".$trans['Transaction']['amount'] ."</td>";
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
			    <?php if(count($resultTransaction) == 5) {?>
			    <div class="rightFloat"> <a href='javascript:void(0);' onclick='allTransactions();'> Click here to view all transactions</a></div><br class="clearRight" />
			     <?php }?>
            </div>
            
          </div>
          
          <div class="box2" style="margin:0px;">            
            <div class="pack2" style="margin:0px 10px 15px 0px;" >
            	<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="Logs" class="ListTable">
        			<caption class="header">
			        Logs
			        </caption>
                    <tr class="noAltRow">
			            <td><a href='javascript:void(0);' onclick='allMessageLogs("By");'>Messages Sent By You</a></td>
			            <td><a href='javascript:void(0);' onclick='allMessageLogs("To");'>Messages Sent To You</a></td>
			            <td><a href='javascript:void(0);' onclick='allPendingTasks();'>Pending Actions</a></td>
			        </tr>                                 

              </table>
          </div>
            </div>
        </div>
  
      </div>
      <br class="clearRight" />
    </div>
    <script>$$('table.ListTable tr:nth-child(odd)').invoke("addClassName", "altRow");</script>