function updateService(service_id){
    if(confirm('Are you sure you want to update this service ?')){
        var form_data = $("#service_form_"+service_id).serialize();
        var submit_button = $('#submit_button_'+service_id).html();
        if( $('#react_submit_button_'+service_id).length > 0 ){
            var react_button = $('#react_submit_button_'+service_id).html();
        }
        var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
        $.ajax({
            url : "/servicemanagement/addUpdateServices",
            type: "POST",
            dataType:'json',
            data : form_data,
            beforeSend: function () {
                $('#submit_button_'+service_id).html(loading_gif);
                $('#react_submit_button_'+service_id).html(loading_gif);
            },
            success:function(data){
                $('#submit_button_'+service_id).html(submit_button);
                if( $('#react_submit_button_'+service_id).length > 0 ){
                    $('#react_submit_button_'+service_id).html(react_button);
                }
                if(data.status == 'success')
                {
                    console.log(data.action);
                    if( (data.action == 'kit_deactivated') || (data.action == 'service_deactivated') ){
                        $("#service_form_"+service_id).find('input[type="checkbox"]').prop('checked',false);
                        if( data.action == 'kit_deactivated' ){
                            $("#service_form_"+service_id).find('input[type="text"]').val("");
                            $("#service_form_"+service_id).find('select').val("");
                        }


                        if( $('#react_submit_button_'+service_id).length > 0 ){
                            var field = 'service_flag';
                            $("#service_form_"+service_id).find('#'+field).removeAttr('checked');
                            $("#service_form_"+service_id).find('#'+field).val("0");
                            var service_flag_success_html = $("#service_form_"+service_id).find('#hidden_'+field).html();
                            $('#react_submit_button_'+service_id).html(service_flag_success_html);
                            $("#service_form_"+service_id).find('#hidden_'+field).remove();
                        }
                        if( data.action == 'kit_deactivated' ){
                            alert('Kit deactivated successfully');
                        } else if( data.action == 'service_deactivated' ){
                            alert('Service deactivated successfully');
                        }

                    } else {
                        alert(data.msg);
                    }
                    location.reload(true);
                }
                else
                {
                    if(data.action == 'otpsent'){
                        $('#updateServices_'+service_id).find('.panel-body').html('<form class="form-inline" action="javascript:void(0)"><div class="form-group" style="margin-top:10px;"><label for="otp">OTP</label>&nbsp;<input class="form-control" id="otp" name="otp" type="text" /></div><span class="otp-submit-button" id="otp_submit_button_'+service_id+'"><input style="margin-left:10px;" class="btn btn-sm btn-success" onclick="verifyOTP(\''+window.btoa(form_data)+'\','+service_id+')" value="Verify" type="button"></span></form>');
                    }
                    alert(data.description);
                }
            }
        });
    } else {
        // location.reload(); //for reloading form
        return false;
    }
}
function saveDetails(service_id){
    $('#service_form_'+service_id).append('<input type="hidden" name="kit_flag" value="1" />');
    updateService(service_id);
}
function verifyOTP(form_data,service_id){
    var submit_button = $('#otp_submit_button_'+service_id).html();
    var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
    var otp = $.trim($('#otp').val());

    if(otp == ''){
        alert('Please provide OTP');
        return false;
    }
    if(!isNaN(otp)){
        $.ajax({
            url : "/servicemanagement/addUpdateServices",
            type: "POST",
            dataType:'json',
            data : window.atob(form_data)+'&otp='+otp,
            beforeSend: function () {
                $('#otp_submit_button_'+service_id).html(loading_gif);
            },
            success:function(data){
                if(data.status == 'success')
                {
                    alert('Plan activated successfully!');
                    location.reload();
                }
                else
                {
                    $('#otp_submit_button_'+service_id).html(submit_button);
                    alert(data.description);
                }
            }
        });
    } else {
        alert('Invalid OTP! OTP can only have numeric characters.');
        return false;
    }
}
// function reactivateService(service_id,user_id,field){
//     alert('Service reactivation will be done from Recovery panel.');
//     return false;
//     if(confirm('Are you sure you want to reactivate this service ?')){
//         var service_flag_failure_html = $('#react_submit_button_'+service_id).html();

