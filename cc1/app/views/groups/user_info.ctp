<?php  //echo "<pre>"; print_r($user_data); echo "</pre>";?>

<script>
function addComment(id,mobile){
	var url = '/comments/addComment';
	var rand   = Math.random(9999);
	var pars   = "id="+id+"&text="+encodeURIComponent($('commentArea').value)+"&followupdate="+$('followupdate').value+"&email="+$('userEmail').value+"&type="+0+"&mobile="+mobile+"rand="+rand;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{ 	
						var html = transport.responseText;
						Element.insert('commentBox',{top:html});
						$('commentArea').value = "";
						$('userEmail').value = "";						
						$('followupdate').value = "";				
					}
				});
}

function openCloseSMS(){
	if($('smsArea').style.display == "none"){
		$('smsArea').show();
	}
	else {
		$('smsArea').hide();
	}
}
</script>
<table border="0" cellpadding="0" cellspacing="0" summary="List Users" width="100%" align="center">

	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
				<caption class="header">
				User Info
				</caption>
			<?php if(!empty($user_data['User']['name'])) {?>
				<tr align="left">
					<td>Name</td>
					<td><?php echo $user_data['User']['name']; ?></td>
				</tr>
			<?php }?>
			
				<tr align="left">
					<td>Mobile</td>
					<td><?php echo $user_data['User']['mobile']; ?></td>
				</tr>
				<tr align="left">
					<td>User Id</td>
					<td><?php echo $user_data['User']['id']; ?></td>
				</tr>
				<tr align="left">
					<td>Email</td>
					<td><?php echo $user_data['User']['email']; ?></td>
				</tr>
				<tr align="left">
					<td>Balance</td>
					<td><?php echo $user_data['User']['balance']; ?></td>
				</tr>
			
				<tr align="left">
					<td>Registered On</td>
					<td><?php echo $user_data['User']['created']; ?></td>
				</tr>
				<tr align="left">
					<td>No. of times logined since 2010-12-09</td>
					<td><?php echo $user_data['User']['login_count']; ?></td>
				</tr>
				<tr align="left">
					<td>Last Logined On</td>
					<td><?php echo $user_data['User']['modified']; ?></td>
				</tr>
				<tr align="left">
					<td>State</td>
					<td><?php echo $mobileDetails[0]; ?></td>
				</tr>
				<tr align="left">
					<td>Telecom Operator</td>
					<td><?php echo $mobileDetails[1]; ?></td>
				</tr>
				<tr align="left">
					<td>DND User</td>
					<td><?php if($user_data['User']['dnd_flag'] == 1) echo "Yes"; else echo "No"; ?></td>
				</tr>
				<tr align="left">
					<td>NCPR Preference</td>
					<td><?php echo $user_data['User']['ncpr_pref']; ?></td>
				</tr>
				<tr align="left">
					<td>Opt Status</td>
					<td><?php if($user_data['User']['opt_flag'] == 0) echo 'Not Opted'; else if($user_data['User']['opt_flag'] == 1) echo 'Opted'; else echo 'Unknown'; ?></td>
				</tr>
				<tr align="left">
					<td> Free SMS Sent </td>
					<td> <?php echo $user_data['User']['smsfree_count']; ?></td>
				</tr>
				<tr align="left">
					<td> SMS Forwarded </td>
					<td> <?php echo $user_data['User']['smsfwd_count']; ?></td>
				</tr>
				<tr align="left">
					<td> Follow-up date</td>
					<td> <?php echo date('j M, y',strtotime($user_data['User']['followup'])); ?></td>
				</tr>
			</table>
			
			<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left" style="margin-top:20px;">
				
				<tr><td>
				<div class="tagged ie6Fix2"> 
                        <span class="leftFloat" style="font-size:11pt"> Tagged : </span>
                        <div style="margin-left:80px;">
                        	<?php foreach($tags as $tag) { ?>
							<div class="taggLink taggLink1" style="font-size:9pt">
                              <div class="taggLinkBG">
                                <div class="taggLinkBorder">
                                  <div class="taggLinkCont"><a href="/tags/getTaggedUsers/<?php echo $tag['Tagging']['name']; ?>"><b><?php echo $tag['Tagging']['name']; ?></b></a></div>
                                </div>
                              </div>
                            </div>
                 			<?php } ?>
                        </div>
                        <div class="clearLeft"></div>                                 		              		
                    </div>
				</td></tr>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
				<caption class="header">
				<?php echo count($comments); ?> comments (<a href="/groups/getLastComments"> All comments </a>)
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
						<table>
						<tr><td><textarea class="input textarea" id="commentArea" style="height: 70px; width: 450px; line-height: 1.5em; 
						font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr;" autocomplete="off"></textarea></td></tr>
						<tr><td>Email: <input type="text" name="userEmail" id="userEmail"></td></tr>
						<tr><td>Follow up: <input type="text" name="followupdate" id="followupdate" value="" onmouseover="fnInitCalendar(this, 'followupdate','expiry=true,close=true,elapse=1')" /></td></tr>
						<tr><td><input type="image" onclick="addComment(<?php if(!empty($user_data['User']['id']))echo $user_data['User']['id']; else echo '-1'; ?>,'<?php echo $mobile; ?>');" class="otherSprite oSPos7" src="/img/spacer.gif"></td></tr>
					</table>
					
						
					</div>
					
					<div style="margin-top:10px;"><a href="javascript:void(0);" onclick="openCloseSMS();">Send SMS</a></div>
					<div id="smsArea" style="display:none">
					<?php echo $this->element('push_sms',array('mobile' => $mobile));?>
					</div>
				</td></tr>
			</table>	
		</td>
	</tr>	
