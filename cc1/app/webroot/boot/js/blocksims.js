        function checkstate(id)
        {            
            if($('input#blockh_'+id).length)
            {
                console.log('input#blockh_'+id);
                $('input#blockh_'+id).remove();
            }
            else
            {
            $('<input/>', {
            'id':'blockh_'+id,
            'type':'hidden',
            'value':id
            }).insertAfter('#block_'+id);
            }
        }

        function showmodal(id)
        {
            var oprId = $("#opr_" + id).val();
            var mobile = $("#mobile_" + id).val();
            var block=$("#block_"+id).is(':checked');
            var ischecked=(block==true)?1:0;
            var simid = $("#sim_" + id).html();
            var Vendorid = $("#vendor_id").val();
            var bal = $("#bal_" + id).html();
            var inv_supplier_id=$("select[name=inv_supplier_id_"+id+"]").val();
            var blocktag = $("select[name=blocktag_"+id+"]").val();
            $.ajax({
            url: '/sims/checkBlocksimStatus/',
            type: "POST",
            data: {mobile: mobile,Vendorid:Vendorid,operator: oprId},
            dataType: "json",
            success: function(data) {
                console.log(data);
                 if(data.status=="success")
                 {
                    $('#opr_id').val(oprId);
                    $('#vendor_id').val(Vendorid);
                    $('#mobileno').val(mobile);
                    $('#balance').val(bal);
                    $('#ischecked').val(ischecked);
                     $("#sim_id").val(simid);
                     $("#inv_supplier_id").val(inv_supplier_id);
                     $("#blocktag").val(blocktag);
                     $("#blocksimsModal").modal("show");
                 }
                 else if(data.status=="failure")
                 {
                     checkblockstate(id);
//                    return false;
                 }
                    
            }
            });
        }
        
        function checkblockstate(id)
        {
         var oprId = $("#opr_" + id).val();
         var mobile = $("#mobile_" + id).val();
         var block=$("#block_"+id).is(':checked');
         var ischecked=(block==true)?1:0;
         var simid = $("#sim_" + id).html();
         var Vendorid = $("#vendor_id").val();
         var bal = $("#bal_" + id).html();
         var inv_supplier_id=$("select[name=inv_supplier_id_"+id+"]").val();
         var blocktag_id=$("select[name=blocktag_"+id+"]").val();
         
         var alldata = {id: id,
            operator: oprId,
            mobile: mobile,
            balance: bal,
            simid: simid,
            Vendorid: Vendorid,
            inv_supplier_id:inv_supplier_id,
            block: ischecked,
            blocktag_id:blocktag_id
        };       
                
        $.ajax({
            url: '/sims/addBlockSimsData/',
            type: "POST",
            data: alldata,
            dataType: "json",
            success: function(data) {
                 alert(data.msg);

            }
        });
        }
        
        function ResetSimStatus()
        {
            var mobile=$("#mobileno").val();
            var oprId=$("#opr_id").val();
            var Vendorid=$("#vendor_id").val();
            $.ajax({
            url: '/sims/resetSimStatus/',
            type: "POST",
            data: {mobile: mobile,Vendorid:Vendorid,operator: oprId},
            dataType: "json",
            success: function(data) {
                 alert(data.msg);
                $('#blocksimsModal').modal('hide');
            } 
        });
        }
        
        function AddNewData()
        {
            var simid=$("#sim_id").val();
            var oprId=$('#opr_id').val();
            var Vendorid=$('#vendor_id').val();
            var mobile=$('#mobileno').val();
            var bal= $('#balance').val();
            var ischecked=$('#ischecked').val();
            var inv_supplier_id=$('#inv_supplier_id').val();
            var blocktag=$('#blocktag').val();
            
            var alldata = {
            operator: oprId,
            mobile: mobile,
            balance: bal,
            simid: simid,
            Vendorid: Vendorid,
            inv_supplier_id:inv_supplier_id,
            block: ischecked,
            blocktag_id: blocktag
            };
            
            $.ajax({
            url: '/sims/addNewBlockSimsData/',
            type: "POST",
            data: alldata,
            dataType: "json",
            success: function(data) {
                 alert(data.msg);
                $('#blocksimsModal').modal('hide');
            }
        });
        }