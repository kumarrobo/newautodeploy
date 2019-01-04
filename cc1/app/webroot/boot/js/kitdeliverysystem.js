function  EnbUpdDetails(id){    
    $('#updDeliveryBy_' + id).removeAttr("disabled");
    $('#upDeliveryAddr_' + id).removeAttr("disabled");
    $('#upDeviceId_' + id).removeAttr("disabled");
    $('#updDeliveryStatus_' + id).removeAttr("disabled");
    $('#upTrackingDet_' + id).removeAttr("disabled");
    $('#upComment_' + id).removeAttr("disabled");   
    $('#updDelBtn_' + id).removeAttr("disabled");
    $('#updBtn_' + id).prop("disabled", "true");
}

function UpdKitDetails(id,service_id,dist_id,row_no){    
   var deliveryBy       =  $('#updDeliveryBy_' + id).val();
   var deliveryAddr     =  $('#upDeliveryAddr_' + id).val();
   var deviceId         =  $('#upDeviceId_' + id).val();
   var deliveryStatus   =  $('#updDeliveryStatus_' + id).val();
   var deliveryDate     =  $('#upDeliveryDate_'+id).val();
   var trackingDet      =  $('#upTrackingDet_' + id).val();
   var comment          =  $('#upComment_' + id).val();
   var plan             =  $('#upPlans_' + id).val();
   

if(deliveryBy == '1' && deliveryStatus == '1' ){
   getServiceFields(id,service_id,dist_id,row_no); 
   
}else {  
    $.ajax({
        type: "POST",
        url: '/kit_delivery_system/kitDeliveryUpdate',
        dataType: "json",
        data: {id : id, deliveryBy: deliveryBy, deliveryAddr: deliveryAddr,deviceId : deviceId,deliveryStatus : deliveryStatus,
            trackingDet: trackingDet,comment:comment,
            delDate:deliveryDate,requested_plan:plan,service_id:service_id},

        success: function (data) {           
            if(data.status == 'success'){
                alert(data.msg);
                location.reload();
            }else {
                alert(data.description);
            }
        },
        failure: function (data) {
            alert('failed');
        }      
        
  }) 
  }
}

      function getServiceFields(id,service_id,dist_id,row_no){        

        $('.operations-modal').attr('id','operation_field_'+service_id+'_'+row_no);                        
        var kits_template = '';
        var fieldUpdateBtn =  '';
        $.ajax({
        type: "POST",
        url: '/kit_delivery_system/getkitsData',
        dataType: "json",
        data: {id: id,service_id : service_id,dist_id : dist_id},
        success: function (data) {
            var kits_template_head = '<table  class = "table table-bordered" id="kitstb" name="kitstb">  <thead> <th> Slect Plan </th> <th> Plan name  </th> <th> Kits Available  </th></thead><tbody>';
            var flddata = data; 
            var kits_template_body = '';
            
            
            if(flddata.length > 0){                            
            $.each(flddata, function (index,field) {                          
                var tr = '<tr>';
                var kitsallot   = '<input type="radio" id="upfieldkitsallot_'+ field["dk"]["id"] +'" name="upfieldkitsallot" value = '+ field["dk"]["service_plans_id"] + ' />';  
                 fieldUpdateBtn = '<button type="button" class="btn btn-primary" id="upfield_'+ field["dk"]["id"] +'" name="upfield"  onclick="UpdKitsVal('+id+','+service_id+','+ row_no +')">Submit</button>';
                 tr += '<td>' + kitsallot + '</td>';          
                 tr += '<td>' + field["sp"]["plan_name"] + '</td>';  
                 tr += '<td>' + field["dk"]["kits"] + '</td>';           
                 tr +='</tr>';
                 kits_template_body += tr;         
            });           
            $('#operation_field_'+service_id+'_'+row_no).find('.modal-body').html(kits_template+kits_template_head+kits_template_body);
            $('#operation_field_'+service_id+'_'+row_no).find('.modal-footer').html(fieldUpdateBtn)
        } else {
            kits_template += '<h4>Sorry no plan\'s to Map </h4>';
            $('#operation_field_'+service_id+'_'+row_no).find('.modal-body').html(kits_template);
        }
            kits_template += '</tbody>';
                                                     
            $('#operation_field_'+service_id+'_'+row_no).find('.modal-dialog').css({"width":"1020px","height":"2000px"});
            $('#operation_field_'+service_id+'_'+row_no).find('.modal-dialog').css({"width":"1020px","height":"2000px"});
            $('#operation_field_'+service_id+'_'+row_no).modal('toggle');
        },
        failure: function (data) {
            alert('failed');
        }
    });                          
    }

function UpdKitsVal(id,service_id,row_no){
   var deliveryBy       =  $('#updDeliveryBy_' + id).val();
   var deliveryAddr     =  $('#upDeliveryAddr_' + id).val();
   var deviceId         =  $('#upDeviceId_' + id).val();
   var deliveryStatus   =  $('#updDeliveryStatus_' + id).val();
   var deliveryDate     =  $('#upDeliveryDate_'+id).val();
   var trackingDet      =  $('#upTrackingDet_' + id).val();
   var comment          =  $('#upComment_' + id).val();
   var plan             =  $('#upPlans_' + id).val();
   var dist_id          =  $('#upDist_id_' + id).val();
   var dist_user_id     =  $('#upDist_userid_' + id).val();
   var val              =  $('input[name="upfieldkitsallot"]:checked').val();      
   
    $.ajax({
        type: "POST",
        url: '/kit_delivery_system/kitDeliveryUpdate',
        dataType: "json",
        data: {id : id, deliveryBy: deliveryBy, deliveryAddr: deliveryAddr,deviceId : deviceId,
            deliveryStatus : deliveryStatus,trackingDet: trackingDet,comment:comment,
            deliveryDate:deliveryDate,selected_plan:val,requested_plan:plan,
            service_id:service_id,dist_id:dist_id,dist_user_id : dist_user_id},

        success: function (data) {
                        
            if(data.status == 'success'){
                alert(data.msg);
                location.reload();
            }else {
                alert(data.description);
            }
        },
        failure: function (data) {
            alert('failed');
        }      
        
  }) 
 
 
}

