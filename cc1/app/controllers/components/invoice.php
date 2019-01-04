<?php

class InvoiceComponent extends Object{
    var $components = array('General', 'Shop','RequestHandler','Documentmanagement');
    var $Memcache = null;

    function getRetailerState()
    {
        $Object = ClassRegistry::init('User');
        $ret_state_query = "SELECT r.id,r.user_id,d.state "
                . "FROM retailers r "
                . "JOIN distributors d "
                . "ON (r.parent_id = d.id) "
                . "WHERE r.parent_id != 1 "
                . "UNION ALL "
                . "SELECT r.id,r.user_id,if(a.area_id = 0 OR a.area_id IS NULL OR a.area_id = '','Maharashtra',ls.name) state "
                . "FROM (SELECT * FROM user_profile ORDER BY updated DESC) a "
                . "JOIN retailers r "
                . "ON (a.user_id = r.user_id AND r.parent_id = 1) "
                . "LEFT JOIN locator_area la "
//                . "ON (r.area_id = la.id) "
                . "ON (a.area_id = la.id) "
                . "LEFT JOIN locator_city lc "
                . "ON (la.city_id = lc.id) "
                . "LEFT JOIN locator_state ls "
                . "ON (lc.state_id=ls.id) "
                . "GROUP BY a.user_id";

        $get_retailer_state = $Object->query($ret_state_query);
        $response = array();
        foreach ($get_retailer_state as $data)
        {
            $response[$data[0]['user_id']]['state'] = $data[0]['state'];
        }

        return $response;
    }

//    function addTaxInvoice($source_id,$target_id,$source_group_id,$target_group_id,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year){
//        $this->data['TaxInvoice'] = array('source_id'=>$source_id,'target_id'=>$target_id,'source_group_id'=>$source_group_id,'target_group_id'=>$target_group_id,'source_gst_no'=>$source_gst_no,'target_gst_no'=>$target_gst_no,'source_state'=>$source_state,'target_state'=>$target_state,'invoice_date'=>$invoicedate,'month'=>$month,'year'=>$year);
//
//        $transObj = ClassRegistry::init('TaxInvoice');
//        $has_any_data_cond = array('source_id'=>$source_id,'target_id'=>$target_id,'month'=>$month,'year'=>$year);
//
//        if($transObj->hasAny($has_any_data_cond))
//        {
//            return FALSE;
//        }
//        else
//        {
//            $transObj->create();
//            if($transObj->save($this->data))
//            {
//                return $transObj->id;
//            }
//            else
//            {
//                return FALSE;
//            }
//        }
//    }
    
    function addTaxInvoice($parent_service_id,$source_id,$target_id,$source_group_id,$target_group_id,$source_gst_no,$target_gst_no,$source_state,$target_state,$invoicedate,$month,$year,$type,$dataSource){
        $invoice_exists = $dataSource->query('SELECT * FROM tax_invoices WHERE parent_service_id = '.$parent_service_id.' AND source_id = '.$source_id.' AND target_id = '.$target_id.' AND source_group_id = '.$source_group_id.' AND target_group_id = '.$target_group_id.' AND month = '.$month.' AND year = '.$year.' AND type = '.$type.' ');
        
        if(!empty($invoice_exists))
        {
            return FALSE;
        }
        else
        {
            $response = $dataSource->query('INSERT INTO tax_invoices(parent_service_id,source_id,target_id,source_group_id,target_group_id,source_gst_no,target_gst_no,source_state,target_state,invoice_date,month,year,type) '
                    . 'VALUES("'.$parent_service_id.'","'.$source_id.'","'.$target_id.'","'.$source_group_id.'","'.$target_group_id.'","'.$source_gst_no.'","'.$target_gst_no.'","'.$source_state.'","'.$target_state.'","'.$invoicedate.'","'.$month.'","'.$year.'","'.$type.'")');
            
            if($response)
            {
                return $dataSource->lastInsertId();
            }
            else
            {
                return FALSE;
            }
        }
    }

