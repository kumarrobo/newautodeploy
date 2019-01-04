<?php echo $this->element('product_sidebar'); ?>
<div class="padding-top-10" style="float:left; width: 75%; margin-left: 20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            Vendor-Operator Mapping
            <span style="float: right; margin-right: 20px;"><a href="/products/local_vendor_mapping">> Add Mapping</a></span>
        </div>
        <?php if($list_lvm) { ?>
        <table class="table-class">
            <thead style="background-color:#e7c3c3">
                <tr>
                    <td style="border: 2px solid red; padding-left: 10px;">Vendor</td>
                    <td style="border: 2px solid red; padding-left: 10px;">Operator</td>
                    <td style="border: 2px solid red"><center>Distributed IDs</center></td>
                    <td style="border: 2px solid red"><center>Activation</center></td>
                    <td style="border: 2px solid red"><center>Action</center></td>
                </tr>
            </thead>
            <tbody>
                <?php $activation = array('Inactive', 'Active'); ?>
                <?php foreach($list_lvm as $list) { ?>
                <tr>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo ucwords($list['vendors']['company']); ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo ucwords($list['products']['name']); ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo $list['local_vendor_mapping']['distributor_id']; ?></td>
                    <td style="border: 1px solid #467CB7; padding-left: 10px;"><?php echo $activation[$list['local_vendor_mapping']['is_deleted']]; ?></td>
                    <td style="border: 1px solid #467CB7"><center><a href="/products/local_vendor_mapping/<?php echo $list['local_vendor_mapping']['vendor_id']."/".$list['local_vendor_mapping']['operator_id']; ?>">Edit</a> <!-- &nbsp;&nbsp;|&nbsp;&nbsp; Delete--></center></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="flash_message padding-top-10 padding-bottom-10">No Data Available</div>
        <?php } ?>
    </div>
</div>