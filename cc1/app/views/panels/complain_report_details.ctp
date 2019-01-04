
<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<?php
$body = '';
$body = "<html>
          <body>
          <table  width=\"70%\" cellpadding = \"0\" cellspacing = \"0\"  border=\"1px;\">
           <tr>
                 <td>Index</td>
                  <td>Tran Id</td>
                  <td>VTransID</td>
                   <td>Vendor</td>
                    <td>Cust Mob</td>
                    <td>Operator</td>
                    <td>Amt</td>
                     <td>Complaint Date</td>
                     <td>Closed by</td>
                     
           </tr>";
           $i=1;
        foreach($transDetails as $key => $value){
               $body.="<tr>
                 <td>$i</td>
                  <td>".$value['vendors_activations']['txn_id']."</td>
                  <td>".$value['vendors_activations']['vendor_refid']."</td>
                  <td>".$value['vendors']['company']."</td>
                  <td>".$value['vendors_activations']['mobile']."</td>
                   <td>".$value['products']['name']."</td>
                   <td>".$value['vendors_activations']['amount']."</td>
                   <td>".$value[0]['complainintime']."</td>
                   <td>".$value['users']['name']."</td>
                   <td></td>
                  </tr>";
                  $i++;
         }

          $body.="</table>
         </body>
          </html>";

         



echo $body;
?>


<script type="text/javascript">

    function setAction() {

        var frmDate = jQuery("#from").val();
        var toDate = jQuery("#to").val();
        window.location.href = "/panels/complainReportDetails/" + frmDate + "/" + toDate;

    }
    
    function showtransDetails(type)
    {
        window.location.href = "/panels/complainReportDetails/" + jQuery("#from").val() + "/" + jQuery("#from").val()+"/"+type;
    }
</script>