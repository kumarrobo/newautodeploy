 		
<script>
function setAction(){
	document.tranReversal.action="/panels/operatorStatus/"+$('from').value+"/"+$('to').value;
	document.tranReversal.submit();
}
</script>

<form name="tranReversal" method="POST" onSubmit="setAction()">
From Date <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!is_null($frm))echo $frm;?>" />
To Date: <input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to','close=true')" value="<?php if(isset($to))echo $to;?>" />
<input type="button" value="Submit" onclick="setAction()">
</form>
</br>

		
		<table border="0" cellpadding="3" cellspacing="0">
		<tr valign="top">
			<td>
				<table border="1" cellpadding="0" cellspacing="0">
				<h4> Operator Reversals(Reversal successful) </h4>
				<tr>
				<th> Operator</th>
				<th>Total Reversal Amount</th>
				</tr>
					<?php 
						
						foreach($operatorReversalResult as $d)
						{
						echo "<tr>";
						echo "<td>".$d['p']['name']."</td>";
						echo "<td>".$d['0']['total']."</td>";
						echo "</tr>";
						} 
					
					?>
				</table>
			</td>
			<td>
				<?php if (isset($operatorSuccessSale)) {  ?>
		
				<table border="1" cellpadding="0" cellspacing="0">
					<h4> Operator Sale(Successful transactions) </h4>
						<th>Operator</th>
						<th>Total Sale</th>
					<!--	<th>Retailer Percent</th> 
						<th>Retailer Earning</th>-->
				<?php 
					foreach($operatorSuccessSale as $d)
					{
					//$retEarning=$d['sp']['percent'] * $d['0']['Total']/100;
					echo "<tr>";
					echo "<td>".$d['p']['name']."</td>";
					echo "<td>".$d['0']['Total']."</td>";
					// echo "<td>".$d['sp']['percent']."</td>";
					//echo "<td>".$retEarning."</td>";
					echo "</tr>";
					}
				?>
				</table>
				<?php	}	?>
			</td>
		</tr>
		</table>




 		
