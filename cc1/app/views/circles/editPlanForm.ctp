
<head>
<title>Pay1 (New Plan Form)</title>
</head>
<body>
	<form class="form-inline" method="post" action="/circles/editPlanEntry/">
		<br />
		<br />
		
				&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
		<div class="form-group">
		<?php $operatorid = $posts[0]['circle_plans']['prod_code_pay1']?>
			<label for="operator">Select Operator</label> &nbsp &nbsp 
			<input type="hidden" name="operator" value="<?php echo $operatorid ?>" />
			<select class="form-control" name="operator" disabled>
				<option value="1" <?php if($operatorid == 1) { echo "selected" ;}?> >Aircel</option>
				<option value="2" <?php if($operatorid == 2) { echo "selected" ;}?> >Airtel</option>
				<option value="16" <?php if($operatorid == 16) { echo "selected" ;}?> >Airtel DTH</option>
				<option value="3" <?php if($operatorid == 3) { echo "selected" ;}?> >BSNL</option>
				<option value="18" <?php if($operatorid == 18) { echo "selected" ;}?> >Dish TV</option>
				<option value="4" <?php if($operatorid == 4) { echo "selected" ;}?> >Idea</option>
				<option value="30" <?php if($operatorid == 30) { echo "selected" ;}?> >MTNL</option>
				<option value="6" <?php if($operatorid == 6) { echo "selected" ;}?> >MTS</option>
				<option value="7" <?php if($operatorid == 7) { echo "selected" ;}?> >Reliance CDMA</option>
				<option value="17" <?php if($operatorid == 17) { echo "selected" ;}?> >Reliance DTH</option>
				<option value="8" <?php if($operatorid == 8) { echo "selected" ;}?> >Reliance GSM</option>
				<option value="9" <?php if($operatorid == 9) { echo "selected" ;}?> >Tata Docomo</option>
				<option value="10" <?php if($operatorid == 10) { echo "selected" ;}?> >Tata Indicom</option>
				<option value="20" <?php if($operatorid == 20) { echo "selected" ;}?> >Tata Sky DTH</option>
				<option value="11" <?php if($operatorid == 11) { echo "selected" ;}?> >Uninor</option>
				<option value="12" <?php if($operatorid == 12) { echo "selected" ;}?> >Videocon</option>
				<option value="21" <?php if($operatorid == 21) { echo "selected" ;}?> >Videocon Dth</option>
				<option value="15" <?php if($operatorid == 15) { echo "selected" ;}?> >Vodafone</option>
                                <option value="83" <?php if($operatorid == 83) { echo "selected" ;}?> >Reliance Jio</option>

			</select>
		</div>
		
		&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
		<div class="form-group">
			<?php $circleid = $posts[0]['circle_plans']['c_id'];?>
			<label for="circle">Select Circle</label> &nbsp &nbsp 
			<input type="hidden" name="circle" value="<?php echo $circleid ?>" />
				<select	class="form-control" name="circle" disabled>		
				<option value="0" <?php if($circleid == 0) { echo "selected" ;}?> >No circle</option>
				<option value="1" <?php if($circleid == 1) { echo "selected" ;}?> >AndhraPradesh</option>
				<option value="2" <?php if($circleid == 2) { echo "selected" ;}?> >Assam</option>
				<option value="4" <?php if($circleid == 4) { echo "selected" ;}?> >Chennai</option>
				<option value="5" <?php if($circleid == 5) { echo "selected" ;}?> >Delhi NCR</option>
				<option value="6" <?php if($circleid == 6) { echo "selected" ;}?> >Gujarat</option>
				<option value="7" <?php if($circleid == 7) { echo "selected" ;}?> >Haryana</option>
				<option value="8" <?php if($circleid == 8) { echo "selected" ;}?> >Himachal Pradesh</option>
				<option value="9" <?php if($circleid == 9) { echo "selected" ;}?> >Jammu & Kashmir</option>
				<option value="3" <?php if($circleid == 3) { echo "selected" ;}?> >Jharkand</option>
				<option value="10" <?php if($circleid == 10) { echo "selected" ;}?> >Karnataka</option>
				<option value="11" <?php if($circleid == 11) { echo "selected" ;}?> >Kerala</option>
				<option value="12" <?php if($circleid == 12) { echo "selected" ;}?> >Kolkata</option>
				<option value="14" <?php if($circleid == 14) { echo "selected" ;}?> >Madhya Pradesh</option>
				<option value="13" <?php if($circleid == 13) { echo "selected" ;}?> >Maharashtra</option>
				<option value="15" <?php if($circleid == 15) { echo "selected" ;}?> >Mumbai</option>
				<option value="17" <?php if($circleid == 17) { echo "selected" ;}?> >Orissa</option>
				<option value="18" <?php if($circleid == 18) { echo "selected" ;}?> >Punjab</option>
				<option value="19" <?php if($circleid == 19) { echo "selected" ;}?> >Rajasthan</option>
				<option value="20" <?php if($circleid == 20) { echo "selected" ;}?> >Tamil Nadu</option>
				<option value="16" <?php if($circleid == 16) { echo "selected" ;}?> >Tripura</option>
				<option value="21" <?php if($circleid == 21) { echo "selected" ;}?> >Uttar Pradesh (East)</option>
				<option value="22" <?php if($circleid == 22) { echo "selected" ;}?> >Uttarakhand</option>
				<option value="23" <?php if($circleid == 23) { echo "selected" ;}?> >West Bengal</option>
			</select>
		</div>


		&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
		<div class="form-group">
			<?php $plantype = $posts[0]['circle_plans']['plan_type'];?>
			<label for="planType">Select Plan Type</label> &nbsp &nbsp
			<input type="hidden" name="planType" value="<?php echo $plantype ?>" />
			 <select class="form-control" name="planType" disabled>
				<option value="Topup" <?php if($plantype == "Topup") { echo "selected" ;}?> >Topup</option>
				<option value="Topup-Plans" <?php if($plantype == "Topup-Plans") { echo "selected" ;}?> >Topup-Plans</option>
				<option value="3G" <?php if($plantype == "3G") { echo "selected" ;}?> >3G</option>
				<option value="Data_2G" <?php if($plantype == "Data_2G") { echo "selected" ;}?>>Data/2G</option>
				<option value="4G" <?php if($plantype == "4G") { echo "selected" ;}?>>4G</option>
				<option value="Other" <?php if($plantype == "Other") { echo "selected" ;}?> >Other</option>
			</select>
		</div>

		<br />
		<br />
		<br />
		<br /> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp

		<div class="form-group">
			<?php $planamt = $posts[0]['circle_plans']['plan_amt'];?>
			<label for="planAmount">Plan Amount</label>&nbsp &nbsp
			 <input type="number" class="form-control" id="planAmount" name="planAmount"
				placeholder="Plan Amount"  value="<?php echo $planamt;?>" readonly> 
				
		</div>

		&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp

		<div class="form-group">
			<?php $planvalidity = $posts[0]['circle_plans']['plan_validity'];?>
			<label for="planValidity">Plan Validity</label>&nbsp &nbsp
			 <input type="text" class="form-control" id="planValidity" 
				name="planValidity" placeholder="Plan Validity" value="<?php echo $planvalidity;?>" >
		</div>

		<br />
		<br />
		<br />
		<br /> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp

		<div class="form-group">
		<?php $plandesc= $posts[0]['circle_plans']['plan_desc'];?>
			<label for="planDescription">Plan Description</label>&nbsp &nbsp
			<!-- 	    <input type="text-area" class="form-control" id="planDescription" placeholder="Plan Description"> -->
			<textarea class="form-control" id="planDescription"
				name="planDescription" rows="4" cols="90" ><?php echo $plandesc;?></textarea>
		</div>
		<br />
		<br />
		<br />
		<br />
		<?php $planID = $posts[0]['circle_plans']['id'];?>
		<input type="hidden" name="id" value="<?php echo $planID;?>">

		<div class="form-group">
			<div class="col-sm-offset-10 col-sm-10">
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
	</form>
</body>