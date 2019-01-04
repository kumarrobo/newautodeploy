<?php 
//App::import('Vendor','tcpdf');
require_once APP.vendors.DS.tcpdf.DS.'tcpdf.php';
$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->AddPage();

$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
//   #Invoice header
$fiscalyear=substr($invoice[0]['inv']['invoice_date'],5,2)< 4?(($invoice[0][0]['year']-1)."-".$invoice[0][0]['year']):($invoice[0][0]['year']."-".($invoice[0][0]['year']+1));
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(169,169,169);
$pdf->Cell(195, 5, 'INVOICE', 1, 1, 'C');
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
$pdf->Cell(10, 0, date("F d, Y", strtotime($date)), 0,1,'L');
$pdf->SetFont( '', 'I' ,10);
$pdf->SetTextColor(169,169,169);
$pdf->Cell(25, 0, "INVOICE#", 0,0,'L');
$pdf->SetFont( '', '' ,10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(10, 0, "PAY1/".$fiscalyear."/".$invoiceid, 0,1,'L');
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
$pdf->Cell(10, 0, $invoice[0]['d']['dname'], 0,1,'L');
$pdf->setCellPaddings(0);
$pdf->Cell(195, 1, '', 0, 1);
$pdf->MultiCell(60,3,"726, Raheja Metroplex (IJMIMA) Link Road,Malad-West,Mumbai Maharashtra - 400064",0,'L',0, 1);
$pdf->Cell(195, 2, '', 0, 1);
$pdf->Line(10, 17, 10, 70);
$pdf->Line(205, 17, 205, 70);
    
$totalsale=$invoice[0]['inv']['invoice_date']>='2017-04-01'?$invoice[0]['inv']['topup_buy']:$invoice[0]['inv']['topup_buy']+$invoice[0]['inv']['earning'];
$discount=$invoice[0]['inv']['earning'];
$net_amt=$invoice[0]['inv']['invoice_date']>='2017-04-01'?$totalsale-$discount:$invoice[0]['inv']['topup_buy'];
//    # Table header
$pdf->SetFillColor(169,169,169);    
$pdf->SetFont( '', 'b' ,10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell( 75, 7, 'DATE', 1, 0, 'L' ,true);
$pdf->Cell( 60, 7, 'DESCRIPTION', 1, 0, 'L' ,true);
$pdf->Cell( 60, 7, 'AMOUNT', 1, 0, 'L' ,true);
$pdf->Ln();
//  #Table body
$pdf->SetFont( '', '' ,10);
for($j=0; $j<=10; $j++){
    $pdf->Cell( 75, 7, (($j==0)?$invoice[0][0]['month']."-".$invoice[0][0]['year']:""), 1, 0, 'L' );
    $pdf->Cell( 60, 7, ($j==0)?"Pay1 Top Up":"", 1, 0, 'L' );
    $pdf->Cell( 60, 7, ($j==0)?($totalsale):(($j==10)?($totalsale):""), 1, 0, 'R' );
    $pdf->Ln();
}

$border1 = array('T' => array('width' => 0.2),'L' => array('width' => 0.2),'B' => array('width' => 0.2));
$border2 = array('T' => array('width' => 0.2),'R' => array('width' => 0.2),'B' => array('width' => 0.2));

$pdf->SetFont( '', '' ,10);
$pdf->Cell( 75, 7, '', 1, 0, 'L' );
$pdf->Cell( 20, 7, 'Less: ', $border1, 0, 'L' );
$pdf->Cell( 40, 7, 'Discount', $border2, 0, 'L' );
$pdf->Cell( 60, 7, $discount, 1, 0, 'R' );
$pdf->Ln();

$pdf->SetFont( '', 'B' ,10);
$pdf->MultiCell( 75, 15,"Amount in words(Rs.):".$this->NumberToWord->convert_number_to_words(ceil($net_amt)), 1,'L',0, 0 );
$pdf->setFont('','BI',10);
$pdf->Cell( 60, 15, 'TOTAL', 1, 0, 'R' );
$pdf->SetFillColor(221,160,221);
$pdf->SetFont( '', 'B' ,10);
$pdf->Cell( 35, 15, 'INR', $border1, 0, 'L' ,true);
$pdf->Cell( 25, 15, number_format(ceil($net_amt)), $border2, 0, 'R' ,true);
$pdf->Ln();

// #Footer
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 3);
$pdf->SetFont( '', '' ,10);
$footer=' <div style="margin-top:10px; font-size:0.85em; text-align:left;"> Make all cheques payable to <b>MindsArray Technologies Pvt. Ltd.</b> If you have<br> any questions concerning this invoice, email info@mindsarray.com<br> CIN No. : <b>U32104KA2007PTC043388</b><br></div>
            <strong><i>THANK YOU FOR YOUR BUSINESS!</i></strong>';

$pdf->writeHTML($footer);
$pdf->lastPage();
ob_clean();
$pdf->Output(  '/tmp/taxInvoice.pdf', 'FD');
exit();
 ?>
