<!--<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<div>
    <form method="post" id="userTxnFloatReport" action="/accounting/userTxnFloatReport/<?php echo $user_id;?>/<?php echo $date;?>">
                       <div class="row">
<!--<form action="/accounting/balanceSheet" method="post">-->
<div class="col-md-2"><span style="font-weight:bold;">Select Date</span></div>
<div class="col-md-2"><input type="text" class="form-control" style="width: 100px; margin-top: -5px; margin-left: -105px;" id="date" name="date" value="<?php echo $date; ?>"></div>
<div class="col-md-2"><input class="btn btn-primary" type="submit" value="Submit" id="submit" style="padding: 5px 10px; margin-left: -180px;margin-top: -3px;"></div>
<input class="btn btn-primary" type="button" value="Back" id="Submit1" onclick="window.location='/accounting/floatReport/1/<?php echo $date; ?>'" style="float : right;padding: 5px 10px; margin-left: -180px;">
<!--</form>-->
</div>
</form>
    <br/><br/>

    <div class="row">
                        <table class="table table-responsive table-bordered" >
                            <thead>
                              <tr class="filters">
                                <th style="text-align:center;">Txn Id</th>
                                <th style="text-align:center;">Particulars</th>
                                <th style="text-align:center;">Debit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                <th style="text-align:center;">Credit (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                <th style="text-align:center;">Opening (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                <th style="text-align:center;">Closing (<span><img class='rupeeBkt' src='/img/rs.gif'/></span>)</th>
                                <th style="text-align:center;">Datetime</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($user_txn as $txn){
                                    ?>
                                    <tr>
                                        <td><?php echo $txn['st']['id'];?></td>
                                        <td><?php echo $txn['st']['particulars'];?></td>
                                        <td><?php echo $txn[0]['debit'];?></td>
                                        <td><?php echo $txn[0]['credit'];?></td>
                                        <td><?php echo $txn[0]['opening'];?></td>
                                        <td><?php echo $txn[0]['closing'];?></td>
                                        <td><?php echo $txn['st']['timestamp'];?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
    
</div>
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>
<script>

    $('#date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "-1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
    
    $('.table').dataTable({
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],
        "pageLength":100,
        "lengthMenu": [100, 200, 500]
    });
</script>
