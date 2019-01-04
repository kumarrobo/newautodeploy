<div style="width:650px; text-align:left">
<div class="invoiceHead">
	<!-- <div class="invoiceTitle strng">CASH MEMO</div> -->
	<div style="text-align: left; width: 410px;" class="rightFloat">		
		<span class="invoiceCompName strng">Mindsarray Technologies Pvt. Ltd.</span><br>
		528, Raheja's Metroplex (IJMIMA),<br/>
				 Link Road, Malad (W),<br/> 
				Mumbai - 400064, Maharashtra.	</div>
	<div style="text-align:left; margin-right:410px;">
		<div><img alt="" src="/img/logo.png?211"></div>
		<span style="font-size:11px;">www.smstadka.com</span><br>
		<span style="font-size:10px;">(A product by MindsArray Technologies Pvt. Ltd.)</span>
	</div>
	
	<div class="clearBoth"></div>
</div>

<div>
	<div class="field leftFloat strng">Invoice No. <span style="text-decoration:underline;font-weight:normal">ST/PW/2011/000001</span></div>
	<div class="field rightFloat strng" style="width:130px;">Date: <span style="text-decoration:underline;font-weight:normal">18-07-2011</span></div>
	<div class="clearBoth">&nbsp;</div>
	<div class="field"><div class="leftFloat strng" style="margin-right:10px;">M/s.</div><div><span style="text-decoration:underline;">Playwin, Pan India Network Ltd</span></div></div>
</div>
<table class="invoice" style="" cellspacing=0 cellpadding=0>
	<thead>
		<tr>
		<?php $tax = 0; $commission_percent = 27; $colspan=4; ?>
			<th style="width:20px;" class="number">Sr. No.</th>
			<th style="width:250px;">Description</th>
			<th style="width:80px;" class="number">Qty.</th>
			<?php if($tax == 1) { ?><th style="width:80px;" class="number">Net Price</th>
			<th style="width:80px;" class="number">Tax</th> <?php } ?>
			<th style="width:80px;" class="number">Unit MRP</th>
			<?php if($tax == 1) { ?><th style="width:80px;" class="number">Net Amount</th><?php } ?>
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
			<?php if($tax == 1) { ?><td class="number"><?php echo $jokes_net; ?></td>
			<td class="number"><?php echo sprintf('%.2f', $jokes_mrp - $jokes_net); ?></td><?php } ?>
			<td class="number"><?php echo sprintf('%.2f', $jokes_mrp); ?></td>
			<?php if($tax == 1) { ?><td class="number"><?php echo sprintf('%.2f', $jokes_net*$jokes); ?></td><?php } ?>
			<td class="number"><?php echo sprintf('%.2f', $jokes_mrp*$jokes); ?></td>
			
		</tr>
		<?php $total_count += $jokes; $total_net += sprintf('%.2f', $jokes_net*$jokes); $total_price += sprintf('%.2f', $jokes_mrp*$jokes); } ?>
		<?php if($cricket > 0) { ?>
		<tr>
			<td class="number">2</td>
			<td><span class="strng">Cricket Plus</td>
			<td class="number"><?php echo $cricket; ?></td>
			<?php if($tax == 1) { ?><td class="number"><?php echo $cricket_net; ?></td>
			<td class="number"><?php echo sprintf('%.2f', $cricket_mrp - $cricket_net); ?></td><?php } ?>
			<td class="number"><?php echo sprintf('%.2f', $cricket_mrp); ?></td>
			<?php if($tax == 1) { ?><td class="number"><?php echo sprintf('%.2f', $cricket_net*$cricket); ?></td><?php } ?>
			<td class="number"><?php echo sprintf('%.2f', $cricket_mrp*$cricket); ?></td>
			
		</tr>
		<?php $total_count += $cricket; $total_net += sprintf('%.2f', $cricket_net*$cricket); $total_price += sprintf('%.2f', $cricket_mrp*$cricket); } ?>
		<tr valign="top" height="20px">
			<td class="number"></td>
			<td><span class="strng"></td>
			<td class="number"></td>
			<td class="number"></td>
			<td class="number"></td>
			<?php if($tax == 1) {  ?><td class="number"></td>
			<td class="number"></td>
			<td class="number"></td><?php } ?>
		</tr>
		
		<tr class="strng">
			<td class="number" style="border-top:1px solid #000;"></td>
			<td style="border-top:1px solid #000;">Total</td>
			<td style="border-top:1px solid #000;" class="number"><?php echo $total_count; ?></td>
			<?php if($tax == 1) {  ?><td style="border-top:1px solid #000;" class="number"></td>
			<td style="border-top:1px solid #000;" class="number"></td><?php } ?>
			<td style="border-top:1px solid #000;" class="number"></td>
			<?php if($tax == 1) {  ?><td class="number" style="border-top:1px solid #000;"><?php echo sprintf('%.2f',$total_net); ?></td><?php } ?>
			<td style="border-top:1px solid #000;" class="number"><?php echo sprintf('%.2f',$total_price); ?></td>
			
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right">Total MRP (a)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo sprintf('%.2f',$total_price); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right">Commission @<?php echo $commission_percent; ?>% on MRP(b)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $commission = sprintf('%.2f', $total_price*$commission_percent/100); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right">Service Tax on Commission @10.3% (c)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $stax = sprintf('%.2f', $commission*10.3/100); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right">Total Commission (b + c)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $net_commission = sprintf('%.2f', $commission + $stax); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right">TDS on Total commission @10% (d)</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $tds = sprintf('%.2f', $net_commission*10/100); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right">Net Payable Amount (a - (b + c -d))</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $net = sprintf('%.2f', $total_price - $net_commission + $tds); ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right">Round Off</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo sprintf('%.2f', abs($net - round($net)));?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>" align="right"><b>Final Payable Amount</b></td>
			<td class="number" style="border-top:1px solid #000;"><b><?php echo sprintf('%.2f', round($net));?></b></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="<?php echo $colspan; ?>"><i>Thank You!</i></td>
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