    function getInvoiceList($user_id,$month,$year,$type)
    {
        $Object = ClassRegistry::init('User');
        $uid = $type==0?'invt.target_id':'invt.source_id';

        $get_invoice_query = "SELECT invt.source_group_id,invt.id as invoice_id,invt.source_id,invt.target_id,invt.invoice_date,invt.month,invt.year,SUM(invl.total_amt) AS total_amt,SUM(invl.payable_amt) AS payable_amt,if(invt.source_group_id = 5,d.company,if(invt.source_group_id = 6,r.shopname,if(invt.source_group_id = 0,'Mindsarray Technologies Pvt Ltd',''))) as source_name,if(invt.target_group_id = 5,d.company,if(invt.target_group_id = 6,r.shopname,if(invt.target_group_id = 0,'Mindsarray Technologies Pvt Ltd',''))) as target_name "
                                . "FROM tax_invoices invt "
                                . "JOIN tax_invoices_logs invl "
                                . "ON (invt.id = invl.invoice_id and invt.month = invl.month and invt.year = invl.year) "
                                . "LEFT JOIN retailers r "
                                . "ON (r.user_id = invt.source_id OR r.user_id = invt.target_id) "
                                . "LEFT JOIN distributors d "
                                . "ON (d.user_id = invt.source_id OR d.user_id = invt.target_id) "
                                . "LEFT JOIN inv_suppliers s "
                                . "ON (s.id = invt.source_id OR s.id = invt.target_id) "
                                . "WHERE $uid = '$user_id' "
                                . "AND invt.month = '$month' "
                                . "AND invt.year = '$year' "
                                . "GROUP BY invt.id ";

        $invoice_data = $Object->query($get_invoice_query);

        $response = array();

        if(!empty($invoice_data))
        {
            /** IMP DATA ADDED : START**/
            $temp = $this->Shop->getUserLabelData($user_id,2,0);
            $imp_data = $temp[$user_id];
            /** IMP DATA ADDED : END**/

            foreach ($invoice_data as $key=>$val)
            {
                $response[$key]['invoice_id'] = $val['invt']['invoice_id'];
                $response[$key]['source_id'] = $val['invt']['source_id'];
                $response[$key]['target_id'] = $val['invt']['target_id'];


                if(in_array($val['invt']['source_group_id'],array(RETAILER,DISTRIBUTOR))){
                    $response[$key]['source_name'] = $imp_data['imp']['shop_est_name'];
                    $response[$key]['target_name'] = 'Mindsarray Technologies Pvt Ltd';
                } else if( in_array($val['invt']['target_group_id'],array(RETAILER,DISTRIBUTOR)) ){
                    $response[$key]['source_name'] = 'Mindsarray Technologies Pvt Ltd';
                    $response[$key]['target_name'] = $imp_data['imp']['shop_est_name'];
                } else {
                    $response[$key]['source_name'] = $val[0]['source_name'];
                    $response[$key]['target_name'] = $val[0]['target_name'];
                }

                $response[$key]['total_amt'] = $val[0]['total_amt'];
                $response[$key]['payable_amt'] = $val[0]['payable_amt'];
                $response[$key]['invoice_date'] = $val['invt']['invoice_date'];
                $response[$key]['month'] = $val['invt']['month'];
                $response[$key]['year'] = $val['invt']['year'];
            }
        }

        return $response;
    }

