<div class="loginCont">
  <div>
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>
        <?php $passClass = '';$accClass = ''; $detailClass = 'sel'; $actClass= 'sel';
           if(isset($par) && $par == 'pass') {
           	$passClass = 'sel';
           	$detailClass = '';
           	$accClass = '';
           	$actClass= '';
           }
           
           if(isset($par) && $par == 'acc') {
           	$passClass = '';
           	$detailClass = '';
           	$accClass = 'sel';
           	$actClass= '';
           }
           ?>
           <li name='innerli'><?php echo $ajax->link( 
    				'Active Packages ', 
    				array('action' => 'activePackages'),
    				array('id' => 'actPackages', 'class' => $actClass ,'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("actPackages").removeClassName("loader")' )
					); ?> </li>
		  <li name='innerli'><?php echo $ajax->link( 
    				'Expired Packages ', 
    				array('action' => 'expiredPackages'),
    				array('id' => 'expPackages', 'class' => $accClass ,'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("expPackages").removeClassName("loader")' )
					); ?> </li>                       
          <li name='innerli'><?php echo $ajax->link( 
    				'Personal Details ', 
    				array('action' => 'details'),
    				array('id' => 'pdetails', 'class' => '', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);' ,'complete' => '$("pdetails").removeClassName("loader")')
					); ?> </li>
          <li name='innerli'><?php echo $ajax->link( 
    				'Change Password ', 
    				array('action' => 'passwordChange'),
    				array('id' => 'cpassword', 'class' => $passClass ,'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("cpassword").removeClassName("loader")' )
					); ?> </li>
		  
		  <li name='innerli'><?php echo $ajax->link( 
    				'Transactions ', 
    				array('action' => 'transactions'),
    				array('id' => 'transactions', 'class' => '' ,'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("transactions").removeClassName("loader")' )
					); ?> </li>
		  <!-- <li name='innerli'><?php /*echo $ajax->link( 
    				'Msgs Sent To You ', 
    				array('controller' => 'logs','action' => 'userMessageLogs','To'),
    				array('id' => 'MsgsLogs_to', 'class' => '' ,'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("MsgsLogs_to").removeClassName("loader")' )
					);*/ ?> </li>	
		  <li name='innerli'><?php /*echo $ajax->link( 
    				'Msgs Sent By You ', 
    				array('controller' => 'logs','action' => 'userMessageLogs','By'),
    				array('id' => 'MsgsLogs_by', 'class' => '' ,'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("MsgsLogs_by").removeClassName("loader")' )
					);*/ ?> </li>
		  <li name='innerli'><?php /*echo $ajax->link( 
    				'Pending Actions ', 
    				array('action' => 'draft'),
    				array('id' => 'drafts', 'class' => '' ,'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("drafts").removeClassName("loader")' )
					);*/ ?> </li>	-->
        </ul>
      </div>
    </div>
    
    <div style=" float:left;" id="innerDiv">
      	<?php
      	if(isset($par) && $par == 'pass'){
      		 echo $this->element('change_password');
      	}
      	
      	else {
			echo $this->element('active_packages',array('resultActive' => $resultActive));
      	}
      	?>
    </div>
    <br class="clearLeft" />
 </div>