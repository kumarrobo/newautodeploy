<div style="width:900px; text-align:left">
<div class="invoiceHead">
	<!-- <div class="invoiceTitle strng">CASH MEMO</div> -->
	<div style="text-align: left; width: 410px;" class="rightFloat">		
		<span class="invoiceCompName strng">Playwin, Pan India Network Ltd.</span><br>
		Worli<br>
Mumbai, Maharashtra.	</div>
	<div style="text-align:left; margin-right:410px;">
		<div><img alt="" src="/img/logo.png?211"></div>
		<span style="font-size:11px;">www.smstadka.com</span><br>
		<span style="font-size:10px;">(A product by MindsArray Technologies Pvt. Ltd.)</span>
	</div>
	
	<div class="clearBoth"></div>
</div>

<div>
	<div class="field leftFloat strng">Invoice No. <span style="text-decoration:underline;font-weight:normal">ST/PW/2011/000001</span></div>
	<div class="field rightFloat strng" style="width:130px;">Date: <span style="text-decoration:underline;font-weight:normal">13-07-2011</span></div>
	<div class="clearBoth">&nbsp;</div>
	<div class="field"><div class="leftFloat strng" style="margin-right:10px;">M/s.</div><div><span style="text-decoration:underline;">Maharaja Lottery Center, Lower Parel, Mumbai</span></div></div>
</div>
<table class="invoice" style="" cellspacing=0 cellpadding=0>
	<thead>
		<tr>
			<th style="width:20px;" class="number">Sr. No.</th>
			<th style="width:250px;">Description</th>
			<th style="width:80px;" class="number">Qty.</th>
			<th style="width:80px;" class="number">Net Price</th>
			<th style="width:80px;" class="number">Tax</th>
			<th style="width:80px;" class="number">Unit MRP</th>
			<th style="width:80px;" class="number">Net Amount</th>
			<th class="number">MRP</th>
		</tr>
	</thead>
	<tbody>
		<?php $jokes = 500; $jokes_mrp = 20; $jokes_net = sprintf('%.2f', ($jokes_mrp*100)/(100+SERVICE_TAX_PERCENT));
		$cricket = 500; $cricket_mrp = 30; $cricket_net = sprintf('%.2f', ($cricket_mrp*100)/(100+SERVICE_TAX_PERCENT));
		$total_count = 0; $total_net = 0; $total_price = 0;
		?>
		<?php if($jokes > 0) { ?>
		<tr>
			<td class="number">1</td>
			<td><span class="strng"  align="left">Jokes Plus</td>
			<td class="number"><?php echo $jokes; ?></td>
			<td class="number"><?php echo $jokes_net; ?></td>
			<td class="number"><?php echo sprintf('%.2f', $jokes_mrp - $jokes_net); ?></td>
			<td class="number"><?php echo sprintf('%.2f', $jokes_mrp); ?></td>
			<td class="number"><?php echo sprintf('%.2f', $jokes_net*$jokes); ?></td>
			<td class="number"><?php echo sprintf('%.2f', $jokes_mrp*$jokes); ?></td>
			
		</tr>
		<?php $total_count += $jokes; $total_net += sprintf('%.2f', $jokes_net*$jokes); $total_price += sprintf('%.2f', $jokes_mrp*$jokes); } ?>
		<?php if($cricket > 0) { ?>
		<tr>
			<td class="number">2</td>
			<td><span class="strng">Cricket Plus</td>
			<td class="number"><?php echo $cricket; ?></td>
			<td class="number"><?php echo $cricket_net; ?></td>
			<td class="number"><?php echo sprintf('%.2f', $cricket_mrp - $cricket_net); ?></td>
			<td class="number"><?php echo sprintf('%.2f', $cricket_mrp); ?></td>
			<td class="number"><?php echo sprintf('%.2f', $cricket_net*$cricket); ?></td>
			<td class="number"><?php echo sprintf('%.2f', $cricket_mrp*$cricket); ?></td>
			
		</tr>
		<?php $total_count += $cricket; $total_net += sprintf('%.2f', $cricket_net*$cricket); $total_price += sprintf('%.2f', $cricket_mrp*$cricket); } ?>
		<tr valign="top" height="20px">
			<td class="number"></td>
			<td><span class="strng"></td>
			<td class="number"></td>
			<td class="number"></td>
			<td class="number"></td>
			<td class="number"></td>
			<td class="number"></td>
			<td class="number"></td>
		</tr>
		
		<tr class="strng">
			<td class="number" style="border-top:1px solid #000;"></td>
			<td style="border-top:1px solid #000;">Total</td>
			<td style="border-top:1px solid #000;" class="number"><?php echo $total_count; ?></td>
			<td style="border-top:1px solid #000;" class="number"></td>
			<td style="border-top:1px solid #000;" class="number"></td>
			<td style="border-top:1px solid #000;" class="number"></td>
			<td class="number" style="border-top:1px solid #000;"><?php echo sprintf('%.2f',$total_net); ?></td>
			<td style="border-top:1px solid #000;" class="number"><?php echo sprintf('%.2f',$total_price); ?></td>
			
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="7" align="right">Total MRP (a)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo sprintf('%.2f',$total_price); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="7" align="right">Commission @23% on Net Amount(b)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $net_commission = sprintf('%.2f', $total_net*23/100); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="7" align="right">TDS on Total commission @10% (c)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $tds = sprintf('%.2f', $net_commission*10/100); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="7" align="right">Net Payable Amount (a - b + c)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $net = sprintf('%.2f', $total_price - $net_commission + $tds); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="7" align="right">Round Off</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo sprintf('%.2f', abs($net - round($net)));?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="7" align="right"><b>Final Payable Amount</b></td>
			<td class="number" style="border-top:1px solid #000;"><b><?php echo sprintf('%.2f', round($net));?></b></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="7"><i>Thank You!</i></td>
			<td class="number" style="border-top:1px solid #000;"><i>E. & O. E.</i></td>
		</tr>

	</tbody>
</table>
<div style="margin-top:20px; text-align:center; font-size:12px">THIS IS COMPUTER GENERATED INVOICE. NO SIGNATURE REQUIRED.</div>
<span id="printId"> <a href="javascript:void(0)" onclick="printChallan()"> Print </a> </span>
</div>
<script>
	function printChallan(){
		document.getElementById('printId').style.display='none';
		window.print();
	}
</script>