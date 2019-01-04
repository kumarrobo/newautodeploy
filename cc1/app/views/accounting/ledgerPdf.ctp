<?php require_once APP.vendors.DS.tcpdf.DS.'tcpdf.php';
$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->AddPage();
if($type ==1){

    $closing = round($data['vendor']['modem']['o'] - $data['vendor']['modem']['to_pay'][0][0]['pay'] + $data['vendor']['modem']['purchase'] - $data['vendor']['modem']['commission']);

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->setFillColor(65,105,225); 
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(195, 10, date('d-M-Y',strtotime($from)).' To '.date('d-M-Y',strtotime($to)), 1, 1, 'C',TRUE);
    $pdf->Cell(195, 10, $details[0]['l']['name'] ? 'Vendor Ledger : Modem [ '.$details[0]['l']['name'].' ]' : '', 1, 1, 'C',TRUE);

    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 97, 10, 'DR', 1, 0, 'C' ,true);
    $pdf->Cell( 98, 10, 'CR', 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 57, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'By Opening Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['modem']['o'], 2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Payment', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['modem']['to_pay'][0][0]['pay'],2)    , 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'To Purchase', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['modem']['purchase'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'To Comm Paid', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['modem']['commission'],2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Closing Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, $closing, 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['modem']['o'] + $data['vendor']['modem']['purchase'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['modem']['to_pay'][0][0]['pay'] + $data['vendor']['modem']['commission'] + $closing, 2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->Output(  '/tmp/Vender_Modem.pdf', 'FD');
    exit();
}else if($type == 2){
    
    $closing = round($data['vendor']['api']['o'][0]['el']['opening'] + $data['vendor']['api']['purchase'][0][0]['purchase'] + $data['vendor']['api'][0][0]['commission'] - $data['vendor']['api'][0][0]['sale'], 2);
    
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->setFillColor(65,105,225); 
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(195, 10, date('d-M-Y',strtotime($from)).' To '.date('d-M-Y',strtotime($to)), 1, 1, 'C',TRUE);
    $pdf->Cell(195, 10, $details[0][0]['name'] ? 'Vendor Ledger : Api [ '.$details[0][0]['name'].' ]' : '', 1, 1, 'C',TRUE);

    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 97, 10, 'DR', 1, 0, 'C' ,true);
    $pdf->Cell( 98, 10, 'CR', 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 57, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'To Purchase', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['api'][0][0]['sale'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Opening Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['api']['o'][0]['el']['opening'], 2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Payment', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['api']['purchase'][0][0]['purchase'],2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'By Closing Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, $closing, 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'To Comm Paid', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['api'][0][0]['commission'],2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['api'][0][0]['sale'] + $closing,2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['vendor']['api']['o'][0]['el']['opening'] + $data['vendor']['api']['purchase'][0][0]['purchase'] + $data['vendor']['api'][0][0]['commission'],2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->Output(  '/tmp/Vender_Api.pdf', 'FD');
    exit();    
}
else if($type == 3){
    
    $distributor_dr = $data['distributor']['o'][0]['dl']['opening'] + $data['distributor']['limit'][0][0]['limit'] + $data['distributor']['commission'][0][0]['commission'] + $data['distributor']['incentive'][0][0]['incentive'];

    $distributor_cr = $data['distributor']['trf_ret'][0][0]['transfer_retailer'] + $data['distributor']['trf_sal'][0][0]['transfer_salesmen'] + $data['distributor']['tds'][0][0]['tds'] + $data['distributor']['kit_charge'][0][0]['kit_charge'] + $data['distributor']['sd'][0][0]['security_deposit'] + $data['distributor']['one_time'][0][0]['one_time'];

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->setFillColor(65,105,225); 
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(195, 10, date('d-M-Y',strtotime($from)).' To '.date('d-M-Y',strtotime($to)), 1, 1, 'C',TRUE);
    $pdf->Cell(195, 10, $details[0][0]['name'] ? 'Distributor Ledger [ '.$details[0][0]['name'].' ]' : '', 1, 1, 'C',TRUE);

    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 97, 10, 'DR', 1, 0, 'C' ,true);
    $pdf->Cell( 98, 10, 'CR', 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 57, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'By Opening Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['o'][0]['dl']['opening'], 2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Limit Given to retailers', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['trf_ret'][0][0]['transfer_retailer'],2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'To Cash/Limits', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['limit'][0][0]['limit'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Limit Given to Salesman', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['trf_sal'][0][0]['transfer_salesmen'],2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'To Commission Received', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['commission'][0][0]['commission'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By TDS Paid', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['tds'][0][0]['tds'],2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'By Incentives', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['incentive'][0][0]['incentive'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Kit Charges', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['kit_charge'][0][0]['kit_charge'],2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'Security Deposit', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['sd'][0][0]['security_deposit'],2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'One Time', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['distributor']['one_time'][0][0]['one_time'],2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Closing Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, $closing=round($distributor_dr - $distributor_cr, 2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($distributor_dr, 2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($distributor_cr + $closing, 2), 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->Output(  '/tmp/Distributor_Ledger.pdf', 'FD');
    exit();
}else if($type == 4){
    
    $retailer_dr = $data['retailer']['o'][0]['rl']['opening'] + $data['retailer']['transfer_nn'][0][0]['transfer'] + $data['retailer']['trf_net_lmt'][0][0]['transfer'] + $data['retailer']['trf_net_lmt_p'][0][0]['transfer'] + $data['retailer']['commission'][0][0]['commission'] + $data['retailer']['incentive'][0][0]['incentive'];
    $retailer_cr = $data['retailer']['kit_charge'][0][0]['kit_charge'] + $data['retailer']['service_chrge'][0][0]['service_charge'] + $data['retailer']['one_time'][0][0]['one_time'] + $data['retailer']['rental'][0][0]['rental'];
    $closing = round($retailer_dr - $retailer_cr, 2);
    
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->setFillColor(65,105,225); 
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(195, 10, date('d-M-Y',strtotime($from)).' To '.date('d-M-Y',strtotime($to)), 1, 1, 'C',TRUE);
    $pdf->Cell(195, 10, $details[0][0]['name'] ? 'Retailer Ledger [ '.$details[0][0]['name'].' ]' : '', 1, 1, 'C',TRUE);

    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 97, 10, 'DR', 1, 0, 'C' ,true);
    $pdf->Cell( 98, 10, 'CR', 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(65,105,225);    
    $pdf->SetFont( '', 'b' ,12);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell( 57, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'Particulars', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, 'Amount', 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'By Opening Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['o'][0]['rl']['opening'], 2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Kit Charges', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['kit_charge'][0][0]['kit_charge'],2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    if ($data['retailer']['transfer_nn']){
    $pdf->Cell( 57, 10, 'To Limit from Distributors', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['transfer_nn'][0][0]['transfer'],2), 1, 0, 'C' ,true);
    }else{
    $pdf->Cell( 57, 10, 'To Cash/Limits', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['trf_net_lmt'][0][0]['transfer'],2), 1, 0, 'C' ,true);
    }
    $pdf->Cell( 58, 10, 'Service Charges', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['service_chrge'][0][0]['service_charge'],2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    if($data['retailer']['transfer_nn']){
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, '', 1, 0, 'C' ,true);
    }else{
    $pdf->Cell( 58, 10, 'To Pay U Limits', 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, round($data['retailer']['trf_net_lmt_p'][0][0]['transfer'],2), 1, 0, 'C' ,true);
    }
    $pdf->Cell( 58, 10, 'One Time', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['one_time'][0][0]['one_time'],2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'To Commission Received', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['commission'][0][0]['commission'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'Rental', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['rental'][0][0]['rental'],2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    foreach ($data['retailer']['services'][CREDIT_NOTE] as $res) { $retailer_dr += $res['amount'];
    $pdf->Cell( 57, 10, $res['name'], 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($res['amount'], 2), 1, 0, 'C' ,true);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, '', 1, 0, 'C' ,true);
    }
//    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    foreach ($data['retailer']['services'][CREDIT_NOTE] as $res) { $retailer_dr += $res['amount'];
    $pdf->Cell( 57, 10, $res['name'], 1, 0, 'C' ,true);
    $pdf->Cell( 57, 10, round($res['amount'], 2), 1, 0, 'C' ,true);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    }
//    $pdf->Ln();
    
    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, 'By Incentives', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($data['retailer']['incentive'][0][0]['incentive'],2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, 'By Closing Balance', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, $closing, 1, 0, 'C' ,true);
    $pdf->Ln();

    $pdf->SetFillColor(255,255,255);  
    $pdf->SetFont( '', 'b' ,10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell( 57, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($retailer_dr, 2), 1, 0, 'C' ,true);
    $pdf->Cell( 58, 10, '', 1, 0, 'C' ,true);
    $pdf->Cell( 40, 10, round($retailer_cr + $closing, 2), 1, 0, 'C' ,true);
    $pdf->Ln();
    
    $pdf->Output(  '/tmp/Retailer_Ledger.pdf', 'FD');
    exit();
}
?>