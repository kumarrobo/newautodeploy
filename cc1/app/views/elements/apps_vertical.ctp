
  	<div style="float:left; width:330px; margin-top:15px;">
    	<div class="bigTitle">Apps</div>
    	<div style="border-right:1px solid #efefef;">
    	<?php foreach($allApps as $app) { ?>
    	<div style="background:#efefef;margin-bottom:15px; margin-right:20px;padding:10px;">
        	<div style="height:auto" class="leftFloat">
        		<a href="/apps/view/<?php echo $app['SMSApp']['url']; ?>">
        			<img title="<?php echo $app['SMSApp']['name']; ?>" src="/img/spacer.gif" class="appImages appImg<?php echo $app['SMSApp']['id'];?>" style="margin-right:10px; " />
        		</a>	
        	</div>
            <div style="margin-left:101px;">
            <div class="appBoxTitle" style="font-size:1.4em;"><?php echo $app['SMSApp']['name']; ?></div>
            <div style="padding-top:5px;font-size:0.8em;"><?php echo $app['SMSApp']['price_tag']; ?>: <span><img src="/img/rs.gif" class="rupee1"></span><?php echo $app['SMSApp']['basic_price']; ?></div>
            <div style="padding-top:10px;font-size:0.8em;"><?php echo substr($app['SMSApp']['description'],0,100); ?>... <a href="/apps/view/<?php echo $app['SMSApp']['url']; ?>">read more</a></div></div>
            <div class="clearLeft">&nbsp;</div>
        </div>
        
		<?php } ?>        
       
        </div>
   