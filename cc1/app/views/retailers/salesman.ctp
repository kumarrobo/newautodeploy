<?php  //echo "<pre>"; print_r($user_data); echo "</pre>";?>

<script>
function addComment(id){
	var url = '/comments/addComment';
	var rand   = Math.random(9999);
	var pars   = "id="+id+"&text="+encodeURIComponent($('commentArea').value)+"&type="+2+"&rand="+rand;
	
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
<table border="0" cellpadding="0" cellspacing="0" summary="Salesman Info" width="100%" align="center">

	<tr>
		<td valign="top" width="100%">
			<table border="1" cellpadding="0" cellspacing="0" width="50%" align="center">
				<caption class="header">
				Salesman Info
				</caption>
				
				<tr align="left">
					<td>Name</td>
					<td><?php echo $salesman['Salesman']['name']; ?></td>
				</tr>
			
				<tr align="left">
					<td>Mobile</td>
					<td><?php echo $salesman['Salesman']['mobile']; ?></td>
				</tr>
				
				<tr align="left">
					<td>Area</td>
					<td><?php echo $salesman['Salesman']['area'] . ", " . $salesman['Salesman']['city']; ?></td>
				</tr>
				<tr align="left">
					<td>Registered On</td>
					<td><?php echo $salesman['Salesman']['created']; ?></td>
				</tr>
				 
				
			</table>
		</td>
	</tr>
</table>
<div class="notification" style="margin-top:30px; <?php if(!$this->Session->check('Message.flash')) echo 'display:none';?>"><?php echo $this->Session->flash();?></div>
<table border="0" cellpadding="0" cellspacing="0" summary="Retailers Info" width="100%"style="padding-top:30px;" >

	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Retailers
			</caption>
			<tr align="left">
	  			<th width="20%">Name</th>
	  			<th width="30%">Address</th>
	  		</tr>
	  	       
			<?php foreach($retailers as $retailer){ ?>
			<tr align="left">
				<td> <a href="/retailers/index/<?php echo $retailer['Retailer']['id']; ?>"><?php echo $retailer['Retailer']['name'];?> </a></td>
				<td> <?php echo $retailer['Retailer']['shopname'] . "<br/>" . $retailer['Retailer']['address'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<div id="addPay" style="display:none">
				<?php echo $form->create(array("url" => array('controller' => 'retailers', 'action' => 'addSalesmanPayment', $salesman['Salesman']['id']))); ?>
			     	<fieldset class="fields">
						<div class="title3">Paid to salesman</div>
						<div class="field" style="padding-top:10px;">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat"><label for="amount"> Amount </label></div>
		                         <div class="fieldLabelSpace">
		                            <input tabindex="4" type="text" id="amount" name="data[SalesmansPayment][amount]"  />
		                         </div>                     
		                 	</div>
		            	 </div>
		            	 
		            	 <div class="field">
		                    <div class="fieldDetail">
		                         <div class="fieldLabel leftFloat"><label for="type"> Cash/Cheque </label></div>
		                         <div class="fieldLabelSpace">
		                            <input tabindex="5" type="text" id="type" name="data[SalesmansPayment][type]" />
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
	  			<th width="15%">Time</th>
	  		</tr>
	  	       
			<?php foreach($payments as $payment){ ?>
			<tr align="left">
				<td> <?php echo $payment['salesmans_payments']['amount'];?> </td>
				<td> <?php echo $payment['salesmans_payments']['type'];?> </td>
				<td> <?php echo $payment['salesmans_payments']['timestamp'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>
</table>			

<table border="0" cellpadding="0" cellspacing="0" summary="Salesman Info" width="100%" align="center" style="padding-top:50px;">

	<tr>
		
		<td valign="top" width="100%" style="padding-left:50px;">
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
						<input type="image" onclick="addComment(<?php echo $salesman['Salesman']['id']; ?>);" class="otherSprite oSPos7" src="/img/spacer.gif">
					</div>
				</td></tr>
			</table>	
		</td>
	</tr>	
</table>