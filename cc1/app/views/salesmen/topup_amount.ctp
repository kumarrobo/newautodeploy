<script>
	var msg = '<?php echo $msg; ?>';
	var flag = '<?php echo $flag; ?>';
	var toCollect = '<?php echo $toCollect; ?>';
	var mobile = '<?php echo $mobile; ?>';
	var amount = '<?php echo $amount; ?>';

	alert(msg);
	if(flag == 1){
		if(toCollect==1)
			document.location.href="/salesmen/payment/2/"+mobile+"/1/"+amount;
		else
			document.location.href="/salesmen/mainMenu";
	}else{
		document.location.href="/salesmen/topup";
	}
</script>