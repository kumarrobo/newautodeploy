<script>

function setAction()
   {
	document.ussdLogs.action="/panels/ussdLogs/<?php echo $mob;?>/"+$('from').value+"/"+$('to').value;
	document.ussdLogs.submit();
   }
</script>	
<div id= "appRequest" style="display:block; text-align:left; padding:5px;   border-color: #111111; border-width: 1px; border-style: solid; color: #000000; font-size: 13px; font-family: Arial; overflow: auto;">

<form name="ussdLogs" method="POST" >
Retailer Mobile No  : <?php if(isset($mob))echo $mob;?> <br/>
<!--Retailer Id  : <?php if(isset($info[0]['retailers']['id']))echo $info[0]['retailers']['id'];?> <br/>-->
From Date  : <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!empty($from))echo $from;?>" />
To Date :   <input type="text" name="to"   id="to"    onmouseover="fnInitCalendar(this, 'to','close=true')"   value="<?php if(!empty($to))echo $to;?>" />
<input type="button" value="Submit" onclick="setAction();" />
</form>

			<table id="ret" border="1" cellpadding="5" width="100%" cellspacing="0" align="left">
			<tr><td colspan="9" align="center">	Retailer USSD Transactions</td></tr>
			<tr>
			<th>Index</th>
  			<!-- <th width="5%">shop_transaction_id </th> -->
  			<th>Log Id</th>
  			<th>Type</th>
  			<th>Request</th>
  			<th>Response</th>
  			<th>Error</th>
  			<th>Time</th>
			</tr>
			<?php
                        $i = 1;
			foreach($logs as $data)
			{ ?>
                        
			<tr>
                        <td><?php  echo $i ;?></td>
			<td><?php  echo $data['ul']['sessionid'] ;?></td>
                        <td><?php  if($data['ul']['type'] == 1) echo "Recharge" ; else if($data['ul']['type'] == 3) echo "Last Trans";?></td>
                        <td><?php  echo $data['ul']['request'] ;?></td>
                        <td><?php  echo $data['ul']['sent_xml'] ;?></td>
                        <td><?php  echo $objShop->ussdErrors($data['ul']['extra']) ;?></td>
                        <td><?php  echo $data['ul']['date']." ".$data['ul']['time'] ;?></td>
			
			</tr>
			<?php $i++;
			}
			?>		
			</table>		
		


</div>