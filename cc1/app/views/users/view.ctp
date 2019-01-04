      <div >
      	<div class="tabs">
   		  <ul id='navTabs'>
   		  		<li id="lidash" class="<?php if(empty($pageType) || $pageType=='dashboard') echo 'sel'; ?>"><?php echo $ajax->link( 
    				'Get Started', 
    				array('action' => 'newDashboard' ), 
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					); ?>
				</li>
   		  		<li id="lipack" class="<?php if($pageType=='package') echo 'sel'; ?>"><?php echo $ajax->link( 
    				'Packages', 
    				array('action' => 'package'),
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					); ?>
				</li>
				<li id="liapp" class="<?php if($pageType=='app') echo 'sel'; ?>"><?php echo $ajax->link( 
    				'Personal Alerts', 
    				array('controller' => 'apps' ,'action' => 'getApps' ), 
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					); ?> 
				</li>
				<!--<li id='lisms' class="<?php /*if($pageType=='WSMS') echo 'sel'; ?>"><?php echo $ajax->link( 
    				'FREE SMS', 
    				array('action' => 'wSMS' ), 
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					); */?> 
				</li>-->
				
				<li id='liset' class="<?php if($pageType=='pass') echo 'sel'; ?>"><?php echo $ajax->link( 
    				'My Account', 
    				array('action' => 'setting' ), 
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					); ?> 
				</li>
				<li id='lirec' class="<?php if($pageType=='recharge') echo 'sel'; ?>">
					<a href="">Recharge</a>
				</li>
				
   		  		<li id='liret' class="<?php if($pageType=='retailer') echo 'sel'; ?>"><?php echo $ajax->link( 
    				'SMSTadka Shops', 
    				array('controller' => 'retailers', 'action' => 'all' ), 
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					); ?> 
				</li>
				
				<li id='liret' class="<?php if($pageType=='freeCredits') echo 'sel'; ?>"><?php echo $ajax->link( 
    				'FREE Credits', 
    				array('controller' => 'promotions', 'action' => 'freeCredits' ), 
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					);  ?> 
				</li>
				<!--<li id='liCustSupport'><?php /*echo $ajax->link( 
    				'cust support', 
    				array('controller' => 'promotions', 'action' => 'custSupport' ), 
    				array( 'update' => 'pageContent','onclick' => 'changeTabClass(this);')
					);  */?> 
				</li>-->
				<!-- <li id="UserBalance" class="balance" style="position:relative; top:-20px; right:5px;">Balance : <span><img class='rupee1' src='/img/rs.gif'/></span><?php echo number_format($objGeneral->getBalance($_SESSION['Auth']['User']['id']),2,'.','');?> </li> -->
				<br class="clearLeft" />
       	  </ul>
          <div class="clearLeft"></div>
        </div>
        <input type="hidden" id="pageType" value=<?php echo $pageType; ?>>
        
       <!-- <div>&nbsp;</div>max-height:750px; -->
      
        <div id="pageContent" style="min-height:500px;position:relative;">
        <?php 
       		if($pageType=='recharge') {
				echo $this->element('payment');
        	}
        	else if($pageType=='dash') {
        		$news = $objGeneral->getRecentMessages(DASH_NEWS,5);
				$bolly = $objGeneral->getRecentMessages(DASH_BOLLY,5);
				$tweet = $objGeneral->getRecentMessages(DASH_TWEET,5);
				$cricket = $objGeneral->getRecentMessages(DASH_CRICKET,5);
				$market = $objGeneral->getRecentMessages(DASH_MARKET,5);
				$tips = $objGeneral->getRecentMessages(DASH_TIPS,5);

				//$this->set(compact('news','bolly','tweet','cricket','market','tips'));
				echo $this->element('dashboard',compact('news','bolly','tweet','cricket','market','tips'));
        	}
        	else if($pageType=='pass') {
				echo $this->element('setting',array('par' => 'pass'));
        	}
        	else if($pageType=='app') {
        		echo $this->requestAction('/'.$controller_name.'s/initial/'.$about);
        	}
        	else if($pageType=='package') {
        		if(isset($category)){
        			echo $this->requestAction('/users/package/' . $category . '/' . $package);
        		}
        		else {
        			echo $this->requestAction('/users/package');
        		}
        	}
        	else if($pageType=='retailer') {
        		echo $this->requestAction('/retailers/all');
        	}
        	else if($pageType=='custSupport') {
        		echo $this->requestAction('/promotions/custSupport');
        	}
        	else if($pageType=='freeCredits') {
        		echo $this->requestAction('/promotions/freeCredits/'.$ccCode);
        	}
        	else {
        		echo $this->requestAction('/users/newDashboard');
        	}
        ?>
        </div>
        <?php  //echo $this->element('horizontal_packs');?>
        <div>&nbsp;</div>
        <!-- <div style="height:1px; background:#cccc11; font-size:1px; margin:0px 5px;">&nbsp;</div> -->
      </div>
      <br class="clearRight" />
      <!-- <div class="catRow"> 
      
      <?php  //echo $this->element('categories',array('ocatData' => $ocatData)); ?>
   			
  	  </div> --> 