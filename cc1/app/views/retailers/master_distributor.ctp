<?php  //echo "<pre>"; print_r($user_data); echo "</pre>";?>

<script>
function addComment(id){
	var url = '/comments/addComment';
	var rand   = Math.random(9999);
	var pars   = "id="+id+"&text="+encodeURIComponent($('commentArea').value)+"&type="+3+"&rand="+rand;
	
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{ 	
						var html = transport.responseText;
						Element.insert('commentBox',{top:html});
						$('commentArea').value = "";
					}
				});
}
</script>
<table border="0" cellpadding="0" cellspacing="0" summary="Master Distributor Info" width="100%" align="center">

	<tr>
		<td valign="top" width="100%">
			<table border="1" cellpadding="0" cellspacing="0" width="50%" align="center">
				<caption class="header">
				Master Distributor Info
				</caption>
			
				<tr align="left">
					<td>Name</td>
					<td><?php echo $master_distributor['MasterDistributor']['name']; ?></td>
				</tr>
			
				<tr align="left">
					<td>Mobile</td>
					<td><?php echo $master_distributor['User']['mobile']; ?></td>
				</tr>
				<tr align="left">
					<td>Balance</td>
					<td><?php echo $master_distributor['MasterDistributor']['balance']; ?></td>
				</tr>
				<tr align="left">
					<td>Company Name</td>
					<td><?php echo $master_distributor['MasterDistributor']['company']; ?></td>
				</tr>
				<tr align="left">
					<td>Company Address</td>
					<td><?php echo $master_distributor['MasterDistributor']['address']; ?></td>
				</tr>
				<tr align="left">
					<td>State</td>
					<td><?php echo $master_distributor['MasterDistributor']['state']; ?></td>
				</tr>
				<tr align="left">
					<td>Registered On</td>
					<td><?php echo $master_distributor['MasterDistributor']['created']; ?></td>
				</tr>
				
			</table>
		</td>
	</tr>
</table>
<div class="notification" style="margin-top:30px; <?php if(!$this->Session->check('Message.flash')) echo 'display:none';?>"><?php echo $this->Session->flash();?></div>
<table border="0" cellpadding="0" cellspacing="0" width="100%"style="padding-top:30px;" >

	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Products
			</caption>
			<tr align="left">
	  			<th width="25%">Name</th>
	  			<th width="12%">Total</th>
	  		</tr>
	  	       
			<?php foreach($products as $product){ ?>
			<tr align="left">
				<td> <?php echo $product['Product']['name'];?> </td>
				<td> <?php echo $product['0']['counts'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<div id="addPay" style="display:none">
				<?php echo $form->create(array("url" => array('controller' => 'retailers', 'action' => 'transferMasterDistributorBalance', $master_distributor['MasterDistributor']['id'],$master_distributor['MasterDistributor']['user_id']))); ?>
			     	<fieldset class="fields">
						<div class="title3">Transfer to Master Distributor</div>
						<div class="field" style="padding-top:10px;">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat"><label for="amount"> Amount </label></div>
		                         <div class="fieldLabelSpace">
		                            <input tabindex="4" type="text" id="amount" name="data[ShopTransaction][amount]"  />
		                         </div>                     
		                 	</div>
		            	 </div>
		            	 
		            	 <div class="field">               		
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat">&nbsp;</div>
		                         <div class="fieldLabelSpace" id="sub_butt">
		                            <?php echo $form->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'6','class' => 'otherSprite oSPos7')); ?>
		                         </div>
		                    </div>
		                 </div>
		            </fieldset>
				<?php echo $form->end(); ?>	 
			</div>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="right">
			<caption class="header">
			Payments (<a href="javascript:void(0);" onclick="$('addPay').show();">Transfer Balance</a>)
			</caption>
			
			<tr align="left">
	  			<th width="10%">Amount</th>
	  			<th width="25%">Transferred By</th>
	  			<th width="15%">Time</th>
	  		</tr>
	  	       
			<?php foreach($payments as $payment){ ?>
			<tr align="left">
				<td> <?php echo $payment['ShopTransaction']['amount'];?> </td>
				<td> <?php echo $payment['User']['name'];?></td>
				<td> <?php echo $payment['ShopTransaction']['timestamp'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>
</table>			

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="padding-top:50px;">

	<tr>
		<td valign="top" width="50%">
			<div id="addCoupons" style="display:none">
				<?php echo $form->create(array("url" => array('controller' => 'retailers', 'action' => 'assignMasterDistributorCoupons',  $master_distributor['MasterDistributor']['id']))); ?>
			     	<fieldset class="fields">
						<div class="title3">Add Serial Numbers</div>
						<div class="field" style="padding-top:10px;">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat"><label for="numbers"> Serial Numbers </label></div>
		                         <div class="fieldLabelSpace">
		                            <input tabindex="7" type="text" id="numbers" name="data[numbers]"  style="width:225px;"/>
		                            <br/><span class="hints">eg. 50441345-50441365, 50442455, 50442485</span>
		                         </div>
		                 	</div>
		            	 </div>
		            	 
		            	 <div class="field">               		
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat">&nbsp;</div>
		                         <div class="fieldLabelSpace" id="sub_butt">
		                            <?php echo $form->submit('spacer.gif', array('id' => 'sub1', 'tabindex'=>'8','class' => 'otherSprite oSPos7')); ?>
		                         </div>
		                    </div>
		                 </div>
		            </fieldset>
				<?php echo $form->end(); ?>	 
			</div>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Dry Stock Assigned (<a href="javascript:void(0)" onclick="$('addCoupons').show();">Assign Coupons</a>)
			</caption>
			<tr align="left">
	  			<th width="25%">Name</th>
	  			<th width="12%">Total</th>
	  			<th width="13%">Date</th>
	  		</tr>
	  	       
			<?php foreach($added as $product){ ?>
			<tr align="left">
				<td> <?php echo $product['products']['name'];?> </td>
				<td> <?php echo $product['0']['total'];?></td>
				<td> <?php echo $product['0']['date'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" align="right">
				<caption class="header">
				<?php echo count($comments); ?> comments
				</caption>
				<tr><td>
					<div id="commentBox">
					<?php
						foreach($comments as $comment){ 
							echo $this->element('commentElement',array('comment' => $comment)); 
						}
					?>
					</div>
					<div style="padding-top:20px">
						<textarea class="input textarea" id="commentArea" style="height: 70px; width: 450px; line-height: 1.5em; 
						font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr;" autocomplete="off"></textarea><br>
						<input type="image" onclick="addComment(<?php echo $master_distributor['MasterDistributor']['id'];; ?>);" class="otherSprite oSPos7" src="/img/spacer.gif">
					</div>
				</td></tr>
			</table>	
		</td>
	</tr>	
</table>