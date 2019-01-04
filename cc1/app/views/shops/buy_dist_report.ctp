<?php if($pageType != 'csv'){?>
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'topup'));?>
    		<div id="innerDiv">
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findDistributor();"></span>
                        <a href="javascript:void(0);" title="Download old data ( 3 months before data ) " onclick="dwnldArchData ()">
<!--                            <img id="export_csv" class="export_csv" style="height:25px" src="/img/csv1.jpg" alt="xp" type="button"/>-->
                                Download Old Data
                        </a>
    			
    			<div style="margin-top:10px;"><span style="font-weight:bold;margin-right:10px;">Select <?php echo $modelName;?>: </span>
					<select id="shop">
               		<option value="0">All <?php echo $modelName;?>s</option>
					<?php foreach($records as $distributor) {?>
						<option value="<?php echo $distributor[$modelName]['id'];?>" <?php if($dist !=0 && $dist == $distributor[$modelName]['id']) echo "selected";?>><?php echo $distributor[$modelName]['company']; ?></option>
					<?php } ?>
					</select>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				<div class="appTitle" style="margin-top:20px;">Topup History <?php if(isset($date_from) && isset($date_to)) echo "(". date('d-m-Y', strtotime($date_from)) . " - " .  date('d-m-Y', strtotime($date_to)) . ")"; ?></div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			          	<th style="width:80px;">Transaction ID</th>
			            <th style="width:100px;">Date</th>
			            <th style="width:150px;">Particulars</th>
			            <th style="width:150px;">Transfer Type</th>
			            <th class="number" style="width:70px">Credit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			          	<th class="number" style="width:70px">Discount  &nbsp; &nbsp;(<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                        <th class="number" style="width:70px">Opening (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                        <th class="number" style="width:70px">Closing (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
			          	<th class="number" style="width:80px;">Action</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($transactions)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } else { ?>
                    <?php $i=0; $total_amt =0; $total_comm = 0; 
                    foreach($transactions as $transaction){
                    	//echo "<pre>"; print_r($transaction); echo "</pre>";
                    	$total_amt += $transaction['shop_transactions']['amount'];
                    	$total_comm += $transaction['st']['commission'];
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>
                      <tr class="<?php echo $class; ?>"> 
			            <td><?php echo $transaction['shop_transactions']['id']; ?></td>
			            <td><?php echo date('d-m-Y H:i:s', strtotime($transaction['shop_transactions']['timestamp'])); ?></td>
			           	<td><?php echo $transaction['0']['company']; ?></td>
			           	<td>
                                        <?php 
                                        

                                        $trans_type = $objGeneral->getTransferTypeName($transaction['shop_transactions']['type']);
                                        //echo $trans_type."<br>";
                                        
                                        if($transaction['shop_transactions']['type_flag'] == 1) 
                                            echo 'Cash'; 
                                        else if($transaction['shop_transactions']['type_flag'] == 2) 
                                            echo 'NEFT'; 
                                        else if($transaction['shop_transactions']['type_flag'] == 3) 
                                            echo 'ATM Transfer'; 
                                        else if($transaction['shop_transactions']['type_flag'] == 4) 
                                            echo 'Cheque'; 
                                        else if($transaction['shop_transactions']['type_flag'] == 5) 
                                            echo 'Payment Gateway';
                                        
                                        echo " - " . $transaction['shop_transactions']['note']; 
                                            
                                            ?></td>
			            <td class="number"><?php echo empty($transaction['shop_transactions']['amount']) ? 0 : $transaction['shop_transactions']['amount']; ?></td>
			            <td class="number"><?php echo empty($transaction['st']['commission']) ? 0 : $transaction['st']['commission'] ; ?></td>
                                    <td class="number"><?php echo empty($transaction['shop_transactions']['opening'])? 0 : $transaction['shop_transactions']['opening']; ?></td> 
                                    <td class="number"><?php echo empty($transaction['shop_transactions']['closing']) ? 0 : $transaction['shop_transactions']['closing']; ?></td> 
                                    <?php if($_SESSION['Auth']['User']['group_id']!=RELATIONSHIP_MANAGER){ ?> 	
			        	<td id="pullback_<?php echo $transaction['shop_transactions']['id']; ?>" class="number"><a href="javascript:void(0);" onclick="pullback(<?php echo $transaction['shop_transactions']['id']; ?>)">Pull Back</a></td>
                                   <?php } ?> 
                                      
                                        
    			    <?php }
    			    $i++; } ?> 					    			      
			         </tbody>
			         <tfoot>   
				     	<tr style="font-weight:bold"> 
				            <td><b>Total</b></td>
				            <td></td>
				            <td></td>
				            <td></td>
				            <td class="number"><b><?php echo isset($total_amt) ? $total_amt : 0; ?></b></td>
				            <td class="number"><b><?php echo isset($total_comm) ? $total_comm : 0; ; ?></b></td>
				            <td></td>
                                            <td></td>
                                            <td></td>
			          	</tr>
		          	 </tfoot>	         
			   	</table>
			</fieldset>
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function findDistributor(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
        
         var dt_from = new Date(date_from.split("-").reverse().join("-")).getTime();
        var dt_to = new Date(date_to.split("-").reverse().join("-")).getTime();
	
        if(dt_from > dt_to){//if(date_from > date_to){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
		$('submit').innerHTML = html;
	}
	else {
		$('date_err').hide();
		var salesman = $('shop').options[$('shop').selectedIndex].value;
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		document.location.href="/shops/topupDist/"+date_from+"-"+date_to+"/"+salesman;
	}
}

function pullback(shop_tran_id){
	var r=confirm("Are You sure, you want to pull back this amount?");
	if(r==true){
		var html = $('pullback_'+shop_tran_id).innerHTML;
		$('pullback_'+shop_tran_id).innerHTML = "Submitted";
		var url = '/shops/pullback';
		var params = {'shop_transid': shop_tran_id};
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
		onSuccess:function(transport)
				{			
					if(transport.responseText == 'success'){
						$('pullback_'+shop_tran_id).innerHTML = "Completed";
						alert('done');
						
					}else{
						$('pullback_'+shop_tran_id).innerHTML = html;
						alert(transport.responseText);
					}
				}
		});
		
	}
}
function dwnldArchData (){
        
        //var html = $('submit').innerHTML;
	//showLoader3('submit');
	
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
        
         var dt_from = new Date(date_from.split("-").reverse().join("-")).getTime();
        var dt_to = new Date(date_to.split("-").reverse().join("-")).getTime();
	//alert((dt_to - dt_from)/(1000*60*60*24));
        if(dt_from > dt_to){//if(date_from > date_to){
		$('date_err').innerHTML = "Error: Please select proper dates";
		$('date_err').show();
		//$('submit').innerHTML = html;
                return;
	}
	else if((dt_to - dt_from)/(1000*60*60*24) > 365 ){//if(date_from > date_to){
		$('date_err').innerHTML = "Error: Can't get more than 1 year data .";
		$('date_err').show();
		//$('submit').innerHTML = html;
                return;
        }else{
		$('date_err').hide();
		var salesman = $('shop').options[$('shop').selectedIndex].value;
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		var url ="/shops/topupDist/"+date_from+"-"+date_to+"/"+salesman;
                
                var newWindow = url+"?res_type=csv&old_data=old_csv";
                window.open(newWindow, '_blank');
	}
        
        
        //e.preventDefault();
}
</script>
<?php } ?>