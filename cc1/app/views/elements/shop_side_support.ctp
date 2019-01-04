
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>
                        <?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) {?>
        	
        	 <li>
        	 <a class="hList" href="javascript:void(0);">
                                    <p>
        	 	<?php echo 'Support';?>
                                    </p>
        	 </a>
          	
        	<div class="sublist">
                                    <ul>
                                                <li name='innerli'>
                                                            <a href="/shops/bankDetails" class="<?php if($side_tab == 'bankDetails') echo 'sel';?>">Bank Details</a>
                                                </li>
                                                <li name='innerli'>
                                                            <a href="/shops/distributorsHelpDesk" class="<?php if($side_tab == 'distributorsHelpDesk') echo 'sel';?>">Distributors Help Desk</a>
                                                </li>
                                                <li name='innerli'>
                                                            <a href="/shops/limitDepartmentDetails" class="<?php if($side_tab == 'limitDepartmentDetails') echo 'sel';?>">Limit Department No.</a>
                                                </li>
                                                <li name='innerli'>
                                                            <a href="/shops/customerCare" class="<?php if($side_tab == 'customerCare') echo 'sel';?>">Customer Care No.(For Retail Partners)</a>
                                                </li>
                                                <li name='innerli'>
                                                            <a href="/shops/distProposition" class="<?php if($side_tab == 'proposition') echo 'sel';?>">Proposition</a>
                                                </li>

                                    </ul>
        	</div>
        	</li>
                <?php } ?>
            </ul>
    </div>
  </div>  
  