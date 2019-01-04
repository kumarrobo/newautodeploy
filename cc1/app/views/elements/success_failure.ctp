<div class="row">
    <div class="col-lg-12">
    <?php foreach($OperatorWiseSuccessFailure as $value):  ?>
        <?php $bgcolor=""; if(intval($value['failure']*100/$value['total']) > 20 || $value['count'] == 0) $bgcolor = '#c73525'; ?>
        <div class="col-lg-3 divblock" style="background-color: <?php echo $bgcolor; ?>">
            <ul class="divblockul">
                <li><b><?php echo $value['name']; ?></b></li>
                <li><?php echo $value['vendor'] . " (" . intval($value['count']*100/$value['total']) . "%)" ;?></li>
                <li><?php echo "Failure: (" . intval($value['failure']*100/$value['total']) . "%)" ;?></li>
            </ul>
        </div>
    <?php endforeach; ?>
    </div>
</div>
