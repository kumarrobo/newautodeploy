<?php if(empty($reversalInProcess))
 {
 echo "Flag is ".$flag;
 echo "<div style='background-color:#FF0000'>No transactions by retailer having status:'Reversal In Process'</div>";
 exit;
 } ?>


<?php if(isset($flag)) echo "Flag is ".$flag; ?>
<table border="1" id="reverseTable" cellpadding="0" cellspacing="0" width="700px" align="left" >
<?php
	 if($flag==0)
	 	 echo "<tr><td colspan='9' align='center'>Retailers Transaction</td></tr>";
	else
		echo "<tr><td colspan='9' align='center'>Retailers request for  Reversal </td></tr>";
   ?>

<tr><td colspan="9" align="center">Retailers request for  Reversal </td></tr>
<tr>
<th>Index</th>
<th>Transaction Number(Ref_code)</th>

<th>Recharge(Mobile/DTH/SMSTadka)</th>
<th>Company(OSS/PPI)</th>

<th>Mobile Number</th>
<th>Operator</th>
<th>Amount</th>
<th>Status</th>
<th>Timestamp</th>
</tr>

<?php

	$count=1;
	$ps='';
	foreach($reversalInProcess as $d)
		{
		//echo $count;
		if($d['va']['status']== '0')
			$ps = 'In Process';
		else if($d['va']['status']== '1')
			$ps = 'Successful';
	  	else if($d['va']['status']== '2')
			$ps = 'Failed';
		else if($d['va']['status']== '3')
			$ps = 'Reversed';
      	else if($d['va']['status']== '4')
			$ps='Reversal In Process';
		else if($d['va']['status']== '5')
			 $ps='Reversal declined';
			
			
		echo "<tr>";
		echo "<td>".$count."</td>";
    	echo "<td><a href='/panels/transaction/".$d['va']['txn_id']."'>".$d['va']['txn_id']."</a></td>";
    
        echo"<td>".$d['s']['name']."</td>";	
    	echo"<td>".$d['v']['company']."</td>";
    
    	echo "<td><a href='/panels/userInfo/".$d['va']['mobile']."'>".$d['va']['mobile']."</a></td>";
    	echo "<td>".$d['p']['name']."</td>";
    	echo "<td>".$d['va']['amount']."</td>";
   	    echo "<td>".$ps."</td>";
    	echo "<td>".$d['va']['timestamp']."</td>";
    	echo "</tr>";
    	$count++; 
 		}  
	//	echo "Final count".$count;
  //} 
?>
</table>

