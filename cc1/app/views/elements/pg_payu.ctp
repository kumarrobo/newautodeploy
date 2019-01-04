<style>
.bank_option {
	border: 1px solid;
	padding: 10px 0px 10px 10px;
	height: 50px;
	margin-bottom: 15px;
	display: table;
	float: left;
	margin-left: 15px;
	width: 150px;
}
.bank_option > img {
	height: 40px;
	width:120px;
}
</style>
<link rel="stylesheet"
	href="<?php echo PAYU_ASSETS_URL ?>boot/css/bootstrap.min.css">
<link rel="stylesheet"
	href="<?php echo PAYU_ASSETS_URL ?>assets/css/style.css">
<link rel="stylesheet"
	href="<?php echo PAYU_ASSETS_URL ?>assets/css/payment.css">

	<form id="postpayu" name="postpayu" method="post"
		action="<?php echo PAYU_URL; ?>">
		<input type="hidden" name="payu_web_service" id="payu_web_service" value="<?php echo PAYU_WEB_SERVICE ?>" />
		<input type="hidden" name="key" id="key" value="<?php echo $key; ?>" />
		<input type="hidden" name="salt" value="<?php echo $salt; ?>" />
		<input type="hidden" name="email" id="email" value="<?php echo $email; ?>" />
		<input type="hidden" name="user_credentials" id="user_credentials" value="<?php echo $user_credentials; ?>" /> 
		<input type="hidden" name="hash" id="hash" value="<?php echo $hash; ?>" /> 
		<input type="hidden" name="txnid" id="txnid" value="<?php echo $txnid; ?>" />
		<input type="hidden" name="phone" id="phone" value="<?php echo $phone; ?>" /> 
		<input type="hidden" name="pg" id="pg" value="NB" /> 
		<input type="hidden" name="bankcode" id="bankcode" value="CC" /> 
		<input type="hidden" name="amount" id="amount" value="<?php echo $amount; ?>" /> 
		<input type="hidden" name="firstname" id="firstname" value="<?php echo $firstname; ?>" /> 
		<input type="hidden" name="surl" id="surl" value="<?php echo $surl; ?>" /> 
		<input type="hidden" name="curl" id="curl" value="<?php echo $curl; ?>" /> 
		<input type="hidden" name="furl" id="furl" value="<?php echo $furl; ?>" /> 
		<input type="hidden" name="productinfo" id="productinfo" value="<?php echo $productinfo; ?>" /> 
			
		<div class="content-section">
			<div class="content light_grey_bg">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-7 col-sm-offset-3">
							<p class="heading2 text-left margin-bottom-30">Payment Summary</p>
							<div class="row">
								<div class="col-md-6">
									<p>
										Number<br> <span class="info"><?php echo $phone; ?></span>
									</p>
								</div>
								<div class="col-md-6">
									<p>
										Transaction ID<br> <span class="info"><?php echo $txnid; ?></span>
									</p>
								</div>
							</div>
						</div>
					</div>

					<!--class="active_payment"-->
					<div class="row">
						<div class="col-sm-3">
							<div id="payment_navigation">
								<ul class="tabs">
									<li class="active"><a href="#netbank" data-toggle="tab"
										onclick="select_payment_type('NB');">Net Banking</a></li>
								</ul>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="tab-content">
							<!--Net banking start-->
							<div class="col-sm-9 border-left tab-pane active" id="netbank">

								<div class="row">

									<div class="col-md-3">
										<label for="bank_icici">
											<div class="bank_option">

												<input id="bank_icici" onclick="select_bank('ICIB')"
													type="radio" data-code="ICIB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/icici_logo.png">

											</div>
										</label> 	
									</div>
									<div class="col-md-3">		
										<label for="bank_hdfc">
											<div class="bank_option">

												<input id="bank_hdfc" onclick="select_bank('HDFB')"
													type="radio" data-code="HDFB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/hdfc_logo.png">

											</div>
										</label>
									</div>
								</div>
								<div class="row">

									<div class="col-md-3">
										<label for="bank_sbi">
											<div class="bank_option">

												<input id="bank_sbi" onclick="select_bank('SBIB')"
													type="radio" data-code="SBIB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/sbi_logo.png">

											</div>
									</div>
									<div class="col-md-3">		
										</label> <label for="bank_idbi">
											<div class="bank_option">

												<input id="bank_idbi" onclick="select_bank('IDBB')"
													type="radio" data-code="IDBB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/logo_idbibank.png">

											</div>
										</label>
									</div>
								</div>
								<div class="row">

									<div class="col-md-3">
										<label for="bank_axis">
											<div class="bank_option">

												<input id="bank_axis" onclick="select_bank('AXIB')"
													type="radio" data-code="AXIB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/logo_axisbank.png">

											</div>
									</div>
									<div class="col-md-3">		
										</label> <label for="bank_boi">
											<div class="bank_option">

												<input id="bank_boi" onclick="select_bank('BOIB')"
													type="radio" data-code="BOIB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/logo_boi.png">

											</div>
										</label>
									</div>
								</div>
								<div class="row">

									<div class="col-md-3">
										<label for="bank_crpb">
											<div class="bank_option">

												<input id="bank_crpb" onclick="select_bank('CRPB')"
													type="radio" data-code="CRPB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/logo_corporation.png">

											</div>
									</div>
									<div class="col-md-3">		
										</label> <label for="bank_jakb">
											<div class="bank_option">

												<input id="bank_jakb" onclick="select_bank('JAKB')"
													type="radio" data-code="JAKB" name="bank" class="validate[required]"><img
													src="<?php echo PAYU_ASSETS_URL ?>assets/images/banks/logo_jkbank.png">

											</div>
										</label>
									</div>
								</div>

								<div class="row margin-top-50 pull-left">
									<div class="col-md-2">
										<select class="other_banks validate[required]"
											id="netbanking_select" name="netbanking_select">
											<option value="">All other banks</option>
       <?php foreach($banks as $bank){ ?>
       <option value="<?php echo $bank['banks']['code']; ?>"><?php echo $bank['banks']['bank']; ?></option>
       <?php  } ?>
	</select>
									</div>
                                                                                                                                                                        <div class="clearfix"></div>
									<div class="row margin-top-20">
										<div class="col-sm- col-sm-offset-5 margin-top-20">
											<div class="col-md- col-md-offset-">
												<div class="">Total</div>
												<span class="info">Rs. <?php echo $amount; ?></span> <input
													type="button" id="confirm_netbanking" onclick="pay();"
													class="confirm-payment" disabled="disabled" value="Pay Now"
													class="btn_style" />
											</div>


										</div>
									</div>
								</div>
							</div>
							<!--Net banking end-->
	
						</div>

					</div>

					<div class="row">
						<div class="col-sm-2 col-sm-offset-1">
							<img
								src="<?php echo PAYU_ASSETS_URL ?>assets/images/secured/3d_secure.png">
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script src="<?php echo PAYU_ASSETS_URL ?>boot/js/jquery-2.0.3.min.js"></script>
	<script src="<?php echo PAYU_ASSETS_URL ?>boot/js/bootstrap.min.js"></script>
<script>
function pay(){
	$('#bankcode').val($('#netbanking_select').val());
	
	$("#postpayu").submit();
}

function select_bank(code){
	$('#netbanking_select').val(code);
	if($('#netbanking_select').val() != ""){
		$('#confirm_netbanking').attr('disabled', false);
	}	
}

$('#netbanking_select').change(function(){
	if($('#netbanking_select').val() != ""){
		$("input[name=bank]").each(function(){
			if($(this).data('code') == $('#netbanking_select').val()){
				$(this).trigger('click');
			}	
			else {
				$(this).attr('checked', false);
			}	
		});
		$('#confirm_netbanking').attr('disabled', false);
	}	
	else 
		$('#confirm_netbanking').attr('disabled', true);
});
</script>