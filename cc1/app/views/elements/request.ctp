<div class="invoiceWidth">
<div class="invoiceHead">
	<div class="invoiceTitle strng">Top-up Request</div>
	<div class="rightFloat" style="text-align:left;width:410px">		
		<span class="invoiceCompName strng"><?php echo $data['from_comp']; ?></span><br>
		<?php echo nl2br($data['from_addr']); ?>
		<br><span class="strng">Contact:</span> <?php echo nl2br($data['mobile']); ?>
	</div>
	<div style="text-align:center; margin-right:410px;">
		<div><img alt="" src="/img/pay1_logo.svg?<?php echo time(); ?>"></div>
		<span style="font-size:11px;">www.smstadka.com</span><br>
		<span style="font-size:10px;">(A product by MindsArray Technologies Pvt. Ltd.)</span>
	</div>
	
	<div class="clearBoth"></div>
</div>

<div>
	<div class="field leftFloat strng">Request No. <span style="text-decoration:underline;font-weight:normal"><?php echo $objShop->getTopUpReceiptNumber($data['ShopTransaction']['id']); ?></span></div>
	<div class="field rightFloat strng" style="width:200px;">Date: <span style="text-decoration:underline;font-weight:normal"> <?php echo date('d-m-Y',strtotime($data['ShopTransaction']['timestamp'])); ?></span></div>
	<div class="clearBoth">&nbsp;</div>
	<div class="field"><div class="leftFloat strng" style="display:inline-block;">M/s.</div><div style="margin-left:30px;"><span style="text-decoration:underline;"><?php echo $data['to_comp']; ?>. <?php echo $data['to_addr']; ?></span></div></div>
</div>
<table class="invoice" style="" cellspacing=0 cellpadding=0>
	<thead>
		<tr>
			<th style="width:20px;" class="number">Sr. No.</th>
			<th style="width:350px;">Description</th>
			<th style="width:200px;" class="number">Amount</th>
		</tr>
	</thead>
	<tbody>
		<tr valign="top">
			<td class="number">1</td>
			<td>Online Top-up</td>
			<td class="number"><?php echo sprintf('%.2f', ($data['ShopTransaction']['amount'])); ?></td>
		</tr>
		<?php for($j=0; $j<5; $j++){ ?>
			<tr valign="top" height="20px">
				<td class="number"></td>
				<td><span class="strng"></td>
				<td class="number"></td>
			</tr>
		<?php } ?>
		<tr class="strng">
			<td class="number" style="border-top:1px solid #000;"></td>
			<td style="border-top:1px solid #000;">Net Payable</td>
			<td class="number" style="border-top:1px solid #000;"><?php echo $data['ShopTransaction']['amount']; ?></td>
		</tr>
		
		<tr>
			<td style="border-top:1px solid #000;" colspan="2"><i>Thank You!</i></td>
			<td class="number" style="border-top:1px solid #000;"><i>E. & O. E.</i></td>
		</tr>

	</tbody>
</table>
<div style="margin-top:20px; text-align:center; font-size:12px">THIS IS COMPUTER GENERATED RECEIPT. NO SIGNATURE REQUIRED.</div>
<span id="printId"> <a href="javascript:void(0)" onclick="printChallan()"> Print </a> </span>
</div>
<script>
	function printChallan(){
		document.getElementById('printId').style.display='none';
		window.print();
	}
</script>