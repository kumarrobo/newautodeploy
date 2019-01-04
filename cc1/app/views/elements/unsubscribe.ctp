<div id="unsubscribe" class="calenderPopInner" style="width:430px;">
	<div class="field">Are you sure you want to unsubscribe the package, <b><?php echo $packageName; ?></b>?<br>
	If yes, click 'OK' to unsubscribe.
	</div>
 		
         
     <div class="field">
       	<input id="packageName" type="hidden" name="data[Package][name]" value="<?php echo $objMd5->encrypt($packageUrl,encKey);?>">
        <div id="unsubOkButt"><a href="javascript:void(0);" onclick="unsubPackage();" class="buttSprite leftFloat" style="margin-top: 5px;"><img src="/img/spacer.gif" class="butOk"></a></div>
	</div>
		
</div>		