    function getAllInvoices($month,$year,$type,$group_id)
    {
        $Object = ClassRegistry::init('User');
        $group = $type==0?'invt.target_group_id':'invt.source_group_id';
        $tbl = "";
        $params = "";
        $joincond = "";

        if($group_id == 5)
        {
            $tbl = "distributors d";
            $params = ",if(invt.source_group_id = 5,d.name,if(invt.source_group_id = 0,'Mindsarray Technologies Pvt Ltd','')) as source_name,if(invt.target_group_id = 5,d.name,if(invt.target_group_id = 0,'Mindsarray Technologies Pvt Ltd','')) as target_name";
            $joincond = "ON (d.user_id = invt.source_id OR d.user_id = invt.target_id) ";

        }
        elseif($group_id == 6)
        {
            $tbl = "retailers r";
            $params = ",if(invt.source_group_id = 6,r.shopname,if(invt.source_group_id = 0,'Mindsarray Technologies Pvt Ltd','')) as source_name,if(invt.target_group_id = 6,r.shopname,if(invt.target_group_id = 0,'Mindsarray Technologies Pvt Ltd','')) as target_name";
            $joincond = "ON (r.user_id = invt.source_id OR r.user_id = invt.target_id) ";
        }
        elseif($group_id == 9)
        {
            $tbl = 'inv_suppliers s';
            $params = ",if(invt.source_group_id = 9,s.name,if(invt.source_group_id = 0,'Mindsarray Technologies Pvt Ltd','')) as source_name,if(invt.target_group_id = 9,s.name,if(invt.target_group_id = 0,'Mindsarray Technologies Pvt Ltd','')) as target_name";
            $joincond = "ON (s.id = invt.source_id OR s.id = invt.target_id) ";
        }

        $get_invoice_query = "SELECT invt.id as invoice_id,invt.source_id,invt.target_id,invt.source_group_id,invt.target_group_id,invt.invoice_date,invt.month,invt.year,SUM(invl.total_amt) AS total_amt,SUM(invl.payable_amt) AS payable_amt ".$params." "
                                . "FROM tax_invoices invt "
                                . "JOIN tax_invoices_logs invl "
                                . "ON (invt.id = invl.invoice_id and invt.month = invl.month and invt.year = invl.year) "
                                . "JOIN ".$tbl." ".$joincond." "
                                . "WHERE $group = '$group_id' "
                                . "AND invt.month = '$month' "
                                . "AND invt.year = '$year' "
                                . "GROUP BY invt.id ";

        $invoice_data = $Object->query($get_invoice_query);

        $response = array();

        if(!empty($invoice_data))
        {
            foreach ($invoice_data as $key=>$val)
            {
                $response[$key]['invoice_id'] = $val['invt']['invoice_id'];
                $response[$key]['source_id'] = $val['invt']['source_id'];
                $response[$key]['target_id'] = $val['invt']['target_id'];
                $response[$key]['source_group_id'] = $val['invt']['source_group_id'];
                $response[$key]['target_group_id'] = $val['invt']['target_group_id'];
                $response[$key]['source_name'] = $val[0]['source_name'];
                $response[$key]['target_name'] = $val[0]['target_name'];
                $response[$key]['total_amt'] = $val[0]['total_amt'];
                $response[$key]['payable_amt'] = $val[0]['payable_amt'];
                $response[$key]['invoice_date'] = $val['invt']['invoice_date'];
                $response[$key]['month'] = $val['invt']['month'];
                $response[$key]['year'] = $val['invt']['year'];
            }
        }

        return $response;
    }

