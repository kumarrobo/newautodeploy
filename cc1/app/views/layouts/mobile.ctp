<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
.enabledBut { background-position:0px 0px; }
a:hover.retailBut, .retailBut { background:url(/img/buttonRetailer.png); height:23px; padding:0px 5px 3px; border:0px; color:#fff; }
fieldset { border:1px;margin:0;padding:0}
.field { padding-bottom:10px; }
input, textarea { border: 1px solid #4d5e69; padding:2px }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $minify->js(array('merge'),MERGE_JS_VERSION); ?>	
<link type="text/css" rel="stylesheet" href="/css/m_style.css?<?php echo M_STYLE_CSS_VERSION;?>">
</head>
<body>
	<div id="container">
		<div id="content" class="mContainer">		
			<?php echo $content_for_layout; ?>
		</div>
	</div>
</body>
</html>