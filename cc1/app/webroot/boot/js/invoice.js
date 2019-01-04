$(document).ready(function() 
{
    $('#fromDate, #toDate').datepicker({
        format: "yyyy-mm-dd",
        //startDate: "-365d",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    }); 
});

function setAction()
{
    $("#download").val('');
    $("#invoicehistory").submit();
}

function exportdata(){
         $("#download").val('download');
         $("#invoicehistory").submit();
}

function exportPdfData(){
         $("#download").val('pdfdownload');
         $("#invoicehistory").submit();
}

function getInvoiceList()
{
    $("#download").val('');
    $("#newinvoicehistory").submit(); 
}

function downloadZip()
{
    $("#download").val('download');
    $("#newinvoicehistory").submit();    
}

function getGstReport()
{
    $("#download_gst").val('');
    $("#gstReport").submit(); 
}

function downloadGSTReport()
{
    $("#download_gst").val('download');
    $("#gstReport").submit();    
}

function downloadMonthlyReport()
{
    $("#download_gst").val('monthlyreport');
    $("#gstReport").submit();    
}

function getTDSReport()
{
    $("#download_tds").val('');
    $("#tdsReport").submit(); 
}

function downloadTDSReport()
{
    $("#download_tds").val('download');
    $("#tdsReport").submit();    
}

function mailcheck(user_id,invoice_id,month,year,type,loader)
{
   var loader_html = (loader==1)?$('#btnsendmail'):$('#sendmail_link_'+invoice_id);
   var btnsendmail = (loader==1)?$('#btnsendmail').html():$('#sendmail_link_'+invoice_id).html();
   var loading_gif = '<img src="/img/ajax-loader-2.gif"></img> loading..';
   $.ajax({
       type : "POST",
       url  : '/shops/getNewInvoice',
       dataType : "json",
       data:{user_id:user_id,invoice_id:invoice_id,month:month,year:year,type:type},
       beforeSend : function () {
                    loader_html.html(loading_gif);
                },
       success : function(res){
           loader_html.html(btnsendmail);
           if(res.status=='success' && res.type == 0)
           {
               alert(res.msg);
               return false;
           } 
           else if(res.status=='failure' && res.type == 1) 
           {
               var emailid = prompt("Please enter your email id:","");
               if(emailid.length !== 0)
               {
                    if(!isValidEmailAddress(emailid)) 
                    {
                        alert('Please enter valid email address');
                        return false;
                    }
                    else
                    {
                        $.ajax({
                                type : "POST",
                                url  : '/shops/getNewInvoice',
                                dataType : "json",
                                data:{user_id:user_id,invoice_id:invoice_id,month:month,year:year,type:type,email_id:emailid},
                                success : function(res){
                                    alert(res.msg);
                                }
                               });  
                    }  
                }
               else
               {
                   alert('Please enter valid email address');
                   return false;
               }
            }
            else if(res.status=='failure' && res.type == 2)
            {
                alert(res.msg);
                return false;
            }
//            loader_html.html(btnsendmail);
        },

       error:function(){
           alert('Error');
       }
   })
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return pattern.test(emailAddress);
};
