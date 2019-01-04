
<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<link type='text/css' rel='stylesheet' href='/min/b=css&f=lib/bootstrap/css/bootstrap.min.css?990' />
<style type="text/css">
     .redcolor {
        color: red;
    }

</style>
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>
	<script type="text/javascript">
function setAction(){
     
	document.tranReversal.action="/panels/pullbackReport/"+$('#from').val()+"/"+$('#to').val();
	document.tranReversal.submit();
}
function pullback(id){
	var r=confirm("Click 'Ok' to confirm");
	
	if (r==true)
	{ 
		var url = '/panels/pullback/';
		$.ajax({
            url: url,
            type: "POST",
            data: {"id": id},
            dataType: "text",
            success: function(data) {
				if(data == 'success'){
					window.location.reload();
				} else {
					alert(data);
				}
               
            }
        });

	}
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
<table  style="" width='100%'cellspacing="0" cellpadding="0">
	<tr style="border:1px solid;">
		<th colspan="11">Pullback Report</th>
	</tr>
<tr style="">
		<th style="width:20px;border:1px solid #ccc;">Index</th>
		<th style="width:20px;border:1px solid #ccc;">Transaction Id</th>
	
		<th style="width:20px;border:1px solid #ccc;">Pullback Vendor</th>
		<th style="width:20px;border:1px solid #ccc;">Vendor</th>
		<th style="width:30px;border:1px solid #ccc;">Transaction Time</th>
		<th style="width:30px;border:1px solid #ccc;">Timestamp</th>
		
		<th style="width:20px;border:1px solid #ccc;">Operator</th>
		<th style="width:20px;border:1px solid #ccc;">Amt</th>
		<th style="width:20px;border:1px solid #ccc;">Status</th>
		<th style="width:20px;border:1px solid #ccc;">cause</th>
		<th style="width:20px;border:1px solid #ccc;">Reported By</th>
		<th style="width:20px;border:1px solid #ccc;">Tag</th>
		
		
		<th style="width:20px;border:1px solid #ccc;">Action</th>
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
			
			if($val['users']['name']=="" && $val['vendors_activations']['status'] == '1'){
				$class = 'redcolor';
			}
?>    
       <tr class = "<?php echo $class; ?>">
		<td style="align:center;"><?php  echo $i; ?></td>
        <td style="align:center;"><a href="/panels/transaction/<?php echo $val['vendors_activations']['txn_id']; ?>" target="_blank"><?php  echo $val['vendors_activations']['txn_id'];?></a></td>
       
		<td style="align:center;"><?php  echo $val['va']['company'];?></td>
		<td style="align:center;"><?php  echo $val['vendors']['company'];?></td>
		<td style="align:center;"><?php  echo $val['vendors_activations']['timestamp'];?></td>
		<td style="align:center;"><?php  echo $val['trans_pullback']['timestamp'];?></td>
		<td style="align:center;"><?php  echo $val['products']['name'];?></td>
		<td style="align:center;"><?php  echo $val['vendors_activations']['amount'];?></td>
		<td style="align:center;"><?php  echo $ps;?></td>
		<td style="align:center;"><?php  echo $val['vendors_activations']['cause'];?></td>
		<td style="align:center;"><?php  echo $val['trans_pullback']['reported_by'];?></td>
		<td style="align:center;"><?php  echo $comments[$val['vendors_activations']['txn_id']]; ?></td>
		
		<?php if($val['trans_pullback']['pullback_by']==0 && $val['vendors_activations']['status'] != '1' ){?>
		<td><button type="button" class="btn btn-success" onclick="pullback(<?php echo $val['vendors_activations']['id']; ?>);">Pullback</button></td>
		<?php } else { ?>
		<td style="align:center;"><?php echo $val['users']['name']; ?><br/><?php echo $val['trans_pullback']['pullback_time']; ?></td>
		<?php } ?>
	</tr>          
			
<?php $i++; } ?>

</table>
