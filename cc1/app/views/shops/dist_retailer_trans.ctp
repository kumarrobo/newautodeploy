<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">

<?php echo $this->element('shop_side_reports',array('side_tab' => $side_tab));?>
            		
                    <div >
                       
                        <div class="appTitle" style="margin-bottom:20px;margin-left:225px;">Retailer History <?php if(isset($date_from) && isset($date_to)) echo "(". date('d-m-Y', strtotime($date_from)) . " - " .  date('d-m-Y', strtotime($date_to)) . ")"; ?></div>
			
	  			<fieldset style="padding:0px;border:0px;margin:0px;">
                                    <div style="margin-right:10px;margin-bottom:10px;">
    			<span style="font-weight:bold;margin-right:10px;" >Retailer : </span>
                        <select style="padding: 0 5px 3px" id="ret_id">
                            <option value="0">--Select Retailer--</option>
                            <?php foreach ($retailers as $key => $retailer) {?>
                           <?php if($ret_id == $retailer['Retailer']['id']){?>
                           <?php  echo "<option  selected value=".$retailer['Retailer']['id'].">".$retailer['Retailer']['shopname']."-".$retailer['Retailer']['mobile']."</option>"; ?>
                             
                           <?php }else{ ?>
                            <option  value="<?php echo $retailer['Retailer']['id'] ;?>" ><?php echo $retailer['Retailer']['shopname']."-".$retailer['Retailer']['mobile'] ;?></option>
                            
                            <?php } } ?>
                            
                        </select>
                        <span style="font-weight:bold;margin-right:10px;" >Operator : </span>
                        <select style="padding: 0 5px 3px" id="opr_id">
                            <option value="0">--All Operator--</option>
                            <?php foreach ($products as $key => $product) {?>
                           <?php if($productId == $product['products']['id']){?>
                           <?php  echo "<option  selected value=".$product['products']['id'].">".$product['products']['name']."</option>"; ?>
                             
                           <?php }else{ ?>
                            <option  value="<?php echo $product['products']['id'] ;?>" ><?php echo $product['products']['name'] ;?></option>
                            
                            <?php } } ?>
                            
                        </select>
    			</div>
	  			<div>
    			<span style="font-weight:bold;margin-right:10px;">Select Date Range: </span>From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">
    			
    			<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findHistory();"></span>
    			</div>
    			<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:60px;"><b>Date</b></th>
			            <th style="width:70px"><b>Number</b></th>
                                    <th style="width:70px"><b>Operator</b></th>

                                    <th class="number" style="width:50px"><b>Amount </b>(<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                    <th style="width:150px;;text-align:center;"><b>Status</b></th>
                                    
                                  </tr>
			        </thead>
                    <tbody>
                    <?php if(empty($transactions) || count($transactions) <= 0 ) { ?>
                    <tr>
                    	<td colspan="4"><span class="success">No Results Found !!</span></td>
                    </tr>
                    
                    <?php } else { ?>
                    <?php $i=0; foreach($transactions as $transaction){ 
                    	if($i%2 == 0)$class = '';
                    	else $class = 'altRow';
                        $i++;
                       // print_r($transaction);
                    ?>
                      <tr class="<?php echo $class; ?>">
			            <td><?php echo date('d-m-Y H:i:s', strtotime($transaction['0']['timestamp'])); ?></td>
                                    <td><?php echo !empty($transaction['vendors_activations']['param']) ? $transaction['vendors_activations']['param'] : $transaction['vendors_activations']['mobile']; ?></td>
                                    <td><?php echo $transaction['products']['name'] ; ?></td>

                                    <td class="number"><?php echo $transaction['vendors_activations']['amount'] ; ?></td>
                                    <td align="center">
                                    <?php
                                        if( $transaction['vendors_activations']['status'] == 2 || $transaction['vendors_activations']['status'] == 3 ){
                                           echo "Failure (".$transaction['vendors_activations']['cause'].")" ; 
                                        }else{
                                           echo "Success" ;  
                                        }
                                    ?>
                                    </td>
                                   
    			    <?php }
    			    $i++; } ?> 					    			      
			         </tbody>	         
			   	</table>
			<?php //if(!empty($transactions)) { 
                            $trans_count = count($transactions);$total_num =ceil(floatval($total_count)/intval(PAGE_LIMIT));
                            $min = (intval($page/5))*5 + 1;
                            $max = (intval($page/5))*5 + 5;
                            if($total_num < $max) $max = $total_num;
                            $pass0 = isset ($this->params['pass'][0]) ? $this->params['pass'][0] : 0;
                            $pass1 = isset ($this->params['pass'][1]) ? $this->params['pass'][1] : 0;
                            $pass2 = isset ($this->params['pass'][2]) ? $this->params['pass'][2] : 0;
                            $pass3 = isset ($this->params['pass'][3]) ? $this->params['pass'][3] : 0;
                            //@TODO change the order of params
                            $url = "/" . $this->params['controller'] . "/" . $this->params['action'] . "/" .$pass0. "/" .$pass1. "/" .$pass2;//. "/" .$pass3
                        ?>   	
                        <div class="ie6Fix2 pagination" style="float:right;"> 
		           <div class="leftFloat">
                               <span class="<?php if($page <= $min) echo 'lightText';?>">
                                    <?php if($page != $min) {?> 
		           		<a href="<?php echo $url.'/'.$min; ?>" class="noAffect">&lt;&lt; Previous </a>
                                    <?php } else if($min==1){ ?>
                                        &lt;&lt; Previous
                                    
                                     <?php } else { ?>
                                        <a href="<?php echo $url.'/'. ( ($min-5) > 0 ? ($min-5) : $min ); ?>" class="noAffect">&lt;&lt; Previous </a>
                                     <?php } ?>
                               </span>
		           </div>
		           <div class="leftFloat paginationNo">
		           	<?php  for($i = $min; $i <= $max; $i++) {?>
		           		<span class="<?php if($i == $page) echo 'current'; ?>"><?php if($i != $page) { ?> <a href="<?php echo $url.'/'.$i ;?>" class="lightText"> <?php } echo $i; if($i != $page) { echo "</a>"; } ?></span>
		           	<?php } ?>
		            <br class="clearLeft">
		          </div>
		          <div class="leftFloat">
                              <span class="<?php if($page == $max) echo 'lightText';?>">
		          <?php if($page != $max){
                                    echo "<a href=".$url.'/'.$max." class='noAffect'>Next &gt;&gt;</a>";
                                }else{
                                    echo "Next &gt;&gt";
                                }
                           ?>
                                     
                              </span>
		           </div> 
		          <div class="clearLeft"></div>
          		</div>
			</fieldset>
                        
                        
                      
                        
                        
   			</div>
            </div></div></div>
<script>
function findHistory(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
        var ret_id = $('ret_id').value;
        var opr_id = $('opr_id').value;
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
                //$('sub').setAttribute('href', "/shops/partnerTrans/"+date_from+"-"+date_to);
                window.location.href = "/shops/allRetailerTrans/"+ret_id+"/"+opr_id+"/"+date_from+"-"+date_to+"/"+<?php echo 1 ;?>;
                
	}
}
</script>