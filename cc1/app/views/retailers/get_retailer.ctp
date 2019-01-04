<?php  //echo "<pre>"; print_r($user_data); echo "</pre>";?>

<script>
function addComment(id){
	var url = '/comments/addComment';
	var rand   = Math.random(9999);
	var pars   = "id="+id+"&text="+encodeURIComponent($('commentArea').value)+"&type="+1+"&rand="+rand;
	
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
<table border="0" cellpadding="0" cellspacing="0" summary="Retailer Info" width="100%" align="center">

	<tr>
		<td valign="top" width="100%">
			<table border="1" cellpadding="0" cellspacing="0" width="50%" align="center">
				<caption class="header">
				Retailer Info
				</caption>
			
				<tr align="left">
					<td>Name</td>
					<td><?php echo $retailer['Retailer']['name']; ?></td>
				</tr>
			
				<tr align="left">
					<td>Mobile</td>
					<td><?php echo $retailer['Retailer']['mobile']; ?></td>
				</tr>
				<tr align="left">
					<td>Address</td>
					<td><?php echo $retailer['Retailer']['shopname'] . "<br/>" .  $retailer['Retailer']['address']; ?></td>
				</tr>
				<tr align="left">
					<td>Area</td>
					<td><?php echo $retailer['Retailer']['city'] . ", " . $retailer['Retailer']['state']; ?></td>
				</tr>
				<tr align="left">
					<td>Registered On</td>
					<td><?php echo $retailer['Retailer']['created']; ?></td>
				</tr>
				 
				<?php if(!empty($retailer['Salesman']['name'])){ ?>
				<tr align="left">
					<td>Salesman</td>
					<td><a href="/retailers/salesman/<?php echo $retailer['Salesman']['id']; ?>"><?php echo $retailer['Salesman']['name'] . " - " . $retailer['Salesman']['area'];  ?></a></td>
				</tr>
				<?php } ?>
				
			</table>
		</td>
	</tr>
</table>
<table border="1" cellpadding="0" cellspacing="0" summary="Retailer Info" width="100%" align="center" style="margin-top:20px;">
<caption class="header">
	His Users
</caption>
<tr><td>
<div>
<?php foreach($users as $user) {?>
<a href="/groups/getUserInfo/<?php echo $user['users']['mobile'];?>"><span style="margin-right:5px;"><?php echo $user['users']['mobile'];?>(<?php echo $user['0']['total'];?>)</span></a>
<?php } ?>
</div>
</td></tr>
</table>


<div class="notification" style="margin-top:30px; <?php if(!$this->Session->check('Message.flash')) echo 'display:none';?>"><?php echo $this->Session->flash();?></div>
<table border="0" cellpadding="0" cellspacing="0" summary="Retailer Info" width="100%"style="padding-top:30px;" >

	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Products
			</caption>
			<tr align="left">
	  			<th width="25%">Name</th>
	  			<th width="12%">Total</th>
	  			<th width="13%">Sold</th>
	  		</tr>
	  	       
			<?php foreach($products as $product){ ?>
			<tr align="left">
				<td> <?php echo $product['products']['name'];?> </td>
				<td> <?php echo $product['0']['total'];?></td>
				<td> <?php echo $product['0']['sold'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<div id="addPay" style="display:none">
				<?php echo $form->create(array("url" => array('controller' => 'retailers', 'action' => 'addRetailerPayment', $retailer['Retailer']['id']))); ?>
			     	<fieldset class="fields">
						<div class="title3">Paid by retailer</div>
						<div class="field" style="padding-top:10px;">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat"><label for="amount"> Amount </label></div>
		                         <div class="fieldLabelSpace">
		                            <input tabindex="4" type="text" id="amount" name="data[RetailersPayment][amount]"  />
		                         </div>                     
		                 	</div>
		            	 </div>
		            	 
		            	 <div class="field">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat"><label for="type"> Cash/Cheque </label></div>
		                         <div class="fieldLabelSpace">
		                            <input tabindex="5" type="text" id="type" name="data[RetailersPayment][type]" />
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
			Payments (<a href="javascript:void(0);" onclick="$('addPay').show();">Add Payment</a>)
			</caption>
			
			<tr align="left">
	  			<th width="10%">Amount</th>
	  			<th width="10%">Type of Payment</th>
	  			<th width="25%">Collected By</th>
	  			<th width="15%">Time</th>
	  		</tr>
	  	       
			<?php foreach($payments as $payment){ ?>
			<tr align="left">
				<td> <?php echo $payment['retailers_payments']['amount'];?> </td>
				<td> <?php echo $payment['retailers_payments']['type'];?> </td>
				<td> <?php echo $payment['users']['name'];?></td>
				<td> <?php echo $payment['retailers_payments']['timestamp'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>
</table>			

<table border="0" cellpadding="0" cellspacing="0" summary="Retailer Info" width="100%" align="center" style="padding-top:50px;">

	<tr>
		<td valign="top" width="50%">
			<div id="addCoupons" style="display:none">
				<?php echo $form->create(array("url" => array('controller' => 'retailers', 'action' => 'addRetailerCoupons',  $retailer['Retailer']['id']))); ?>
			     	<fieldset class="fields">
						<div class="title3">Add Coupons</div>
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
			Coupons Added (<a href="javascript:void(0)" onclick="$('addCoupons').show();">Add Coupons</a>)
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
				<?php echo count($comments); ?> comments (<a href="/retailers/getLastComments">All Retailer Comments</a>)
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
						<input type="image" onclick="addComment(<?php echo $retailer['Retailer']['id']; ?>);" class="otherSprite oSPos7" src="/img/spacer.gif">
					</div>
				</td></tr>
			</table>	
		</td>
	</tr>	
</table>