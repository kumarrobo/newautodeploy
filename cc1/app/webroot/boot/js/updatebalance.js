$(document).ready(function(){
    
      $('#editbalancemodal').on('show.bs.modal', function(e) {
          
            var oldbalance = $(e.relatedTarget).data('balance');
            var vendorid = $(e.relatedTarget).data('vendorid');
            var parbal = $(e.relatedTarget).data('parbal');
            var mobile = $(e.relatedTarget).data('mobile');
            var simid = $(e.relatedTarget).data('simid');
     
           
            $(e.currentTarget).find('span.oldbalancelabel').text(oldbalance);
            $(e.currentTarget).find('input#inp_oldbalance').val(oldbalance);
            
             $(e.currentTarget).find('input#inpbalance_vendorid').val(vendorid);
             $(e.currentTarget).find('input#inpbalance_parbal').val(parbal);
             $(e.currentTarget).find('input#inpbalance_mobile').val(mobile);
             $(e.currentTarget).find('input#inpbalance_simid').val(simid);
             
              $('button#updatebalancebtn').removeClass('disabled');
      });
      
       $('button#updatebalancebtn').on('click',function(){
           
             if($.trim($('input#txt_balance').val())==""){
                  alert("Invalid Balance");
                  return;
             }
        
        var oldbalance=$('#editbalancemodal input#inp_oldbalance').val();
        var parbal=$('#editbalancemodal input#inpbalance_parbal').val();
        var vendorid=$('#editbalancemodal input#inpbalance_vendorid').val();
        var mobile=$('#editbalancemodal input#inpbalance_mobile').val();
        var simid=$('#editbalancemodal input#inpbalance_simid').val();
        var newbalance=$('#editbalancemodal input#txt_balance').val();
        
        $('#updateBalanceloadingbar').show();
        $('button#updatebalancebtn').addClass('disabled');
         
       var dataString={oldbalance:oldbalance,parbal:parbal,vendor_id:vendorid,mobile:mobile,newbalance:newbalance};
     
      
       var updateBalance=$.post(HOST+'sims/updateBalance',dataString);
       
       updateBalance.done(function(res){
             
            res=$.parseJSON(res);
            
             if(res.status=="success")
            {
                    if(res.data=="Balance Update Success")
                    {
                        $('td#cur_'+simid+'_'+vendorid).find('span').text(newbalance);
                        $('td#cur_'+simid+'_'+vendorid).find('a').attr('data-balance',newbalance);
                    }
             
               alert(res.data);
               
            }
            else
            {
                alert('Oops Error : '+res.data);
            }
            
            $('#updateBalanceloadingbar').hide();
            $('button#updatebalancebtn').removeClass('disabled');
            $('#editbalancemodal').modal('hide');
            
       });
        
        
        
       });
});

