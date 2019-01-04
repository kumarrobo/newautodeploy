<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'allot'));?>
    		<div id="innerDiv">
    			<div>
    				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    				<span id="submit" style="margin-left:30px;"><input type="button" value="Search" style="padding: 0 5px 3px" class="retailBut enabledBut" id="sub" onclick="findAllotted();"></span>
    				<?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
    				<div style="margin-top:10px;"><span style="font-weight:bold;margin-right:10px;">Select Distributor: </span>
    				<select id="shop">
                   		<option value="0"></option>
						<?php foreach($distributors as $distributor) {?>
							<option value="<?php echo $distributor['Distributor']['id'];?>" <?php if(isset($distributor_id) && $distributor_id == $distributor['Distributor']['id']) echo "selected";?>><?php echo $distributor['Distributor']['company'] . " - " . $distributor['Distributor']['id'] ; ?></option>
						<?php } ?>
					</select>
					</div>
    				<?php } ?>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				
	  			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:15px;">
				<div class="appTitle">Card Allotment History</div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:150px;">Date</th>
			            <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
			            <th style="width:40px;">Distributor</th>
			            <?php } ?>
			            <th style="width:40px;">Product</th>
			            <th class="number" style="width:40px;">Quantity</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($alloted)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($alloted as $allot){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>
                      <tr class="<?php echo $class; ?>"> 
			            <td><?php echo date('d-m-Y', strtotime($allot['0']['date'])); ?></td>
			             <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
			            <td><?php echo $allot['Distributor']['company']; ?></td>
			            <?php } ?>
			            <td><?php echo $allot['Product']['name'] . ' (' . $objShop->shortSerials($allot['0']['serials']) . ')'; ?></td>
			            <td class="number"><?php echo count(explode(",",$allot['0']['serials'])); ?></td>
    			      </tr>
    			    <?php $i++; } ?> 					    			      
			         </tbody>	         
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
function findAllotted(){
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	var date = 0;
	if($('shop')) var distributor = $('shop').options[$('shop').selectedIndex].value;
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
			if(distributor == 0 && date == -1){
				$('date_err').innerHTML = "Error: Please select something for search";
				$('date_err').show();
				$('submit').innerHTML = html;
			}
			else if(distributor == 0){
				$('date_err').hide();
				window.location = "http://" + siteName + "/shops/cardsAllotted/"+date;
			}
			else{
				$('date_err').hide();
				window.location = "http://" + siteName + "/shops/cardsAllotted/"+date+"/"+distributor;
			}
		}
	}
	else {
		if(date_from == '' || date_to == ''){
			$('date_err').innerHTML = "Error: Please select dates";
			$('date_err').show();
			$('submit').innerHTML = html;
		}
		else {
			$('date_err').hide();
			date_from = date_from.replace(/-/g,"");
			date_to = date_to.replace(/-/g,"");
			window.location = "http://" + siteName + "/shops/cardsAllotted/"+date_from+"-"+date_to;
		}
	}
	
}
</script>