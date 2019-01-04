$(document).ready(function () {
    $('#pan_serviceFrom, #pan_serviceTo').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
    $('.table').dataTable({
        // "order": [[0, "desc" ]],
        "pageLength": 50,
        "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500, 'All']],
    });
    $('#transval').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true
    });
        
    });
    
  function AllotCoupon(id,count){
    var agent = $('#agent_id_'+ id).val();      
    if (confirm('Are you sure you want to allot '+ count + ' coupons to Agent : ' + agent + ' ? '  )) {
           
        var status = 1;
        $.ajax({
        type: "POST",
        url: '/pan_services/panCouponProccess',
        dataType: "json",
        data: {id : id, status : status},
        success: function (data) {            
            if(data.status == 'success'){
                alert(data.description);
            }else{
                alert(data.description);
            }
      //      location.reload();
        },
        failure: function (data) {
            alert('Something Went Wrong');
        }              
            
    });
   }   
  }

  function RefundCoupon(id,count){
    var agent = $('#agent_id_'+ id).val();    
      if (confirm('Are you sure you want to refund '+ count + ' coupons to Agent : ' + agent + ' ? '  )) {
           
        var status = 2;
        $.ajax({
        type: "POST",
        url: '/pan_services/panCouponProccess',
        dataType: "json",
        data: {id : id, status : status},
        success: function (data) {            
            if(data.status == 'success'){
                alert(data.description);
            }else{
                alert(data.description);
            }
            location.reload();

        },
        failure: function (data) {
            alert('Something Went Wrong');
        }              
            
    });
   }   
  }
  
    function EnbUpdDetails(id){        
        $('#upComment_'+id).removeAttr('disabled');
        $('#updBtn_'+id).attr('disabled',true);
        $('#updDetBtn_'+id).removeAttr('disabled');
    }
    
    function UpdDetails(id){
        
        var comment = $('#upComment_'+id).val();
        $.ajax({
        type: "POST",
        url: '/pan_services/panDetailUpdate',
        dataType: "json",
        data: {id : id, comment : comment},
        success: function (data) {            
            if(data.status == 'success'){
                alert(data.msg);
            }else{
                alert(data.description);
            }
            location.reload();

        },
        failure: function (data) {
            alert('Something Went Wrong');
        }              
            
    }); 
}
 function leadStatus(id,status){     
    if (confirm('Are you sure you want to update the status  ? '  )) {
                   
        $.ajax({
        type: "POST",
        url: '/pan_services/leadProccess',
        dataType: "json",
        data: {id : id, status : status},
        success: function (data) {            
            if(data.status == 'success'){
                alert(data.description);
            }else{
                alert(data.description);
            }
            location.reload();
        },
        failure: function (data) {
            alert('Something Went Wrong');
        }              
            
    });
   }   
 }
 
   function EnbUpdlDetails(id){        
       $('#updStatus_'+id).removeAttr('disabled');
        $('#upComment_'+id).removeAttr('disabled');
        $('#updBtn_'+id).attr('disabled',true);
        $('#updDetBtn_'+id).removeAttr('disabled');
    }
    
    function UpdlDetails(id){
        
        var comment = $('#upComment_'+id).val();
        $.ajax({
        type: "POST",
        url: '/pan_services/leadDetailUpdate',
        dataType: "json",
        data: {id : id, comment : comment},
        success: function (data) {            
            if(data.status == 'success'){
                alert(data.msg);
            }else{
                alert(data.description);
            }
            location.reload();

        },
        failure: function (data) {
            alert('Something Went Wrong');
        }              
            
    }); 
}