<?php  //echo "<pre>"; print_r($user_data); echo "</pre>";?>

<script>
function addComment(id){
	var url = '/comments/addComment';
	var rand   = Math.random(9999);
	var pars   = "id="+id+"&text="+encodeURIComponent($('commentArea').value)+"&retCode="+$('retCode').value+"&followupdate="+$('followupdate').value+"&email="+$('userEmail').value+"&type="+0+"&rand="+rand;
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
				<!--<tr align="left">
					<td>Balance</td>
					<td><?php //echo $user_data['User']['balance']; ?></td>
				</tr>-->
			
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
				<!--<tr align="left">
					<td> Free SMS Sent </td>
					<td> <?php //echo $user_data['User']['smsfree_count']; ?></td>
				</tr>
				<tr align="left">
					<td> SMS Forwarded </td>
					<td> <?php //echo $user_data['User']['smsfwd_count']; ?></td>
				</tr>-->
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
                                  <div class="taggLinkCont"><?php echo $tag['Tagging']['name']; ?></div>
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
					<table>
						<tr><td><textarea class="input textarea" id="commentArea" style="height: 70px; width: 450px; line-height: 1.5em; 
						font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr;" autocomplete="off"></textarea></td></tr>
						<tr><td>Email: <input type="text" name="userEmail" id="userEmail"></td></tr>
						<tr><td>Follow up: <input type="text" name="followupdate" id="followupdate" value="" onmouseover="fnInitCalendar(this, 'followupdate','expiry=true,close=true,elapse=1')" /></td></tr>
						<tr><td><input type="image" onclick="addComment(<?php echo $user_data['User']['id'] ?>);" class="otherSprite oSPos7" src="/img/spacer.gif"></td></tr>
					</table>	
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
			<td> <?php echo $product['Product']['name'];?></td>
			<td> <?php if($product['ProductsUser']['active'] == '1') { echo "Active"; } else { echo "InActive"; }?></td>
			<td> <?php if($product['ProductsUser']['trial'] == '1' && $product['ProductsUser']['count'] > 1){ echo "Trial + (Product - " . ($product['ProductsUser']['count'] - $product['ProductsUser']['trial']). " )"; } else if($product['ProductsUser']['trial'] == '1') { echo "Trial"; } else { echo "Product - " . $product['ProductsUser']['count']; }?></td>
			<td> <?php echo $product['ProductsUser']['start'];?></td>
			<td> <?php echo $product['ProductsUser']['end'];?></td>
			</tr>
			<?php } ?>
			</table>
		</td>
			
		<td valign="top" width="40%" style="padding-left:50px;">
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
		</td>
	</tr>

</table>
<input type="hidden" name="retCode" id="retCode" value="<?php echo $retCode; ?>" />