

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
 <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>
	<style type="text/css">
		.checkbox {
			font-size: 12px;
			line-height: 23px;
		}
		.reddiv{
			background-color:#FF0000;
/*			//border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
		.greendiv{
			background-color: #00FF00;
			/*//border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
	</style>
	
	<div class="container">
		
  <div class="panel panel-default">
	  <div class="panel-body"><h4>Exceed Transactions Deatils</h4></div>
  </div>
  <div class="row" style="padding: 40px 10px 10px;">
	  
  
  <table class="table table-hover">
    <thead>
      <tr>
		<th class="col-lg-1"></th>
		<th class="col-lg-1">Tran Id</th>
		<th class="col-lg-2">VendorTxn Id</th>
		<th class="col-lg-2">Vendor</th>
		<th class="col-lg-2">Operator Name</th>
        <th>Cust Mob</th>
        <th>Amt</th>
		<th>Status</th>
        <th> Resolution Tag </th>
		<th>Date</th>
		
      </tr>
    </thead>
    <tbody>
		
		<?php $i = 1; foreach ($exceedresult as $key => $val){ 
			$ps = '';
  		if($val['vendors_activations']['status'] == '0'){
			$ps = 'In Process';
		}else if($val['vendors_activations']['status'] == '1'){
			$ps = 'Successful';
		}else if($val['vendors_activations']['status'] == '2'){
			$ps = 'Failed';
		}else if($val['vendors_activations']['status'] == '3'){
			$ps = 'Reversed';
		}else if($val['vendors_activations']['status'] == '4'){
			$ps = 'Reversal In Process';
		}else if($val['vendors_activations']['status'] == '5'){
			$ps = 'Reversal declined';
		}   ?>
                    <?php
                    if (!empty($val['complaints']['takenby']))
                        $color = '#99ff99';
                    else
                        $color = '';
                    ?>
		<tr bgcolor='<?php echo $color; ?>'>
			<td><?php echo $i;?></td>
			<td><a target="_blank" href="/panels/transaction/<?php echo $val['vendors_activations']['txn_id'];?>"><?php echo $val['vendors_activations']['txn_id'];?></a></td>
			<td><?php echo $val['vendors_activations']['vendor_refid'];?></td>
			<td><?php echo $val['vendors']['company'];?></td>
			<td><?php echo $val['products']['name'];?></td>
			<td><a target="_blank" href="/panels/userInfo/<?php echo $val['vendors_activations']['mobile']; ?>"><?php echo $val['vendors_activations']['mobile'];?></a></td>
			<td><?php echo $val['vendors_activations']['amount'];?></td>
			<td><?php echo $ps; ?></td>
                         <td> <?php echo ($resolution_tag[$val['vendors_activations']['txn_id']] == '' ? '' : $resolution_tag[$val['vendors_activations']['txn_id']]) ?></td>
			<td><?php echo $val['vendors_activations']['timestamp'];?></td>
		</tr>
		<?php $i++;} ?>
		
	</tbody>
  </table>

			

		
 
</div>
 
	











