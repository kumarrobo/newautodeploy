<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'invoice'));?>
    		<div id="innerDiv">
    			<div>
    				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    				<span id="submit" style="margin-left:30px"><input type="button" value="Search" style="padding: 0 5px 3px" class="retailBut enabledBut" id="sub" onclick="findInvoices();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
	  			
	  			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:15px;">
				<!-- <div class="appTitle">Invoices For You</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Date</th>
			            <th style="width:100px;">Type</th>
			            <th style="width:60px;">Invoice</th>
			            <?php if($_SESSION['Auth']['User']['group_id'] == RETAILER){?>
			            <th style="width:60px;">O/S Balance</th>
			            <th style="width:60px;">Receipts</th>
			            <?php } ?>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($invoices)) { ?>
                    <tr>
                    	<td colspan="5"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($invoices as $invoice){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    	if(empty($invoice['0']['os_amount'])) $outstanding =  $invoice['Invoice']['amount'];
                		else $outstanding = $invoice['0']['os_amount'];
                		if(!isset($start_inv) || (isset($start_inv) && $outstanding > 0 && $invoice['Invoice']['invoice_type'] == DISTRIBUTOR_ACTIVATION)) { 
                    ?>
                      <tr class="<?php echo $class; ?>"> 
			            <td><?php echo date('d-m-Y',strtotime($invoice['0']['date'])); ?></td>
			            <?php if($invoice['Invoice']['invoice_type'] == RETAILER_ACTIVATION) { ?>
			            <td><?php echo "Online Card Activation"; ?></td>
			            <?php } else if($invoice['Invoice']['invoice_type'] == DISTRIBUTOR_ACTIVATION) { ?>
			            <td><?php echo "Physical Card Activation"; ?></td>
			            <?php } ?>
			            
			            <td><a href="javascript:void(0)" onclick="showInvoice('<?php echo $objMd5->encrypt($invoice['Invoice']['id'],encKey); ?>')"><?php echo $invoice['Invoice']['invoice_number']; ?></a></td>
    			      	<?php if($_SESSION['Auth']['User']['group_id'] == RETAILER){	?>
    			      	<td><?php if($invoice['Invoice']['invoice_type'] == DISTRIBUTOR_ACTIVATION) { echo $outstanding; } ?></td>
			            <td> <?php if(!empty($invoice['0']['recids'])){ 
    			      		$ids = explode(",",$invoice['0']['recids']);
    			      		$numbers = explode(",",$invoice['0']['numbers']);
    			      		for($i = 0;$i<count($ids);$i++){
    			      	?> 
    			      		<div class="taggLink taggLink1">
                              <div class="taggLinkBG">
                                <div class="taggLinkBorder">
                                  <div class="taggLinkCont"><a href="javascript:void(0)" onclick="printReceipt(<?php echo $ids[$i]; ?>)"><?php echo $numbers[$i]; ?></a></div>
                                </div>
                              </div>
                            </div>
                            <?php }} ?>
                        </td>
			            <?php } ?>
    			      </tr>
    			    <?php } $i++; } ?> 					    			      
			         </tbody>	         
			   	</table> -->
			   <div class="appTitle">Top-up Requests By You</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Date</th>
			            <th style="width:180px;">Description</th>
			            <th style="width:60px;">Request</th>
			            <th style="width:60px;">O/S Balance</th>
			            <th style="width:60px;">Receipts</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($data_parent)) { ?>
                    <tr>
                    	<td colspan="5"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($data_parent as $invoice){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    	if(empty($invoice['0']['os_amount'])) $outstanding =  $invoice['ShopTransaction']['amount'];
                    	else $outstanding = $invoice['0']['os_amount'];
                    	
                    	if(!isset($start) || (isset($start) && $outstanding > 0)) { 
                    ?>
                      <tr class="<?php echo $class; ?>"> 
			            <td><?php echo date('d-m-Y',strtotime($invoice['ShopTransaction']['timestamp'])); ?></td>
			            <td>Amount transferred to you</td>
			            <td><a href="javascript:void(0)" onclick="showReceipt('<?php echo $objMd5->encrypt($invoice['ShopTransaction']['id'],encKey); ?>')"><?php echo $objShop->getTopUpReceiptNumber($invoice['ShopTransaction']['id']); ?></a></td>
    			      	<td><?php echo $outstanding; ?></td>
			            <td> <?php if(!empty($invoice['0']['recids'])){ 
    			      		$ids = explode(",",$invoice['0']['recids']);
    			      		$numbers = explode(",",$invoice['0']['numbers']);
    			      		for($i = 0;$i<count($ids);$i++){
    			      	?> 
    			      		<div class="taggLink taggLink1">
                              <div class="taggLinkBG">
                                <div class="taggLinkBorder">
                                  <div class="taggLinkCont"><a href="javascript:void(0)" onclick="printReceipt(<?php echo $ids[$i]; ?>)"><?php echo $numbers[$i]; ?></a></div>
                                </div>
                              </div>
                            </div>
                            <?php }} ?>
                        </td>
    			      </tr>
    			    <?php } $i++; } ?> 					    			      
			         </tbody>	         
			   	</table>
			   	<!-- 
			   	<div class="appTitle">Sale Report</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Sale Report">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:120px;">Product</th>
			            <th style="width:150px;">Total Sold</th>
			            <th style="width:80px;">Unit MRP</th>
			            <th style="width:80px;">Total MRP</th>
			            <th style="width:60px;">Total Commission</th>
			            <th style="width:60px;">TDS</th>
			            <th style="width:60px;">Net Amount</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($products)) { ?>
                    <tr>
                    	<td colspan="5"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } else { ?>
                    <?php $i=0; $total_cards=0; $total_mrp=0; $total_commission=0; $total_tds=0; $net=0;
                    foreach($products as $product){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    	$total_cards += $product['0']['counts'];
                    	$total_mrp += $product['0']['counts']*$product['products']['price'];
                    	$total_commission += $product['0']['commission'];
                    	$total_tds += $product['0']['tds'];
                    	$net +=  $product['0']['counts']*$product['products']['price'] - ($product['0']['commission'] - $product['0']['tds']);
                    ?>	
                      <tr class="<?php echo $class; ?>"> 
			            <td><?php echo $product['products']['name']; ?></td>
			            <td><?php echo $product['0']['counts']; ?></td>
			            <td><?php echo $product['products']['price']; ?></td>
			            <td><?php echo $product['0']['counts']*$product['products']['price']; ?></td>
			            <td><?php echo $product['0']['commission']; ?></td>
			            <td><?php echo $product['0']['tds']; ?></td>
			            <td><?php echo $product['0']['counts']*$product['products']['price'] - ($product['0']['commission'] - $product['0']['tds']); ?></td>
			          </tr> 
                        <?php $i++; } if(!empty($products)) { ?>
    			     <tr style="font-weight:bold"> 
			            <td><b>Total</b></td>
			            <td><b><?php echo $total_cards; ?></b></td>
			            <td>-</td>
			            <td><b><?php echo $total_mrp; ?></b></td>
			            <td><b><?php echo $total_commission; ?></b></td>
			            <td><b><?php echo $total_tds; ?></b></td>
			            <td><b><?php echo $net; ?></b></td>
			          </tr>
    			    <?php }} ?> 					    			      
			         </tbody>	         
			   	</table>
			   -->
			</fieldset>
   			</div>
   			<br class="clearLeft" />
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function showInvoice(invoice_id){
	var url = "/shops/printInvoice/" + invoice_id;
	window.open(url,"Invoice","width=700,height=600,scrollbars=1");
}

function showReceipt(receipt_id){
	var url = "/shops/printRequest/" + receipt_id;
	window.open(url,"Request","width=700,height=600,scrollbars=1");
}

function findInvoices(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	if(date_from == '' || date_to == ''){
		$('date_err').show();
		$('submit').innerHTML = html;
	}
	else {
		$('date_err').hide();
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		window.location = "http://" + siteName + "/shops/invoices/"+date_from+"-"+date_to;
	}
}
</script>