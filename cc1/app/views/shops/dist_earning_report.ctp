<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'dist_earning_report'));?>
    		<div id="innerDiv">

				<div class="filter-container">
					<form method="get" action="/shops/distEarningReport" id="dist_earning_form">
						<div>
							<label><strong>Select Product type:</strong></label>
							<select class="product-types" name="service" id="product_types">
							<option value="">--Filter by product type--</option>
							<?php
								if( count($product_types) > 0 ){
									foreach($product_types as $id => $type){
										$ifselected = '';
										if( ($selected_product_type) && ($selected_product_type == $id) ) {
											$ifselected = 'selected';
										}
										echo '<option '.$ifselected.' value="'.$type.'">'.$type.'</option>';
									}
								}
								$ifselected = '';
								if( ($selected_product_type) && ($selected_product_type == 'additional-incentives') ) {
									$ifselected = 'selected';
								}
							?>
							<option <?php echo $ifselected; ?> value="additional-incentives">Additional Incentives</option>
							</select>
						</div><br>
						<div>
							<label><strong>Select Salesman:</strong></label>
							<select class="salesmen" name="salesman" id="salesmen">
							<option value="">--Filter by salesman--</option>
							<?php
								if( count($dist_salesmen) > 0 ){
									foreach($dist_salesmen as $salesman_id => $salesman_name){
										$ifselected = '';
										if( ($selected_salesman) && ($selected_salesman == $salesman_id) ) {
											$ifselected = 'selected';
										}
										echo '<option '.$ifselected.' value="'.$salesman_name.'">'.$salesman_name.'</option>';
									}
								}
							?>
							</select>
						</div><br>
						<div>
							<label><strong>Select Retailer:</strong></label>
							<select class="retailers" name="retailer" id="retailers">
							<option value="">--Filter by retailer--</option>
							<?php
								if( count($dist_retailers) > 0 ){
									foreach($dist_retailers as $retailer_id => $retailer_name){
										$ifselected = '';
										if( ($selected_retailer) && ($selected_retailer == $retailer_id) ) {
											$ifselected = 'selected';
										}
										echo '<option '.$ifselected.' value="'.$retailer_name.'">'.$retailer_name.'</option>';
									}
								}
							?>
							</select>
						</div><br>
						<div>
							<span style="font-weight:bold;margin-right:10px;">
								Select Date Range:
							</span>
							From<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="from" value="<?php if(isset($date_from)) echo date('d-m-Y', strtotime($date_from));?>">
							- To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="to" value="<?php if(isset($date_to)) echo date('d-m-Y', strtotime($date_to));?>">

							 <!-- <span style="margin-left:30px;" id="submit"> -->
								<!-- <input type="submit" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="getReport();"> -->
								<span id="submit_span"><input id="search_btn" type="submit" value="Search" class="retailBut enabledBut" title="Search" style="cursor:pointer;padding: 0 5px 3px"></span>
							 <!-- </span> -->
						</div>
					</form>
				</div>

				<fieldset style="padding:0px;border:0px;margin:0px;">
				<?php
				if( ($validation_error) && !empty($validation_error) ){ ?>
					<div style="margin-top:10px;">
						<span class="error" >
							Error: <?php echo $validation_error; ?>
						</span>
					</div>
				<?php }
				 else {?>
				<div class="appTitle" style="margin-top:20px;">
				<?php
				if( ($selected_product_type) && (array_key_exists($selected_product_type,$product_types)) ) {
					echo $product_types[$selected_product_type];
				?>
					<span style="margin-left:10px;float:right;">Total Earning: <img class='rupeeBkt' src='/img/rs.gif'/><?php echo $total_earning; ?></span>
					<span style="margin-left:10px;float:right;">Total Sale: <img class='rupeeBkt' src='/img/rs.gif'/><?php echo $total_sale; ?></span>
				<?php
				if( $total_refund && ($total_refund > 0) ){ ?>
					<span style="float:right;">Incentive: <img class='rupeeBkt' src='/img/rs.gif'/><?php echo $total_refund; ?></span>
				<?php }
				} else if($selected_product_type == 'additional-incentives'){
					echo 'Additional Incentives';
				} ?>
				<?php if($total_incentive) { ?>
					<span style="margin-left:10px;float:right;">Total Incentive: <img class='rupeeBkt' src='/img/rs.gif'/><?php echo $total_incentive; ?></span>
				<?php } if(isset($date_from) && isset($date_to)) echo "(". date('d-m-Y', strtotime($date_from)) . " - " .  date('d-m-Y', strtotime($date_to)) . ")"; ?></div>
			<?php
				if( count($transactions) > 0 ){ ?>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">
        			<thead>
			          <tr class="noAltRow altRow">
			            <th style="width:80px;">Date</th>
			            <th style="width:80px;">Txn Id</th>
						<?php if( ($selected_product_type) && (array_key_exists($selected_product_type,$product_types)) ) { ?>
			            <th style="width:100px;">Salesman <br>Mobile</th>
			            <th style="width:100px;">Salesman <br>Name</th>
			            <th style="width:100px;">Retailer <br>Mobile</th>
			            <th style="width:100px;">Retailer <br>Name</th>
			            <th style="width:100px;">Customer <br>Mobile</th>
						<?php } ?>
			            <th style="width:50px;">Amount(<img class='rupeeBkt' src='/img/rs.gif'/>)</th>
			            <!-- <th style="width:50px;text-align:left;">Status</th> -->
						<?php if( ($selected_product_type) && (array_key_exists($selected_product_type,$product_types)) ) { ?>
			            <th style="width:50px;">Earning(<img class='rupeeBkt' src='/img/rs.gif'/>)</th>
						<?php } ?>
			            <th style="width:150px;">Description</th>
			          </tr>
			        </thead>
                    <tbody>
					 <?php
					 //if(isset($date_limit) && $date_limit == 0) { ?>
                    <!-- <tr>
                    	<td colspan="4"><span class="success">Date difference cannot be greater than 7 days !!</span></td>
                    </tr> -->
					<?php
					foreach($transactions as $index => $transaction){
						$class = '';
						if($index%2 != 0){
							$class = 'altRow';
						}

                    ?>
                      <tr class="<?php echo $class; ?>">
                            <?php //if($report == 0) { ?>
						<td style=""><?php echo $transaction['st']['date'];?></td>
			            <td style="">
							<?php echo $transaction['st']['id']; ?>
						</td>
						<?php if( ($selected_product_type) && (array_key_exists($selected_product_type,$product_types)) ) { ?>
			            <td style="">
							<?php
								echo ( isset($transaction['sal']['salesman_mobile']) && !empty($transaction['sal']['salesman_mobile']) ) ? $transaction['sal']['salesman_mobile'] : "";
							?>
						</td>
			            <td style="">
							<?php
								echo ( isset($transaction['sal']['salesman_name']) && !empty($transaction['sal']['salesman_name']) ) ? str_ireplace(' ','<br>',$transaction['sal']['salesman_name']) : "";
							?>
						</td>
			            <td style="">
							<?php
								echo ( isset($transaction['ret']['retailer_mobile']) && !empty($transaction['ret']['retailer_mobile']) ) ? $transaction['ret']['retailer_mobile'] : "";
							?>
						</td>
			            <td style="">
							<?php
								echo ( isset($transaction['ret']['retailer_name']) && !empty($transaction['ret']['retailer_name']) ) ? str_ireplace(' ','<br>',$transaction['ret']['retailer_name']) : "";
							?>
						</td>
			            <td style="">
							<?php
								echo ( isset($transaction['ret']['customer_mobile']) && !empty($transaction['ret']['customer_mobile']) ) ? $transaction['ret']['customer_mobile'] : "";
							?>
						</td>
						<?php } ?>
						<td style=""><?php echo $transaction['st']['amount']; ?></td>
						<!-- <td style="text-align:right;"><?php // echo 'Success'; ?></td> -->
						<?php if( ($selected_product_type) && (array_key_exists($selected_product_type,$product_types)) ) { ?>
						<td style="">
							<?php
								echo $transaction['st']['earning'];
							?>
						</td>
						<?php } ?>
						<td style="">
							<?php
								switch ($transaction['st']['type']) {
									case REFUND:
										echo !empty($transaction['st']['note']) ? $transaction['st']['note'] : "Incentive";
									break;
									case KIT_CHARGE:
										echo !empty($transaction['st']['note']) ? $transaction['st']['note'] : "Kit Charges";
									break;
									default:
										if( $selected_product_type && in_array($selected_product_type,array(8,9,10,12)) && !empty($transaction['st']['note']) ){
											echo $transaction['st']['note'];
										}
									break;
								}
							?>
						 </td>
			          </tr>
					  <?php } ?>
			         </tbody>
			   	</table>
			   	<?php


					$total_num = ceil($trans_count/PAGE_LIMIT);
					$min = (intval($page/5))*5 + 1;
					$max = (intval($page/5))*5 + 5;
					if($total_num < $max) $max = $total_num;

					unset($_GET['page']);
					unset($_GET['url']);
					$url = $this->here.'?'.http_build_query($_GET);

			   	?>
			   	 <div class="ie6Fix2 pagination" style="float:right;">
		           <div class="leftFloat paginationNo"><span class="<?php if($page == 1) echo 'lightText';?>"><?php if($page != 1) {?>
		           		<a href="<?php echo $url.'&page=1'; ?>" class="noAffect">
						   <?php } echo "First"; if($page != 1) echo "</a>"; ?></span> |
		           </div>
		           <div class="leftFloat"><span class="<?php if($page == 1) echo 'lightText';?>"><?php if($page != 1) {?>
		           		<a href="<?php echo $url.'&page='.($page-1); ?>" class="noAffect">
						   <?php } echo "Previous"; if($page != 1) echo "</a>"; ?></span> |
		           </div>
		           <div class="leftFloat paginationNo">
		           	<?php for($i = $min; $i <= $max; $i++) {?>
		           		<span class="<?php if($i == $page) echo 'current'; ?>">
						   <?php if($i != $page) { ?>
						   		<a href="<?php echo $url.'&page='.$i; ?>" class="lightText">
							<?php } echo $i; if($i != $page) { echo "</a>"; } ?>
						</span>
		           	<?php } ?>
		            <br class="clearLeft">
					</div>
					<div class="leftFloat paginationNo">
					 | <span class="<?php if($page == $total_num) echo 'lightText';?>">
		          		<?php if($page != $total_num){?>
		           		<a href="<?php echo $url.'&page='.($page+1); ?>" class="noAffect">
						   <?php } echo "Next"; if($page != $total_num) echo "</a>"; ?>
					</span>
		           </div> |
					<div class="leftFloat paginationNo">
					<span class="<?php if($page == $total_num) echo 'lightText';?>">
		          		<?php if($page != $total_num){?>
		           		<a href="<?php echo $url.'&page='.$total_num; ?>" class="noAffect">
						   <?php } echo "Last"; if($page != $total_num) echo "</a>"; ?>
					</span>
		           </div>
		          <div class="clearLeft"></div>
          		</div>
          		<div class="clearRight"></div>
   			</div>
			<?php } else if( ($selected_product_type) && (array_key_exists($selected_product_type,$product_types)) ) {
				echo '<span class="success">No Results Found !!</span>';
			} else if($selected_product_type == 'additional-incentives'){
				echo '<span class="success">No Results Found !!</span>';
			}
			}?>
			</fieldset>
   			<br class="clearLeft" />
 		</div>

    </div>
 </div>
<br class="clearRight" />
</div>
<script>
	document.getElementById("search_btn").onclick = function() {
		this.disabled = true;
		showLoader3('submit_span');
		document.getElementById("dist_earning_form").submit();
	}
</script>