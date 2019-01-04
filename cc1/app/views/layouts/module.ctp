
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
<LINK REL="SHORTCUT ICON" HREF="/img/pay1_favic.png"/>
<title>Pay1 - Distributor Portal | Cash to Digital Network</title>
<meta name="description" content="India's fastest growing recharge channel network. Fast and reliable recharge technology platform"></meta>

<?php echo $minify->css(array('retailer'),RETAIL_STYLE_CSS_VERSION); ?>
<!--[if gt IE 6]>
	<?php echo $minify->css(array('style_ie'),STYLE_CSS_IE_VERSION); ?>
<![endif]-->
<!--[if lt IE 7]>
	<?php echo $minify->css(array('style_ie6'),STYLE_CSS_IE_VERSION); ?>
<![endif]-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script> 
<script src="/boot/js/jquery-2.0.3.min.js"></script>

<script>
	 $.noConflict();
</script>
	
</head>
<body>

<style type="text/css">
.taggLinkBG1 {background-color: #FF8800 !important;}
.success {
background: #FFCC00;
padding: 2px 5px;
}
.error{
font-size: 1em;
background: red;
padding: 2px 5px;
color: white;
}
.btn-main-download {
    background: none repeat scroll 0 0 #ed1c24;
    border: 1px solid #fff;
    border-radius: 0.25em;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    padding: 8px 12px;
    position: relative;
    text-align: center;
    text-decoration: none;
    transition: all 0.25s ease-in-out 0s;
	color:white; text-decoration: none;
}

.btn-main-download a{
 	color: #fff;
}

.floating-box {
    display: inline-table;
    width: 125px;
    height: 35px;
    margin: 10px;
    box-shadow: 0px 0px 2px grey;
	text-align: center;
	padding: 8px;
	
}
.after-box {
  clear: left;
}
</style>
<?php echo $minify->js(array('merge'),MERGE_JS_VERSION); ?>
<?php echo $minify->js(array('merge1'),MERGE_1_JS_VERSION); ?>
<?php echo $minify->js(array('script_app'),SCRIPT_APP_JS_VERSION); ?>
    <?php flush(); ?>
		<div class="headerIndex">
            <div class="headerMainCont">
                <div class="headerSpace">                	
	                <div class="logo" style="float:left;position:relative;">
	                	<?php if(isset($_SESSION['Auth']['User']['group_id']) && $_SESSION['Auth']['User']['group_id'] != ADMIN) echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'shops','action'=>'view'))); 
	                	else echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'shops','action'=>'view')));
	                	?>
	                	
	                	<span class="slogan fntSz17 strng positionSlogan" style=""><!-- "Bring mobile closer to life" -->&nbsp;</span>
	                </div>
	                <?php if($this->params['controller'] == 'panels' || ($this->params['controller'] == 'cc' && $this->params['action'] == 'panel')) { ?>
	    					<div style="display:block;position:absolute;left:20%;top:30px; width:800px">
    							<marquee id="notice" style="font-size:20px;font-weight:bold;color:red;" scrollamount="3" behavior="alternate" direction="left"></marquee>
                                                        <marquee id="failure" scrollamount="4" style="font-size:16px;font-weight:bold;color:red;" behavior="scroll" direction="left"></marquee>
                                                </div>   
                                                           
	    					<script>
		    					//setInterval(checkForCalls,10000);
		    					
								function checkForCalls(){
									var params = {};
									var url = '/cc/checkPendingCalls';
									var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
									onSuccess:function(transport)
											{		
												var response = transport.responseText;
                                                var obj = JSON.parse(response);
                                                
												if($('notice')){
                                                                                                    $('notice').innerHTML = "";
                                                                                                    $('failure').innerHTML = "";
													if(response != 0){
														if(obj.callDropped > 0 || obj.callDroppedDist > 0){
                                                                                                                    if(obj.callDropped > 0){
                                                                                                                        $('notice').innerHTML = obj.callDropped + " retailer calls pending  <br>" ;   
                                                                                                                    }
                                                                                                                    if(obj.callDroppedDist > 0){
                                                                                                                        $('notice').innerHTML = $('notice').innerHTML + obj.callDroppedDist + " distributor calls pending  <br>" ;   
                                                                                                                    }
                                                                                                                    
                                                                                                                }                                                        
                                                                                                                $('failure').innerHTML = obj.failureMsg;                                                                                                               
													}
												}
											},
									onFailure:function()
											{		
												alert('Something went wrong...');
											}
									});
								}
								checkForCalls();
							</script>
	               	<?php } ?>
                    <div id="rightHeaderSpace" style="position:relative">
					  <?php  if(!isset($_SESSION['Auth']['User']['group_id'])){ ?>
						<a  href="http://pay1.in/partners?ref=<?php echo base64_encode("panel.pay1.in") ?>" target="_blank"><input type="button" value="CONTACT US" style="float:right;font-size: -13px;padding: 10px;" class="btn-main-download"></a>
							<a  href="http://pay1.in" target="_blank"><input class="btn-main-download"type="button" value="HOME" style="float:right;font-size: -13px;padding: 10px;"></a>
							<?php } ?>
                     	<?php  if(isset($_SESSION['Auth']['User']['group_id']))echo $this->element('shop_header'); ?>
	             	</div>
	                <div class="clearBoth">&nbsp;</div>	                
            	</div>
    		</div> 
    	</div>
	<hr/>
    		
    	
		<div id="container" class="mainCont">
			
		<?php echo $this->element('popup_element');?>
		<div id="login_user" style="display:none"> <?php echo $this->element('login_sessionOut');?> </div>
		<div id="content" class="container">

   
		
   <?php foreach ($modulelist as $key => $val): ?>
        
		<div class="floating-box">
				<a href= "/<?php echo $val['action']; ?>"><?php echo $key; ?>
				</a>
			</div>
            
         
			<?php endforeach; ?>
		
			 	
			 		<br/>
				
			</div>
		<?php echo $content_for_layout; ?>	
		<div id="footer" class="footer">
         A MindsArray Technologies Pvt. Ltd. Product. All Rights Reserved © <?php echo date('Y'); ?> Pay1&trade;
    	</div>
		<div class="row" style="width:100%;border:0px solid;padding-top: 30px;float: left;">
        <div style="width:100%;border:0px solid;float: left;"> <a href="https://goo.gl/3QMUqf" target="_blank"><img src="/img/panel-728x90.gif" class="img-responsive" style="border:1px solid;"></img></a></div>
				</div>
		
</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
<script>
function showLoader3(id){
	$(id).innerHTML = '<span id="loader2" class="loader2" style="display:inline-block; width:50px">&nbsp;</span>';
}

</script>
</html>