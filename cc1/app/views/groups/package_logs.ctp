<div class="box2">
            
            <div style="width:100%;max-height:800px;overflow-y:auto">
            	<table border="0" cellpadding="0" cellspacing="0" summary="Logs" class="ListTable">
        			<caption class="header">
			        Messages Sent
			        </caption>
			          <tr>
			            <th style="width:45%">Message</th>
			            <th style="width:15%">Package</th>
			            <th style="width:20%">Users</th>
			            <th style="width:20%">Time</th>
			          </tr>
			          
			          <?php foreach($logData as $log) {?>
			          <tr>
			            <td><?php echo $log['Log']['content'];?></td>
			            <td><?php echo $log['Log']['packageName'];?></td>
			            <td><?php echo $log['Log']['users'];?></td>
			            <td><?php echo $objGeneral->dateTimeFormat($log['Log']['timestamp']);?></td>
			            
			          </tr>
			          <?php } ?>
			          
			    </table>
            </div>
          </div>
       <script>$$('table.ListTable tr:nth-child(odd)').invoke("addClassName", "altRow");</script>