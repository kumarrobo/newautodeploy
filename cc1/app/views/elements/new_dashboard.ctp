<?php //echo "<pre>"; print_r($recentMessages); echo "</pre>"; ?>
<div class="loginCont">
	<!-- Right Column -->
	<div class="mainColumnLeft">
		<div class="fntSz21 color3 strng">Recommended Packages for you</div>
		<div class="title2Sub color2">Select your favaourite packages from wide options available and subscribe to them.</div>
		<div style="padding-bottom:20px;">
			<?php foreach($recommended as $package) { ?>
			<div class="leftFloat packBox">                        	
	            <div style="width:265px;" class="box3Width1">                          
	              	<div class="leftCol3 thumbImage">                      	
	                  	<a href="/packages/view/<?php echo $package['Package']['url'];?>"> <img border="0" src="/img/spacer.gif" alt="<?php echo $package['Package']['name']; ?>" title="<?php echo $package['Package']['name']; ?>" class="package_<?php echo $package['Package']['id']; ?>"> </a>         		                      	                          		
	         		</div>
	                <div style="display: block; text-align:left;color:#000;" class="thumbDesc1 fntSz15">
	                	<div style="text-align:left;" class="fntSz17 strng color1"><a href="/packages/view/<?php echo $package['Package']['url'];?>"> <?php echo $package['Package']['name']; ?></a></div>
	                	<div><span class="strng">Price - <span><img src="/img/rs.gif" style="margin-right: 1px;" class="rupee2"></span><?php echo $package['Package']['price']; ?></span><span> for <?php echo $package['Package']['validity']; ?> days</span></div>
	                	<div>
	                	Frequency - <?php echo $objGeneral->getFrequency($package['Package']['frequency']);?>
	                	</div>
	                	<div><input type="button" class="css3But2" value="Subscribe" onclick="subPackage('<?php echo $objMd5->encrypt($package['Package']['id'],encKey) ?>','sub',this);">
	                		<?php if($package['Package']['trial_days'] > 0) {?>
                                 <span> &nbsp; or</span> <a href="javascript:void(0);" style="font-size:11px; display:inline-block; margin-left:5px; text-decoration:underline;" onclick="subPackageTrial('<?php echo $objMd5->encrypt($package['Package']['id'],encKey) ?>','sub',this);">Try FREE</a>
                        	<?php } ?>
	                	</div>
	                </div>
	                <div class="clearLeft">&nbsp;</div>                 
	            </div>
            </div>
            <?php } ?>
            <div class="clearLeft">&nbsp;</div>
            <div style="text-align:center; padding-top:10px;"><input type="button" class="css3But1 css3But1Big" value="Browse for more Packages" onclick="morePackages();"></div>
		</div>
		<!-- App boxes -->
		<div class="rowDivider" style="padding-bottom: 20px;">
			<div class="title2 color3 strng">Create Personalize Alerts</div>
			<div class="title2Sub color2">Choose from the following services & create personal alerts.</div>
			<div class="appMainBox">
				<?php foreach($allApps as $app) { ?>
                <div class="appSmallBox adjust appImg<?php echo $app['SMSApp']['id'];?> appSmallBoxie6">
                    <div class="leftFloat"><img height="93px" src="/img/spacer.gif"></div>
                    <div class="appSmallBoxCont fntSz15">
                        <div class="strng"><div class="rightFloat color1"><?php if($app['SMSApp']['basic_price'] >= 1) { ?><span><img class="rupee1" src="/img/rs.gif"></span><?php echo $app['SMSApp']['basic_price']; } else { echo $app['SMSApp']['basic_price']*100 . " paise"; } echo " " . $app['SMSApp']['price_tag'];?> </div><span class="color1 fntSz17"><a href="javascript:void(0);" onclick="subApp('<?php echo $app['SMSApp']['controller_name']; ?>',1);"><?php echo $app['SMSApp']['name'];?></a></span></div>		            
                        <ul class="color5 ulStyle1">
                            <?php $desc = explode("\n",$app['SMSApp']['shortDesc']); 
							$i=0;
                                    foreach($desc as $post) {
								$i++;
                            ?>
                            <li><?php echo $post; ?></li>
                            <?php } ?>					
                        </ul>
                        
                        <div style="margin-top:-7px;">
                         <?php if ($i==1){?>
                         <input type="button" class="css3But2 rightFloat" value="<?php echo $app['SMSApp']['call_to_action']; ?>" onclick="subApp('<?php echo $app['SMSApp']['controller_name']; ?>',0)">
                         <?php } else {?>
                         
                         <input type="button" class="css3But2 rightFloat" style="margin:0;" value="<?php echo $app['SMSApp']['call_to_action']; ?>" onclick="subApp('<?php echo $app['SMSApp']['controller_name']; ?>',0)">
                         <?php }?>
                         
                         </div>
                        <div class="clearRight">&nbsp;</div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>	            
                </div>
                <?php } ?>
            </div>
		</div>
		<div class="rowDivider">
			<div class="title2 color3 strng">Browse Categories</div>
			<div style="text-align:center">
	          <?php echo $this->element('categories_login'); ?>      
	          <div class="clearLeft">&nbsp;</div>          
	        </div>
		</div>
	</div>
	<!-- Left COlumn ends -->
	<!-- right COlumn -->
	<div class="mainColumnRight" style="background:#f8f8f8;">
		<div class="color4 fntSz21 box6Cont2 strng" style="padding:7px 10px">Recent Messages</div>
		<div style="height:1150px; overflow:auto" id="recMessages">
			<br/>
			<span style="margin-left:20px"><img src="/img/loader2.gif" /></span>
		<?php //echo $this->requestAction('/users/newDashboard'); ?>		
        </div>
	</div>
	<!-- right Column ends -->
	<div class="clearLeft">&nbsp;</div>
</div>
<script>

function getRecMsg(){
		var url = '/users/getRecentMsgs';
		var params = {};		
		new Ajax.Updater('recMessages', url, {
	  			parameters: params,
	  			evalScripts:true
		});
}
getRecMsg();
</script>