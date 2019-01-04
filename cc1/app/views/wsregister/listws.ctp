<div class="panel panel-default">
    <div class="panel-heading">
        Wholesaler List
        <span style="float: right; margin-right: 20px;"><a href="/wsregister/">> Add Wholesaler</a></span>
    </div>
    <?php if($listWS) { ?>
    <table class="table-class">
        <thead style="background-color:#e7c3c3">
            <tr>
                <td style="border: 2px solid red; padding-left: 10px;">ID</td>
                <td style="border: 2px solid red; padding-left: 10px;">Wholesaler Name</td>
                <td style="border: 2px solid red"><center>Commission</center></td>
                <td style="border: 2px solid red"><center>Created Date</center></td>
                <td style="border: 2px solid red"><center>Action</center></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listWS as $list) { $list = $list['cash_payment_client']; ?>
            <tr>
                <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo $list['id']; ?></td>
                <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo $list['company_name']; ?></td>
                <td style="border: 1px solid #467CB7"><center><?php echo $list['commission'] . ' %'; ?></center></td>
                <td style="border: 1px solid #467CB7"><center><?php echo ($list['created_date'] == '' || $list['created_date'] == '0000-00-00 00:00:00') ? '-' : date('jS-M-Y', strtotime($list['created_date'])); ?></center></td>
                <td style="border: 1px solid #467CB7"><center><a href="/wsregister/index/<?php echo $list['id']; ?>">Edit</a> <!-- &nbsp;&nbsp;|&nbsp;&nbsp; Delete--></center></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
    <div class="flash_message padding-top-10 padding-bottom-10">No Data Available</div>
    <?php } ?>
</div>