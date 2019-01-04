<!--  Subscription -->
<div>
	<div>
		<form name="subscriptions" id="subscriptions" method="POST" action="/networks/refine/sub">
		<span><strong>Select Date Range:</strong> From </span>
		<span> <input type="text" name="fromDate" id="fromDate" value="<?php echo $frmDate; ?>" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,close=true')" /> </span>
		<span> - To </span>
		<span> <input type="text" name="toDate" id="toDate" value="<?php echo $toDate; ?>" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,close=true')" /> </span>
		<span style="display:inline-block;padding-left:30px;"> Product: </span>
		<span>
			<select name="product" id="product">
				<option value="0" <?php if($prodId == '0') echo 'selected'; ?>>All</option>
				<?php foreach($prdData as $pd){ ?>
				<option value="<?php echo $pd['partners_products']['product_id']; ?>" <?php if($prodId == $pd['partners_products']['product_id']) echo 'selected'; ?>><?php echo $pd['products']['name']; ?></option>
				<?php } ?>
			</select>
		</span>
		<input type="submit" value="Search">
		</form>
	</div>
	<?php if(count($prdSub) > 0){ ?>
	<div style="padding:25px 0px;">
	Total sell: <?php echo $totSale; ?> Rs<br/>
	Subscription(s): <?php echo $totSubscription; ?>
	</div>
	<div class="box2" >
		<table class="ListTable" cellSpacing="0" cellPadding="0">
			<!-- <caption class="header"></caption>  -->
			<thead>
				<tr>
					<th style="width:100px">Mobile No</th>
					<th style="width:250px">Current Subscription</th>
				</tr>												
			</thead>
			<tbody>
				
				<?php $k=1;foreach($prdSub as $ps){ if($k%2 == 0)$class='';else $class='altRow'; ?>
				<tr class="<?php echo $class; ?>">
					<td><a onclick="userSwitch(<?php echo $ps['users']['id']; ?>)" href="javascript:void(0)"><?php echo $objGeneral->maskNumber($ps['users']['mobile']); ?></a></td>
					<td><?php echo $ps['products']['name']; ?></td>
				</tr>
				<?php $k++; } ?>
			</tbody>
		</table>
	</div>
	<div style="padding-bottom: 10px; padding-top: 0px; float: right;" class="ie6Fix2 pagination"> <!-- rightFloat -->
		<ul style="margin: 0px; padding: 0px;">
			<?php echo $paginationStr; ?>
			<!--<li>
				<span class="lightText"> &lt;&lt; Previous </span>
			</li>
			<li class="paginationNo">
				<span class="current">1</span>
		 		<span> <a onclick="getPage(2);" href="javascript:void(0);" class="">2</a> </span>
		 		<span> <a onclick="getPage(3);" href="javascript:void(0);" class="">3</a> </span>
			</li>
			<li class="lastElement">
				<span> <a onclick="getPage(2);" href="javascript:void(0);" class="noAffect">Next &gt;&gt;</a> </span>
			</li>-->
		</ul>
		<br class="clearLeft">			          
	</div>
	<?php }else{ echo "<br/>No result found"; } ?>
</div>					