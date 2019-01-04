<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<!--<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/boot/css/fixedHeader.dataTables.min.css">-->
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">

<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px; 
        border-radius: 10px;
    }

    .tab a {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab a:hover {
        background-color: #428bca!important;
        color: #fff!important;
    }

    .tab a.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }
    
    thead{
       color: #000;
    }
    
    tr {
	border:none;
}

    td {
         text-align: center;
    }

    .table>thead>tr>th {
    vertical-align: middle;
    }
    
</style>
<div class="container">
<div class="tab">

    <ul class="nav nav-tabs">
        <li><a class="tablinks active" href="/accounting/floatReport">Float Report</a></li>
    </ul>
    
</div>
<br/><br/>
<div class="row">
<!--<form action="/accounting/balanceSheet" method="post">-->
<div id = "type1">
    <div class="col-md-1"><span style="font-weight:bold;">From Date</span></div>
    <div class="col-md-2"><input type="text" class="form-control datepicker" style="margin-top: -5px;" id="from_date" name="from_date" value="<?php echo $from_date; ?>"></div>
    <div class="col-md-1"><span style="font-weight:bold;">To Date</span></div>
    <div class="col-md-2"><input type="text" class="form-control datepicker" style=" margin-top: -5px; " id="to_date" name="to_date" value="<?php echo $to_date; ?>"></div>
    <div class="col-md-2"><input class="btn btn-primary" type="button" value="Submit" id="submit" onclick="submit(1)" style="padding: 5px 10px; "></div>
</div>
<div id = "type2">
    <div class="col-md-1"><span style="font-weight:bold;">Select Date</span></div>
    <div class="col-md-2"><input type="text" class="form-control datepicker" style="margin-top: -5px;" id="date" name="date"></div>
    <input type="hidden" class="form-control datepicker" style="margin-top: -5px;" id="from_date" name="from_date">
    <input type="hidden" class="form-control datepicker" style="margin-top: -5px;" id="to_date" name="to_date">
    <div class="col-md-2"><input class="btn btn-primary" type="button" value="Submit" id="submit" onclick="submit(2)" style="padding: 5px 10px; "></div>
</div>

<!--</form>-->
</div>
</br>
<div class="row">
<div id="ds"></div>
</div>
</div>
</br>
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>
<script>
    
    $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        endDate: "-1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });

    jQuery(function(){
      <?php  if($back){ ?>
           submit(2,'<?php echo $date;?>');
       <?php } else{ ?>
           submit(1);
       <?php } ?>
    });

