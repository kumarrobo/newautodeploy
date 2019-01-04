<?php 
//App::import('Vendor','tcpdf');
require_once APP.vendors.DS.tcpdf.DS.'tcpdf.php';
$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->AddPage();

$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
//   #Invoice header
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(169,169,169);
$pdf->Cell(195, 5, 'SUMMARY OF INVOICE', 1, 1, 'C');
$pdf->SetTextColor(0,0,0);
$invoiceid= str_pad($invoice[0]['inv']['invoice_id'],4,0,STR_PAD_LEFT);
$date=$invoice[0]['inv']['invoice_date'];
$pdf->Cell(195, 2, '', 0, 1);
$pdf->Image("/img/mindsarray_logo.png");
$pdf->setCellPaddings(110,'');
$pdf->SetFont( '', 'I' ,10);
$pdf->SetTextColor(169,169,169);
$pdf->Cell(25, 0, "DATE: ", 0,0,'L');
$pdf->SetFont( '', '' ,10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(10, 0, date('d-m-Y',strtotime($fromdate)).' To '.date('d-m-Y',strtotime($todate)), 0,1,'L');
$pdf->SetFont( '', 'I' ,10);
$pdf->SetTextColor(169,169,169);
$pdf->Cell(25, 0, "FOR: ", 0,0,'L');
$pdf->SetFont( '', '' ,10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(10, 0, "Pay1 Top Up", 0,1,'L');
$pdf->SetFont( '', 'I' ,12);
$pdf->SetTextColor(169,169,169);
$pdf->Cell(25, 0, "BILL TO: ", 0,0,'L');
$pdf->SetFont( '', '' ,10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(10, 0, $dist_name, 0,1,'L');
$pdf->setCellPaddings(0);
$pdf->Cell(195, 1, '', 0, 1);
$pdf->MultiCell(60,3,"726, Raheja Metroplex (IJMIMA) Link Road,Malad-West,Mumbai Maharashtra - 400064",0,'L',0, 1);
$pdf->Cell(195, 2, '', 0, 1);
$pdf->Line(10, 17, 10, 70);
$pdf->Line(205, 17, 205, 70);
    
//    # Table header
$pdf->SetFillColor(169,169,169);    
$pdf->SetFont( '', 'b' ,10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell( 35, 7, 'DATE', 1, 0, 'C' ,true);
$pdf->Cell( 40, 7, 'INVOICE#', 1, 0, 'C' ,true);
$pdf->Cell( 40, 7, 'GROSS AMOUNT', 1, 0, 'C' ,true);
$pdf->Cell( 40, 7, 'DISCOUNT', 1, 0, 'C' ,true);
$pdf->Cell( 40, 7, 'NET AMOUNT', 1, 0, 'C' ,true);
$pdf->Ln();
//  #Table body
$pdf->SetFont( '', '' ,10);
$topup=0;$discount=0;$gross_sum=0;
for($j=0; $j<count($invoicedata); $j++){
    $fiscalyear=$invoicedata[$j][0]['month']<4?($invoicedata[$j][0]['year']-1)."-".$invoicedata[$j][0]['year']:($invoicedata[$j][0]['year'])."-".($invoicedata[$j][0]['year']+1);
    $totalsale=$invoicedata[$j]['inv']['invoice_date']>='2017-04-01'?$invoicedata[$j]['inv']['topup_buy']:$invoicedata[$j]['inv']['topup_buy']+$invoicedata[$j]['inv']['earning'];
    $earning=$invoicedata[$j]['inv']['earning'];
    $net_amt=$invoicedata[$j]['inv']['invoice_date']>='2017-04-01'?ceil($totalsale-$earning):ceil($invoicedata[$j]['inv']['topup_buy']);
    $topup+=$invoicedata[$j]['inv']['topup_buy'];
    $gross_sum+=$totalsale;                            
    $discount+=$invoicedata[$j]['inv']['earning'];
    $gross_amt+=$net_amt;
    $pdf->Cell( 35, 7, date('d-m-Y',strtotime($invoicedata[$j]['inv']['invoice_date'])), 1, 0, 'C' );
    $pdf->Cell( 40, 7, "PAY1/".$fiscalyear."/".$invoicedata[$j]['inv']['invoice_id'], 1, 0, 'C' );
    $pdf->Cell( 40, 7,$totalsale , 1, 0, 'C' );
    $pdf->Cell( 40, 7,$earning , 1, 0, 'C' );
    $pdf->Cell( 40, 7, $net_amt, 1, 0, 'C' );
    $pdf->Ln();
}
$pdf->SetFont( '', 'B' ,10);
$pdf->Cell( 35, 7, 'Total', 1, 0, 'C' );
$pdf->Cell( 40, 7, '', 1, 0, '' );
$pdf->Cell( 40, 7,$gross_sum , 1, 0, 'C' );
$pdf->Cell( 40, 7,$discount , 1, 0, 'C' );
$pdf->Cell( 40, 7, $gross_amt, 1, 0, 'C' );
$pdf->Ln();

// #Footer
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 3);
$pdf->SetFont( '', '' ,10);
$footer=' <div style="margin-top:10px; font-size:0.85em; text-align:left;"> Make all cheques payable to <b>MindsArray Technologies Pvt. Ltd.</b> If you have<br> any questions concerning this invoice, email info@mindsarray.com<br> CIN No. : <b>U32104KA2007PTC043388</b><br></div>
            <strong><i>THANK YOU FOR YOUR BUSINESS!</i></strong>';

$pdf->writeHTML($footer);
$pdf->lastPage();
ob_clean();
$pdf->Output(  '/tmp/invoiceSummary.pdf', 'FD');
exit();
 ?>
