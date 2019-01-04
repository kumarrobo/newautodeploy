
<head>
    <title>DMT Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="/boot/js/jquery-2.0.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
        <?php $ibanktype = 'ekonew'; ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="/dmt/index">Search</a></li>
                <li><a href="/dmt/dmtFromto/ekonew" >All Transactions</a></li>
                <li><a  href="/dmt/accvalidationreport/ekonew" >A/c Validation</a></li>
                <li><a  href="/dmt/dmtAdminPanel" >Admin Panel</a></li>
                <li><a  href="/dmt/dmtCommentSystem" >Comment Panel</a></li>
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <ul class="nav navbar-nav">
                <li class="<?php
                if ($ibanktype == "ekonew") {
                    echo "active";
                }
                ?>"><a href = "/dmt/index/ekonew/" >NewEko</a></li>
<!--                <li class="<?php
                if ($ibanktype == "eko") {
                    echo "active";
                }
                ?>"><a href = "/dmt/index/eko/">Eko </a></li>-->
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
            var retNo = $("#retailer_number").val();
          //var retShopname = $("#retailer_shop").val();
            var sendNo = $("#sender_number").val();
            var acctno = $("#acct_no").val();
            var txnId = $("#txn_id").val();
            var retId = $("#retailer_id").val();
            var dmtId = $("#dmt_id").val();
            var param1 = '';
            var param2 = '';
            var param3 = '';
            var url = '';
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;
            var from = today;
            var to = today;

            if (retNo == "" && sendNo == "" && acctno == "" && txnId == "" && retId == "" && dmtId == "") {
                alert('Kindly fill the fields to get Result');
                return false;
            } else if (retNo != "" ||  sendNo != "" || acctno != "" || txnId != "" || retId != "" || dmtId != "") {
                var searchvalue = [$("#retailer_number").val(), $("#retailer_shop").val(), $("#sender_number").val(), $("#acct_no").val(), $("#txn_id").val(), $("#retailer_id").val(), $("#dmt_id").val()];
            }

            if (retNo != "") {

                param1 = retNo; param2 = '0';
                url = "/dmt/retailersReport/<?php echo $ibanktype; ?>/" + param1.trim() + "/" + param2.trim() +  "/" + from.trim() + "/" + to.trim();


            }
//            else if (retShopname != "") {
//
//                param1 = '0'; param2 = retShopname; param3 = '0';
//                url = "/dmt/retailersreport/<?php// echo $ibanktype; ?>/" + param1.trim() + "/" + param2.trim() + "/" + param3.trim() + "/" + from.trim() + "/" + to.trim();
//            } 
            else if (retId != "") {

                param1 = '0'; param3 = retId;
                url = "/dmt/retailersReport/<?php echo $ibanktype; ?>/" + param1.trim() + "/"  + param3.trim() + "/" + from.trim() + "/" + to.trim();
            }

            if (sendNo != "") {

                param2 = sendNo; param3 = '0';
                url = "/dmt/sendersReport/<?php echo $ibanktype; ?>/" + param2.trim() + "/" + param3.trim() + "/";
            }

            if (acctno != "") {

                param1 = '0'; param2 = '0'; param3 = acctno;
                url = "/dmt/transactionReport/<?php echo $ibanktype; ?>/" + param1.trim() + "/" + param2.trim() + "/" + param3.trim() + "/";
            }



            if (txnId != '') {

                param1 = txnId; param2 = '0';
                url = "/dmt/transactionReport/<?php echo $ibanktype; ?>/" + param1.trim() + "/" + param2.trim() + "/";
            } else if (dmtId != '') {

                param1 = '0'; param2 = dmtId;
                url = "/dmt/transactionReport/<?php echo $ibanktype; ?>/" + param1.trim() + "/" + param2.trim() + "/";
            }


            document.index.action = url;
            document.index.submit();
        }

    </script> 
