<html>
    <head>
        <title>Emi Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-4.1.0.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
          
        <style>
    .table th{
        align-self: center;
        background: #5F9ED3;
    }
        </style>
    </head>
    <body>


        <h2> Emi Details</h2><br><br><br>
        
                <div class="row">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sr no</th>
                        <th>Emi Date</th> 
                        <th>Installment Amount</th>
                        <th>Actual Amount</th>                      
                        <th>Defaulter Count</th>
                        <th>Is Defaulter</th>
                        <th>Payment Date</th>                     
                    </tr>
                </thead>
                <tr>
                    <?php  $i = 1;
                    foreach($EmiDetails as $emidet) {?>
                    <td> <?php echo $i; ?></td>
                    <td> <?php echo $emidet['e']['emi_date']; ?></td>     
                    <td> <?php echo $emidet['e']['installment_amount']; ?></td>                    
                    <td> <?php echo $emidet['e']['actual_amount']; ?></td>   
                    <td> <?php echo $emidet['e']['defaulter_count']; ?> </td>
                    <td> <?php echo $emidet['e']['is_defaulter']; ?> </td>
                    <td> <?php echo date('Y-m-d', strtotime($emidet['e']['emi_date'] . ' + '.$emidet['e']['defaulter_count'].' day')); ?> </td>
                </tr>
                    <?php $i++; } ?>
            </table>
        </div>
       
    </body>
</html>
