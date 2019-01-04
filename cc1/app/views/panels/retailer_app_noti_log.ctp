<script>

function setAction()
   {
	document.appNotificationLogs.action="/panels/appNotificationLogs/<?php echo $mob;?>/"+$('from').value+"/"+$('to').value;
	document.appNotificationLogs.submit();
   }
</script>	
<div id= "appRequest" style="display:block;  text-align:left; padding:5px;   border-color: #111111; border-width: 1px; border-style: solid; color: #000000; font-size: 13px; font-family: Arial; overflow: auto;" >

<form name="appNotificationLogs" method="POST" >
Retailer Mobile No  : <?php if(isset($mob))echo $mob;?> <br/>
From Date  : <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!empty($from))echo $from;?>" />
To Date :   <input type="text" name="to"   id="to"    onmouseover="fnInitCalendar(this, 'to','close=true')"   value="<?php if(!empty($to))echo $to;?>" />
<input type="button" value="Submit" onclick="setAction();" />
</form>
</div>
<div style="display:block; text-align:left; padding:5px; border-color: #111111; border-width: 1px; border-style: solid; color: #000000; font-size: 13px; font-family: Arial; overflow: auto;">

			<table id="ret" border="1" cellpadding="5" width="95%" cellspacing="0">
			<tr><td colspan="9" align="center"> Retailer App Notification Transactions</td></tr>
			<tr>
                          
			<th>Index</th>
  			<!-- <th width="5%">shop_transaction_id </th> -->
  			<th>Log Id</th>
  			<th>Notification Type</th>
                        
  			<th>Notification</th>
                        <th>App Type</th>
  			<th>Response</th>
  			<th>Time</th>
                        <th>User Notification Key/URL</th>
			</tr>
			<?php
                        $i = 1;
			foreach($logs as $data)
			{ ?>
                        
			<tr>
                        <td><?php  echo $i ;?></td>
			<td><?php  echo $data['notificationlog']['id'] ;?></td>
                        <td><?php  echo $data['notificationlog']['notify_type']; ?></td>
                       
                        <td><?php  echo $data['notificationlog']['msg'] ;?></td>
                        <td><?php  echo $data['notificationlog']['user_type'] ;?></td>
                        <td><?php  echo $data['notificationlog']['response'] ;?></td>
                        <td><?php  echo $data['notificationlog']['created'] ;?></td>
                         <td ><?php  echo $data['notificationlog']['user_key'] ;?></td>
			
			</tr>
			<?php $i++;
			}
			?>		
			</table>		
		


</div>