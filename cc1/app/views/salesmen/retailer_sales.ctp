<?php
	$todayAmount=0;
	if(empty($success))
		$todayAmount=0;
	else
	{
		foreach($success as $st)
		{
		$todayAmount+=$st['st']['amount'];
		}
	}
?>

<table width="100%"><tr><td><div style="width:100%;text-align:center;background:#ff0000;font-size:1.5em"><strong><span style="color:#ffffff">Pay</span><span style="color:#000000">1</span></strong></div></td><td width="90px"><a href="/salesmen/mainMenu">Main Menu</a></td></tr></table>
<h3>Retailer Details</h3>
<form name="retailerSales" method="POST">
<table>	
	<?php
		$index = intval($average/500);
		$max = 500*($index+1);
		$needed = $average - $todayAmount - $rBalance;
		$index1 = intval($needed/500);
		$needed = ($index1+1)*500;
		if($needed < 0) $needed ="TopUp not required for today";
	?>
	<tr><td><span style="color:red"><?php if($needed > 0) { echo "TopUp Needed: $needed"; } else $needed; ?></span></td></tr>
	<tr><td>Retailer Mobile </td><td><?php echo $rMobile; ?></td></tr>
	<tr><td>Shop Name </td><td><?php echo $rShopname; ?> </td></tr>
	<tr><td>Balance </td><td>Rs.<?php echo "<b>".number_format($rBalance, 2, '.', '')."</b>"; ?> </td></tr>
	<tr><td>Today's Sale </td><td>Rs.<?php echo $todayAmount;?></td></tr>
	<tr><td>Average Sale </td><td><?php echo 500*$index . " - " . 500*($index+1); ?></td></tr>
	
	
	<?php if($pendSetUp!=0)
		echo "<tr><td><b>Set-up Pending</td><td><b>Rs.".$pendSetUp."</b></td></tr>";
		
		//if($pendTopUp!=0)
			echo "<tr><td><b>Top-up Pending</td><td><b>Rs.".$pendTopUp."</b></td></tr>";
	?>
	<tr>
		<td align="left"></td>
		<td align="right"></td>
	</tr>
	<tr>
		<td align="left"></td>
		<td align="right"></td>
	</tr>
	
</table>
</form>
<a href="/salesmen/topup" class="menu">TopUp</a></br>
<a href="/salesmen/collection" class="menu">Collection</a></br>