</head>
<?php
if ($this->params['url']['page'] == '') {
    $this->params['url']['page'] = 1;
}
?>
<body>
    <!-- Start your project here-->
    <form name="index" id = "index" method="POST" onsubmit="setAction();">
        <div class="wrapper">
            <div class="container">
                <h1>Search Panel</h1>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="retailer_number">Retailer Number</label>
                            <input type="text" class="form-control" id="retailer_number" name="retailer_number" value="<?php echo isset($_POST['retailer_number']) ? $_POST['retailer_number'] : ''; ?>" /> 
                        </div>
                        <div class="form-group">
                            <label for="sender_number">Sender Number</label>
                            <input type="text" class="form-control" id="sender_number" name="sender_number" value="<?php echo isset($_POST['sender_number']) ? $_POST['sender_number'] : ''; ?>" /> 
                        </div>
                        <!--<div class="form-group">-->
                            <!--<label for="retailer_shop">Retailer Shop Name</label>-->
                            <!--<input type="text" class="form-control" id="retailer_shop" name="retailer_shop" value="<?php echo isset($_POST['retailer_shop']) ? $_POST['retailer_shop'] : ''; ?>" />--> 
                        <!--</div>-->
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="acct_no">Account Number</label>
                            <input type="text" class="form-control" id="acct_no" name="acct_no" value="<?php echo isset($_POST['acct_no']) ? $_POST['acct_no'] : ''; ?>" /> 
                        </div>
                        <div class="form-group">
                            <label for="txn_id">Wallet Id</label>
                            <input type="text" class="form-control" id="txn_id" name="txn_id" value="<?php echo isset($_POST['txn_id']) ? $_POST['txn_id'] : ''; ?>" /> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="retailer_id">Retailer Id</label>
                            <input type="text" class="form-control" id="retailer_id" name="retailer_id" value="<?php echo isset($_POST['retailer_id']) ? $_POST['retailer_id'] : ''; ?>" /> 
                        </div>
                        <div class="form-group">
                            <label for="txn_id">Order Id</label>
                            <input type="text" class="form-control" id="dmt_id" name="dmt_id" value="<?php echo isset($_POST['dmt_id']) ? $_POST['dmt_id'] : ''; ?>" /> 
                        </div>                       
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="Submit"class="btn btn-primary search" id="srcbtn" name="srcbtn">Search</button>

                        </div>
                    </div>
                    <div>

                        <input type="hidden" class="form-control" id="from_date"  name="from_date" value ="<?php echo isset($_POST['from_date']) ? $_POST['from_date'] : ''; ?>" />
                        <input type="hidden" class="form-control" id="till_date"    name="till_date" value="<?php echo isset($_POST['till_date']) ? $_POST['till_date'] : ''; ?>" /> 
                        <input type="hidden" class="form-control" id="transtatus"    name="transtatus" value="<?php echo $transtatus; ?>" /> 
                        <input type="hidden" class="form-control" id="txnpage"      name="txnpage" value="<?php echo isset($_POST['txnpage']) ? $_POST['txnpage'] : ''; ?>" /> 
                    </div>
                </div>
            </div>
        </div>
    </form>     

    <!-- Start your project here--> 

    <?php if (!$hidden_fld == '') {
        ?>
        <form name="reportform" id = "reportform" method="POST" onsubmit="/dmt/index">
            <div class="wrapper retailer-main">
                <div class="container">
                    <h1>Reports</h1>
                    <div class="row">
                        <div>
                            <input type="hidden" class="form-control" id="retailer_number"  name="retailer_number" value = "<?php echo isset($_POST['retailer_number']) ? $_POST['retailer_number'] : ''; ?>" />
                            <input type="hidden" class="form-control" id="retailer_shop"    name="retailer_shop" value="<?php echo isset($_POST['retailer_shop']) ? $_POST['retailer_shop'] : ''; ?>" /> 
                            <input type="hidden" class="form-control" id="sender_number"    name="sender_number" value="<?php echo isset($_POST['sender_number']) ? $_POST['sender_number'] : ''; ?>" /> 
                            <input type="hidden" class="form-control" id="sender_name"      name="sender_name" value="<?php echo isset($_POST['sender_name']) ? $_POST['sender_name'] : ''; ?>" /> 
                            <input type="hidden" class="form-control" id="txn_id"           name="txn_id" value="<?php echo isset($_POST['txn_id']) ? $_POST['txn_id'] : ''; ?>" /> 
                            <input type="hidden" class="form-control" id="retailer_id"      name="retailer_id" value="<?php echo isset($_POST['retailer_id']) ? $_POST['retailer_id'] : ''; ?>" /> 
                            <input type="hidden" class="form-control" id="search" name="search" value="<?php echo isset($_POST) ? $_POST : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
            </div>
            <div class="col-md-5 text-right">
                <?php echo $this->element('pagination'); ?>
            </div>
        </div>
    </div>
    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="/boot/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
    <script>
            $('#rtbtn').click(function (e) {
                e.preventDefault();
                $('#fer_fld').val('1');
                $('#filerbtn').click();
                $('#fer_fld').val('');
            });


            function goToPage(page = 1, recs =<?php echo $url[3]; ?>) {
                $('#reportform').attr('action', '/dmt/index/' + recs + '?page=' + page);
                $('#reportform').submit();
            }

    </script>
<?php } ?>
</form>
</body>


