<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>
<script>
function setAction(){
     
	document.tranReversal.action="/panels/manualReversalReport/"+$('#from').val()+"/"+$('#to').val();
	document.tranReversal.submit();
}

</script>

<table>
	<tr>
		<td>
			<form name="tranReversal" method="POST" onSubmit="setAction()">
From Date <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!is_null($frm))echo $frm;?>" />
To Date: <input type="text" name="to" id="to" onmouseover="fnInitCalendar(this, 'to','close=true')" value="<?php if(isset($to))echo $to;?>" />
<button type="button" class="btn btn-success" onclick="setAction()">Submit</button>
		</td>
		
	</tr>	
</table>
<table style="width: 100%;border:1px solid #ddd;">
	<tr style="border:1px solid;">
		<th colspan="11">ManualReversal Report</th>
	</tr>
<tr style="border:1px solid;">
		<th style="width:20px;border:1px solid #ccc;">Index</th>
		<th style="width:20px;border:1px solid #ccc;">Transaction Id</th>
		<th style="width:20px;border:1px solid #ccc;">VTransID</th>
		<th style="width:20px;border:1px solid #ccc;">Vendor</th>
		<th style="width:20px;border:1px solid #ccc;">CustMob</th>
		<th style="width:20px;border:1px solid #ccc;">Operator</th>
		<th style="width:20px;border:1px solid #ccc;">Amt</th>
		<th style="width:20px;border:1px solid #ccc;">Previous Status</th>
		<th style="width:20px;border:1px solid #ccc;">Status</th>
		<th style="width:20px;border:1px solid #ccc;">Timestamp</th>
		<th style="width:20px;border:1px solid #ccc;">cause</th>
		<th style="width:20px;border:1px solid #ccc;">Closed By</th>
	</tr>
<?php

$i = 1;
		foreach($result as $val){
                 if($i%2 == 0)$class = '';
                  else $class = 'altRow';
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
				$ps = 'Complaint taken';
			}else if($val['vendors_activations']['status'] == '5'){
				$ps = 'Complaint declined';
			}
			if($val['vendors_activations']['prevStatus'] == '0'){
				$previousstatus = 'In Process';
				
			}
			if($val['vendors_activations']['prevStatus'] == '1'){
				$previousstatus = 'Successful';
				
			}
			if($val['vendors_activations']['prevStatus'] == '2'){
				$previousstatus = 'Failed';
				
			}
			if($val['vendors_activations']['prevStatus'] == '3'){
				$previousstatus = 'Reversed';
				
			}
			if($val['vendors_activations']['prevStatus'] == '4'){
				$previousstatus = 'Complaint taken';
				
			}
			if($val['vendors_activations']['prevStatus'] == '5'){
				$previousstatus = 'Complaint declined';
				
			}
?>    
       <tr class="<?php echo $class; ?>">
		<td style="align:center;"><?php  echo $i; ?></td>
        <td style="align:center;"><?php  echo $val['vendors_activations']['txn_id'];?></td>
        <td style="align:center;"><?php  echo $val['vendors_activations']['vendor_refid'];?></td>
		<td style="align:center;"><?php  echo $val['vendors']['company'];?></td>
		<td style="align:center;"><?php echo $val['vendors_activations']['mobile']; ?></td>
		<td style="align:center;"><?php  echo $val['products']['name'];?></td>
		<td style="align:center;"><?php  echo $val['vendors_activations']['amount'];?></td>
		<td style="align:center;"><?php  echo $previousstatus;?></td>
		<td style="align:center;"><?php  echo $ps;?></td>
		<td style="align:center;"><?php  echo $val['vendors_activations']['timestamp'];?></td>
		<td style="align:center;"><?php  echo $val['vendors_activations']['cause'];?></td>
		<td style="align:center;"><?php echo $val['users']['name']; ?></td>
	</tr>          
			
<?php $i++; } ?>
</table>
