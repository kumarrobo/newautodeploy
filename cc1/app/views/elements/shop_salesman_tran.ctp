<script>
function salesmanTranSearch(){
	var date_from =  $('fromDate').value;
	var date_to = $('toDate').value;
        var dt_from = new Date(date_from.split("-").reverse().join("-")).getTime();
        var dt_to = new Date(date_to.split("-").reverse().join("-")).getTime();
	if(dt_from > dt_to){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
	}else{
		var salesman = $('salesman').options[$('salesman').selectedIndex].value;
		document.location.href="/shops/salesmanTran/"+date_from+"/"+date_to+"/"+salesman;
	}
}

function edit(date,id){
	var top_id = 'topup_coll_'+date+'_'+id;
	var topup = $(top_id).innerHTML;
	//var setup = $('setup_coll_'+date+'_'+id).innerHTML;
	var cash = $('cash_coll_'+date+'_'+id).innerHTML;
	var cheque = $('cheque_coll_'+date+'_'+id).innerHTML;
	$('topup_coll_'+date+'_'+id).innerHTML="<input type='text' size='8' id='input_top_"+date+"_"+id+"' value='"+topup+"'>";
	//$('setup_coll_'+date+'_'+id).innerHTML="<input type='text' size='8' id='input_set_"+date+"_"+id+"' value='"+setup+"'>";
	$('cash_coll_'+date+'_'+id).innerHTML="<input type='text' size='8' id='input_cash_"+date+"_"+id+"' value='"+cash+"'>";
	$('cheque_coll_'+date+'_'+id).innerHTML="<input type='text' size='8' id='input_cheque_"+date+"_"+id+"' value='"+cheque+"'>";
	$('edit_'+date+'_'+id).innerHTML="<a href='javascript:void(0)' onclick='editDetails(\""+date+"\","+id+")'>Submit</a>";
}

function editDetails(date,id){
	var topup = $("input_top_"+date+"_"+id).value;
	//var setup = $("input_set_"+date+"_"+id).value;
	var setup = 0;
	var cash = $("input_cash_"+date+"_"+id).value;
	var cheque = $("input_cheque_"+date+"_"+id).value;
	var edit_html = $('edit_'+date+'_'+id).innerHTML;
	var r=confirm("Confirm?");
	if(r==true){
		$('edit_'+date+'_'+id).innerHTML='Submitting';
	
		var url = '/shops/addSalesmanCollection';
			var params = {'date' : date,'id' : id,'topup' : topup, 'setup' : setup, 'cash':cash,'cheque':cheque};
			var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
			onSuccess:function(transport)
					{		
						var html = transport.responseText;
						if(html == 'done'){
							$('topup_coll_'+date+'_'+id).innerHTML = topup;
							//$('setup_coll_'+date+'_'+id).innerHTML = setup;
							$('cash_coll_'+date+'_'+id).innerHTML = cash;
							$('cheque_coll_'+date+'_'+id).innerHTML = cheque;
							$('edit_'+date+'_'+id).innerHTML='done';
						}
						else {
							$('edit_'+date+'_'+id).innerHTML=edit_html;
							alert(html);
						}
					}
			});
	}
}
</script>