    function getInvoiceData($user_id,$invoice_id,$month,$year,$type,$email_id)
    {
        $Object = ClassRegistry::init('User');

        $get_invoice_query = "SELECT invt.*,invl.*,if(invt.source_group_id = 5,d.company,if((invt.source_group_id = 6) AND (trim(r.shopname) != '' OR r.shopname IS NOT NULL),r.shopname,if((invt.source_group_id = 6) AND (trim(r.shopname)='' OR r.shopname IS NULL),r.mobile,if(invt.source_group_id = 9,s.name,if(invt.source_group_id = 0,'Mindsarray Technologies Pvt Ltd',''))))) as source_name,"
                            . "if(invt.target_group_id = 5,d.name,if((invt.target_group_id = 6) AND (trim(r.shopname) != '' OR r.shopname IS NOT NULL),r.shopname,if((invt.target_group_id = 6) AND (trim(r.shopname)='' OR r.shopname IS NULL),r.mobile,if(invt.target_group_id = 9,s.name,if(invt.target_group_id = 0,'Mindsarray Technologies Pvt Ltd',''))))) as target_name,"
                            . "if(invt.source_group_id = 5,d.address,if(invt.source_group_id = 6,r.address,if(invt.source_group_id = 9,s.address,if(invt.source_group_id = 0,'726, Raheja Metroplex (IJMIMA) Link Road,Malad-West,Mumbai Maharashtra - 400064','')))) as source_address,"
                            . "if(invt.target_group_id = 5,d.address,if(invt.target_group_id = 6,r.address,if(invt.target_group_id = 9,s.address,if(invt.target_group_id = 0,'726, Raheja Metroplex (IJMIMA) Link Road,Malad-West,Mumbai Maharashtra - 400064','')))) as target_address,sv.gst "
                            . "FROM tax_invoices invt "
                            . "JOIN tax_invoices_logs invl "
                            . "ON (invt.id = invl.invoice_id and invt.month = invl.month and invt.year = invl.year) "
                            . "LEFT JOIN retailers r "
                            . "ON ((r.user_id = invt.source_id AND invt.source_group_id = 6) OR (r.user_id = invt.target_id AND invt.target_group_id = 6)) "
                            . "LEFT JOIN distributors d "
                            . "ON ((d.user_id = invt.source_id AND invt.source_group_id = 5) OR (d.user_id = invt.target_id AND invt.target_group_id = 5)) "
                            . "LEFT JOIN inv_suppliers s "
                            . "ON ((s.id = invt.source_id AND invt.source_group_id = 9) OR (s.id = invt.target_id AND invt.target_group_id = 9)) "
                            . "LEFT JOIN services sv ON (invl.service_id = sv.id) "
                            . "WHERE 1 "
                            . "AND invt.id = '$invoice_id' "
                            . "AND invt.month = '$month' "
                            . "AND invt.year = '$year' ";

        $invoice_data = $Object->query($get_invoice_query);

        if(!empty($email_id))
        {
            $label_id = 24;
            $this->Documentmanagement->updateTextualInfo($user_id,$label_id,0,$email_id,$user_id);
        }

        $response = array();


        /** IMP DATA ADDED : START**/
        $temp = $this->Shop->getUserLabelData($user_id,2,0);
        $imp_data = $temp[$user_id];
        /** IMP DATA ADDED : END**/

        foreach ($invoice_data as $data)
        {
//            if($_SESSION['Auth']['User']['group_id'] != ADMIN){
//                if((($data['invt']['source_group_id'] == 6) && (strlen($data['invt']['source_gst_no']) != 15)) || (($data['invt']['target_group_id'] == 6) && (strlen($data['invt']['target_gst_no']) != 15)))
//                {
//                   continue;
//                }
//            }
            $response['data']['invoice_id'] = $data['invt']['id'];
            $response['data']['invoice_date'] = $data['invt']['invoice_date'];
            $response['data']['month'] = $data['invt']['month'];
            $response['data']['year'] = $data['invt']['year'];

            if(in_array($data['invt']['source_group_id'],array(RETAILER,DISTRIBUTOR))){

                $response['data']['source_name'] = $imp_data['imp']['shop_est_name'];
                $response['data']['source_address'] = $imp_data['imp']['address'];

                $response['data']['target_name'] = 'Mindsarray Technologies Pvt Ltd';
                $response['data']['target_address'] = '726, Raheja Metroplex (IJMIMA) Link Road,Malad-West,Mumbai Maharashtra - 400064';

            } else if(in_array($data['invt']['target_group_id'],array(RETAILER,DISTRIBUTOR))){

                $response['data']['source_name'] = 'Mindsarray Technologies Pvt Ltd';
                $response['data']['source_address'] = '726, Raheja Metroplex (IJMIMA) Link Road,Malad-West,Mumbai Maharashtra - 400064';

                $response['data']['target_name'] = $imp_data['imp']['shop_est_name'];
                $response['data']['target_address'] = $imp_data['imp']['address'];

            }else {
                $response['data']['source_name'] = $data[0]['source_name'];
                $response['data']['target_name'] = $data[0]['target_name'];
                $response['data']['source_address'] = $data[0]['source_address'];
                $response['data']['target_address'] = $data[0]['target_address'];
            }

            $response['data']['source_state'] = $data['invt']['source_state'];
            $response['data']['target_state'] = $data['invt']['target_state'];
            $response['data']['source_gst_no'] = $data['invt']['source_gst_no'];
            $response['data']['target_gst_no'] = $data['invt']['target_gst_no'];
            $response['data']['source_group_id'] = $data['invt']['source_group_id'];
            $response['data']['target_group_id'] = $data['invt']['target_group_id'];
            $response['data']['type'] = $data['invt']['type'];
            $response[$data['invl']['description']]['source_id'] = $data['invt']['source_id'];
            $response[$data['invl']['description']]['target_id'] = $data['invt']['target_id'];
            $response[$data['invl']['description']]['hsn_no'] = $data['invl']['hsn_no'];
            $response[$data['invl']['description']]['gst_flag'] = $data['sv']['gst'];
            $response[$data['invl']['description']]['description'] = $data['invl']['description'];
            $response[$data['invl']['description']]['total_amt'] = $data['invl']['total_amt'];
            $response[$data['invl']['description']]['payable_amt'] = $data['invl']['payable_amt'];
            $response[$data['invl']['description']]['cgst'] = $data['invl']['cgst'];
            $response[$data['invl']['description']]['sgst'] = $data['invl']['sgst'];
            $response[$data['invl']['description']]['igst'] = $data['invl']['igst'];
        }

        return $response;
    }

