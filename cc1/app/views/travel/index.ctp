<html>
<head>
    <title>Travel Panel</title>
    <meta charset="utf-8">    
    <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="/travel/index/">Search</a></li>
                <li><a href="/travel/travelFromTo" >All Transactions</a></li>                
            </ul>
        </div>
    </nav>
   

    <style>
        tfoot {border:none !important;}
    </style>
    <?php
    $fldno = 0;
    $fldname = 0;
    ?>
    <script>
        function setAction() {            
            var retNo = $("#reg_mobnumber").val();          
            var pnr = $("#pnr_no").val();
            var shopid = $("#shop_txn_id").val();
            var txnId = $("#txn_id").val();
            var retId = $("#ret_Id").val();
            var ven_txn_id = $("#ven_txn_id").val();
            var param1 = '';
            var param2 = '';
            var param3 = '';
            var param4 = '';
            var url = '';

            if (retNo == "" && pnr == "" && shopid == "" && txnId == "" && retId == "" && ven_txn_id == "") {
                alert('Kindly fill the fields to get Result');
                return false; 
            }
//            } else if (retNo != "" ||  pnr != "" || shopid != "" || txnId != "" || retId != "" || ven_txn_id != "") {
//                var searchvalue = [$("#reg_mobnumber").val(), $("#pnr_no").val(), $("#shop_txn_id").val(), $("#ret_Id").val(), $("#txn_id").val(), $("#ven_txn_id").val();
//            }

            if (retNo != "") {

                param1 = retNo; param2 = '0';
                url = "/travel/travelRetailersReport/" + param1.trim() + "/" + param2.trim();


            }
//            else if (retShopname != "") {
//
//                param1 = '0'; param2 = retShopname; param3 = '0';
//                url = "/travel/retailersreport/<?php// echo $ibanktype; ?>/" + param1.trim() + "/" + param2.trim() + "/" + param3.trim() + "/" + from.trim() + "/" + to.trim();
//            } 
            else if (retId != "") {

                param1 = '0'; param3 = retId;
                url = "/travel/travelRetailersReport/" + param1.trim() + "/"  + param3.trim();
            }

            if (pnr != "") {

                param2 = pnr; param3 = '0';
                url = "/travel/travelTransactionReport/" + param2.trim() + "/" + param3.trim() + "/";
            }

            if (shopid != "") {

                param1 = '0'; param2 = '0'; param3 = shopid;
                url = "/travel/travelTransactionReport/" + param1.trim() + "/" + param2.trim() + "/" + param3.trim() + "/";
            }



            if (txnId != '') {

                param1 = '0'; param2 = txnId; param3 = '0'; param4 = '0';
                url = "/travel/travelTransactionReport/" + param1 + "/" + param2.trim() + "/" +  param3 + "/" + param4 + "/";
            } else if (ven_txn_id != '') {

                 param1 = '0'; param2 = '0'; param3 = '0'; param4 = ven_txn_id;
                url = "/travel/travelTransactionReport/" + param1.trim() + "/" + param2.trim() + "/" +  param3.trim() + "/" + param4.trim() + "/";
            }


            document.index.action = url;
            document.index.submit();
        }

    </script> 
</head>
<body>
    <!-- Start your project here-->
    <form name="index" id = "index" method="POST" onsubmit="setAction()">
        <div class="wrapper">
            <div class="container">
                <h1>Search Panel</h1>
                <div class="row">
                    <div class="col-md-4">                        
                            <label for="reg_mobnumber">Registered Mobile Number</label>
                            <input type="text" class="form-control" id="reg_mobnumber" name="reg_mobnumber" value="<?php echo isset($_POST['reg_mobnumber']) ? $_POST['reg_mobnumber'] : ''; ?>" /> 
                        </div>
                    <div class="col-md-4">
                        <label for="pnr_no">PNR</label>
                        <input type="text" class="form-control" id="pnr_no" name="pnr_no" value="<?php echo isset($_POST['pnr_no']) ? $_POST['pnr_no'] : ''; ?>" /> 
                    </div>
                    <div class="col-md-4">
                        <label for="txn_id">Pay1 Travel Id</label>
                        <input type="text" class="form-control" id="txn_id" name="txn_id" value="<?php echo isset($_POST['txn_id']) ? $_POST['txn_id'] : ''; ?>" /> 
                    </div>
                        <!--<div class="form-group">-->
                            <!--<label for="retailer_shop">Retailer Shop Name</label>-->
                            <!--<input type="text" class="form-control" id="retailer_shop" name="retailer_shop" value="<?php echo isset($_POST['retailer_shop']) ? $_POST['retailer_shop'] : ''; ?>" />--> 
                        <!--</div>-->
                    </div>
                    <div class="row">
                        <div class="col-md-4">    
                            <label for="ret_Id">Retailer Id</label>
                            <input type="text" class="form-control" id="ret_Id" name="ret_Id" value="<?php echo isset($_POST['ret_Id']) ? $_POST['ret_Id'] : ''; ?>" /> 
                        </div>
                        <div class="col-md-4">
                            <label for="shop_txn_id">Shop Txn Id</label>
                            <input type="text" class="form-control" id="shop_txn_id" name="shop_txn_id" value="<?php echo isset($_POST['shop_txn_id']) ? $_POST['shop_txn_id'] : ''; ?>" /> 
                        </div>
                        <div class="col-md-4">
                            <label for="ven_txn_id">Vendor Txn Id</label>
                            <input type="text" class="form-control" id="ven_txn_id" name="ven_txn_id" value="<?php echo isset($_POST['ven_txn_id']) ? $_POST['ven_txn_id'] : ''; ?>" /> 
                        </div>                       
                    </div><br>
                    <div class="col-md-4">                        
                            <button type="button"class="btn btn-primary search" id="srcbtn" name="srcbtn" onclick="setAction()">Search</button>                        
                    </div>
                </div>
            </div>       
    </form>     
</body>


