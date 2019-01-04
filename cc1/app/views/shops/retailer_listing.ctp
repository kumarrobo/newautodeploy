<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_activities',array('side_tab' => 'retailerList'));?>
    		<div id="innerDiv">
    			<div>
    				<div style="margin-top:10px;"><span style="font-weight:bold;margin-right:10px;">Select Distributor: </span>
    				<select id="shop">
                   		<option value="0"></option>
						<?php foreach($distributors as $dist) {?>
							<option value="<?php echo $dist['Distributor']['id'];?>" <?php if(isset($distributor_id) && $distributor_id == $dist['Distributor']['id']) echo "selected";?>><?php echo $dist['Distributor']['company'] . " - " . $dist['Distributor']['id'] ; ?></option>
						<?php } ?>
					</select>
					<span id="submit" style="margin-left:30px;"><input type="button" value="Search" style="padding: 0 5px 3px" class="retailBut enabledBut" id="sub" onclick="findRetailers();"></span>
    				
					</div>
    			</div>
    			<div style="margin-top:10px;"><span id="err" class="error" style="display:none;">Error: Please select a distributor</span></div>
	  			
	  			<fieldset style="padding:0px;border:0px;margin:0px;margin-top:20px">
					<div class="appTitle">Retailers <?php if(isset($distributor)) echo "(" . $distributor . ")"; ?></div>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Retailers">
        			<thead>
			          <tr class="noAltRow altRow">
			          	<th style="width:20px;">Sr. No.</th>
			            <th style="width:100px;">Name</th>
			            <th style="width:40px;">Phone</th>
			            <th class="number" style="width:105px">Opening Balance <span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <th class="number" style="width:85px">Possible Sale <span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <th class="number" style="width:100px">Closing Balance <span>(<img align="absMiddle" style="margin-bottom: 3px;" src="/img/rs.gif">)</span></th>
			            <th class="number" style="width:50px;">Slab</th>
			          </tr>
			        </thead>
                    <tbody>
                    <?php if(!isset($empty) && empty($retailers)) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Retailers !!</span></td>
                    </tr>
                    
                    <?php } ?>
                    <?php $i=0; foreach($retailers as $rec){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                    ?>			            
                    <tr class="<?php echo $class; ?>"> 
			            <!-- <td><a href="/shops/showDetails/r/<?php echo $rec['Retailer']['id']; ?>/<?php echo $distributor_id; ?>"><?php echo $rec['Retailer']['shopname']; ?></a></td>-->
			            <td><?php echo $i+1; ?></td>
			            <td><?php echo $rec['Retailer']['shopname']; ?></td>
			            <td><?php echo $rec['Retailer']['mobile']; ?></td>
			            <td class="number"><?php echo $rec['Retailer']['opening_balance']; ?></td>
			            <td class="number"><?php echo sprintf('%.2f', $rec[0]['xfer']+$rec['Retailer']['opening_balance']); ?></td>
			            <td class="number"><?php echo $rec['Retailer']['balance']; ?></td>
			            <td class="number"><?php echo $rec['slabs']['name']; ?></td>
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
function findRetailers(){
	var distributor = $('shop').options[$('shop').selectedIndex].value;
	var html = $('submit').innerHTML;
	showLoader3('submit');
	
	if(distributor == 0){
		$('err').show();
		$('submit').innerHTML = html;
	}
	
	else{
		$('err').hide();
		window.location = "http://" + siteName + "/shops/retailerListing/"+distributor;
	}
}
</script>