    function generatePdf($invoice,$type)
    {
        require_once APP.vendors.DS.tcpdf.DS.'tcpdf.php';
        require_once APP.vendors.DS.phpmailer.DS.'class.phpmailer.php';
        App::import('Helper', 'NumberToWord');
        $obj = new NumberToWordHelper();

        $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->AddPage();

        $source_group_id = ((int)$invoice['data']['source_group_id']);

//        if(!empty($invoice['data']['source_gst_no']) && (strlen($invoice['data']['source_gst_no']) == 15))
        if($source_group_id == 0)
        {
            $inv_type = $invoice['data']['type'] == 0?'TAX INVOICE':'SALES RETURN';
            $pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(190, 4, $inv_type, 1, 1, 'C');

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
                <table width="100%">';
                if($invoice['data']['source_group_id']==0){
            $tbl .=  '<tr>
                <th><img src="/img/mindsarray_logo.png" alt="" ></th>
                <td></td>
                </tr>';}
            $tbl .= '<tr>
                <th width="40%">Company Name : </th>
                <td width="55%">'.$invoice['data']['source_name'].'</td>
                </tr>
                <tr>
                <th width="40%">Address : </th>
                <td width="55%">'.$invoice['data']['source_address'].'</td>
                </tr>
                <tr>
                <th width="40%">State : </th>
                <td width="55%">'.$invoice['data']['source_state'].'</td>
                </tr>
                <tr>
                <th width="40%">GSTN Regn. No. : </th>
                <td width="55%">'.$invoice['data']['source_gst_no'].'</td>
                </tr>
                </table>
                </td>
                <td width="50%">
            <table width="100%">
                <tr>
                <th width="40%"> Date : </th>
                <td width="55%">'.date("F d, Y", strtotime($date)).'</td>
                </tr>
                <tr>
                <th width="40%"> Invoice No : </th>
                <td width="55%">'.$fiscalyear."/".$month."/".$invoiceid.'</td>
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
                <tr><th colspan="4" align="center">Bill to</th></tr>
                <tr>
                <th width="40%">Customer Name : </th>
                <td width="55%">'.$invoice['data']['target_name'].'</td>
                </tr>
                <tr>
                <th width="40%">Customer Address : </th>
                <td width="55%">'.$invoice['data']['target_address'].'</td>
                </tr>
                <tr>
                <th width="40%">State : </th>
                <td width="55%">'.$invoice['data']['target_state'].'</td>
                </tr>
                <tr>
                <th width="40%">GSTN Regn. No. : </th>
                <td width="55%">'.$invoice['data']['target_gst_no'].'</td>
                </tr>
                </table>
                </td>
                <td width="50%">
            <table width="100%">
                <tr><th colspan="4" align="center">Remarks if any</th></tr>
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
//                   $taxable_amt = $description=='MPOS Service Charges'?$inv['payable_amt']:$inv['payable_amt'] - ($cgst_amt+$sgst_amt+$igst_amt);
//                   $taxable_amt = $inv['gst_flag'] == 1?$inv['payable_amt']:$inv['payable_amt']/1.18;
                   $taxable_amt = $inv['payable_amt']/1.18;
                   $cgst_amt = ($taxable_amt*$inv['cgst'])/100;
                   $sgst_amt = ($taxable_amt*$inv['sgst'])/100;
                   $igst_amt = ($taxable_amt*$inv['igst'])/100;
//                   $payable_amt = $inv['gst_flag'] == 0?$inv['payable_amt']:$inv['payable_amt']+($cgst_amt+$sgst_amt+$igst_amt);
                   $payable_amt = $inv['payable_amt'];
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
                       <td colspan="7"><b>Total : </b></td>
                       <td colspan="7" align="right"><img src="/img/rs.gif" height="8" width="8"/> <b>'.number_format(ceil($total_payable_amt),2).'</b></td>
                   </tr>';
            $tbl .='</table>';
            $pdf->writeHTML($tbl, true, false, false, false, '');
            $pdf->SetFont( '', 'B' ,9);
            $pdf->MultiCell( 0, 5,"Total Invoice Value (In words) : ".$obj->convert_number_to_words(ceil($total_payable_amt)), 0,'L',0, 0 );
            $pdf->Ln();
            $pdf->SetFont( '', '' ,9);
            $pdf->MultiCell( 0, 8,"This is a computer generated invoice.", 0,'L',0, 0 );
            $pdf->lastPage();
            ob_clean();
        }
        else
        {
            $pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
               #Invoice header
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(190, 4, 'INVOICE', 1, 1, 'C');

            $invoiceid = $invoice['data']['invoice_id'];
            $date=$invoice['data']['invoice_date'];
            $month = str_pad($invoice['data']['month'],2, "0", STR_PAD_LEFT);
            $fiscalyear=substr($date,5,2)< 4?((substr($invoice['data']['year'],2,2)-1)."-".(substr($invoice['data']['year'],2,2))):((substr($invoice['data']['year'],2,2))."-".(substr($invoice['data']['year'],2,2)+1));

            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0,0,0);
           $tbl =
            '<style>th{font-weight:bold;}</style>
                <table width="100%" cellpadding="0" border="1">
                <tr>
                <td width="50%">
                <table width="100%">
                <tr>
                <th width="40%">Company Name : </th>
                <td width="55%">'.$invoice['data']['source_name'].'</td>
                </tr>
                <tr>
                <th width="40%">Address : </th>
                <td width="55%">'.$invoice['data']['source_address'].'</td>
                </tr>
                <tr>
                <th width="40%">State : </th>
                <td width="55%">'.$invoice['data']['source_state'].'</td>
                </tr>
                </table>
                </td>
                <td width="50%">
            <table width="100%">
                <tr>
                <th width="40%"> Date : </th>
                <td width="55%">'.date("F d, Y", strtotime($date)).'</td>
                </tr>
                <tr>
                <th width="40%"> Invoice No : </th>
                <td width="55%">'.$fiscalyear."/".$month."/".$invoiceid.'</td>
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
                <tr><th colspan="4" align="center">Bill to</th></tr>
                <tr>
                <th width="40%">Customer Name : </th>
                <td width="55%">'.$invoice['data']['target_name'].'</td>
                </tr>
                <tr>
                <th width="40%">Customer Address : </th>
                <td width="55%">'.$invoice['data']['target_address'].'</td>
                </tr>
                <tr>
                <th width="40%">State : </th>
                <td width="55%">'.$invoice['data']['target_state'].'</td>
                </tr>
                </table>
                </td>
                <td width="50%">
            <table width="100%">
                <tr><th colspan="4" align="center">Remarks if any</th></tr>
            </table>
                </td>
                </tr>
            </table>
            ';

            $tbl .=
            '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th width="50%">DATE</th>
                    <th width="30%">DESCRIPTION</th>
                    <th width="20%">AMOUNT</th>
                </tr>';
                $i = 1;
                $total_payable_amt = 0;
                unset($invoice['data']);
                foreach ($invoice as $inv){
                   $description = $inv['description'];
                   $payable_amt = $inv['payable_amt'];
                   $total_payable_amt += $payable_amt;
                   $tbl .='<tr>
                       <td>'.date("M-Y", strtotime($date)).'</td>
                       <td>'.$description.'</td>
                       <td align="right">'.number_format($payable_amt,2).'</td>
                   </tr>';
                   $i++;
               }
            $tbl .='<tr>
                       <td><b>Amount in words(Rs.):'.$obj->convert_number_to_words(ceil($total_payable_amt)).'</b></td>
                       <td><b>Total</b></td>
                       <td align="right"><img src="/img/rs.gif" height="8" width="8"/> <b>'.number_format(ceil($total_payable_amt),2).'</b></td>
                   </tr>';
            $tbl .='</table>';
            $pdf->writeHTML($tbl, true, false, false, false, '');

                // #Footer
            $pdf->SetFont( '', '' ,10);
            $footer=' <div style="margin-top:10px; font-size:8; text-align:left;">This is a computer generated invoice.</div>';

            $pdf->writeHTML($footer);
            $pdf->lastPage();
            ob_clean();
        }

        if($type == 'invoice'){
            return $pdf->Output(  '/tmp/taxInvoice.pdf', 'INVOICE');
        }
        elseif($type == 0)
        {
            $pdf->Output(  '/tmp/taxInvoice.pdf', 'I');
        }
        elseif($type == 1)
        {
            $pdf->Output(  '/tmp/taxInvoice.pdf', 'FD');
        }
        elseif($type==3)
        {
            $pdf->Output('/tmp/taxInvoice'.$invoiceid.'.pdf', 'F');
            return TRUE;
        }
        exit();
    }

