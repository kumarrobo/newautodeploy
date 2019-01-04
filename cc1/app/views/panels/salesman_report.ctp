<script>

function showInfo(){
	var sel=$('distDD');
    var distid=sel.options[sel.selectedIndex].value;
	document.salesmanReport.action="/panels/salesmanReport/"+distid;
	document.salesmanReport.submit();
}

function setAction(){
	var sel=$('salesmanDD');
    var salesmanMobile=sel.options[sel.selectedIndex].value;
    var sel=$('distDD');
    var distid=sel.options[sel.selectedIndex].value;    
	document.salesmanReport.action="/panels/salesmanReport/"+distid+"/"+salesmanMobile+"/"+$('from').value+"/"+$('to').value;
	document.salesmanReport.submit();
}

</script>

<form name="salesmanReport" method="POST" onSubmit="setAction()">
Distributor : <select name="distDD" id="distDD" onChange="showInfo()">
	<?php
	$i=0;
	
		foreach($distributors as $d)
		{
			$sel = '';
			if($distId == $d['Distributor']['id'])
			$sel = 'selected';
			
	 		echo "<option ".$sel." value='".$d['Distributor']['id']."' >".$d['Distributor']['company']." - " .$d['Distributor']['mobile'] ."</option>";
	 		$i++;
		}
			
			
?>
</select>
Salesman : <select name="salesmanDD" id="salesmanDD">
	<?php
	$i=0;
	echo "<option  value='0'>All</option>";		
		foreach($salesman as $d)
		{
			$sel = '';
			if($salesmanId == $d['s']['id'])
			$sel = 'selected';
			
	 		echo "<option ".$sel." value='".$d['s']['id']."' >".$d['s']['name']." (".$d['s']['mobile'].")</option>";
	 		$i++;
		}
			
?>
</select>
From Date : <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!is_null($from))echo $from ; ?>"/>
To Date: <input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to','close=true')" value="<?php if(isset($to))echo $to;?>"/>
<input type="button" value="Submit" onclick="setAction()"/>
</form>

</br>
<table border="0" width="100%">
	<tr>
		<td valign="top" width="25%">
			<h4>Retailers Acquired </br>from <?php echo $from; ?> to <?php echo $to; ?></h4>	
			<table border="1" cellspacing="0" cellpadding="0" width="80%"> 
				<tr>
					<td>Salesman</td><td>Retailers acquired</td><td>Set up</td>
				</tr>
				<?php
				$tot_acquired = 0;
				$tot_setup = 0;
				foreach($retAcquiredDtRng as $rA){
					$tot_acquired = $tot_acquired + $rA['0']['num'];
					$tot_setup = $tot_setup + $setupDtRng[$rA['salesmen']['id']];
					echo "<tr><td>".$rA['salesmen']['name']."</td><td>".$rA['0']['num']."</td><td>".$setupDtRng[$rA['salesmen']['id']]."</td></tr>";
				}?>
				<tr>
					<td><b>Total</b></td><td><b><?php echo $tot_acquired; ?></b></td><td><b><?php echo $tot_setup; ?></b></td>
				</tr>
			</table>
			
			<h4>Total Retailers Acquired</h4>	
			<table border="1" cellspacing="0" cellpadding="0" width="80%"> 
				<tr>
					<td>Salesman</td><td>Retailers acquired</td><td>Set up</td>
				</tr>
				<?php
				$tot_acquired = 0;
				$tot_setup = 0;
				foreach($retAcquired as $rA){
					$tot_acquired = $tot_acquired + $rA['0']['num'];
					$tot_setup = $tot_setup + $setup[$rA['salesmen']['id']];
					echo "<tr><td>".$rA['salesmen']['name']."</td><td>".$rA['0']['num']."</td><td>".$setup[$rA['salesmen']['id']]."</td></tr>";
				}
				?>
				<tr>
					<td><b>Total</b></td><td><b><?php echo $tot_acquired; ?></b></td><td><b><?php echo $tot_setup; ?></b></td>
				</tr>
				
			</table>
		</td>
		
		<td valign="top">
			<h4>Payment Collection</h4>		
			<table id="salesResult" border="1" cellspacing="0" cellpadding="2" width="100%">
			<?php if($salesmanId == 0) $var='Salesman'; ?>
				<tr>
					<th width="40%">Retailer Shop Name</th>
					<?php
					if($salesmanId==0)
						echo "<th>".$var."</th>";
					?>
					<th>Type</th>
					<th>Amount</th>
					<th>Date</th>
				</tr>
			<?php
				$setUp = 0;
				$topUp = 0;
				$total = 0;
				$collAmt = 0;
				
				foreach($salesResult as $d)
				{
				if($d['a']['payment_type']==1 && $d['a']['collection_amount']==0) continue;
				if(is_null($d['a']['name']))
				$retDetails=$d['a']['mobile'];
				else
				$retDetails=$d['a']['name'];
				
				echo "<tr>";
				echo "<td><a href='/panels/retInfo/".$d['a']['mobile']."'>".$d['a']['shopname']." (".$d['a']['mobile'].")</a></td>";
				 if($salesmanId == 0)
				echo "<td>".$d['a']['sm_name']."</td>";
				
				if($d['a']['payment_type']==1){
					echo "<td>Set-up</td>";
					$setUp += $d['a']['collection_amount'];
					echo "<td>".$d['a']['collection_amount']."</td>";
				}
				else {
					echo "<td>Top-up</td>";
					$topUp += $d['a']['amount'];
					echo "<td>".$d['a']['amount']."</td>";
				}
				
				echo "<td>".$d['a']['created']."</td>";
				echo "</tr>";
				}			
				
			?>
			</table>
			<table>
				<tr>
					<td><b>Total</b></td><td><?php echo ($setUp + $topUp); ?></td>
				</tr>
				<tr>
					<td><b>Total SetUp Fee:</b></td><td><?php echo $setUp; ?></td>
				</tr>
				<tr>
					<td><b>Total TopUp Fee:</b></td><td><?php echo $topUp; ?></td>
				</tr>
			</table>			
		</td>
	</tr>	
</table>