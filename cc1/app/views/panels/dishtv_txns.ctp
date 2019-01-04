<style>
.top-buffer {
	margin-top: 20px;
}
.checkbox-margin {
	margin-top: -13px;
}
.table td, .table th {
   text-align: center;   
}
.btn-b2c, .bg-b2c {
	background-color: rgba(176, 252, 35, 0.28);
}
.btn-complaint, .bg-complaint {
	background-color: rgba(128, 128, 128, 0.15);
}
.btn-novendor, .bg-novendor {
	background-color: rgba(255, 0, 0, 0.2);
}
.btn-disabledmodem, .bg-disabledmodem {
	background-color: rgba(249, 204, 9, 0.47);;
}
.new-retailer{
	background-color: rgba(0,255, 0, 0.2);
}
</style>	

<script>
var loader = "<img src='/img/ajax-loader-1.gif' />";

jQuery.fn.addHiddenInput = function (name, value) {
    return this.each(function () {
        var input = $("<input>").attr("type", "hidden").attr("name", name).val(value);
        $(this).append($(input));
    });
};




function manualSuccess(id){
        var r = confirm("Confirm?");
        if(r){
                var url = '/panels/manualSuccess';
                var data = 'id='+ id;
                jQuery.ajax({
                type: "POST",
                url: url,
                datatype: "json",
                data: data,
                success: function(data) {
                 vJSONResp = JSON.parse(data);
                 alert("done");
                }
            });
                

        }       
}

function manualProcess(id){
        var r = confirm("Confirm?");
        if(r){
                var url = '/panels/processDishTvTransaction';
                var data = 'id='+ id;
                jQuery.ajax({
                type: "POST",
                url: url,
                datatype: "json",
                data: data,
                success: function(data) {
                 vJSONResp = JSON.parse(data);
                 alert("done");
                }
            });
                

        }       
}

function manualFailure(id){
	var r = confirm("Confirm?");
	if(r){
		$('#mf_' + id).html(loader);
		var url = '/panels/manualFailure';
		var params = {'id' : id};

		$('#mf_' + id).load(url, params);
	}	
}


function filter(transaction_class){
	$('.table > tbody > tr').each(function(){
		$(this).show();
		if($(this).hasClass(transaction_class)){
			$(this).show();
		}	
		else
			$(this).hide();
	});
	$('.table-head').show();
}
</script>
<link rel="SHORTCUT ICON" href="/img/pay1_favic.png">	
<title>DishTv Transactions</title>

<div class="panel panel-default">
  <div class="panel-heading">
  	<h1>Dish TV Manual Transactions <?php echo "(".count($data).")"?></h1>
  	Generated at: <span style="color:#428bca"><?php echo date("h : i A"); ?></span>
  </div>
  <div class="panel-body">
    

    <div class="row top-buffer">
        <table border="1" class="table table-hover table-condensed table-bordered" style="border-collapse:collapse;">
			<tr class="table-head">
						<th>Operator</th>                      
						<th>Amt</th> 
						<th>Tran Id.</th>
						<th>VC No.</th>
	  					<th>Action</th>
	  		    		<th>Trans Time</th>
	  		    		<th>Time left</th>
	  		    		<th>Process Txn</th>
	  		    		
			</tr>
        <?php foreach($data as $d): ?>
        	<tr>
        		<td>Dish Tv</td>
        		<td><?php echo $d['va']['amount'] ?></td>
  				<td><a target="_blank" href="/panels/transaction/<?php echo $d['va']['txn_id'] ?>"><?php echo $d['va']['txn_id'] ?></a></td>
        		<td><?php echo $d['va']['param'] ?></td>
        		<td id="ms_<?php echo $d['va']['txn_id'] ?>">
        			<a href='javascript:void(0)' onclick="manualSuccess('<?php echo $d['va']['txn_id'] ?>')"
        			class="btn btn-sm btn-success">Success</a>
				</td>
        		<td><?php echo substr($d['va']['timestamp'], 11) ?></td>
        		<?php 
        		$buffer_time = 10 * 60;
        		$effective_time = strtotime($d['va']['timestamp']) + $buffer_time; 
        		$secs = $effective_time - time(); 
	  			if($secs < 0){
	  				$secs = - $secs;
	  				$mins = floor($secs / 60) % 60;
	  				$hours = floor($secs / 3600);
	  				
	  				if($hours > 0)
	  					echo "<td style='color:red'>".$hours." Hrs ".$mins." mins delayed </td>";
	  				else if($mins > 0)
	  					echo "<td style='color:#ff7500;'>".$mins." mins delayed </td>";
	  				else 
	  					echo "<td style='color:#ffab00;'>".$secs." secs delayed </td>";
	  			}
	  			else {
	  				$mins = floor($secs / 60) % 60;
	  				
	  				if($mins > 0)
	  					echo "<td>".$mins." mins left </td>";
	  				else
	  					echo "<td style='color:rgb(255, 135, 0);'>".$secs." secs left </td>";
	  			}
?>
        		<td id="pms_<?php echo $d['va']['txn_id'] ?>">
        			<a href='javascript:void(0)' onclick="manualProcess('<?php echo $d['va']['txn_id'] ?>')"
        			class="btn btn-sm btn-success">Process Txn</a>
				</td>
        	</tr>
        <?php endforeach ?>
        </table>
    </div>
  </div>
</div>
