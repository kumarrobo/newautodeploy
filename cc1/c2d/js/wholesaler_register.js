function validation() {
    
        var action          = $('#action').val();
        var id              = $('#id').val();
        var name            = $('#name').val();
        var username        = $('#username').val();
        var contact_person  = $('#contact_person_name').val();
        var contact_no      = $('#contact_no').val();
        var pay1discount    = $('#pay1discount').val();
        var slab_commision  = $('#slab_commision').val();
        var ivr_no          = $('#ivr_no').val();
        if(id == 0) {
            var password        = $('#password').val();
            var confirmpassword = $('#confirmpassword').val();
        }
        var bankname        = $('#bankname').val();
        var branchname      = $('#branchname').val();
        var ifsccode        = $('#ifsccode').val();
        var address         = $('#address').val();
        var key             = 0;
        
        $('.error-msg').hide();

        $.post('/wsregister/checkUnique', {id: id, username: username}, function(e) {

                if(name == '') {
                    $('#name_err').show();
                    key = 1;
                }
                if(username == '') {
                    $('#username_err').show();
                    key = 1;
                }
                if(contact_person == '') {
                    $('#c_p_n_err').show();
                    key = 1;
                }
                if(contact_no == '') {
                    $('#contact_no_err').show();
                    key = 1;
                }
                if(pay1discount == '') {
                    $('#pay1discount_err').show();
                    key = 1;
                }
                if(slab_commision == '') {
                    $('#slab_commision_err').show();
                    key = 1;
                }
                if(id == 0) {
                    if(password == '') {
                        $('#password_err').show();
                        key = 1;
                    }
                    if(password != '' && password != confirmpassword) {
                        $('#password_same').show();
                        key = 1;
                    }
                }
                if(bankname == '') {
                    $('#bankname_err').show();
                    key = 1;
                }
                if(branchname == '') {
                    $('#branchname_err').show();
                    key = 1;
                }
                if(ifsccode == '') {
                    $('#ifsccode_err').show();
                    key = 1;
                }
                if(address == '') {
                    $('#address_err').show();
                    key = 1;
                }
                if(e>0) {
                    $('#unique_err').html('* Username is already registered');
                    $('#unique_err').show();
                    key = 1;
                }

                if(key == 0) {
                    $('#wholesaler').submit();
                } else {
                    alert('Some info is wrongly filled');
                }
        }, 'json');
}