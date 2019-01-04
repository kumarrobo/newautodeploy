<div class="leftFloat dashboardPack">
	<div class="catList">
		<ul id="innerul">
			<li>
				<a href="#" class="hList">Menu</a>
				<div class="sublist">
	          		<ul>
	          		    <li name="innerli">
	          				<a href="/networks/subscriptions/<?php echo $frmDate; ?>/<?php echo $toDate; ?>/<?php echo $prodId; ?>">Subscription</a>
	          			</li>
	          			<?php if($switch == 1){ ?>
	          		    <li name="innerli">
	          				<a href="/networks/switchings/<?php echo $frmDate; ?>/<?php echo $toDate; ?>/<?php echo $prodId; ?>">Switches</a>
	          			</li>
	          			<?php } ?>	          		    
	          		</ul>
	          	</div>
			</li>
		</ul>
	</div>
</div>