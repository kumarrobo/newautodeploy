<html>
    <head>
        <title>Mutual Fund Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">        
        <link  rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="/boot/js/insurance.js"></script>
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-4.1.0.min.js"></script>          
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

        
        <style>
        .table th{
            align-self: center;
            background: #93E5DD;
        }
        .btn.btn-primary.search {
            margin: 24px;
        }        
    </style>

    </head>
    <body>        
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">                
                    <ul class="nav navbar-nav">
                        <li class="<?php
                        if ($type == "0") {
                            echo "active";
                        }
                        ?>" ><a href = "/insurance/index/0/" >Insurance</a></li>
                        <li class="<?php
                        if ($type == "1") {
                            echo "active";
                        }
                        ?>"><a href = "/insurance/mutualfundPanel/1/">Mutual fund</a></li>
                    </ul>
                </div>
            </div>
        </nav> <br><br>
        <h2> Mutual Fund Panel</h2>
        
        
            <form id="mutualfund" name="mutualfund" method="POST">
                <div class="form-group">
                    <div class="row">
                    <div class="col-md-3">
                        <label for="mfdate"> From Date</label>
                        <input type="text" id="mfdate" name="mfdate" class="form-control" value="<?php echo $frmdate; ?>">
                    </div>
                     <div class="col-md-3">
                        <label for="mfdate"> To Date</label>
                        <input type="text" id="mtdate" name="mtdate" class="form-control" value="<?php echo $todate; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="mretuserid">Retailer UserId</label>
                        <input type="text" id="mretuserid" name="mretuserid" class="form-control" value="<?php echo $userid; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="mretmobile">Retailer Mobile</label>
                        <input type="text" id="mretmobile" name="mretmobile" class="form-control" value="<?php echo $mobile; ?>">
                    </div>
                    </div><br>
                    <div class="row">
                     <div class="col-md-3">
                        <label for="mcustmobile">Customer Mobile</label>
                        <input type="text" id="mcustmobile" name="mcustmobile" class="form-control" value="<?php echo $custmobile; ?>">
                    </div>
                     <div class="col-md-3">
                        <label for="mcustmobile">Reference Code</label>
                        <input type="text" id="mreferencecode" name="mreferencecode" class="form-control" value="<?php echo $refercode; ?>">
                    </div>
                        <div class="col-md-1" style="margin-top:11px">                        
                        <button style="float:right; margin: 10px 0px 0px 20px" type="submit" class="btn btn-primary"> Submit </button>
                     </div>                        
                    </div>
                    
                </div>
                
                <br>                              
                <div class="table-responsive">
                    <h4>Retailer wise Count</h4>
                    <table class="table table-hover table-bordered" style="width:300px;border-width: thick">
                    <thead style="background: #93E5DD;">                            
                                <th> Customer Count</th>
                                <th> Sip Count </th>
                                <th> Amount Invested </th>
                                <!--<th> Market Value </th>-->
                    </thead>                      
                    <tr style="font-weight:bold;font-size: 14px">                        
                        <td> <?php echo  isset($mutualfundCount['0']['0']['unique_customers'])?$mutualfundCount['0']['0']['unique_customers']:0 ?></td>
                        <td> <?php echo  isset($mutualfundCount['0']['0']['total_sips'])?$mutualfundCount['0']['0']['total_sips']:0 ?></td>
                        <td> <?php echo  isset($mutualfundAmt['0']['0']['total_investment'])?$mutualfundAmt['0']['0']['total_investment']:0 ?></td>
                        <!--<td> <?php //echo $mfundcount['0']['market_value']; ?></td>-->                        
                    </tr>
                </table>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                    <thead style="background: aquamarine;">                            
                                <th> Id</th>
                                <th> Name </th>
                                <th> Cust Mobile </th>
                                <th> User Id </th> 
                                <th> Reference code </th> 
                                <th> Sip Amount </th> 
                                <th> Investment date </th> 
                                <th> Investment Start date </th>                                                                 
                    </thead>                      
                    <tr>                        
                        <?php $i = 1; 
                            foreach($mutualfundReport as $data){ ?>
                        <td> <?php echo  $i; ?></td>
                        <td> <?php echo  $data['a']['name']; ?></td>
                        <td> <?php echo  $data['a']['mobile']; ?></td>
                        <td> <?php echo  $data['a']['user_id']; ?></td>                        
                        <td> <?php echo  $data['a']['reference_code']; ?></td>
                        <td> <?php echo  $data['c']['sip_amount'];?></td>
                        <td> <?php echo  date($data['c']['investment_date']); ?></td>
                        <td> <?php echo  $data['b']['investment_start_date']; ?></td>
                    </tr>
                            <?php $i++;}  ?>
                </table>
                </div>
                                
                
                
            </form>        
    </body>
</html>