//         var submit_button = $('#submit_button_'+service_id).html();
//         var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
//         $.ajax({
//             url : "/servicemanagement/reactivateService",
//             type: "POST",
//             dataType:'json',
//             data : {user_id:user_id,service_id:service_id,service_flag:1},
//             beforeSend: function () {
//                 $('#submit_button_'+service_id).html(loading_gif);
//                 $('#react_submit_button_'+service_id).html(loading_gif);
//             },
//             success:function(data){
//                 $('#submit_button_'+service_id).html(submit_button);
//                 if(data.status == 'success')
//                 {
//                     $("#service_form_"+service_id).find('#'+field).prop('checked',true);
//                     $("#service_form_"+service_id).find('#'+field).val("1");
//                     var service_flag_success_html = $("#service_form_"+service_id).find('#hidden_'+field).html();
//                     $('#react_submit_button_'+service_id).html(service_flag_success_html);
//                     $("#service_form_"+service_id).find('#hidden_'+field).remove();
// //                    console.log(data.action);
// //                    if(data.action == 'kit_deactivated'){
// //                        $("#service_form_"+service_id).find('input[type="checkbox"]').prop('checked',false);
// //                        $("#service_form_"+service_id).find('input[type="text"]').val("");
// //                        $("#service_form_"+service_id).find('select').val("");
// //                    } else {
//                         alert(data.msg);
// //                    }
//                 }
//                 else
//                 {
//                     $('#react_submit_button_'+service_id).html(service_flag_failure_html);
//                     alert(data.description);
//                 }
//             }
//         });
//     } else {
//         return false;
//     }
// }

function check(checkbox)
{
    var value=(checkbox.checked==true)?1:0;
    $(checkbox).val(value);
    if( value == 1 ){
        $(checkbox).attr('checked','');
        $(checkbox).prop('checked',true);
    }
}

//date for filteration through service activation
$(document).ready(function () {
    $('#activationfrom,#activationto').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });

});

//for deactivating the Mpos service and for showing deactivation button
//    $(document).ready(function(){
    //    var servcId = $('#service_id').val();
    //    if(servcId == '8' && $('#kit_flag').prop("checked")){
    //        $('#mpos_deactive').show();
    //    }
//    });

