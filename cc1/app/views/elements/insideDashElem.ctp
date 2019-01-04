<?php foreach($logs as $log) {?>
	<li class="liDash"><a href="javascript:void(0);"><?php echo urldecode($log['Log']['content']);?></a></li>
<?php } ?>

<script>

	$$('ul#dashMsgs'+<?php echo $num;?>+' li.liDash').each(function(e) { e.observe('click', function(event) {
	 	var dims = Element.positionedOffset(e);
		$('dashClick').setStyle({'left':dims.left+'px','top':dims.top+'px'});
		$('dashClick').innerHTML = '<div style="margin-right:10px;"><div class="rightFloat popClose1"><a onclick="closePopUp1(\'dashClick\');" href="javascript:void(0);">x</a></div>' + e.childElements()[0].innerHTML + '</div>';
		Effect.Grow('dashClick',{'direction':'top-left'});
		//$('dashClick').show();
    	});
	});

</script>