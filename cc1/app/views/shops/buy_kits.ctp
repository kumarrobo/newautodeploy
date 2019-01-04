<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
        <div class="loginCont">
            <?php echo $this->element('shop_side_activities',array('side_tab' => 'buyKits'));?>
            <div id="innerDiv">
                <?php echo $this->element('buy_kits_form'); ?>
            </div>
        </div>
    </div>
</div>