<fieldset style="padding:0px;border:0px;margin:0px;">
	<div>
	<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($from)) echo $from;?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($to)) echo $to;?>">
	
	<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="salesmanTranSearch();"></span>
	<div style="margin-top:10px;"><span style="font-weight:bold;margin-right:10px;">Select Salesman: </span>
		<select id="salesman">
	   		<option value="0">ALL</option>
			<?php foreach($salesmans as $salesman) {?>
				<option value="<?php echo $salesman['salesmen']['id'];?>" <?php if(isset($id) && $id == $salesman['salesmen']['id']) echo "selected";?>><?php echo $salesman['salesmen']['name'] . " - " . $salesman['salesmen']['mobile'] ; ?></option>
			<?php } ?>
		</select>
	</div>
    				
	</div>
	<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
	<?php 
		/*$date1 = new DateTime($from);
		$date2 = new DateTime($to);
		$interval = $date1->diff($date2);
		$days = $interval->d;
		
		for($i=0;$i<=$days;$i++){*/
                foreach($data as $date=>$d){
			//$date = date('Y-m-d',strtotime($from . ' + '.$i. ' days'));
			$date_set = date('d-m-Y',strtotime($date));
			$tot_topup = 0;$tot_setup = 0;$tot_topup_coll = 0;$tot_setup_coll = 0;$tot_cash = 0;$tot_cheque = 0;
			if(isset($data[$date])){
	?>
	<div class="appTitle" style="margin-top:20px;">Collection History: <?php echo $date_set; ?></div>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
		<thead>
          <tr class="noAltRow altRow">
            <th style="width:60px;">Salesman</th>
            <th style="width:30px;">Total TopUp</th>
            <!--<th style="width:30px;">Total SetUp</th>-->
            <th style="width:30px;">TopUp Collected</th>
            <!--<th style="width:30px">SetUp Collected</th>-->
            <th style="width:30px;">Cash Collected</th>
            <th style="width:30px">Cheque Collected</th>
            <th style="width:20px">Action</th>
          </tr>
        </thead>
        <tbody>
        	<?php if($id != 0){ if(isset($data[$date][$id])){?>
        	  <tr> 
	            <td><?php echo $sinfo['0']['salesmen']['name']; ?></td>
	            <td><?php if(isset($data[$date][$id]['topup'][2])) echo $data[$date][$id]['topup'][2]; else echo 0;?></td>
	            <!--<td><?php if(isset($data[$date][$id]['topup'][1])) echo $data[$date][$id]['topup'][1]; else echo 0;?></td>-->
	            <td><span id="topup_coll_<?php echo $date . "_" . $id; ?>"><?php if(isset($data[$date][$id]['collection'][2])) echo $data[$date][$id]['collection'][2]; else echo 0;?></span></td>
	            <!--<td><span id="setup_coll_<?php echo $date . "_" . $id; ?>"><?php if(isset($data[$date][$id]['collection'][1])) echo $data[$date][$id]['collection'][1]; else echo 0;?></span></td>-->
	            <td><span id="cash_coll_<?php echo $date . "_" . $id; ?>"><?php if(isset($data[$date][$id]['collection'][3])) echo $data[$date][$id]['collection'][3]; else echo 0;?></span></td>
	            <td><span id="cheque_coll_<?php echo $date . "_" . $id; ?>"><?php if(isset($data[$date][$id]['collection'][4])) echo $data[$date][$id]['collection'][4]; else echo 0;?></span></td>
	            <td><span id="edit_<?php echo $date . "_" . $id; ?>"><a href="javascript:void(0);" onclick="edit('<?php echo $date; ?>',<?php echo $id; ?>)">Edit</a></span></td>
	          </tr>
        	<?php }} else { ?>
        	<?php foreach($salesmans as $salesman) {
        		$sales_id = $salesman['salesmen']['id'];
        		$name = $salesman['salesmen']['name'];
        		//if(isset($data[$date][$sales_id])){
        			$tot_topup += isset($data[$date][$sales_id]['topup'][2]) ? $data[$date][$sales_id]['topup'][2] : "";
        			//$tot_setup += isset($data[$date][$sales_id]['topup'][1]) ? $data[$date][$sales_id]['topup'][1] : "";
        			$tot_topup_coll += isset($data[$date][$sales_id]['collection'][2]) ? $data[$date][$sales_id]['collection'][2] : "0";
        			//$tot_setup_coll += isset($data[$date][$sales_id]['collection'][1]) ? $data[$date][$sales_id]['collection'][1] : "0";
        			$tot_cash += isset($data[$date][$sales_id]['collection'][3]) ? $data[$date][$sales_id]['collection'][3] : "0" ;
        			$tot_cheque += isset($data[$date][$sales_id]['collection'][4]) ? $data[$date][$sales_id]['collection'][4] : "0" ;
        		?>
	        	  <tr> 
		            <td><?php echo $name; ?></td>
		            <td><?php if(isset($data[$date][$sales_id]['topup'][2])) echo $data[$date][$sales_id]['topup'][2]; else echo 0;?></td>
		            <!--<td><?php if(isset($data[$date][$sales_id]['topup'][1])) echo $data[$date][$sales_id]['topup'][1]; else echo 0;?></td>-->
		            <td><span id="topup_coll_<?php echo $date . "_" . $sales_id; ?>"><?php if(isset($data[$date][$sales_id]['collection'][2])) echo $data[$date][$sales_id]['collection'][2]; else echo 0;?></span></td>
		            <!--<td><span id="setup_coll_<?php echo $date . "_" . $sales_id; ?>"><?php if(isset($data[$date][$sales_id]['collection'][1])) echo $data[$date][$sales_id]['collection'][1]; else echo 0;?></span></td>-->
		            <td><span id="cash_coll_<?php echo $date . "_" . $sales_id; ?>"><?php if(isset($data[$date][$sales_id]['collection'][3])) echo $data[$date][$sales_id]['collection'][3]; else echo 0;?></span></td>
		            <td><span id="cheque_coll_<?php echo $date . "_" . $sales_id; ?>"><?php if(isset($data[$date][$sales_id]['collection'][4])) echo $data[$date][$sales_id]['collection'][4]; else echo 0;?></span></td> 
		            <td><span id="edit_<?php echo $date . "_" . $sales_id; ?>"><a href="javascript:void(0);" onclick="edit('<?php echo $date; ?>',<?php echo $sales_id; ?>)">Edit</a></span></td>
		          </tr>
	        	<?php //}
	        	} ?>
	        	<tr>
		            <td><b>Total</b></td>
		            <td><b><?php echo $tot_topup;?></b></td>
		            <!--<td><b><?php echo $tot_setup;?></b></td>-->
		            <td><b><?php echo $tot_topup_coll;?></b></td>
		            <!--<td><b><?php echo $tot_setup_coll;?></b></td>-->
		            <td><b><?php echo $tot_cash;?></b></td>
		            <td><b><?php echo $tot_cheque;?></b></td>
		            <td></td>
		          </b></tr>
		<?php } ?>  
        </tbody>	         
   	</table>
   	<?php }} ?>
   
</fieldset>