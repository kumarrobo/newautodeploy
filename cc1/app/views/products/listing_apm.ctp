<?php echo $this->element('product_sidebar'); ?>
<div class="padding-top-10" style="float:left; width: 75%; margin-left: 20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            Amount Priority Mapping
            <span style="float: right; margin-right: 20px;"><a href="/products/a_p_mapping">> Add Mapping</a></span>
        </div>
        <?php if($list_apm) { ?>
        <table class="table-class">
            <thead style="background-color:#e7c3c3">
                <tr>
                    <td style="border: 2px solid red; padding-left: 10px;">Operator</td>
                    <td style="border: 2px solid red; padding-left: 10px;">Vendor</td>
                    <td style="border: 2px solid red"><center>Min Amount</center></td>
                    <td style="border: 2px solid red"><center>Max Amount</center></td>
                    <td style="border: 2px solid red"><center>List Amount</center></td>
                    <td style="border: 2px solid red"><center>Activation</center></td>
                    <td style="border: 2px solid red"><center>Action</center></td>
                </tr>
            </thead>
            <tbody>
                <?php $activation = array('Inactive', 'Active'); ?>
                <?php foreach($list_apm as $list) { ?>
                <tr>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo ucwords($list['products']['name']); ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo ucwords($list['vendors']['company']); ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo $list['amount_priority_mapping']['min_amount']; ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo $list['amount_priority_mapping']['max_amount']; ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo ($list['amount_priority_mapping']['list_amount'] == '') ? '0' : $list['amount_priority_mapping']['list_amount']; ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo $activation[$list['amount_priority_mapping']['is_deleted']]; ?></td>
                    <td style="border: 1px solid #467CB7"><center><a href="/products/a_p_mapping/<?php echo $list['amount_priority_mapping']['id']; ?>">Edit</a> <!-- &nbsp;&nbsp;|&nbsp;&nbsp; Delete--></center></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="flash_message padding-top-10 padding-bottom-10">No Data Available</div>
        <?php } ?>
    </div>
</div>