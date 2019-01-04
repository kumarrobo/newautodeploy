<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'sale'));?>
    		<div id="innerDiv">
	  			<div>
    				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    				<span id="submit" style="margin-left:30px;"><input type="button" value="Search" style="padding: 0 5px 3px" class="retailBut enabledBut" id="sub" onclick="findReports();"></span>
    				<?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
    				<div style="margin-top:10px;"><span style="font-weight:bold;margin-right:10px;">Select Retailer: </span>
    				<select id="shop">
                   		<option value="0">All Retailers</option>
						<?php foreach($retailers as $retailer) {?>
							<option value="<?php echo $retailer['Retailer']['id'];?>" <?php if(isset($id) && $id == $retailer['Retailer']['id']) echo "selected";?>><?php echo $retailer['Retailer']['shopname'] . " - " . $retailer['Retailer']['mobile'] ; ?></option>
						<?php } ?>
					</select>
					</div>
    				<?php } else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
    				<div style="margin-top:10px;"><span style="font-weight:bold;margin-right:10px;">Select Distributor: </span>
    				<select id="shop">
                   		<option value="0"></option>
						<?php foreach($distributors as $distributor) {?>
							<option value="<?php echo $distributor['Distributor']['id'];?>" <?php if(isset($id) && $id == $distributor['Distributor']['id']) echo "selected";?>><?php echo $distributor['Distributor']['company'] . " - " . $distributor['Distributor']['id'] ; ?></option>
						<?php } ?>
					</select>
					</div>
    				<?php } ?>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
	  			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:15px;">
	  			<?php if(empty($products)) { ?>
	  			<span class="success">No Results Found !!</span>
	  			<?php } else foreach($products as $service){ ?>
	  			<div class="appTitle">Sale Report - <?php echo $service['name'];?></div>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Sale Report">
                <thead>
		          <tr class="noAltRow altRow">
		            <th style="width:120px;">Product</th>
		            <th style="width:150px;" class="number">No. of Transactions</th>
		            <th style="width:80px;"  class="number">Total Amount</th>
		            <!-- <th style="width:80px;">Total Income</th> -->
		          </tr>
		        </thead>
		        <tbody>
                
                <?php $i=0; $total_cards=0; $total_mrp=0; $total_commission=0;
                foreach($service['data'] as $product){ 
                	if($i%2 == 0)$class = '';
                	else $class = 'altRow';
                	$total_cards += $product['0']['counts'];
                	$total_mrp += $product['0']['amount'];
                	//$total_commission += $product['0']['income'];
                ?>	
                  <tr class="<?php echo $class; ?>"> 
		            <td><?php echo $product['products']['name']; ?></td>
		            <td class="number"><?php echo $product['0']['counts']; ?></td>
		            <td class="number"><?php echo $product['0']['amount']; ?></td>
		            <!-- <td><?php echo $product['0']['income']; ?></td> -->
		          </tr> 
                    <?php $i++; } if($i > 0) { ?>
                 <tfoot>   
			     <tr style="font-weight:bold"> 
		            <td><b>Total</b></td>
		            <td class="number"><b><?php echo $total_cards; ?></b></td>
		            <td class="number"><b><?php echo $total_mrp; ?></b></td>
		            <!--<td><b><?php echo $total_commission; ?></b></td>-->
		          </tr>
		          </tfoot>
			    <?php } ?> 					    			      
		         </tbody>	         
		   	</table>
		   	<?php } ?>
		   	</fieldset>
 		</div>
    	
    </div>
 </div>
<br class="clearRight" />
</div>
<script>
function findReports(){
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	var date = 0;
	if($('shop')) var retailer = $('shop').options[$('shop').selectedIndex].value;
	var prob = false;
	var html = $('submit').innerHTML;
	showLoader3('submit');
	if($('shop')){
		if(date_from == '' && date_to == '') {
			date = -1;
		}
		else if(date_from != '' && date_to != ''){
			date = date_from.replace(/-/g,"") + "-" + date_to.replace(/-/g,"");
		}
		else {
			$('date_err').innerHTML = "Error: Please select proper dates";
			$('date_err').show();
			$('submit').innerHTML = html;
			prob = true;
		}
		
		if(!prob){
			if(retailer == 0 && date == -1){
				$('date_err').innerHTML = "Error: Please select something for search";
				$('date_err').show();
				$('submit').innerHTML = html;
			}
			else if(retailer == 0){
				$('date_err').hide();
				window.location = siteprotocol + "//" + siteName + "/shops/saleReport/"+date;
			}
			else{
				$('date_err').hide();
				window.location = siteprotocol + "//" + siteName + "/shops/saleReport/"+date+"/"+retailer;
			}
		}
	}
	else {
		if(date_from == '' || date_to == ''){
			$('date_err').innerHTML = "Error: Please select dates";
			$('submit').innerHTML = html;
			$('date_err').show();
		}
		else {
			$('date_err').hide();
			date_from = date_from.replace(/-/g,"");
			date_to = date_to.replace(/-/g,"");
			window.location = siteprotocol + "//" + siteName + "/shops/saleReport/"+date_from+"-"+date_to;
		}
	}
	
}
</script>