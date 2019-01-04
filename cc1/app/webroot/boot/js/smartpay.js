var HOST= "https://"+window.location.hostname+"/";
$(document).ready(function()
{
    $('#sandbox-container .input-daterange').datepicker({
    format: "yyyy-mm-dd",
    startDate: "-365d",
    endDate: "1d",
    todayHighlight: true,
     orientation: "top right"
   });

  $('button#btnsavedocs').on('click',function(){
      var pay1_status=$('select[name=pay1_status]').val();
      var pay1_comment=$.trim($('#pay1_comment').val());
      var bank_status=$('select[name=bank_status]').val();
      var bank_comment=$.trim($('#bank_comment').val());

      if(pay1_status==2)
      {
          if(pay1_comment=="")
          {
              alert("Please enter comment from pay1 side");
              return false;
          }
      }
      if(bank_status==2)
      {
          if(bank_comment=="")
          {
              alert("Please enter comment from bank side");
              return false;
          }
      }
  });

  $('button#btndevicecomment').on('click',function(){
      var serviceid=$('#service_id').val();
      var deviceid=$.trim($('#device_id').val());
      var userid=$('#user_id').val();

      if(deviceid=="")
      {
//          alert("Enter valid device id");
//          return false;
      }

      var servicecomments=$.post(HOST+'smartpay/saveDeviceComments',{service_id:serviceid,device_id:deviceid,user_id:userid});

      servicecomments.done(function(res){
          res=$.parseJSON(res);
          if(res.status=='failure'){
              alert(res.description);
          }
          else
          {
              alert(res.msg);
          }
          $('#servicecommentmdl').modal('hide');
      });
  });

  $('button#btncspcomment').on('click',function(){
      var serviceid=$('#service_id').val();
      var cspid=$.trim($('#csp_id').val());
      var csp_pass=$.trim($('#csp_pass').val());
      var userid=$('#user_id').val();

      if(cspid=="")
      {
          alert("Enter valid CSR Id");
          return false;
      }
      if(csp_pass=="")
      {
          alert("Enter valid CSR password");
          return false;
      }

      var servicecomments=$.post(HOST+'smartpay/saveCspComments',{service_id:serviceid,csp_id:cspid,csp_pass:csp_pass,user_id:userid});

      servicecomments.done(function(res){
          res=$.parseJSON(res);
          if(res.status=='failure'){
              alert(res.description);
          }
          else
          {
              alert(res.msg);
          }
          $('#cspcommentmdl').modal('hide');
      });
  });

  $('button#btntidcomment').on('click',function(){

      var serviceid=$('#service_id').val();
      var tid=$.trim($('#tid').val());
      var userid=$('#user_id').val();

      if(tid=="")
      {
          alert("Enter valid TID");
          return false;
      }

      var servicecomments=$.post(HOST+'smartpay/saveTIDComments',{service_id:serviceid,tid:tid,user_id:userid});
      servicecomments.done(function(res){
          res=$.parseJSON(res);
          if(res.status=='failure'){
              alert(res.description);
          }
          else
          {
              alert(res.msg);
          }

          $('#tidcommentmdl').modal('hide');
      });
  });

$('button#btnsavesettlements').on('click',function(){
    var txn_id=$('#txn_id').val();
    var utr_id=$('#utr_id').val();
    var utr_date=$('.utr_date').val();
    var utr_comments=$('#utr_comments').val();

    var settlementDetails=$.post(HOST+'smartpay/saveSettlementComments',{txn_id:txn_id,utr_id:utr_id,utr_date:utr_date,utr_comments:utr_comments});

    settlementDetails.done(function(res){
          res=$.parseJSON(res);
          if(res.status=='failure'){
              alert(res.description);
          }
          else
          {
              $('#utr_button_'+txn_id).html('');
              $('#utr_id_'+txn_id).text(utr_id);
              $('#utr_date_'+txn_id).text(utr_date);
              $('#utr_comments_'+txn_id).text(utr_comments);
              $('#settlement_status_'+txn_id).text('B - Settled');
              $('#settled_at_'+txn_id).text(res.settled_at);
              alert(res.msg);
          }
          $('#settlementModal').modal('hide');
      });
});

$('#select_all').on('click',function(){
    $('input[type=checkbox][name="chktxn"]').prop('checked', ($(this).val() == 'Check'));
  // Change caption of this button
  $(this).val( ($(this).val() == 'Check' ? 'Uncheck' : 'Check') );

    });

$('button#btndownload').on('click',function(){
        $("#download").val('download');
        $('#settlementForm').submit();
    });
$('button#btndisputedownload').on('click',function(){
    $("#download").val('dispute');
    $('#settlementForm').submit();
});

$('button#btntxndownload').on('click',function(){
    var idsarray=[];
    var from_bank=$('input[type=radio][name="frombank"]:checked').val();
    if(from_bank=='' || typeof from_bank=='undefined'){ alert("Error : Please select the bank");return;}
    var selected_ids=$('input[type=checkbox][name="chktxn"]:checked').map(function(){
         idsarray.push($(this).val());
         return $(this).val();
    }).get().join(',');

    if(selected_ids!=""){
                        $("#download").val('txndownload');
                        $('#txn_ids').val(selected_ids);
                        $('#from_bank').val(from_bank);
                        $('#settlementForm').submit();
                    }
                    else
                    {
                        alert("Nothing to download");
                        return false;
                    }
    });
});

function checkKitFlag(flag,id)
     {
         var kit_flag=(flag.checked==true)?1:0;
         $("input[type=hidden][name='services["+id+"][kit_flag]']").val(kit_flag);
     }

function checkServiceFlag(flag,id)
   {
        var service_flag=(flag.checked==true)?1:0;
        $("input[type=hidden][name='services["+id+"][service_flag]']").val(service_flag);
   }
function checkDocStatus(flag,id)
   {
        var status=(flag.checked==true)?1:0;
        var doc_array=['pan','aadhar','photo'];
        var doc_label=($.inArray(id,doc_array) !== -1)?id+"_status":id+"_form_status";
        $("input[type=hidden][name='"+doc_label+"']").val(status);
   }
   function saveDeviceMapping(id,devid)
   {
       $('#service_id').val(id);
       $('#device_id').val(devid);
       $('#servicecommentmdl').modal('show');
   }
   function saveCspMapping(id,cspid,csp_pass)
   {
       $('#service_id').val(id);
       $('#csp_id').val(cspid);
       $('#csp_pass').val(csp_pass);
       $('#cspcommentmdl').modal('show');
   }
   function saveTIDMapping(id,tid)
   {
       $('#service_id').val(id);
       $('#tid').val(tid);
       $('#tidcommentmdl').modal('show');
   }
   function saveUtrMapping(id,utr_id,utr_date,utr_comments)
   {
       $('#txn_id').val(id);
        $('#utr_id').val(utr_id);
        $('.utr_date').val(utr_date);
        $('#utr_comments').val(utr_comments);
       $('#settlementModal').modal('show');
       $('.utr_date').datepicker({
        format: "yyyy-mm-dd",
        startDate: "-365d",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true,
        orientation: "top right"
        });
   }

   function searchTxn()
   {
       $("#download").val('');
       $('#settlementForm').submit();
   }

