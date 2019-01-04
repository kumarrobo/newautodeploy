<style>
    .error-msg { color: red; display: none; }
</style>

<div class="panel panel-default">
    <div class="panel-heading">
        Add Vendor
        <span style="float: right">-> <a href="/panels/vendors">List Vendors</a></span>
    </div>
    <div class="panel-body">
        <form id="vendors_form" method="POST" action="/panels/addEditBackVendor/<?php echo $vendor_data['id'] ?>">

            <label for="name" class="control-label">Machine ID :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="text" class="form-control" id="machine_id" name="machine_id" value="<?php echo $vendor_data['machine_id']; ?>" placeholder="Enter Machine ID" <?php if(isset($vendor_data)) { echo "readonly"; } ?> />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="machine_id_err">* Enter Machine ID</div>
            </div><br />
            
            <label for="company" class="control-label">Machine Name :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="text" class="form-control" id="company" name="company" value="<?php echo $vendor_data['company']; ?>" placeholder="Enter Machine Name" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="company_err">* Enter Machine Name</div>
            </div><br />

            <label for="shortform" class="control-label">Short Form :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="text" class="form-control" id="shortform" name="shortform" value="<?php echo $vendor_data['shortForm']; ?>" placeholder="Enter Short Form" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 error-msg" id="shortform_err">* Enter Shortform</div>
            </div><br />

            <?php if(isset($vendor_data)) { ?>
            <label for="company" class="control-label">User ID / Mobile No :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <input type="text" class="form-control" id="user" name="user" value="<?php echo $vendor_data['user'] != 0 ? $vendor_data['user'] : ''; ?>" placeholder="Enter User ID / Mobile No" />
                </div>
            </div><br />
            <?php } else { ?>
            <label for="show_flag" class="control-label">Show Flag :</label>
            <div class="row">
                <div class="col-md-12 padding-top-10">
                    <label class="radio-inline"><input type="radio" id="show_flag" name="show_flag" value="1" <?php echo isset($vendor_data) ? $vendor_data['show_flag'] == 1 ? 'checked' : '' : 'checked'; ?> >Show</label>
                    <label class="radio-inline"><input type="radio" id="show_flag" name="show_flag" value="0" <?php echo isset($vendor_data) && $vendor_data['show_flag'] == 0 ? 'checked' : ''; ?>>Don't Show</label>
                </div>
            </div><br />
            <?php } ?>

            <div class="row padding-top-10">
                <div class="col-md-1 col-xs-3 padding-top-30">
                    <button type="button" class="btn btn-success" onclick="return validation();"><?php echo isset($vendor_data) ? "Update" : "&nbsp; Add &nbsp;" ?></button>
                </div>
                <div class="col-md-1 col-xs-3 padding-top-10">
                    <button type="button" class="btn btn-default" onclick="window.location='/panels/vendors'">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    
    function validation() {

        $('.error-msg').hide();

        var company    = $('#company').val();
        var shortform  = $('#shortform').val();
        var machine_id = $('#machine_id').val();
        var user       = $('#user').val();
            
        var res = 0;
        if(company == '') {
            $('#company_err').show();
            res = 1;
        }
        if(shortform == '') {
            $('#shortform_err').show();
            res = 1;
        }
        if(machine_id == '') {
            $('#machine_id_err').show();
            res = 1;
        }
        
        if(typeof(user) != "undefined" && user != null && user != '') {
            if(user.length >= 10) {
                $.post('/panels/checkMobileExist/', {'mobile': user}, function(e) {
                    if(e == 0) {
                        var result = confirm("New User will be created !!!");
                        if(result == true) {
                            $('#vendors_form').submit();
                        }
                    } else {
                        $('#vendors_form').submit();
                    }
                }, 'json');
            } else {
                $('#vendors_form').submit();
            }
        } else if(res == 0) {
            $('#vendors_form').submit();
        }
    }
    
</script>