//for deactivating the Mpos service calling the deactivation function on button click
//  function deactivateService(servId){
//         $('#kit_flag').prop("checked",false);
//         $('#service_flag').prop("checked",false);
//         updateService(servId);
//  }

 function showPlans(service_id){
    $('#plans_modal_'+service_id).modal('toggle');
 }
 function showRefundKitDialogue(service_id){
    $('#refund_kit_modal_'+service_id).modal('toggle');
 }
 function upgradePlan(service_id){
     var new_plan = $('#new_plan_'+service_id).val();
     if(new_plan != ''){

        $('#service_form_'+service_id).find('input[name="payment_mode"]').remove();
        $('#service_form_'+service_id).find('select[name="payment_mode"]').attr('disabled','true');

        $('#service_form_'+service_id).find('input[name="plan"]').remove();
        $('#service_form_'+service_id).find('select[name="plan"]').attr('disabled','true');
        $('#service_form_'+service_id).find('input[name="kit_flag"]').remove();

        $('#service_form_'+service_id).append('<input type="hidden" name="kit_flag" value="1" />');
        $('#service_form_'+service_id).append('<input type="hidden" name="payment_mode" value="2" />');
        $('#service_form_'+service_id).append('<input type="hidden" name="plan" value="'+new_plan+'" />');
        $('#plans_modal_'+service_id).modal('toggle');
        updateService(service_id);
        return false;
     } else {
         alert('Please select plan');
         return false;
     }
 }

 function refundKit(service_id,user_id){
    var amount = $('#refund_kit_modal_'+service_id).find('#refund_amount').val().replace(/[^\d\.]/g, '');

    if(!amount)
    {
        alert('Enter valid amount');
        return false;
    }
    $.ajax({
        url : "/panels/refundAmount",
        type: "POST",
        data : {user_id:user_id,service_id:service_id,refund_amt:amount,referer:"activation_panel"},
        dataType:'json',
        beforeSend: function () {
            $('#refundamt_div').show();
            $("#btnrefundamt").attr("disabled", true);
        },
        success:function(res){
            $('#refundamt_div').hide();
            alert(res.description);
            if(res.status == 'success')
            {
                location.reload();
            }

        }
    });

 }
 function pullbackKit(service_id,user_id){
    $.ajax({
        url : "/servicemanagement/pullbackKit",
        type: "POST",
        data : {user_id:user_id,service_id:service_id},
        dataType:'json',
        beforeSend: function () {
        },
        success:function(res){
            alert(res.description);
            if(res.status == 'success')
            {
                location.reload();
            }
        }
    });

 }
 function requestService(service_id,user_id){
    $.ajax({
        url : "/servicemanagement/requestService",
        type: "POST",
        data : {user_id:user_id,service_id:service_id},
        dataType:'json',
        beforeSend: function () {
        },
        success:function(res){
            alert(res.description);
            if(res.status == 'success')
            {
                location.reload();
            }
        }
    });

 }

function activateService(service_id){
    $('#service_form_'+service_id).find('input[name="kit_flag"]').remove();
    $('#service_form_'+service_id).append('<input type="hidden" name="kit_flag" value="1" />');

    $('#service_form_'+service_id).find('input[name="service_flag"]').remove();
    $('#service_form_'+service_id).append('<input type="hidden" name="service_flag" value="1" />');

    if( service_id == 12 ){
        $('#service_form_'+service_id).append('<input type="hidden" name="vendor" value="0" />');
        $('#service_form_'+service_id).append('<input type="hidden" name="bc_agent" value="" />');
    }

    updateService(service_id);
    return false;
}

function deactivateService(service_id){

    $('#service_form_'+service_id).find('input[name="kit_flag"]').remove();
    $('#service_form_'+service_id).append('<input type="hidden" name="kit_flag" value="1" />');

    $('#service_form_'+service_id).find('input[name="service_flag"]').remove();
    $('#service_form_'+service_id).append('<input type="hidden" name="service_flag" value="0" />');
    updateService(service_id);
    return false;
}
function deactivateKit(service_id){
    $('#service_form_'+service_id).find('input[name="kit_flag"]').remove();
    $('#service_form_'+service_id).append('<input type="hidden" name="kit_flag" value="0" />');
    updateService(service_id);
    return false;
}

 function purchaseKit(service_id){
     var plan = $('#plan_'+service_id).val();
     var payment_mode = $('#payment_mode_'+service_id).val();
     if( (plan != '') && (payment_mode != '') ){

        $('#service_form_'+service_id).find('input[name="payment_mode"]').remove();
        $('#service_form_'+service_id).find('input[name="plan"]').remove();
        $('#service_form_'+service_id).find('input[name="kit_flag"]').remove();
        $('#service_form_'+service_id).find('input[name="service_flag"]').remove();

        $('#service_form_'+service_id).append('<input type="hidden" name="payment_mode" value="'+payment_mode+'" />');
        $('#service_form_'+service_id).append('<input type="hidden" name="plan" value="'+plan+'" />');
        $('#service_form_'+service_id).append('<input type="hidden" name="kit_flag" value="1" />');
        $('#service_form_'+service_id).append('<input type="hidden" name="service_flag" value="4" />');
        $('#plans_modal_'+service_id).modal('toggle');
        updateService(service_id);
        return false;
     } else {
        alert('Please select proper plan and payment mode');
        return false;
     }
 }
