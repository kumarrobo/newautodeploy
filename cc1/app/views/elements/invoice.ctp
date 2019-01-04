<div class="invoiceWidth">
<div class="invoiceHead">
	<!-- <div class="invoiceTitle strng">CASH MEMO</div> -->
	<div class="rightFloat" style="text-align:left;width:410px">		
		<span class="invoiceCompName strng"><?php echo $data['company']; ?></span><br>
		<?php echo nl2br($data['address']); ?>
		<br><span class="strng">Contact:</span> <?php echo nl2br($data['mobile']); ?>
	</div>
	<div style="text-align:center; margin-right:410px;">
		<div><img alt="" src="/img/logo.png?211"></div>
		<span style="font-size:11px;">www.smstadka.com</span><br>
		<span style="font-size:10px;">(A product by MindsArray Technologies Pvt. Ltd.)</span>
	</div>
	
	<div class="clearBoth"></div>
</div>

<div>
	<div class="field leftFloat strng">Invoice No. <span style="text-decoration:underline;font-weight:normal"> <?php echo $data['invoice_number']; ?></span></div>
	<div class="field rightFloat strng" style="width:200px;">Date: <span style="text-decoration:underline;font-weight:normal"> <?php echo date('d-m-Y',strtotime($data['date'])); ?></span></div>
	<div class="clearBoth">&nbsp;</div>
	<div class="field"><div class="leftFloat strng" style="display:inline-block;">M/s.</div><div style="margin-left:30px;"><span style="text-decoration:underline;"><?php echo $data['name']; ?>. <?php echo $data['addr']; ?></span></div></div>
</div>
<table class="invoice" style="" cellspacing=0 cellpadding=0>
	<thead>
		<tr>
			<th style="width:20px;" class="number">Sr. No.</th>
			<th style="width:300px;">Description</th>
			<th style="width:50px;" class="number">Qty.</th>
			<th style="width:60px;" class="number">MRP (<img src="/img/rs.gif" align="absmiddle">)</th>
			<th style="width:50px;" class="number">Comm. (%)</th>
			<th class="number">Amount (<img src="/img/rs.gif" align="absmiddle">)</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 0; $tot_quantity = 0; $amount = 0; $commission = 0; 
		while(isset($data['Product'][$i])) { ?>
		<tr valign="top">
			<td class="number"><?php echo $i+1; ?></td>
			<td><span class="strng"><?php echo $data['Product'][$i]; ?></span><?php //if(isset($data['serials'][$i])) { echo ": " . $objShop->shortSerials($data['serials'][$i]); } ?></td>
			<td class="number"><?php echo $data['quantity'][$i]; ?></td>
			<td class="number"><?php echo $data['rate'][$i]; ?></td>
			<td class="number"><?php echo $data['percent'][$i]; ?></td>
			<td class="number"><?php echo sprintf('%.2f', ($data['quantity'][$i] * $data['rate'][$i])); ?></td>
		</tr>
		<?php 
		$tot_quantity += $data['quantity'][$i];
		$amount += $data['quantity'][$i] * $data['rate'][$i];
		$commission += $data['commission'][$i];
		$i++;
		} 
		$tds = sprintf('%.2f', $commission*TDS_PERCENT/100);
		$commission = sprintf('%.2f', $commission);
		$amount = sprintf('%.2f', $amount);
		?>
		<?php
		for($j=$i; $j<10; $j++){ ?>
			<tr valign="top" height="20px">
				<td class="number"></td>
				<td><span class="strng"></td>
				<td class="number"></td>
				<td class="number"></td>
				<td class="number"></td>
				<td class="number"></td>
			</tr>
		<?php }
		?>
		<tr class="strng">
			<td class="number" style="border-top:1px solid #000;"></td>
			<td style="border-top:1px solid #000;">Total</td>
			<td style="border-top:1px solid #000;" class="number"><?php echo $tot_quantity; ?></td>
			<td style="border-top:1px solid #000;" class="number"></td>
			<td style="border-top:1px solid #000;" class="number"></td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $amount; ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="5" align="right">Total Commission</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $commission;?></td>
		</tr>
		<?php if($data['tds'] == 1) { $commission = sprintf('%.2f',$commission - $tds);?>
		<tr>
			<td style="border-top:1px solid #000;" colspan="5" align="right">TDS @10% on Commission</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $tds;?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="5" align="right">Net Commission</td>
			<td class="number" style="border-top:1px solid #000;">
				<?php echo $commission;?>
			</td>
		</tr>
		<?php } ?>
		<?php $net =  $amount - $commission; ?>
		<tr>
			<td style="border-top:1px solid #000;" colspan="5" align="right">Net Payable Amount</td>
			<td class="number" style="border-top:1px solid #000;">
				<?php echo sprintf('%.2f', $net);?>
			</td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="5" align="right">Rounded Off Amount</td>
			<td class="number" style="border-top:1px solid #000;">
				<?php echo sprintf('%.2f', abs($net - round($net)));?>
			</td>
		</tr>
		<tr>
			<td style="border-top:1px solid #000;" colspan="5" align="right"><b>Total Payable Amount</b></td>
			<td class="number" style="border-top:1px solid #000;">
				<b><?php echo sprintf('%.2f', round($net));?></b>
			</td>
		</tr>
		
	</tbody>
</table>
<div style="margin-top:20px;">
	<ul style="padding-left: 16px;">
		<li>Subject to realization of Cheque / D.D./ P.O.</li>
		<?php if($group_id == RETAILER) {?>
		<li>Activated cards once sold will not be taken back</li>
		<?php } ?>
		<li><i>E. & O. E.</i></li>
	</ul>
</div>			
<div style="margin-top:20px; text-align:center; font-size:12px">THIS IS COMPUTER GENERATED INVOICE. NO SIGNATURE REQUIRED.</div>
<span id="printId"> <a href="javascript:void(0)" onclick="printChallan()"> Print </a> </span>
</div>
<script>
	function printChallan(){
		document.getElementById('printId').style.display='none';
		window.print();
	}
</script>