<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
    
<nav class="navbar navbar-default">
        <div class="container-fluid">
                <div class = "row">	
                        <div class = "col-md-2">
                                <div class="navbar-header">
                                        <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'panels','action'=>'tranDiffReport'))); ?>
                                </div>
                        </div>
                        <div class = "col-md-8" align = "center">
                                <h2><b>Transaction Difference Report</b></h2>
                        </div>  
                </div>
        </div>
</nav>
<span>
    <b>Date -> </b> From : <input type="text" id="frm_date" value="<?php echo $frm_date; ?>" style="border-radius: 3px; padding: 0px 10px; width: 125px;" />&nbsp;
    To : <input type="text" id="to_date" value="<?php echo $to_date; ?>" style="border-radius: 3px; padding: 0px 10px; width: 125px;" />&nbsp;
    Company  :   <select id="vendor" style="width: 200px;">
                    <option value="all">All</option>
                    <?php foreach($vendors as $key=>$vendor) { ?>
                    <option value="<?php echo $key; ?>" <?php if($sel_vendor == $key) { echo "selected"; } ?>><?php echo $vendor; ?></option>
                    <?php } ?>
                </select>&nbsp;
    Operator :   <select id="product" style="width: 200px;">
                    <option value="all">All</option>
                    <?php foreach($products as $key=>$product) { ?>
                    <option value="<?php echo $key; ?>" <?php if($sel_product == $key) { echo "selected"; } ?>><?php echo $product; ?></option>
                    <?php } ?>
                </select>&nbsp;&nbsp;
    <input type="button" value="Submit" onclick="window.location='/panels/tranDiffReport/'+$('#frm_date').val()+'/'+$('#to_date').val()+'/'+$('#vendor').val()+'/'+$('#product').val()" class="btn btn-primary" />
</span><br /><br />

<div class="tab-content">
        <div class="tab-pane active" id="list">
                <div class="table-responsive">
                        <table class="tablesorter table table-hover table-bordered" id = "plantable">
                                <thead>
                                        <tr>
                                                <th class = "field-label active" style = "width: 4%;">#</th>
                                                <th class = "field-label active" style = "width: 8%;">TxnId</th>
                                                <th class = "field-label active" style = "width: 6%;">Amount</th>
                                                <th class = "field-label active" style = "width: 8%;">Company</th>
                                                <th class = "field-label active" style = "width: 8%;">Operator</th>
                                                <th class = "field-label active" style = "width: 16%;">Vendor Ref_Code</th>
                                                <th class = "field-label active" style = "width: 6%;">Reversed</th>
                                                <th class = "field-label active" style = "width: 18%;">Reversed By</th>
                                                <th class = "field-label active" style = "width: 16%;">Date</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach($tran_data as $list) { ?>
                                        <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo "<a href='/panels/transaction/".$list['a']['ref_code']."' target='parent'>".$list['a']['ref_code']."</a>"; ?></td>
                                                <td><?php echo $list['a']['amount']; ?></td>
                                                <td><?php echo $vendors[$list['a']['vendor_id']]; ?></td>
                                                <td><?php echo $products[$list['a']['product_id']]; ?></td>
                                                <td><?php echo $list['a']['vendor_refid']; ?></td>
                                                <td><?php echo $list['a']['response'] == '' ? 'System' : 'Manual'; ?></td>
                                                <td><?php echo $list['a']['response'] == '' ? '<center>-</center>' : $list['a']['response']; ?></td>
                                                <td><?php echo date('d-M-Y', strtotime($list['a']['timestamp'])) . ' &nbsp;<strong>at</strong>&nbsp; ' . date('h:i:s A', strtotime($list['a']['timestamp'])); ?></td>
                                        </tr>
                                        <?php } ?>
                                </tbody>
                        </table>
                </div>
        </div>
</div>

<script>
    $(document).ready(function () {
            $('#frm_date, #to_date').datepicker({
                    format: "yyyy-mm-dd",
                    endDate: "1d",
                    multidate: false,
                    autoclose: true,
                    todayHighlight: true
            });  
    });
</script>