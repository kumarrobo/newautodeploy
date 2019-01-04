<?php 
require_once APP.vendors.DS.tcpdf.DS.'tcpdf.php';
//require_once ROOT.DS.cake.DS.libs.DS.controller.DS.components.DS.'email.php';
require_once APP.vendors.DS.phpmailer.DS.'class.phpmailer.php';
//require_once('/var/www/html/cc.pay1.com/cake/libs/controller/components/email.php');

$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->AddPage();

$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(190, 4, 'TAX INVOICE', 1, 1, 'C');

$invoiceid = $invoice['data']['invoice_id'];
$date=$invoice['data']['invoice_date'];
$month = str_pad($invoice['data']['month'],2, "0", STR_PAD_LEFT);
$fiscalyear=substr($date,5,2)< 4?((substr($invoice['data']['year'],2,2)-1)."-".(substr($invoice['data']['year'],2,2))):((substr($invoice['data']['year'],2,2))."-".(substr($invoice['data']['year'],2,2)+1));
$pdf->SetFont('helvetica', '', 9);

$tbl = 
'<style>th{font-weight:bold;}</style>
    <table width="100%" cellpadding="0" border="1">
    <tr>
    <td width="50%">
    <table width="100%">
    <tr>
    <th><img src="/img/mindsarray_logo.png" alt="" ></th>
    <td></td>
    
    </tr>
    <tr>
    <th width="35%">Supplier Name : </th>
    <td width="55%">'.$invoice['data']['source_name'].'</td>
    </tr>
    <tr>
    <th width="35%">Address : </th>
    <td width="55%">'.$invoice['data']['source_address'].'</td>
    </tr>
    <tr>
    <th width="35%">State : </th>
    <td width="55%">'.$invoice['data']['source_state'].'</td>
    </tr>
    <tr>
    <th width="35%">GSTN Regn. No. : </th>
    <td width="55%">'.$invoice['data']['source_gst_no'].'</td>
    </tr>
    </table>
    </td>
    <td width="50%">
<table width="100%">
    <tr>
    <th width="35%"> Date : </th>
    <td width="55%">'.date("F d, Y", strtotime($date)).'</td>
    </tr>
    <tr>
    <th width="35%"> Invoice No : </th>
    <td width="55%">'."PAY1/".$fiscalyear."/".$month."/".$invoiceid.'</td>
    </tr>
    
    </table>    
    </td>
    </tr>
</table>
';

$tbl .= 
'
    <table width="100%" cellpadding="0" border="1">
    
    <tr>
    <td width="50%">
    <table width="100%">
    <tr><th colspan="4" align="center">Detail of Receiver (Bill to)</th></tr>
    <tr>
    <th width="35%">Customer Name : </th>
    <td width="55%">'.$invoice['data']['target_name'].'</td>
    </tr>
    <tr>
    <th width="35%">Customer Address : </th>
    <td width="55%">'.$invoice['data']['target_address'].'</td>
    </tr>
    <tr>
    <th width="35%">State : </th>
    <td width="55%">'.$invoice['data']['target_state'].'</td>
    </tr>
    <tr>
    <th width="35%">GSTN Regn. No. : </th>
    <td width="55%">'.$invoice['data']['target_gst_no'].'</td>
    </tr>
    </table>
    </td>
    <td width="50%">
<table width="100%">
    <tr><th colspan="4" align="center">Detail of Consignee (Shipped to)</th></tr>
    <tr>
    <th width="35%"> Consignee Name : </th>
    <td width="55%">'.$invoice['data']['target_name'].'</td>
    </tr>
    <tr>
    <th width="35%"> Consignee Address : </th>
    <td width="55%">'.$invoice['data']['target_address'].'</td>
    </tr>
    <tr>
    <th width="35%"> State : </th>
    <td width="55%">'.$invoice['data']['target_state'].'</td>
    </tr>
    <tr>
    <th width="35%"> GSTN Regn. No. : </th>
    <td width="55%">'.$invoice['data']['target_gst_no'].'</td>
    </tr>
    </table>    
    </td>
    </tr>
</table>
';

