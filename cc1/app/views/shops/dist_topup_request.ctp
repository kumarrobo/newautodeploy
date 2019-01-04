<div>
    <?php echo $this->element('shop_upper_tabs', array('tob_tab' => $top_tab)); ?>
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <?php echo $this->element('shop_side_activities', array('side_tab' => $side_tab)); ?>
            <div id="innerDiv">
                <div class="appTitle" style="margin-bottom:20px;margin-left:225px;">Pay1 TopUp Request </div>
			
                 <?php echo $this->element('dist_topup_request', array('mobile' => $mobile)); ?>
            </div>
            <br class="clearLeft" />
        </div>

    </div>
</div>
<br class="clearRight" />
</div>
