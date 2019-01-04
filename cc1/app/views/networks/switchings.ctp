<!-- Switches -->
<div>
	<div>
		<form name="subscriptions" id="subscriptions" method="POST" action="/networks/refine/swi">
		<span><strong>Select Date Range:</strong> From </span>
		<span> <input type="text" name="fromDate" id="fromDate" value="<?php echo $frmDate; ?>" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,close=true')" /> </span>
		<span> - To </span>
		<span> <input type="text" name="toDate" id="toDate" value="<?php echo $toDate; ?>" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,close=true')" /> </span>
		<span style="display:inline-block;padding-left:30px;"> Product: </span>
		<span>
			<select name="product" id="product">
				<?php foreach($prdData as $pd){ ?>
				<option value="<?php echo $pd['partners_products']['product_id']; ?>" <?php if($prodId == $pd['partners_products']['product_id']) echo 'selected'; ?>><?php echo $pd['products']['name']; ?></option>
				<?php } ?>
			</select>
		</span>
		<input type="submit" value="Search">
		</form>
	</div>
	<br/>
	<?php if($count > 0){ ?>
	<div class="box2" >
		<table cellSpacing="0" cellPadding="0">
			<tr>
				<?php foreach($subProd as $sp){ ?>
				<td valign="top">
					<table class="ListTable" cellSpacing="0" cellPadding="0">
						<thead>
							<tr>
								<th style="width:80px;height:100px;"><?php echo $sp['products']['name']; ?></th>
							</tr>												
						</thead>
						<tbody>
							<?php $k=0; for($k=0;$k<$count;$k++) { if($k%2 == 0)$class='altRow';else $class=''; ?>
								<tr class="<?php echo $class; ?>">
									<td><a onclick="userSwitch(<?php echo $sp['users'][$k]['users']['id']; ?>)" href="javascript:void(0)"><?php  if(trim($sp['users'][$k]['users']['mobile']) == '' ){echo '&nbsp'; }else{ echo $objGeneral->maskNumber($sp['users'][$k]['users']['mobile']);} ?></a></td>
								</tr> 
							<?php  } ?>							
						</tbody>
					</table>
				</td>				
				<?php } ?>
			</tr>
		</table>	
	</div>
	<div style="padding-bottom: 10px; padding-top: 0px; float: right;" class="ie6Fix2 pagination"> <!-- rightFloat -->
		<ul style="margin: 0px; padding: 0px;">
			<?php echo $paginationStr; ?>			
		</ul>
		<br class="clearLeft">			          
	</div>
	<?php }else{ echo "<br/>No result found"; } ?>
</div>