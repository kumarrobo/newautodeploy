
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>
        	<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN) { ?>
        	 <li>
        	 <a class="hList" href="javascript:void(0);"><p>
        	 	<?php echo 'Master Distributor';?>
        	 	</p>
        	 </a>
          	
        	<div class="sublist">
        		<ul>
        			<li name='innerli'>
        				<a href="/shops/transfer" class="<?php if($side_tab == 'transfer') echo 'sel';?>">Balance Transfer</a>
        			</li>
				<li name='innerli'>
        				<a href="/shops/allDistributor" class="<?php if($side_tab == 'alldistributor') echo 'sel';?>">List Master Distributors</a>
        			</li>
        			
        			
<!--        			<li name='innerli'>
        				<a href="/shops/investmentReport" class="<?php if($side_tab == 'investment') echo 'sel';?>">Enter Investments</a>
        			</li>-->
                                
                                <li name='innerli'>
        				<a href="/shops/pullBackApproval" class="<?php if($side_tab == 'pullbackApprove') echo 'sel';?>">PullBack Approval</a>
        			</li>
        		</ul>
        	</div>
        	</li>
        	<?php } else if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR) { ?>
        	 <li>
        	 <a class="hList" href="javascript:void(0);"><p>
        	 	<?php echo 'Distributor';?>
        	 	</p>
        	 </a>
          	
        	<div class="sublist">
        		<ul>
        			<li name='innerli'>
        				<a href="/shops/transfer" class="<?php if($side_tab == 'transfer') echo 'sel';?>">Balance Transfer</a>
        			</li>
        			<?php // if($_SESSION['Auth']['User']['id'] == 1) { ?>
<!--        			<li name='innerli'>
        				<a href="/shops/kitsTransfer" class="<?php // if($side_tab == 'kits_transfer') echo 'sel'; ?>">Debit System</a>
        			</li>-->
        			<?php // } ?>
					<li name='innerli'>
        				<a href="/shops/allDistributor" class="<?php if($side_tab == 'alldistributor') echo 'sel';?>">List Distributors</a>
        			</li>
        			<li name='innerli'>
        				<a href="/shops/formDistributor" class="<?php if($side_tab == 'create') echo 'sel';?>">Create Distributor</a>
        			</li>
        			
        			<li name='innerli'>
        				<a href="/shops/allRetailer" class="<?php if($side_tab == 'allretailer') echo 'sel';?>">Retailers List</a>
        			</li>
        			<!--<li name='innerli'>
        				<a href="/shops/investmentReport" class="<?php if($side_tab == 'investment') echo 'sel';?>">Enter Investments</a>
        			</li>-->
                    <li name='innerli'>
        				<a href="/shops/formRm" class="<?php if($side_tab == 'create_rm') echo 'sel';?>">Create RM</a>
        			</li>
                    <li name='innerli'>
                        <a href="/shops/allSuperDistributor" class="<?php if($side_tab == 'allsuperdistributor') echo 'sel';?>">List Super Distributor</a>
                    </li>
                    <li name='innerli'>
                        <a href="/shops/formSuperDistributor" class="<?php if($side_tab == 'createSD') echo 'sel';?>">Create Super Distributor</a>
                    </li>
        		</ul>
        	</div>
        	</li>
        	<?php } else if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) { ?>
             <li>
             <a class="hList" href="javascript:void(0);"><p>
                <?php echo 'Distributor';?>
                </p>
             </a>
            
            <div class="sublist">
                <ul>
                    <li name='innerli'>
                        <a href="/shops/transfer" class="<?php if($side_tab == 'transfer') echo 'sel';?>">Balance Transfer</a>
                    </li>
                    <li name='innerli'>
                        <a href="/shops/allDistributor" class="<?php if($side_tab == 'alldistributor') echo 'sel';?>">List Distributors</a>
                    </li>
                    <li name='innerli'>
                        <a href="/shops/formDistributor" class="<?php if($side_tab == 'create') echo 'sel';?>">Create Distributor</a>
                    </li>
                    <li name='innerli'>
                        <a href="/shops/allRetailer" class="<?php if($side_tab == 'allretailer') echo 'sel';?>">Retailers List</a>
                    </li>
                    <li name='innerli'>
                        <a href="/shops/distTopUpRequest" class="<?php if($side_tab == 'topup_request') echo 'sel';?>">TopUp Request</a>
                    </li>
                </ul>
            </div>
            </li>
            <?php } else if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
        		<li>
        	 <a class="hList" href="javascript:void(0);"><p>
        	 	<?php echo 'Retailer';?>
        	 	</p>
        	 </a>
          	
        	<div class="sublist">
        		<ul>
        			<li name='innerli'>
        				<a href="/shops/transfer" class="<?php if($side_tab == 'transfer') echo 'sel';?>">Balance Transfer</a>
        			</li>
                                                                        <li name='innerli'>
        				<a href="/shops/formRetailer" class="<?php if($side_tab == 'create') echo 'sel';?>">Create Retailer</a>
        			</li>
                                                                        <li name='innerli'>
        				<a href="/shops/allRetailer" class="<?php if($side_tab == 'allretailer') echo 'sel';?>">Retailers List </a>
        			</li>
                                                                        <li name='innerli'>
        				<a href="/shops/formSalesman" class="<?php if($side_tab == 'salesman') echo 'sel';?>">Create Salesman</a>
        			</li>
                                                                        <li name='innerli'>
        				<a href="/shops/salesmanListing" class="<?php if($side_tab == 'salesmanList') echo 'sel';?>">Salesmen List</a>
        			</li>
        			<!--<li name='innerli'>
        				<a href="/shops/activateCards" class="<?php /*if($side_tab == 'activate') echo 'sel'; */?>">Activate Cards</a>
        			</li>-->
                                <?php if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) { ?>
                                                                        <li name='innerli'>
        				<a href="/shops/salesmanTran" class="<?php if($side_tab == 'cashtran') echo 'sel';?>">Salesman Collections</a>
        			</li>
                                <?php } 

                    if(!isset($_SESSION['Auth']['sd_id']) || $_SESSION['Auth']['sd_id'] == "") {
                                ?>
                    <li name='innerli'>
        				<a href="/shops/distTopUpRequest" class="<?php if($side_tab == 'topup_request') echo 'sel';?>">TopUp Request</a>
        			</li>
                    <?php
                    }
                    ?>
                                                                        <li name='innerli'>
        				<a href="/shops/deletedRetailer" class="<?php if($side_tab == 'deletedRetailer') echo 'sel';?>">Deleted Retailer</a>
        			</li>
