<html>
    <head>
        <title>Dmt Commenting System</title>
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">        
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">        
        <link   rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="/boot/js/panservices.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>         
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>         
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

       <style>
            .form-control{
            width:180px;}
        </style>
    </head>
    <body>
        <?php $banktype = 'ekonew'; ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                </div>
                <ul class="nav navbar-nav">
                    <li><a href = "/dmt/index/<?php echo $banktype; ?>" >Search</a></li>
                    <li><a href="/dmt/dmtFromto/<?php echo $banktype; ?>" >All Transactions</a></li>
                    <li><a   href="/dmt/accvalidationreport/<?php echo $banktype; ?>" >A/c Validation</a></li>
                    <li><a  href="/dmt/dmtAdminPanel" >Notification Panel</a></li>      
                    <li class="active"><a  href="/dmt/dmtCommentSystem" >Comment Panel</a></li>
                </ul>
            </div>
        </nav>
        <br><h3>Comment Section</h3><br>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <th> id </th>
                        <th> Order id </th>
                        <th> Tag </th>
                        <th> Comment</th>
                        <th> Commented By </th>   
                        <th> Created on </th>
                        </thead>
                        <tbody>
                                <?php 
                                $i = 1;
                                foreach($comm as $dmt) { ?>
                                <tr> 
                                <td><?php echo $i; ?> </td>
                                <td> <?php echo "<a style='font-size:Normal;' target='_blank' href='/dmt/transactionReport/ekonew/0/". $dmt['comments_new']['ref_id'] . "'>" . $dmt['comments_new']['ref_id'] . " </a>" ?></td>
                                <td> <?php echo $tagname[$dmt['comments_new']['subtag_id']]; ?></td>
                                <td> <?php echo $dmt['comments_new']['comment']; ?></td>
                                <td> <?php echo $users[$dmt['comments_new']['cc_id']]; ?></td>
                                <td> <?php echo $dmt['comments_new']['created_at']; ?></td>
                                </tr>
                                <?php $i++; } ?>
                        </tbody>
                    </table>
                </div>
    </body>
    
</html>