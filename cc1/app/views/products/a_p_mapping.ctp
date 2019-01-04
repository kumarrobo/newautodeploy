<?php echo $this->element('product_sidebar'); ?>
<div class="padding-top-10" style="float: left; width: 75%; margin-left: 20px;">
<?php
    if(isset($data)) {
        $data   = $data[0]['amount_priority_mapping'];
        $action = 'u';
    } else {
        $action = 'a';
    }
?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Amount Priority Mapping
        </div>
        <div class="panel-body">
            <form id="amount_priority_map" method="POST" action="/products/a_p_m_entry">
                <input type="hidden" id="action" name="action" value="<?php echo $action; ?>">
                <input type="hidden" id="id" name="id" value="<?php echo (isset($data)) ? $data['id'] : '0'; ?>">

                <label for="operator" class="control-label">Operator :</label>
                <div class="row padding-top-10">
                    <div class="col-md-12">
                        <select id="operator" name="operator" class="form-control">
                            <option value="">Select Operator</option>
                            <?php foreach($operators as $operator) { ?>
                            <option value="<?php echo $operator['products']['id']; ?>" <?php if($operator['products']['id'] == $data['product_id']) { echo "selected"; } ?>><?php echo ucwords($operator['products']['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 padding-top-5 error-msg" id="operator_err">* Select Operator</div>
                </div>

                <label for="vendor" class="control-label padding-top-20">Vendor :</label>
                <div class="row">
                    <div class="col-md-12 padding-top-10">
                        <select id="vendor" name="vendor" class="form-control">
                            <option value="">Select Vendor</option>
                            <?php foreach($vendors as $vendor) { ?>
                            <option value="<?php echo $vendor['vendors']['id']; ?>" <?php if($vendor['vendors']['id'] == $data['vendor_id']) { echo "selected"; } ?>><?php echo ucwords($vendor['vendors']['company']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 padding-top-5 error-msg" id="vendor_err">* Select Vendor</div>
                </div>

                <div class="row padding-top-20">
                        <label for="amount_range" class="control-label" style="margin-left: 15px;">Amount :</label><br />
                        <div class="col-md-1 col-xs-4 padding-top-10">
                            <input type="text" id="min_amount" name="min_amount" class="form-control" placeholder="Min" value="<?php echo $data['min_amount'] ?>" />
                        </div>
                        <div class="col-md-1 col-xs-4 padding-top-10">
                            <input type="text" id="max_amount" name="max_amount" class="form-control" placeholder="Max" value="<?php echo $data['max_amount'] ?>" />
                        </div>
                </div>
                <div class="row">
                        <div class="col-md-2 col-xs-8 padding-top-10" style="text-align: center">
                            -- OR --
                        </div>
                </div>
                <div class="row">
                        <div class="col-md-2 col-xs-8 padding-top-10">
                            <input type="text" id="list_amount" name="list_amount" class="form-control" placeholder="List Amount" value="<?php echo $data['list_amount'] ?>" />
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-12 padding-top-5">
                        <div class="error-msg" id="amount_err">* Enter Amount</div>
                        <div class="error-msg" id="both_amount_err">* Can't enter both amounts, either enter 'Min-Max' amount else 'List' amount</div>
                    </div>
                </div>

                <label for="activation" class="control-label padding-top-20">Activation :</label>
                <div class="row padding-top-10">
                    <div class="btn-group" id="radio" data-toggle="buttons" style="padding-left: 15px;">
                        <label class="btn btn-primary <?php if(isset($data) && $data['is_deleted'] == 1) { echo 'active'; } ?>">
                            <input type="radio" id="is_active" autocomplete="off" value="1" /> Active
                        </label>

                        <label class="btn btn-primary <?php if(!isset($data) || (isset($data) && $data['is_deleted'] == 0)) { echo 'active'; } ?>">
                            <input type="radio" id="is_inactive" autocomplete="off" value="0" /> Inactive
                        </label>
                    </div>
                    <input type="hidden" id="activation" name="activation" value="<?php echo (isset($vendor_data)) ? $vendor_data['is_deleted'] : '0'; ?>" />
                </div>

                <div class="row padding-top-20">
                    <div class="col-md-1 col-xs-3 padding-top-10">
                        <button type="button" class="btn btn-success" onclick="return apm_validation();"><?php echo (isset($data)) ? 'Update' : 'Register'; ?></button>
                    </div>
                    <div class="col-md-1 col-xs-3 padding-top-10">
                        <button type="button" class="btn btn-default" onclick="location.href='/products/listing_apm'">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>