</table>			
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="padding-top:50px;">
	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Products
			</caption>
			<tr align="left">
	  			<th width="14%">Name</th>
	  			<th width="8%">Status</th>
	  			<th width="8%">Trial/Product</th>
	  			<th width="10%">Start Date</th>
	  			<th width="10%">End Date</th>
	  		</tr>
	  	       
			<?php foreach($products as $product){ ?>
			<tr align="left">
			<td> <a href="/retailers/product/<?php echo $product['ProductsUser']['product_id'];?>"><?php echo $product['Product']['name'];?></a></td>
			<td> <?php if($product['ProductsUser']['active'] == '1') { echo "Active"; } else { echo "InActive"; }?></td>
			<td> <?php if($product['ProductsUser']['trial'] == '1' && $product['ProductsUser']['count'] > 1){ echo "Trial + (Product - " . ($product['ProductsUser']['count'] - $product['ProductsUser']['trial']). " )"; } else if($product['ProductsUser']['trial'] == '1') { echo "Trial"; } else { echo "Product - " . $product['ProductsUser']['count']; }?></td>
			<td> <?php echo $product['ProductsUser']['start'];?></td>
			<td> <?php echo $product['ProductsUser']['end'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="25%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Retailers
			</caption>
			<tr align="left">
	  			<th width="5%">Name</th>
	  			<th width="20%">Address</th>
	  		</tr>
	  	       
			<?php foreach($retailers as $retailer){ ?>
			<tr align="left">
				<td> <a href="/retailers/index/<?php echo $retailer['Retailer']['id'];?>"><?php echo $retailer['Retailer']['name'];?></a></td>
				<td> <?php echo $retailer['Retailer']['shopname'] . "<br/>" . $retailer['Retailer']['address'];?></td>
			</tr>
			<?php } ?>
			<?php foreach($retailers_ext as $retailer){ ?>
			<tr align="left">
				<td> <a href="/retailers/index/<?php echo $retailer['Retailer']['id'];?>"><?php echo $retailer['Retailer']['name'];?></a></td>
				<td> <?php echo $retailer['Retailer']['shopname'] . "<br/>" . $retailer['Retailer']['address'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="25%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			SMSes Sent By User
			</caption>
			<tr><td>
					<div id="commentBox">
					<?php foreach($msgs as $msg){ ?>
						<div class="appDataCont appDataContClr1">
							<div class="rightFloat">
								<span style="padding-top: 2px;" class="leftFloat"><?php echo $msg['log_smsrequest']['timestamp']; ?></span>
							</div>
							<br class="clearRight"/>
							<p class="appDataDesc"><?php echo $msg['log_smsrequest']['message_in']; ?></p>
						</div>
					<?php }?>
					</div>
				</td></tr>
			</table>
		</td>
	</tr>

</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="padding-top:50px;">
	<tr>
		<td valign="top" width="50%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Packages
			</caption>
			<tr align="left">
	  			<th width="15%">Name</th>
	  			<th width="10%">Status</th>
	  			<th width="12%">Start Date</th>
	  			<th width="13%">End Date</th>
	  		</tr>
	  	       
			<?php foreach($user_data['Package'] as $package){ ?>
			<tr align="left">
			<td> <a href="/groups/getPackInfo/<?php echo $package['id'];?>"><?php echo $package['name'];?></a></td>
			<td> <?php if($package['PackagesUser']['active'] == '1') { echo "Active"; } else { echo "InActive"; }?></td>
			<td> <?php echo $package['PackagesUser']['start'];?></td>
			<td> <?php echo $package['PackagesUser']['end'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="50%" style="padding-left:50px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			Payment Details
			</caption>
			<tr align="left">
	  			<th width="15%">Date</th>
	  			<th width="20%">Flag</th>
	  			<th width="15%">Amount</th>
	  		</tr>
	  	       
			<?php foreach($payment_details as $payment){ ?>
			<tr align="left">
				<td> <?php echo $payment['payment']['start_time'];?></td>
				<td> <?php if(empty($payment['payment']['flag'])) { echo "TRY"; } else { echo $payment['payment']['flag']; }?></td>
				<td> <?php echo $payment['payment']['amount'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>

</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="padding-top:50px;">
	<tr>
		<td valign="top" width="33%" style="padding-right:10px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			<a href="/groups/getAlertsInfo/pnr">PNR Alerts</a>
			</caption>
			<tr align="left">
	  			<th width="10%">PNR Title</th>
	  			<th width="7%">Status</th>
	  			<th width="8%">Start Date</th>
	  			<th width="8%">End Date</th>
	  		</tr>
	  	       
			<?php foreach($user_data['Pnr'] as $pnr){ ?>
			<tr align="left">
			<td><?php echo $pnr['title'];?></td>
			<td> <?php echo ucfirst(strtolower($pnr['chart_status'])); ?></td>
			<td> <?php echo Date('Y-m-d', strtotime($pnr['start']));?></td>
			<td> <?php if(!empty($pnr['end'])) echo Date('Y-m-d', strtotime($pnr['end']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="33%" style="padding-right:10px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			<a href="/groups/getAlertsInfo/stock">Stock Alerts</a>
			</caption>
			<tr align="left">
	  			<th width="10%">Stock Code (NSE/BSE)</th>
	  			<th width="7%">Status</th>
	  			<th width="8%">Start Date</th>
	  			<th width="8%">End Date</th>
	  		</tr>
	  	       
			<?php foreach($user_data['Stock'] as $stock){ ?>
			<tr align="left">
			<td><?php echo $stock['stock_code']; if($stock['news_flag'] == 1) echo " (News)";?></td>
			<td> <?php if($stock['status_flag'] == '1') { echo "Active"; } else if($stock['status_flag'] == '0' && $stock['temp_flag'] == '0') { echo "Disabled"; } else if($stock['status_flag'] == '0' && $stock['temp_flag'] == '1') {echo "Disabled - No Money";} ?></td>
			<td> <?php echo Date('Y-m-d', strtotime($stock['start']));?></td>
			<td> <?php if(!empty($stock['end'])) echo Date('Y-m-d', strtotime($stock['end']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
		<td valign="top" width="33%">
			<table border="1" cellpadding="0" cellspacing="0" width="100%" align="left">
			<caption class="header">
			<a href="/groups/getAlertsInfo/reminder">Reminders</a>
			</caption>
			<tr align="left">
	  			<th width="10%">For</th>
	  			<th width="8%">Type</th>
	  			<th width="8%">Status</th>
	  			<th width="8%">Start Date</th>
	  		</tr>
	  	       
			<?php foreach($user_data['Reminder'] as $reminder){ ?>
			<tr align="left">
			<td><?php if($user_data['User']['mobile'] == $reminder['reminder_for'])echo "Me"; else {echo "Others (" . count(explode(",",$reminder['reminder_for'])) . ")" ;} ?></td>
			<td> <?php if(!empty($reminder['day']))echo "Daily"; else if(!empty($reminder['week']))echo "Weekly"; else if(!empty($reminder['month']))echo "Monthly"; else if(!empty($reminder['year']))echo "Yearly"; else echo "One Time";?></td>
			<td> <?php if($reminder['status'] == '0') echo "Active"; else echo "In-Active"; ?></td>
			<td> <?php echo Date('Y-m-d', strtotime($reminder['created']));?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>

</table>