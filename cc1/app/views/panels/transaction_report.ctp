
<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<?php
$body = '';
$body = "<html>
         <body>
         From Date:<input type='text' style='height:20px;'name='from' id='from'  onmouseover='fnInitCalendar(this, \"from\",\"close=true\")' value=\"$fromDate\">
         To Date:<input type='text' style='height:20px;'name='to' id='to'  onmouseover='fnInitCalendar(this, \"to\",\"close=true\")' value=\"$toDate\">
         <input type=\"button\" value=\"Submit\" onclick=\"setAction()\"/>
          <br/><br/>
          <table  width=\"100%\" cellpadding = \"0\" cellspacing = \"0\">
           <tr>
             <td width=\"20%\" valign=\"top\">
              <table border= \"2px;\" cellpadding = \"0\" cellspacing = \"0\">
              <tr>
             <td bgcolor=\"#DBEB23\"><b>Total</b></td>
             <td bgcolor=\"#DBEB23\"><b>Open</b></td>
             <td bgcolor=\"#DBEB23\"><b>Closed</b></td>
            </tr>
             <tr>
             <td align='center'>$totalComplaint</td>
             <td align='center'>$totalOpen</td>
             <td align='center'>$totalClosed</td>
            </tr>
             </table>
             <br/><br/>";
        if (count($dataset['user']) > 0) {
            $body.="<table border= \"2px;\" cellpadding = \"0\" cellspacing = \"0\">
                      <tr>
                     <td bgcolor=\"#DBEB23\"><b>Agent Name</b></td>
                     <td bgcolor=\"#DBEB23\"><b>Manually</b></td>
                    </tr>";
            foreach ($dataset['user'] as $userkey => $userval) {
                $body.="<tr>
                     <td>" . $userval[0]['name'] . "</td>
                     <td align='center'>" . $userval[0]['closed'] . "</td>
                    </tr>";
            }
            $body.="</table>";
        }
       $body.="<table border= \"1px;\" cellpadding = \"0\" cellspacing = \"0\"><tr><td colspan=\"3\" bgcolor=\"#DBEB23\" align='center'><b>Refunded Transaction</b></td></tr>";
                $body.="<tr><td bgcolor=\"#DBEB23\"><b>Manually Reversed</b></td><td colspan=\"2\" bgcolor=\"#DBEB23\"><b>Auto/System Reversed</b></td>";
                $body.="</tr><tr><td align='center'>$totalManualReversed</td><td colspan=\"2\" align='center'>$totalAutoReversed</td>";
                $body.="</tr>";
                $body.="</table>";
        
        $body.="</td>";
               if(count($dataset['vendor'])>0){
                      $body.="<td width=\"20%\" valign=\"top\">
                      <table border= \"1px;\" cellpadding = \"0\" cellspacing = \"0\">
                      <tr><td colspan=\"4\" bgcolor=\"#DBEB23\"><b>Vendor Wise Complaint Status</b></td></tr>
                     <tr><td bgcolor=\"#DBEB23\"><b>VendorName</b></td><td bgcolor=\"#DBEB23\"align='center'><b>Open</b></td><td align='center' bgcolor=\"#DBEB23\"><b>Closed</b></td><td align='center' bgcolor=\"#DBEB23\"><b>Re-Open</b></td></tr>";
        foreach ($dataset['vendor'] as $key => $val) {
            $body.="<tr><td>" . $val[0]['name'] . "</td><td align='center'>" . $val[0]['open'] . "</td><td align='center'>" . $val[0]['closed'] . "</td><td align='center'>" . $val[0]['reopen'] . "</td></tr>";
        }
        $body.="</table></td>";
               }
        if(count($dataset['product'])>0){
        $body.="<td width=\"20%\" valign=\"top\">
                      <table  border= \"1px;\" cellpadding = \"0\" cellspacing = \"0\">
                       <tr><td colspan=\"4\" bgcolor=\"#DBEB23\"><b>Operator Wise Complaint status</b></td></tr>
                       <tr><td bgcolor=\"#DBEB23\"><b>Operator Name</b></td><td bgcolor=\"#DBEB23\"><b>Open</b></td><td bgcolor=\"#DBEB23\"><b>Closed</b></td><td bgcolor=\"#DBEB23\"><b>Re-Open</b></td></tr>";
        foreach ($dataset['product'] as $prodkey => $prodval) {
            $body.="<tr><td>" . $prodval[0]['name'] . "</td><td align='center'>" . $prodval[0]['open'] . "</td><td align='center'>" . $prodval[0]['closed'] . "</td><td align='center'>" . $prodval[0]['reopen'] . "</td></tr>";
        }
        $body.="</table>
                </td>";
                }
        if (count($dataset['hour']) > 0) {
            $body.="<td width=\"20%\" valign=\"top\">
                      <table  border= \"1px;\" cellpadding = \"0\" cellspacing = \"0\">
                       <tr><td colspan=\"4\" bgcolor=\"#DBEB23\"><b>Total Resolved Transaction  Hour wise</b></td></tr>
                       <tr><td colspan=\"2\">Between 1 hour</td><td colspan=\"2\">" . $dataset['hour'][0] . "</td></tr>
                       <tr><td colspan=\"2\">Between 2 hour</td><td colspan=\"2\">" . $dataset['hour'][1] . "</td></tr>
                       <tr><td colspan=\"2\">Between 3 hour</td><td colspan=\"2\">" . $dataset['hour'][2] . "</td></tr>
                       <tr><td colspan=\"2\">Between 4 hour</td><td colspan=\"2\">" . $dataset['hour'][3] . "</td></tr>
                       <tr><td colspan=\"2\">Between 5 hour</td><td colspan=\"2\">" . $dataset['hour'][4] . "</td></tr>
                       <tr><td colspan=\"2\">Between 6 hour</td><td colspan=\"2\">" . $dataset['hour'][5] . "</td></tr>
                       <tr><td colspan=\"2\">Between 7 hour</td><td colspan=\"2\">" . $dataset['hour'][6] . "</td></tr>
                       <tr><td colspan=\"2\">Between 8 hour</td><td colspan=\"2\">" . $dataset['hour'][7] . "</td></tr>
                       <tr><td colspan=\"2\">Between 9 hour</td><td colspan=\"2\">" . $dataset['hour'][8] . "</td></tr>";
            $body.="</table></td>";
        }

        if (count($dataset['days']) > 0) {
            $body.="<td width=\"25%\" valign=\"top\">
                      <table  border= \"1px;\" cellpadding = \"0\" cellspacing = \"0\">
                       <tr><td colspan=\"4\" bgcolor=\"#DBEB23\"><b>Overall Close Transaction Day Wise</b></td></tr>
                       <tr><td colspan=\"2\">6 Days Pending</td><td colspan=\"2\">" . $dataset['days'][6] . "</td></tr>
                       <tr><td colspan=\"2\">5 Days Pending</td><td colspan=\"2\">" . $dataset['days'][5] . "</td></tr>
                       <tr><td colspan=\"2\">4 Days Pending</td><td colspan=\"2\">" . $dataset['days'][4] . "</td></tr>
                       <tr><td colspan=\"2\">3 Days Pending</td><td colspan=\"2\">" . $dataset['days'][3] . "</td></tr>
                       <tr><td colspan=\"2\">2 Days Pending</td><td colspan=\"2\">" . $dataset['days'][2] . "</td></tr>
                       <tr><td colspan=\"2\">1 Days Pending</td><td colspan=\"2\">" . $dataset['days'][1] . "</td></tr>
                       <tr><td colspan=\"2\">Same Days</td><td colspan=\"2\">" . $dataset['days'][0] . "</td></tr> ";
            $body.="</table></td>";
        }

           $body.="</tr>
              </table>";


$body.="</body></html>";



echo $body;
?>


<script type="text/javascript">

    function setAction() {

        var frmDate = jQuery("#from").val();
        var toDate = jQuery("#from").val();
        window.location.href = "/panels/transactionReport/" + frmDate + "/" + toDate;

    }
</script>