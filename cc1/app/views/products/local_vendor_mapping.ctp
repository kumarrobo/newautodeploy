<?php echo $this->element('product_sidebar'); ?>
<div class="padding-top-10" style="float: left; width: 75%; margin-left: 20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            Local Vendor Mapping
        </div>
        <?php $vendor_data = $vendor_data[0]['local_vendor_mapping']; ?>
        <div class="panel-body">
            <form id="vendor_operator_map" method="POST" action="/products/l_v_m_entry">
                <input type="hidden" id="action" name="action" value="<?php echo (isset($vendor_data)) ? 'u' : 'a'; ?>">

                <label for="vendor" class="control-label">Vendor :</label>
                <div class="row">
                    <div class="col-md-12 padding-top-10">
                        <?php if(!isset($vendor_data)) { ?>
                        <select id="vendor" name="vendor" class="form-control">
                            <option value="">Select Vendor</option>
                            <?php foreach($vendors as $vendor) { ?>
                            <option value="<?php echo $vendor['id']; ?>" <?php if($vendor['id'] == $vendor_data['vendor_id']) { echo "selected"; } ?>><?php echo ucwords($vendor['company']); ?></option>
                            <?php } ?>
                        </select>
                        <?php } else { ?>
                        <input type="hidden" id="vendor" name="vendor" class="form-control" value="<?php echo $vendor_data['vendor_id']; ?>" />
                        <input type="text" id="vendor_name" name="vendor_name" class="form-control" value="<?php echo ucwords($vendors[$vendor_data['vendor_id']]['company']); ?>" readonly />
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 error-msg" id="vendor_err">* Select Vendor</div>
                </div>

                <label for="operator" class="control-label padding-top-20">Operator :</label>
                <div class="row padding-top-10">
                    <div class="col-md-12">
                        <select id="operator" name="operator" class="form-control">
                            <option value="">Select Operator</option>
                            <?php foreach($operators as $operator) { ?>
                            <option value="<?php echo $operator['products']['id']; ?>" <?php if($operator['products']['id'] == $vendor_data['operator_id']) { echo "selected"; } ?>><?php echo ucwords($operator['products']['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 error-msg" id="operator_err">* Select Operator</div>
                </div>

                <div class="row padding-top-20">
                    <div class="col-md-12 padding-top-10">
                        <label for="distributed" class="control-label">Distributed ID :</label>
                        <textarea class="form-control" id="distributed" name="distributed" placeholder="Enter Distributed Ids (Comma Seperated)" ><?php if(isset($vendor_data)) { echo $vendor_data['distributor_id']; } ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="error-msg" id="distributed_err">* Enter atleast 1 Distributed ID</div>
                    </div>
                </div>

                <label for="activation" class="control-label padding-top-20">Activation :</label>
                <div class="row">
                    <div class="btn-group" id="radio" data-toggle="buttons" style="padding-left: 15px;">
                        <label class="btn btn-primary <?php if(isset($vendor_data) && $vendor_data['is_deleted'] == 1) { echo 'active'; } ?>">
                            <input type="radio" id="is_active" autocomplete="off" value="1" /> Active
                        </label>

                        <label class="btn btn-primary <?php if(!isset($vendor_data) || (isset($vendor_data) && $vendor_data['is_deleted'] == 0)) { echo 'active'; } ?>">
                            <input type="radio" id="is_inactive" autocomplete="off" value="0" /> Inactive
                        </label>
                    </div>
                    <input type="hidden" id="activation" name="activation" value="<?php echo (isset($vendor_data)) ? $vendor_data['is_deleted'] : '0'; ?>" />
                </div>

                <div class="row padding-top-20"">
                    <div class="col-md-1 col-xs-3 padding-top-10">
                        <button type="button" class="btn btn-success" onclick="return lvm_validation();"><?php echo (isset($vendor_data)) ? 'Update' : 'Register'; ?></button>
                    </div>
                    <div class="col-md-1 col-xs-3 padding-top-10">
                        <button type="button" class="btn btn-default" onclick="location.href='/products/listing_lvm'">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>