//  
//    jQuery('#submit').click(function() {
     
    function submit(type=1,date = ''){

        if(type == 1){
            jQuery("#type1").show();
            jQuery("#type2").hide();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
        }else{
            jQuery("#type1").hide();
            jQuery("#type2").show();
            if(date != ''){
                $('#date').val(date);
            }else{
                date = $('#date').val();
            }
            
            var from_date = date;
            var to_date = date;
        }

        var data = {
                'ajax' : 1,
                'from_date' : from_date,
                'to_date' : to_date,
                'type' : type
            };
    jQuery.ajax({
        type: 'POST',
        url: '/accounting/floatReport',
        dataType: 'json',
        data: data,

        success: function(response) {
                if(type == 2){
                    var floatreport = "<input class='btn btn-primary' type='button' value='Back' id='submit' onclick='submit(1)' style='float : right;padding: 5px 10px; margin-left: -180px;margin-top: -50px;'>";
                }else{
                    floatreport = "";
                }
                    floatreport += "<table class='table table-responsive table-bordered' id='floatreport'>";
                    floatreport += "<thead>";
                    floatreport += "<tr class='filters'>";
                if(type == 1){
                         floatreport += "<th rowspan='2' style = 'text-align: center'>Date</th>";
                }
                if(type != 1){
                    floatreport += "<th rowspan='2' style = 'text-align: center'>User Id</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Name</th>";
                }
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Opening</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Closing</th>";
                    floatreport += "<th colspan='2' style = 'text-align: center'>Top-Up</th>";
                    floatreport += "<th colspan='2' style = 'text-align: center'>Sale Dr</th>";
                    floatreport += "<th colspan='2' style = 'text-align: center'>Sale Cr</th>";
                    floatreport += "<th colspan='2' style = 'text-align: center'>Commission</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>TDS</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Setup Fee</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Service Charge</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Kit Charge</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Security Deposit</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>One Time Charge</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Rental</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Incentive</th>";
                    floatreport += "<th rowspan='2' style = 'text-align: center'>Diff</th>";
                    floatreport += "</tr>";
                    floatreport += "<tr class='filters'>";
                    floatreport += "<th>Given</th><th>Return</th>";
                    floatreport += "<th>Given</th><th>Return</th>";
                    floatreport += "<th>Given</th><th>Return</th>";
                    floatreport += "<th>Given</th><th>Return</th>";
                    floatreport += "</tr>";
                    floatreport += "</thead>";
                    floatreport += "<tbody>";
                for (var x in response) {
                    
                    if (!isNaN(x) && response[x][0].opening != null) {
                        
                    var selected_date = response[x].float_report.date;
                    var opening = response[x][0].opening;
                    var closing = response[x][0].closing;
                    var topup   = response[x][0].topup;
                    var topup_reverse = response[x][0].topup_reverse;
                    var sale_dr = response[x][0].sale_dr;
                    var sale_cr = response[x][0].sale_cr;
                    var sale_reverse_cr = response[x][0].sale_reverse_cr;
                    var sale_reverse_dr = response[x][0].sale_reverse_dr;
                    var commission = response[x][0].commission;
                    var commission_reverse = response[x][0].commission_reverse;
                    var tds = response[x][0].tds;
                    var setup_fee = response[x][0].setup_fee;
                    var service_charge= response[x][0].service_charge;
                    var kit_charge = response[x][0].kit_charge;
                    var security_deposit = response[x][0].security_deposit;
                    var one_time_charge = response[x][0].one_time_charge;
                    var rental = response[x][0].rental;
                    var incentive = response[x][0].incentive;
                    
                    
                    floatreport += "<tr>";
                if(type != 1){
                    floatreport += "<td>"+ (response[x].float_report.user_id == null ? '' : response[x].float_report.user_id)+"</td>";
                    floatreport += "<td>"+ (response[x].float_report.shop_est_name == null ? '' : response[x].float_report.shop_est_name)+"</td>";
                }
                if(type == 1){
                    floatreport += "<td>"+ (opening == null ? '' : selected_date)+"</td>";
                }
                    floatreport += "<td>"+ (opening == null ? '' : opening)+"</td>";
                    floatreport += "<td>"+ (closing == null ? '' : closing)+"</td>";
                    floatreport += "<td>"+ (topup == null ? '' : topup)+"</td>";
                    floatreport += "<td>"+ (topup_reverse == null ? '' : topup_reverse)+"</td>";
                    floatreport += "<td>"+ (sale_dr == null ? '' : sale_dr)+"</td>";
                    floatreport += "<td>"+ (sale_cr == null ? '' : sale_cr)+"</td>";
                    floatreport += "<td>"+ (sale_reverse_cr == null ? '' : sale_reverse_cr)+"</td>";
                    floatreport += "<td>"+ (sale_reverse_dr == null ? '' : sale_reverse_dr)+"</td>";
                    floatreport += "<td>"+ (commission == null ? '' : commission)+"</td>";
                    floatreport += "<td>"+ (commission_reverse == null ? '' : commission_reverse)+"</td>";
                    floatreport += "<td>"+ (tds == null ? '' : tds)+"</td>";
                    floatreport += "<td>"+ (setup_fee == null ? '' : setup_fee)+"</td>";
                    floatreport += "<td>"+ (service_charge == null ? '' : service_charge)+"</td>";
                    floatreport += "<td>"+ (kit_charge == null ? '' : kit_charge)+"</td>";
                    floatreport += "<td>"+ (security_deposit == null ? '' : security_deposit)+"</td>";
                    floatreport += "<td>"+ (one_time_charge == null ? '' : one_time_charge)+"</td>";
                    floatreport += "<td>"+ (rental == null ? '' : rental)+"</td>";
                    floatreport += "<td>"+ (incentive == null ? '' : incentive)+"</td>";
                
                var diff_value = (parseInt(opening) + parseInt(topup) - parseInt(topup_reverse) - parseInt(sale_dr) + parseInt(sale_reverse_dr) + parseInt(sale_cr) - parseInt(sale_reverse_cr) + parseInt(commission) - parseInt(commission_reverse) - parseInt(tds) - parseInt(setup_fee) - parseInt(service_charge) - parseInt(kit_charge) - parseInt(security_deposit) - parseInt(one_time_charge) - parseInt(rental) + parseInt(incentive)); 
                var diff = parseInt(closing) - diff_value;
                if(diff > 0){
                    var style = 'background-color:green;';
                }else{
                    var style = 'background-color:red;';
                }
                if(type == 1){
                    floatreport += "<td style='"+style+"'><a href='javascript:void(0)' style='color:white' onClick='submit(2,\""+selected_date+"\")'>"+ diff+"</a></td>";
                }else if(type == 2){
                    floatreport += "<td style='"+style+"'><a href='/accounting/userTxnFloatReport/"+response[x].float_report.user_id+"/"+response[x].float_report.date+"' style='color:white'>"+ (diff == null ? '' : diff)+"</a></td>";
                }
                }
                }
                    floatreport += "</tr>";
                    floatreport += "</tbody>";
                    floatreport += "</table>";

                jQuery("#ds").html(floatreport);
                
            }
        });
        
        jQuery('.table').dataTable({
                        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],
                        "pageLength":100,
                        "lengthMenu": [100, 200, 500]
                });
    }
        
    
</script>