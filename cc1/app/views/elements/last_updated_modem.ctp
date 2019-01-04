<div class="row">
    <div class="col-lg-12">

        <?php
        $currentTimestamp = time();
        if (isset($currentTimestamp)):
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <h3>Modems</h3>
                    <?php
                    foreach ($last as $last_timestamp):
                        $bgcolor = ($currentTimestamp - strtotime($last_timestamp['timestamp']) ) > 900 ? "#c73525" : "#99ff99";
                        if ($last_timestamp['is_api_vendor']):
                            ?>
                            <div class="col-lg-3 divblocklastsuccess" style="background-color: <?php echo $bgcolor; ?>">
                                <ul class="divblockapi">
                                    <li><?php echo strtoupper($last_timestamp['shortForm']); ?></li>
                                    <li class="balance"><?php echo date("H:i:s", strtotime($last_timestamp['timestamp'])); ?></li>
                                </ul>
                            </div>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>

            <div class="row" style="margin-bottom: 14px;">
                <div class="col-lg-12">
                    <h3>Api's</h3>
                    <?php
                    foreach ($last as $last_timestamp):
                        $bgcolor = ($currentTimestamp - strtotime($last_timestamp['timestamp']) ) > 900 ? "#c73525" : "#99ff99";
                        ?>

                        <?php if (!$last_timestamp['is_api_vendor']): ?>
                            <div class="col-lg-3 divblocklastsuccess" style="background-color: <?php echo $bgcolor; ?>">
                                <ul class="divblockapi">
                                    <li><?php echo strtoupper($last_timestamp['shortForm']); ?>[<?php echo date("H:i:s", strtotime($last_timestamp['timestamp'])); ?>]</li>
                                    <?php if ($last_timestamp['balance'] > 0): ?><li class="balance"><?php echo number_format($last_timestamp['balance'], 2); ?></li><?php endif; ?>
                                </ul>
                            </div>
                      <?php endif; ?>


                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>