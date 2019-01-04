<div>
        <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
        <div id="pageContent" style="min-height:500px;position:relative;">
                <div class="loginCont">
                        <?php echo $this->element('shop_side_activities',array('side_tab' => 'transfer'));?>
                        <div id="innerDiv">
                        <?php
                                if(!isset($_SESSION['Auth']['system_used']) || $_SESSION['Auth']['system_used'] == 0) {
                                        echo $this->element('shop_transfer');
                                } else {
                                        echo $this->element('shop_transfer_new');
                                }
                        ?>
   			</div>
   			<br class="clearLeft" />
                </div>
        </div>
</div>
<br class="clearRight" />
