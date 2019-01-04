
<form id="send" name="send" method="post" accept-charset="utf-8" >
<div>
<br>Following are the users subscribed for <b><?php echo $packData['Package']['name']; ?></b> <br>

<?php foreach($userData as $data) { ?>
	<span style="padding-right:10px"> <?php echo $data['User']['mobile']; ?></span>
<?php } if(count($userData) > 2) { ?> ...<?php } ?><br><br>
<?php if($time == '00:00:00') {?>
This message can be posted anytime</b><br>
<?php } else { ?>
This message should go before <b><?php echo $time; ?></b><br>
<?php } ?>
</div> <hr>
<div><span><b>Description:</b></span>
<?php echo $packData['Package']['description'];?></div>
<div><?php if(!empty($packData['Package']['frequency'])) { ?><b>Frequency:</b> <span style="padding-right:10px;"><?php echo $packData['Package']['frequency']; ?></span><?php } if(!empty($lefttogo)) {?><b>Left to go for today:</b> <span><?php echo $lefttogo; ?></span><?php } ?></div>
<hr>
<?php echo $this->element('messages_sent',array('logData' => $logData));?>
<b>Content</b>
<?php if($packData['Package']['id'] == '152'){ ?>
<span id="getCoolVideo">
	<a href="javascript:void(0);" onclick="getCoolVideo()">&nbsp;get cool video</a>
</span>
<br>
<div id="coolVideo" style="background-color:#F88017"></div>
<?php } ?>

<br>
	<?php if(!empty($table) && empty($content)) echo "Data is not there !!";else { ?>
	<table width="700">
	<tr>
		<td width="350" valign="top"> 
			<textarea onkeyup="countChars();" onkeydown="countChars();" 
			class="input textarea" id="transl" 
			name="data[<?php echo $table; ?>][content]" style="height: 250px; width: 350px; line-height: 1.5em; 
			font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr;" autocomplete="off"><?php echo strip_tags(trim($content));?></textarea>
		</td>	
		<td valign="top">
		<div id="dataUrls" style="max-height:260px;overflow:auto">
			<?php for($g=0;$g<$noOfFields;$g++){ 
				$bgcolor="#C9C299";
				if($g%2 == 1)$bgcolor="#C0C0C0";
			?>
			
			<table id="urlTable<?php echo $g; ?>" style="margin-top:2px;"  width="100%" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td colspan="2">
						Title: <input id="urlTitle<?php echo $g; ?>" type="text" style="width:85%">
					</td>
				</tr>
				<tr>
					<td style="width:20%">
						<select id="urlType<?php echo $g; ?>" onchange="typeChange('<?php echo $g; ?>')">
							<?php foreach($urlType as $uk=>$uv){ 
								echo "<option value='".$uv."'>".$uk."</option>";
							} ?>								
						</select>						
					</td>
					<td>
						<input id="urlUrl<?php echo $g; ?>" type="text" style="width:95%">
					</td>
				</tr>
			</table>
			<div style="" id="urlErr<?php echo $g; ?>"></div>
			<?php } ?>
			</div>
			<div style="margin-top:5px;">
			<div id="shortDataUrl"></div>
			<span id="createShortUrl">
			<input type="button" style="background-color:#657383;" value="Get short url" onclick="createShortUrl('<?php echo $packData['Package']['id']; ?>','');">			
			</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:void(0);" onclick="moreDataUrl(4);">add more</a>
			</div>
			
			<input id="urlDataCnt" type="hidden" value="<?php echo $g; ?>">
						
		</td>
	</tr>
	</table>
	<br>		
	<small><i>
	<span id="left"> 0&nbsp;chars </span>
	</i>
    </small>
	<?php } ?>
		
<br><br>
<input type="hidden" id="contentBase" value="<?php echo strip_tags(trim($content));?>">
<input type="hidden" name="data[table]" value="<?php echo $table; ?>">
<input type="hidden" name="data[package]" value="<?php echo $packData['Package']['id']; ?>">
<input type="hidden" name="data[category]" value="<?php echo $catId; ?>">
<?php if(isset($dataId)) {?>
<input type="hidden" name="data[dataId]" value="<?php echo $dataId; ?>">
<?php }?>
<?php if(isset($messageId)) {?>
<input type="hidden" name="data[message]" value="<?php echo $messageId; ?>">
<?php }?>
<div id="submitted"></div>
<div class="field">
	<?php echo $ajax->submit('/img/spacer.gif', array('class' => 'otherSprite oSPos7','url'=> array('controller'=>'groups', 'action'=>'submitData'), 'update' => 'data','condition' => 'dataValidate($("transl").value,306)','after' => '$("submitted").innerHTML = "Submitted successfully";')); ?>
</div>
</form>
<?php if(isset($dataId) && $dataId != '0') { ?>
<div> <input type="button" value="delete & next" onclick="nextMessage(<?php echo $dataId; ?>,<?php echo $packData['Package']['id']; ?>,<?php echo $catId; ?>,'<?php echo $table; ?>')"> </div>
<?php } ?>
<div id="feeds">
<?php echo $this->element('feed_data',array('packid' => $packData['Package']['id'],'feedData' => $feedData));?>
</div>
<script>
countChars();
</script>