<!--                                                                        <li name='innerli'>
        				<a href="/shops/buyKits" class="<?php // if($side_tab == 'buyKits') echo 'sel';?>">Buy Kits</a>
        			</li>-->
                                                </ul>
        	</div>
        	</li>
        	<?php } else if($_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) { ?>
        		<li>
        	 <a class="hList" href="javascript:void(0);">
                     <p>Relationship Manager</p>
        	 </a>
          	
        	<div class="sublist">
        		<ul>
        			
        			<?php if($_SESSION['Auth']['User']['id'] == 1) { ?>
        			
        			<?php }?>
					<li name='innerli'>
        				<a href="/shops/allDistributor" class="<?php if($side_tab == 'alldistributor') echo 'sel';?>">List Distributors</a>
        			</li>
          			<li name='innerli'>
        				<a href="/shops/allRetailer" class="<?php if($side_tab == 'allretailer') echo 'sel';?>">Retailers List</a>
        			</li>
                    <li name='innerli'>
                        <a href="/shops/allSuperDistributor" class="<?php if($side_tab == 'allsuperdistributor') echo 'sel';?>">List Super Distributor</a>
                    </li>
        			
        		</ul>
        	</div>
        	</li>
                <?php } else if($_SESSION['Auth']['User']['group_id'] == ACCOUNTS) { ?>
        		<li>
        	 <a class="hList" href="javascript:void(0);">
                     <p>Accounts</p>
        	 </a>
          	
        	<div class="sublist">
        		<ul>
        			
        			<li name='innerli'>
        				<a href="/shops/limitTransfer" class="<?php if($side_tab == 'limit_transfer') echo 'sel';?>">Limit Transfer</a>
        			</li>
          			<!--<li name='innerli'>
        				<a href="/shops/allRetailer" class="<?php if($side_tab == 'allretailer') echo 'sel';?>">Retailers List</a>
        			</li>-->
        			
        		</ul>
        	</div>
        	</li>
                <?php } ?>    
        	
		</ul>
    </div>
  </div>  
  