$tbl .= 
'<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th width="5%">Sr.No.</th>
        <th width="10%">Description</th>
        <th width="9%">HSN/<br />Accounting Code</th>
        <th width="9%">Qty</th>
        <th width="5%">Unit <br/>Price</th>
        <th width="10%">Total</th>        
        <th width="8%">Discount</th>
        <th width="8%">Taxable Value</th>
        <th width="12%" colspan="2">CGST</th>
        <th width="12%" colspan="2">SGST</th>
        <th width="12%" colspan="2">IGST</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td width="5%">Rate</td>
        <td width="7%">Amount</td>
        <td width="5%">Rate</td>
        <td width="7%">Amount</td>
        <td width="5%">Rate</td>
        <td width="7%">Amount</td>
    </tr>';    
    $i = 1;
    $total_payable_amt = 0;
    unset($invoice['data']);
    foreach ($invoice as $inv){ 
       $description = $inv['description'];
       $total_amt = ($inv['total_amt']!=0)?$inv['total_amt']:'';
       $unit_price = ($total_amt!=0)?0.85:'';
       $total = ($total_amt!=0)?$total_amt*0.85:'';
       $cgst_amt = ($inv['payable_amt']*$inv['cgst'])/100;
       $sgst_amt = ($inv['payable_amt']*$inv['sgst'])/100;
       $igst_amt = ($inv['payable_amt']*$inv['igst'])/100;
       $payable_amt = $description!='MPOS Service Charges'?$inv['payable_amt']:$inv['payable_amt']+($cgst_amt+$sgst_amt+$igst_amt);       
       $taxable_amt = $description=='MPOS Service Charges'?$inv['payable_amt']:$inv['payable_amt'] - ($cgst_amt+$sgst_amt+$igst_amt);
       $discount = ($total_amt!=0)?$total - $taxable_amt:'';
       $total_payable_amt += $payable_amt;
       $tbl .='<tr>
           <td>'.$i.'</td>
           <td>'.$description.'</td>
           <td>'.$inv['hsn_no'].'</td>
           <td>'.number_format($total_amt,2).'</td>
           <td>'.$unit_price.'</td>
           <td>'.number_format($total,2).'</td>
           <td>'.number_format($discount,2).'</td>
           <td>'.number_format($taxable_amt,2).'</td>
           <td width="5%">'.$inv['cgst'].'%</td>
           <td width="7%">'.number_format($cgst_amt,2).'</td>
           <td width="5%">'.$inv['sgst'].'%</td>
           <td width="7%">'.number_format($sgst_amt,2).'</td>
           <td width="5%">'.$inv['igst'].'%</td>
           <td width="7%">'.number_format($igst_amt,2).'</td>
       </tr>';
       $i++;
   }
$tbl .='<tr>
           <td><b>Total</b></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td><b>'.$total_payable_amt.'</b></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
       </tr>';
$tbl .='</table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->SetFont( '', 'B' ,9);
$pdf->MultiCell( 0, 5,"Total Invoice Value (In words) : ".$this->NumberToWord->convert_number_to_words(ceil($total_payable_amt)), 0,'L',0, 0 );
$pdf->Ln();
$pdf->SetFont( '', '' ,9);
$pdf->MultiCell( 0, 10,"Tax under Reverse Charge : Yes/No", 0,'L',0, 0 );

//// #Footer
//$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 3);
//$pdf->SetFont( '', '' ,10);
//$footer=' <div style="margin-top:10px; font-size:0.85em; text-align:left;"> Make all cheques payable to <b>MindsArray Technologies Pvt. Ltd.</b> If you have<br> any questions concerning this invoice, email info@mindsarray.com<br> CIN No. : <b>U32104KA2007PTC043388</b><br></div>
//            <strong><i>THANK YOU FOR YOUR BUSINESS!</i></strong>';
//
//$pdf->writeHTML($footer);
$pdf->lastPage();
ob_clean();
if($type == 0)
{
    $pdf->Output(  '/tmp/taxInvoice.pdf', 'I');
}
elseif($type == 1)
{
    $pdf->Output(  '/tmp/taxInvoice.pdf', 'FD');
}
elseif($type == 2)
{
    $pdfString = $pdf->Output('/tmp/taxInvoice1.pdf', 'F');
    $mail = new PHPMailer(true);
    $mail->IsSMTP(); // telling the class to use SMTP

try {
    $mail->Host = 'ssl://smtp.gmail.com';
    $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Port       = 460;                    // set the SMTP port for the GMAIL server
    $mail->Username   = "dipali@mindsarray.com"; // SMTP account username
    $mail->Password   = "1603198627";        // SMTP account password
    $mail->AddAddress('dipali@mindsarray.com', 'Dipali');
    $mail->SetFrom('dipali@mindsarray.com', 'Dipali');
    $mail->AddReplyTo('dipali@mindsarray.com', 'Dipali');
    $mail->Subject = 'Invoice';
    $mail->Body = "Hi,<br>Please find the invoice attached.";
    $mail->AddAttachment('/tmp/taxInvoice1.pdf', 'taxInvoice.pdf');
    $mail->send();
    $response = array('status' => 'success', 'msg' => 'Mail has been sent successfully.');
    return $response;
    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }
}
exit();
 ?>
