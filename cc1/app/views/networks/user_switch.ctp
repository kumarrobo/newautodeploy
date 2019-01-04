<div style="text-align:left" >
<div class="calenderPopInner" id="subscribe">
  <div class="field">
  	<div class="strng">Switching history</div>
  	<div style="padding:10px 0 15px;">Moblie No. - <?php echo $objGeneral->maskNumber($mobile); ?></div>
  	<div class="box2" >
  		<?php foreach($usersUniqueProd as $uup){ ?>
		<table class="ListTable" cellSpacing="0" cellPadding="0" width="100%">			
			<thead>
				<tr>
					<td colspan="2" style=""><?php echo $uup['products']['name']; ?></td>															
				</tr>
				<tr>
					<th style="width:80px">Date</th>
					<th style="width:80px">Subscription</th>															
				</tr>												
			</thead>
			<tbody>
				<?php $k=1; foreach($uup['switches'] as $s){ if($k%2 == 0)$class='';else $class='altRow'; ?>
				<tr class="<?php echo $class; ?>">
					<td><?php echo date('jS M, Y',strtotime($s['products_users_switch']['timestamp'])); ?></td>
					<td><?php echo $s['products']['name']; ?></td>															
				</tr>
				<?php $k++; } ?>				
			</tbody>
		</table>
		<?php } ?>
	</div>
  </div>								          
</div>
</div>