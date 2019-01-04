<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'pnrList'));?>
    		<div id="innerDiv">
    			<div>
    				<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			
    				<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findPnrs();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
				
    			<div style="margin-top:10px;"><span id="err" class="error" style="display:none;">Error: Please select a distributor</span></div>
	  			
	  			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:20px">
					<div class="appTitle">PNR Listing</div>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Retailers">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:100px;">Product</th>
			            <th style="width:40px;">PNR Number</th>
			            <th style="width:105px">Mobile Number</th>
			            <th style="width:85px">Timestamp</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($pnrs)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Pnr Numbers found !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($pnrs as $rec){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>			            
                    <tr class="<?php echo $class; ?>"> 
			            <td><?php echo $rec['Product']['name']; ?></td>
			            <td><?php echo $rec['Pnr']['pnr_number']; ?></td>
			            <td><?php echo substr_replace($rec['Pnr']['mobile'], 'XXXX', 6); ?></td>
			            <td><?php echo date('d-m-Y H:i:s', strtotime($rec['Pnr']['start'])); ?></td>
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
function findPnrs(){
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
		window.location = "http://" + siteName + "/shops/PNRListing/"+date_from+"-"+date_to;
	}
}
</script>