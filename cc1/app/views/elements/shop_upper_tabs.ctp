<div class="tabs">
    <ul id='navTabs'>
    <?php if($_SESSION['Auth']['User']['group_id'] == RETAILER) {?>
	<li class='<?php if($tob_tab == "home") echo "sel";?>'>
		<a href="/shops/view">Home</a>
	</li>
	<?php } ?>
	<?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER) {?>
        <?php if ( $_SESSION['Auth']['User']['group_id'] == RELATIONSHIP_MANAGER || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR ) { ?>

            <!-- <li class='<?php // if ($tob_tab == "dashboard") echo "sel"; ?>'>
                <a href="/shops/rmdashboard">Dashboard</a>
            </li> -->
            <!-- <li class='<?php // if ($tob_tab == "new_lead") echo "sel"; ?>'>
                <a href="/shops/rmNewLead">New Lead</a>
            </li> -->
            <li class='<?php if ($tob_tab == "overall_report") echo "sel"; ?>'>
                <a href="/shops/rmOverAll">Performance Report</a>
            </li>
            <li class='<?php if ($tob_tab == "lead_report") echo "sel"; ?>'>
                <a href="/shops/newLead">Lead</a>
            </li>
            <li class='<?php if ($tob_tab == "rm_dashboard") echo "sel"; ?>'>
                <a href="/shops/rmGraph">Dashboard</a>
            </li>
            <li class='<?php if ($tob_tab == "compare_tool") echo "sel"; ?>'>
                <a href="/shops/rmComapreTool">Compare Tool</a>
            </li>
            <li class='<?php if ($tob_tab == "target_report") echo "sel"; ?>'>
                <a href="/shops/rmTargetReport">Target Report</a>
            </li>
            <!-- <li class='<?php // if ($tob_tab == "proposition") echo "sel"; ?>'>
                <a href="/shops/rmProposition">Proposition</a>
            </li>
            <li class='<?php // if ($tob_tab == "support") echo "sel"; ?>'>
                <a href="/shops/rmSupport">Support</a>
            </li> -->
			<li class='<?php if ($tob_tab == "activity") echo "sel"; ?>'>
				<a href="/shops/allDistributor">Activities</a>
			</li>

		<?php } else { ?>
                        <li class='<?php if ($tob_tab == "home") echo "sel"; ?>'>
				<a href="/shops/distHomePage">Home</a>
			</li>
			<li class='<?php if ($tob_tab == "activity") echo "sel";  ?>'>
				<a href="/shops/transfer">Activities</a>
			</li>

		<?php } ?>
	<li class='<?php if($tob_tab == "setting") echo "sel";?>'>
		<a href="/shops/changePassword">My Profile</a>
	</li>
	<li class='<?php if($tob_tab == "reports") echo "sel";?>'>
        <?php
                if(isset($_SESSION['Auth']['system_used']) && $_SESSION['Auth']['system_used'] == 1) {
                        $report = 1;
                } else {
                        $report = 0;
                }
        ?>
		<a href="/shops/mainReport">Reports</a>
	</li>
        <?php /*if ($_SESSION['Auth']['User']['group_id'] ==  RELATIONSHIP_MANAGER) {  //TECHNOLOGY ?>
             <li class='<?php if ($tob_tab == "schemes") echo "sel"; ?>'>
                <a href="/shops/viewscheme">Schemes</a>
            </li>
           
        <?php }*/ ?>
                        <?php if ($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR) { ?>
        <li class='<?php if($tob_tab == "targetreport") echo "sel";?>'>
		<a href="/shops/distTargetReport">Target Report</a>
	</li>
	<li class='<?php if($tob_tab == "support") echo "sel";?>'>
		<a href="/shops/bankDetails">Support</a>
	</li>
       <li class='<?php if($tob_tab == "terms") echo "sel";?>'>
		<a href="/shops/distTermsCondition">Agreement</a>
	</li>
	<!-- <li class='<?php if($tob_tab == "inc") echo "sel";?>'>
		<a href="/shops/distIncentive" style='background:#4caf50'>Incentive</a>
	</li> -->
                        <?php } ?>
	<?php } else if($_SESSION['Auth']['User']['group_id'] == ACCOUNTS) {?>
	<li class='<?php if($tob_tab == "activity") echo "sel";?>'>
		<a href="/shops/limitTransfer">Activities</a>
	</li>

	<li class='<?php if($tob_tab == "setting") echo "sel";?>'>
		<a href="/shops/changePassword">My Profile</a>
	</li>

	<li class='<?php if($tob_tab == "setting") echo "sel";?>'>
		<a href="/shops/floatReport">Reports</a>
	</li>

	<!--<li class='<?php if($tob_tab == "reports") echo "sel";?>'>
		<a href="/shops/mainReport">Reports</a>
	</li>-->
	<?php } else if($_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) {

            if(isset($_SESSION['Auth']['system_used']) && $_SESSION['Auth']['system_used'] == 1) {
                    $report = 1;
            } else {
                    $report = 0;
            }

        ?>
    <li class='<?php if($tob_tab == "activity") echo "sel";?>'>
        <a href="/shops/transfer">Activities</a>
    </li>

    <li class='<?php if($tob_tab == "setting") echo "sel";?>'>
        <a href="/shops/changePassword">My Profile</a>
    </li>

    <li class='<?php if($tob_tab == "reports") echo "sel";?>'>
        <a href="/shops/accountHistory/<?php echo "0/1/".$report ?>">Reports</a>
    </li>
    <?php } ?>
   </ul>
  <div class="clearLeft"></div>
</div>