
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
</style>
<?php echo $minify->js(array('merge'),MERGE_JS_VERSION); ?>
<?php echo $minify->js(array('merge1'),MERGE_1_JS_VERSION); ?>
<?php echo $minify->js(array('script_app'),SCRIPT_APP_JS_VERSION); ?>
    <?php flush(); ?>
		<div class="headerIndex">
            <div class="headerMainCont">
                <div class="headerSpace">                	
	                <div class="logo" style="float:left;position:relative;">
	                	<?php if(isset($_SESSION['Auth']['User']['group_id']) && $_SESSION['Auth']['User']['group_id'] != ADMIN) echo $html->image("pay1_logo.svg", array("url" => array('controller'=>'shops','action'=>'view'), "width" => "185", "height" => "76")); 
	                	else echo $html->image("pay1_logo.svg", array("url" => array('controller'=>'shops','action'=>'view'), "width" => "185", "height" => "76"));
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
    		
    	<?php if(isset($_SESSION['Auth'])) { ?>
        <marquee style="margin-top:10px;"><span style="color:blue;">Login to Pay1 Smartbuy with your Merchant / Distributor id and get access to exclusive deals & discounts on select mobiles & accessories. Visit </span> <span style="color:red; font-weight: bold"><a href="https://shop1.in" target="blank">https://shop1.in</a></span></marquee>
                <?php } ?>
		<div id="container" class="mainCont">
			
		<?php echo $this->element('popup_element');?>
		<div id="login_user" style="display:none"> <?php echo $this->element('login_sessionOut');?> </div>
		<div id="content" class="container">
		<?php if($this->params['controller'] == 'panels' || ($this->params['controller'] == 'recharges' && $this->params['action'] == 'ossTest') || ($this->params['controller'] == 'cc' && $this->params['action'] == 'panel' && $this->params['action'] == 'shops')) { ?>
	                <table class="table table-bordered" border='0' cellpadding="2" cellspacing="5">
			 			<tr BGCOLOR="#DBEBD1">
			 			<td><a href="/panels/search" style="width:100%;display:inline-block">Search</a></td>
			 			<td BGCOLOR="#DBEB23"><a href="/cc/panel">Calls Dropped</a></td>
			 			<td BGCOLOR="#DBEB23"><a href="/cc/panel/Distributor">Distributor Calls</a></td>
			 			
			 			<td BGCOLOR="#DBEB23"><a href="/panels/tranReversal">Reversal Complaints</a></td>
			 			<td BGCOLOR="#DBEB23"><a href="/panels/inProcessTransactions">Transactions in process</a></td>
<!-- 			 			<td><a href="/panels/retInfo">Retailer info</a></td> -->
<!-- 			 			<td><a href="/panels/userInfo">Customer Info</a></td>  -->
<!-- 			 			<td><a href="/panels/transaction">Transactions</a></td> -->
			 			<td><a href="/panels/tranRange">Transactions from-to</a></td>
			 			<td><a href="/panels/newLead">New Leads</a></td>
			 			<td><a href="/panels/ccReport">Customer Care Report</a></td>
                             
			 			<td BGCOLOR="#DBEB23"><a href="/monitor/smsIncomingMonitoring">Monitor</a></td>
			 			</tr>
			 			<tr BGCOLOR="#DBEBD1">
			 			<td><a href="/panels/retMsg">Notification</a></td>
			 			<td><a href="/panels/prodVendor">Provider switching</a></td>
			 			<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR): ?>
			 			<td><a href="/panels/vendorsCommissions">Vendor Product Mapping</a></td>
			 			<?php endif ?>
			 			<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['id'] == 1){ ?>
			 			<td><a href="/panels/salesmanReport">Salesman Report</a></td>
			 			<td><a href="/panels/retailerSale">Sale Report</a></td>
			 			<td><a href="/panels/retColl">All Retailers</a></td>
                        <td><a href="/panels/retailers">Retailer Address Panel</a></td>
			 			
			 			
			 			<?php } ?>
			 			<td ><a href="/panels/reports">Reports</a></td>
			 			<td class="" style="background-color:#DBEBD1"><a href="/panels/manualReversalReport">ManualReversal Report</a></td>
						<td class="" style="background-color:#DBEBD1"><a href="/panels/pullbackReport">Pullback Report</a></td>
                             
                                                
                                                
			 			</tr>
                        <tr BGCOLOR="#DBEBD1">
                            <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['id'] == 1){ ?>
                            <td><a href="/panels/reconsile">Modem Reconciliation Panel</a></td>
                            <?php } ?>
                            <td><a href="/panels/leads">Online leads</a></td>
                            <!--<td><a href="/recharges/simPanel">SIM Panel</a></td>-->
                             <td><a href="/sims">New SIM Panel</a></td>
                             <!--<td><a href="/recharges/simOverview">SIM Overview</a></td>-->
                             <?php if(($_SESSION['Auth']['User']['group_id'] == ADMIN) || ($_SESSION['Auth']['User']['id']) == 1){ ?>
                             <td><a href="/panels/complainReport">Complain Report</a></td>
							 <!--<td class="" style="background-color:#DBEBD1"><a href="/panels/module">Acl Module</a></td>-->
							 <?php } ?>
                        </tr>
			 		</table>
			 		<?php } ?>
			 		
				<?php echo $content_for_layout; ?>	
			</div>				    			
		<div id="footer" class="footer">
   		 	<!-- <span class="rightFloat"><a href="http://www.mindsarray.com" target="_blank">About us</a> | <a href="http://blog.smstadka.com" target="_blank">Blog</a> | <a href="/users/dnd">Do Not Disturb Registry</a> | <a href="http://blog.smstadka.com/contact-us" target="_blank" alt="Contact Us opens in new window">Contact Us</a> | <a href="http://blog.smstadka.com/privacy-policy" target="_blank">Privacy Policy</a> | <a href="http://blog.smstadka.com/terms-and-condition" target="_blank" alt="Terms of Services">Terms of Service</a> | <a href="http://blog.smstadka.com/faq" target="_blank">FAQs</a> | <a href="http://blog.smstadka.com/feedback" alt="Feedback opens in new window" target="_blank">Feedback</a><a href="http://www.rapidssl.com/" target="_blank"><img src="/img/spacer.gif"  class="oSPos30 otherSprite" align="absmiddle"></a></span> -->
         A MindsArray Technologies Pvt. Ltd. Product. All Rights Reserved © <?php echo date('Y'); ?> Pay1&trade;
    	</div>
		<div class="row" style="width:100%;border:0px solid;padding-top: 30px;float: left;">
        <div style="width:100%;border:0px solidapp/webroot/boot/js/kitdeliverysystem.js;float: left;"> <a href="https://goo.gl/3QMUqf" target="_blank"><img src="/img/panel-728x90.gif" class="img-responsive" style="border:1px solid;"></img></a></div>
        <!--<div style="width:100%;border:0px solid;float: left;"> <a href="/img/dist_panel_navratri_popup.png" target="_blank"><img src="/img/dist_panel_navratri_banner-02.png" class="img-responsive" style="border:1px solid;"></img></a></div>-->
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