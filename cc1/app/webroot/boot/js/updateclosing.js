$(document).ready(function(){
    
    $('#editclosingmodal').on('show.bs.modal', function(e) {
        
         var device_id = $(e.relatedTarget).data('deviceid');
         var old_closing = $(e.relatedTarget).attr('data-closing');
         var vendor_id = $(e.relatedTarget).data('vendorid');
         var mobile = $(e.relatedTarget).data('mobile');
         
         $(e.currentTarget).find('input#inp_device_id').val(device_id);
         
         $(e.currentTarget).find('input#inp_date').val(getUrlParameter('searchbydate'));
         
         $(e.currentTarget).find('input#inp_oldclosing').val(old_closing);
         
         $(e.currentTarget).find('input#inp_vendorid').val(vendor_id);
         
         $(e.currentTarget).find('input#inp_mobile').val(mobile);
           
         $(e.currentTarget).find('span.oldclosinglabel').text(old_closing);
         
          $(e.currentTarget).find('input#txt_closing').val('');
          
          $(e.currentTarget).find('input#txt_closing').ForceNumericOnly();
          
          $('button#updateclosingbtn').removeClass('disabled');
    });
    
    
    $('button#updateclosingbtn').on('click',function(){
    
        if($('input#txt_closing').val()==""){
            alert("Invalid Closing");
            return;
        }
        
        $('#updateClosingloadingbar').show();
        $('button#updateclosingbtn').addClass('disabled');
        
        var oldclosing=$('input#inp_oldclosing').val();
        var closing=$('input#txt_closing').val();
        var device_id=$('input#inp_device_id').val();
        var date=$('input#inp_date').val();
        var vendorid=$('input#inp_vendorid').val();
        var mobile=$('input#inp_mobile').val();
        
        var closingajax=$.post(HOST+'sims/updateClosing',{device_id:device_id,old_closing:oldclosing,closing:closing,date:date,vendor_id:vendorid,mobile:mobile});
        
        closingajax.done(function(res){
            
            res=$.parseJSON(res);
            
            if(res.status=="success")
            {
                    if(res.data=="Closing Updated Successfully" || res.data=="Closing inserted Successfully")
                    {
                        $('td#closing_'+device_id+'_'+vendorid).find('span').text(closing);
                        $('td#closing_'+device_id+'_'+vendorid).find('a').attr('data-closing',closing);
                    }
             
               alert(res.data);
               
            }
            else
            {
                alert('Oops Error : '+res.data);
            }
            
            $('#updateClosingloadingbar').hide();
            $('button#updateclosingbtn').removeClass('disabled');
            $('#editclosingmodal').modal('hide');
            
        });
        
    });
    
    
});