    function sendMail($user_id,$invoice_ids,$month,$year,$email_id)
    {
        App::import('vendor', 'S3', array('file' => 'S3.php'));
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $bucket = invoicebucket;
        $zip = new ZipArchive;
        $document_path = '/tmp/' .$user_id . '.zip';
        $filename = $user_id . '.zip';

        $zip->open('/tmp/' . $user_id . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($invoice_ids as $id)
        {
            $invoice_data = $this->getInvoiceData($user_id,$id,$month,$year,3,$email_id);
            $response = $this->generatePdf($invoice_data,3);

            if($response)
            {
                $filepath = "/tmp/taxInvoice".$id.".pdf";
                $zip->addFile($filepath);
            }
        }

        $zip->close();

        $response = $s3->putObjectFile($document_path, $bucket, $filename, S3::ACL_PRIVATE);

        if($response)
        {
            $mail_subject = 'Invoices';
            $mail_body = 'Please find the invoice attached.';
            $sender_id = null;
            $presigned_url = $s3->aws_s3_link(awsAccessKey,awsSecretKey,$bucket,'/'.$filename,time() - strtotime(date('Y-m-d'))+50);
            if($this->General->sendEmailAttachments($mail_subject,$mail_body,$sender_id,$email_id,$presigned_url,'mail'))
            {
                echo json_encode(array('status'=>'success','type'=>0,'msg'=>'Invoice emailed successfully'));exit();
            }
            else
            {
                echo json_encode(array('status'=>'failure','type'=>2,'data'=>'','msg'=>"Something went wrong. Please try again."));exit();
            }
        }
        else
        {
            echo json_encode(array('status'=>'failure','type'=>2,'data'=>'','msg'=>"Something went wrong. Please try again."));exit();
        }
    }


}