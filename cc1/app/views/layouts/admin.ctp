<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<LINK REL="SHORTCUT ICON" HREF="/img/favicon.ico?322">
<title>Active Stores Panel</title>

<link type="text/css" rel="stylesheet" href="/css/style.css?<?php echo STYLE_CSS_VERSION;?>">
<!--[if gt IE 6]>
	<link type="text/css" rel="stylesheet" href="/css/style_ie.css?<?php echo STYLE_CSS_IE_VERSION;?>">   
<![endif]-->
<!--[if lt IE 7]>
	<link type="text/css" rel="stylesheet" href="/css/style_ie6.css?<?php echo STYLE_CSS_IE_VERSION;?>">   
<![endif]-->
   
</head>
<body>

<style type="text/css">
.taggLinkBG1 {background-color: #FF8800 !important;}
</style>
<script src="/js/merge.js?<?php echo SCRIPT_APP_JS_VERSION; ?>" type="text/javascript"></script>
<script src="/js/script_app.js?<?php echo SCRIPT_APP_JS_VERSION; ?>" type="text/javascript"></script>
    <?php flush(); ?>
		<script>
			var EACH_MESSAGE_COST = <?php echo EACH_MESSAGE_COST; ?>;
			var DEFAULT_MESSAGE_LENGTH = <?php echo DEFAULT_MESSAGE_LENGTH; ?>;
			var APP_REM_MSG_FIXED = <?php echo APP_REM_MSG_FIXED; ?>;
			var ADSPACE = <?php echo ADSPACE; ?>;
			var APP_REM_MSG_LMT = <?php echo APP_REM_MSG_LMT; ?>;
			var DND_FLAG = <?php echo DND_FLAG; ?>;
		</script>
		<div class="headerIndex">
            <div class="headerMainCont">
                <div class="headerSpace">
                	
	                <div class="logo" style="float:left;">
	                	<?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'users','action'=>'view'))); ?>
	                </div>
                    <div id="rightHeaderSpace" style="position:relative">                    
                     <div class="headerLinks1">
						 <div class="globalLinks strng" style="float:right;">
						  <ul>						    
						    <li><a href='/users/view'>Dashboard</a></li>							
						    <li class="lastElement" style="padding-right:0px !important;margin-right:0px !important;"> <?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?></li>
						  </ul>
						  <br class="clearLeft" />
						</div>
	             	</div>
	             	</div>
	                <div class="clearBoth">&nbsp;</div>
            	</div>
    		</div>
    	</div>
    	
		<div id="container" class="mainCont">
		<div id="content" class="container">
				
				<?php echo $content_for_layout; ?>
	
			</div>
				    			
		<div id="footer" class="footer">
   		 	All Rights Reserved © <?php echo date('Y'); ?> Mindsarray Technologies Pvt Ltd
    	</div>
		
</div>
	<?php echo $this->element('sql_dump'); ?>
	
</body>
</html>