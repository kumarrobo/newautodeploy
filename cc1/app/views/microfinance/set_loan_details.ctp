<html>
    <head>
        <title>Loan Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-4.1.0.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

    </head>
    <body>        
        <h2> Loan Panel</h2><br><br><br><br>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">                
                    <ul class="nav navbar-nav">
                        <li class="<?php
                        if ($loantype == "0") {
                            echo "active";
                        }
                        ?>" ><a href = "/microfinance/setLoanDetails/0/" >Paid</a></li>
                        <li class="<?php
                        if ($loantype == "1") {
                            echo "active";
                        }
                        ?>"><a href = "/microfinance/setLoanDetails/1/">Running</a></li>
                        <li class="<?php
                        if ($loantype == "2") {
                            echo "active";
                        }
                        ?>"> <a href = "/microfinance/getLoanDetails/2/">Loan List</a></li>
                    </ul>
                </div>
            </div>
        </nav> <br><br>
        <div class="row">
            <table class="table table-hover">
                <thead style="background: #5F9ED3; align-self: center;">
                    <tr>
                        <th>Sr no</th>
                        <th>Loan No</th>
                        <th>Borrower ShopName</th>
                        <th>Loan Type</th>
                        <th>Pending Amount</th>
                        <th>Loan Amount</th>
                        <th>Emi Count</th>
                        <th>Default Emi Count</th>
                        <?php if ($loantype == 1) { ?>
                        <th>Total Default EMI</th>
                        <?php } ?>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Last Paid Emi Date</th>
                    </tr>
                </thead>
                <tr>
                    <?php $i = 1;
                    foreach ($LoanDetails as $loandet) {
                        ?>
                        <td> <?php echo $i; ?></td>
                        <td> <a href="/microfinance/setEmiDetails/<?php echo $loandet['l']['id']; ?>" target="_blank"><?php echo $loandet['l']['loan_number']; ?></a> </td>                        
                        <td> <a href="/panels/retInfo/<?php echo ($borrower_name[$loandet['b']['user_id']]['ret']['mobile']) ? $borrower_name[$loandet['b']['user_id']]['ret']['mobile'] : $borrower_name[$loandet['b']['user_id']]['dist']['mobile']; ?>" target="_blank"><?php echo $borrower_name[$loandet['b']['user_id']]['imp']['shop_est_name']; ?></a></td>
                        <td> <?php  echo ($loandet['l']['additional_param2'] == 'smart_buy')?'Credit Facility Loan':'Daily Emi Loan'; ?></td>
                        <td> <?php echo $loandet['l']['payment_due']; ?> </td>
                        <td><?php echo $loandet['l']['total_amount']; ?> </td>
                        <td> <?php echo $loandet['l']['emi_count']; ?> </td>
                        <td> <?php echo isset($def_count[$loandet['l']['id']])?$def_count[$loandet['l']['id']]:'0' ?></td>
                        <?php if ($loantype == 1) { ?>                        
                        <td> <?php echo isset($totdefault_count[$loandet['l']['id']])?$totdefault_count[$loandet['l']['id']]:'0' ?></td>
                        <?php  } ?>
                        <td> <?php echo isset($loandet['l']['approval_date'])?date('Y-m-d', strtotime($loandet['l']['approval_date'] . ' + 1 days')):'0000-00-00'; ?></td>
                        <td> <?php echo isset($loandet['l']['due_date'])?$loandet['l']['due_date']:'0000-00-00' ?></td>                        
                        <?php if ($loantype == 0) { ?>
                            <td> <?php echo isset($loandet['l']['emi_date'])?date('Y-m-d', strtotime($loandet['l']['emi_date'] . ' - 1 days')) : '0000-00-00'; ?></td> 
                            <?php } elseif ($loantype == 1) { ?>
                            <td> <?php echo isset($emi_date[$loandet['l']['id']])?$emi_date[$loandet['l']['id']]:'0000-00-00'; ?></td>
                        <?php } ?>
                    </tr>
                    <?php $i++;
                } ?>
            </table>
        </div>

    </body>
</html>
<script>

$(document).ready(function() {
    $('.table').dataTable({
    // "order": [[0, "desc" ]],
    "pageLength":100,
    "lengthMenu": [[10,100, 200, 500,-1],[10,100, 200, 500,'All']],
    });

    });
    </script>
