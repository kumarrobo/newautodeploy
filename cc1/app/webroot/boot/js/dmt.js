//DMT Toggle Form
   function setImpsToogle(id, vid, type) {
    var toggle_show = 0;
    if ($('#serviceTog_IMPSShowFlag_' + id).is(":checked")) {
        toggle_show = 1;
    }
    $.ajax({
        type: "POST",
        url: '/dmt/serviceToggle',
        dataType: "json",
        data: {toggle_show: toggle_show, toggle_type: type, toggle_vendor: vid},
        success: function (data) {
            if (data.status == 'success') {
                alert(data.description);
            } else {
                alert(data.description);
            }
            location.reload();
        },
        failure: function (data) {
            alert('Something Went Wrong');
        }
    });
  }

   function setNeftToogle(id, vid, type) {
    var toggle_show = 0;
    if ($('#serviceTog_NEFTShowFlag_' + id).is(":checked")) {
        toggle_show = 1;
    }
    $.ajax({
        type    : "POST",
        url     : '/dmt/serviceToggle',
        dataType: "json",
        data    : {toggle_show: toggle_show, toggle_type: type, toggle_vendor: vid},
        success : function (data) {
            if(data.status == 'success') {
                alert(data.description);
            } else {
                alert(data.description);
            }
                location.reload();
        },
        failure: function (data) {
            alert('Something Went Wrong');
        }
    });
   }




//Notification Panel

        function setNotification(){         
         var from       = $('#notf_From').val();
         var to         = $('#notf_To').val();
         var fromt       = $('#notf_ftime').val();
         var tot         = $('#notf_ttime').val();
         var vendor     = $('#notf_Vendor').val();
         var priority   = $('#notf_Priority').val();
         var message    = $('#notf_Message').val();
         var plan       = $('#notf_Plan').val();
         
                  
         $.ajax({
                    type: "POST",
                    url: '/dmt/dmtAdminPanel',
                    dataType: "json",
                    data: {from : from, to : to ,vendor : vendor ,priority : priority ,message : message,fromt:fromt,tot:tot,plan:plan},
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
        
        function notfEnable(id){            
            $('#upnotf_From_'+id).removeAttr('disabled');
            $('#upnotf_To_'+id).removeAttr('disabled');
            $('#upnotf_Priority_'+id).removeAttr('disabled');
            $('#upnotf_Vendor_'+id).removeAttr('disabled');
            $('#upnotf_Message_'+id).removeAttr('disabled');  
            $('#upnotf_ShowFlag_'+id).removeAttr('disabled'); 
            $('#upnotf_Plan_'+id).removeAttr('disabled'); 
            $('#upnotf_'+id).removeAttr('disabled');            
            $('#upnotenb_'+id).attr('disabled','true');            
        }
        
        function notfUpdate(id){
            var flag      = 0;
            var from      = $('#upnotf_From_'+id).val();
            var to        = $('#upnotf_To_'+id).val();
            var priority  = $('#upnotf_Priority_'+id).val();
            var vendor    = $('#upnotf_Vendor_'+id).val();
            var message   = $('#upnotf_Message_'+id).val();           
            var plan      = $('#upnotf_Plan_'+id).val();
            if($('#upnotf_ShowFlag_'+id).is(":checked"))
            {
            flag = 1;
            }                    
            $.ajax({
                    type: "POST",
                    url: '/dmt/dmtUpdateNotification',
                    dataType: "json",
                    data: {id:id,from : from, to : to ,vendor : vendor ,priority : priority ,message : message,flag : flag,plan : plan},
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
        
   //Notification Panel end's here     
   
   
   
   
   // When the document is ready
$(document).ready(function () {
    $('#dmt_from, #dmt_till').datepicker({
        format: "yyyy-mm-dd",
        //startDate: "-365d",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
});
