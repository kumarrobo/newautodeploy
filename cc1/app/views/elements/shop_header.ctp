<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109049732-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109049732-1');
</script>
<style>
#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  height: 30px;
  background-color: #4CAF50;
  text-align: center; /* To center it horizontally (if you want) */
  line-height: 30px; /* To center it vertically */
  color: white;
}
</style>
<?php
$typeId = $this->Session->read('Auth.User.group_id'); //.$info['name'];
$userType = "";
$shop = "";
if($typeId == RETAILER){
        $shop = $info['shopname'];
        if( $this->Session->read('Auth.is_partner') == 'true' ){
            $userType = "API-Partner";
        }else{
            $userType = "Retailer";
        }
 }else if ($typeId == DISTRIBUTOR)
{ $shop = $info['company'];
$userType = "Distributor";
$target = $scheme['target']['target1']['recharge'];
$achieved = empty($scheme['achieved']['0']['sale']) ? 0 : $scheme['achieved']['0']['sale'];
$width = intval($achieved*100/$target);
}
else if ($typeId == MASTER_DISTRIBUTOR)
{ $shop = isset($info['company'])?$info['company'] : "";
$userType = "Master Distributor"; }
else if ($typeId == SUPER_DISTRIBUTOR)
{ $shop = isset($info['company'])?$info['company'] : "";
$userType = "Super Distributor"; }
else if ($typeId == RELATIONSHIP_MANAGER)
{ $shop = $info['name'];
    if ($_SESSION['Auth']['show_sd'] == 1) {
        $userType = "Master Distributor";
    } else {
        $userType = "Relationship Manager";
    }
}
else if ($typeId == ADMIN)
{ $shop = 'Pay1 Company';
$userType = "Admin"; }
?>
<div class="rightFloat">
	<div>
	<?php if ($this->Session->read('Auth.User')) { ?>
		<div class="headerLinks1">
			<div class="globalLinks strng rightFloat">
			 	<ul>
					<li style="border-right:0px; padding-right:15px !important;font-weight:normal;">Welcome  <?php if($userType != "API-Partner"){   echo "+91 ";  } ?><?php echo $this->Session->read('Auth.User.mobile'). " - <i>$shop</i> (<strong>$userType</strong>)";?>,</li>
			    	<li class="lastElement" style="padding-right:0px !important;margin-right:0px !important;"> <?php echo $this->Html->link(__('Logout', true), array('controller' => 'shops', 'action' => 'logout')); ?></li>
			  	</ul>
			</div>
		  	<br class="clearRight" />
		</div>
<!-- <br class="clearRight" /> -->
<?php if($userType == 'Admin' || $userType == 'Master Distributor' || $userType == 'Super Distributor' || $userType == 'Distributor') { ?>
<div id="UserBalance" class="globalLinks strng fntSz17" style="text-align:right; float:right; padding-top:25px;">
 Balance : <span><img class='rupee1' src='/img/rs.gif' align="absMiddle" style="margin-bottom:3px;"/></span><?php if(isset($info['balance']))echo sprintf('%.2f', $info['balance']);?>
</div>
	<?php } /*else if($userType == 'Distributor') { ?>

	<div id="UserBalance" class="globalLinks strng fntSz17" style="text-align:right; float:right; padding-top:25px;">
         Balance : <span><img class="rupee1" src="/img/rs.gif" style="margin-bottom:3px;" align="absMiddle"></span><?php if(isset($info['balance']))echo sprintf('%.2f', $info['balance']);?></div>

<div style="text-align: left; float: left; padding-top: 25px; padding-right: 20px;">
Recharge Target: <?php echo $target; ?> <a href="/shops/distIncentive">Know more</a>
 <div id="myProgress"> <p style="width:<?php echo $width; ?>%" id="myBar"></p>
</div><?php echo "$achieved ($width %)"; ?>
                </div></div>
	<?php }*/ else { ?>

	<div id="UserBalance" class="globalLinks strng fntSz17" style="text-align:right; float:right; padding-top:25px;"> <a href='/shops/changePassword' target="_blank">Change Password</a></div>

	<?php }} ?>
	</div>
</div>