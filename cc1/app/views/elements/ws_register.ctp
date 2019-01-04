<div class="panel-heading">
    Registration
    <span style="float: right; margin-right: 20px;"><a href="/wsregister/listws">> List All Wholesalers</a></span>
</div>
<div class="panel-body">
    <form id="wholesaler" method="POST" action="/wsregister/registerWholesaler" enctype="multipart/form-data" >
        <input type="hidden" id="action" name="action" value="<?php echo (isset($id_data)) ? 'u' : 'a'; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo (isset($id_data)) ? $id_data['id'] : 0; ?>">
        <input type="hidden" id="product_id" name="product_id" value="<?php echo (isset($id_data)) ? $id_data['product_id'] : 0; ?>">

        <label for="name" class="control-label">Wholesaler Name :</label>
        <div class="row">
            <div class="col-md-12 padding-top-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Wholesaler Name" value="<?php echo (isset($id_data)) ? $id_data['company_name'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 error-msg" id="name_err">* Wholesaler Name is compulsory</div>
        </div>

        <label for="name" class="control-label padding-top-20">User Name :</label>
        <div class="row">
            <div class="col-md-12 padding-top-10">
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="<?php echo (isset($id_data)) ? $id_data['username'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 error-msg" id="username_err">* Username is compulsory</div>
            <div class="col-md-12 error-msg" id="unique_err"></div>
        </div>

        <label for="name" class="control-label padding-top-20">Contact Person Name :</label>
        <div class="row">
            <div class="col-md-12 padding-top-10">
                <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" placeholder="Enter Contact Person Name" value="<?php echo (isset($id_data)) ? $id_data['contact_person_name'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 error-msg" id="c_p_n_err">* Contact person is compulsory</div>
        </div>

        <label for="name" class="control-label padding-top-20">Contact No :</label>
        <div class="row">
            <div class="col-md-12 padding-top-10">
                <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Enter Contact No" value="<?php echo (isset($id_data)) ? $id_data['contact_no'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 error-msg" id="contact_no_err">* Contact no is compulsory</div>
        </div>

        <label for="name" class="control-label padding-top-20">Logo Url :</label>
        <div class="row">
            <div class="col-md-12 padding-top-10">
                <input type="text" class="form-control" id="logo_url" name="logo_url" placeholder="Enter Logo Url" value="<?php echo (isset($id_data)) ? $id_data['logo_url'] : ''; ?>" />
            </div>
        </div>

        <label for="name" class="control-label padding-top-20">Cover Photo :</label>
        <div class="row">
            <div class="col-md-12 padding-top-10">
                <textarea class="form-control" id="cover_photo" name="cover_photo" placeholder="Cover Photo Url"><?php echo (isset($id_data)) ? $id_data['cover_photo'] : ''; ?></textarea>
            </div>
        </div>

        <label for="name" class="control-label padding-top-20">Description :</label>
        <div class="row">
            <div class="col-md-12 padding-top-10">
                <textarea class="form-control" id="description" name="description" placeholder="Description"><?php echo (isset($id_data)) ? str_replace("~","'",$id_data['description']) : ''; ?></textarea>
            </div>
        </div>

        <label for="pay1discount" class="control-label padding-top-20">Pay1 Commission :</label>
        <div class="row padding-top-10">
            <div class="col-md-2">
                <input type="text" class="form-control" id="pay1discount" name="pay1discount" placeholder="Enter Pay1 Discount" value="<?php echo (isset($id_data)) ? $id_data['commission'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 error-msg" id="pay1discount_err">* Pay1 Commission is compulsary</div>
        </div>

        <label for="slab_commision" class="control-label padding-top-20">Retailer Slab Commission :</label>
        <div class="row padding-top-10">
            <div class="col-md-2">
                <input type="text" class="form-control" id="slab_commision" name="slab_commision" placeholder="Enter Slab Discount" value="<?php echo (isset($id_data)) ? $id_data['slab_commision'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 error-msg" id="slab_commision_err">* Slab Commission is compulsary</div>
        </div>

        <label for="ivr_nos" class="control-label padding-top-20">IVR Nos :</label>
        <div class="row padding-top-10">
            <div class="col-md-2">
                <select class="form-control" id="ivr_no" name="ivr_no">
                    <option value="">Select IVR No</option>
                    <?php foreach($ivr_nos as $ivr) { ?>
                    <option <?php if((isset($id_data)) && ($ivr == $id_data['ivr_no'])) { echo "selected"; } ?>><?php echo $ivr; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <?php if(!isset($id_data)) { ?>
        <div class="row">
            <div class="col-md-3 padding-top-20">
                <label for="password" class="control-label">Set Password :</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" />
            </div>
            <div class="col-md-3 padding-top-20">
                <label for="confirmpassword" class="control-label">Confirm Password :</label>
                <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm your password" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="error-msg" id="password_err">* Set Password</div>
                <div class="error-msg" id="password_same">* Both Password should be same</div>
            </div>
        </div>
        <?php } ?>

        <label for="retailerdiscount" class="control-label padding-top-20 underline">Bank Details :-</label>
        <div class="row">
            <div class="col-md-12">
                <label for="bankname" class="control-label">Bank Name :</label>
                <input type="text" class="form-control" id="bankname" name="bankname" placeholder="Enter Bank Name" value="<?php echo (isset($id_data)) ? $id_data['bank_name'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 error-msg" id="bankname_err">* Bank Name is compulsory</div>
        </div>
        <div class="row">
            <div class="col-md-6 padding-top-10">
                <label for="branchname" class="control-label">Bank Branch Name :</label>
                <input type="text" class="form-control" id="branchname" name="branchname" placeholder="Enter Branch Name" value="<?php echo (isset($id_data)) ? $id_data['bank_branch_name'] : ''; ?>" />
            </div>
            <div class="col-md-6 padding-top-10">
                <label for="ifsccode" class="control-label">IFSC Code :</label>
                <input type="text" class="form-control" id="ifsccode" name="ifsccode" placeholder="Enter IFSC Code" value="<?php echo (isset($id_data)) ? $id_data['IFSC_code'] : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 error-msg" id="branchname_err">* Branch Name is compulsary</div>
            <div class="col-md-6 error-msg" id="ifsccode_err">* IFSC Code is compulsary</div>
        </div>
        <div class="row padding-top-10">
            <div class="col-md-12">
                <label for="address" class="control-label">Bank Address :</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Bank Address" value="<?php echo (isset($id_data)) ? str_replace("~","'",$id_data['bank_address']) : ''; ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 error-msg" id="address_err">* Bank Address is compulsary</div>
        </div>

        <div class="row padding-top-10">
            <div class="col-md-1 col-xs-3 padding-top-10">
                <button type="button" class="btn btn-success" onclick="return validation();"><?php echo (isset($id_data)) ? 'Update' : 'Register'; ?></button>
            </div>
            <div class="col-md-1 col-xs-3 padding-top-10">
                <button type="button" class="btn btn-default" onclick="location.href='/wsregister/listws'">Cancel</button>
            </div>
        </div>
    </form>
</div>