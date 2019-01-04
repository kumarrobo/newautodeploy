<html>
    <head>
        <title>Service Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">        
        <link  rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">        
        <link rel="stylesheet" href="/boot/css/dmt.css">
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script type="text/javascript" src="/boot/js/dmt.js"></script>
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
    <h2> Service Panel</h2>        
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <ul class="nav navbar-nav">
                <li><a href="/dmt/dmtFromto/ekonew" >All Transactions</a></li>
                <li><a  href="/dmt/dmtAdminPanel" >Notification Panel</a></li>                
                <li class="active"><a href="/dmt/serviceToggle">Service Panel</a></li>
                <li><a href="/dmt/refundPanel">Refund Panel</a></li>
            </ul>
        </div>
    </nav>        
         <div class="container-fluid">
            <form id="servinceTogForm" name="serviceTogForm" method="POST">
                <div class="table-responseive">
                    <table class="table table-bordered">
                        <thead>
                        <th>id</th>
                        <th>Vendor</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Service</th>
                        <th>Status</th>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach($vendorDet as $vend) {?>
                            <tr>
                                <td><?php echo $i; ?></td>
                               <td><label><?php echo $vend['vendor_master']['vendor_name'];?></label> </td>
                              <input type="hidden" id = "serviceTog_Vendor_"<?php echo $vend['vendor_master']['id'] ?> name ="serviceTog_Vendor" value="<?php echo $vend['vendor_master']['platform_vendor_id'];?>">
                                <td><label>IMPS</label> </td>
                                 <td> <label class="switch">
                                <input type="checkbox" id="serviceTog_IMPSShowFlag_<?php echo $vend['vendor_master']['id'];?>" name="serviceTog_IMPSShowFlag" <?php echo ($vend['vendor_master']['imps_active'] == '1')?"checked":""; ?>
                                       onchange="setImpsToogle(<?php echo $vend['vendor_master']['id'];?>,<?php echo $vend['vendor_master']['platform_vendor_id'];?>,'imps')"> <span class="slider round"></span>
                                    </label>
                                 </td>        
                                 <td><label>NEFT</label> </td>
                                 <td> <label class="switch">
                                <input type="checkbox" id="serviceTog_NEFTShowFlag_<?php echo $vend['vendor_master']['id'];?>" name="serviceTog_NEFTShowFlag" <?php echo ($vend['vendor_master']['neft_active'] == '1')?"checked":""; ?>
                                       onchange="setNeftToogle(<?php echo $vend['vendor_master']['id'];?>,<?php echo $vend['vendor_master']['platform_vendor_id'];?>,'neft')"> <span class="slider round"></span>
                                    </label>
                                 </td>
                            </tr>
                            <?php $i++ ;} ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </body>
</html>
