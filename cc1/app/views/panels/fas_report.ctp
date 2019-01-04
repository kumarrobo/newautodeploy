<html>
    <head><title> Failure After Success </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link   rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
    </head>
    <style>
            .type .btn-group{display: block;}
            .type .multiselect.dropdown-toggle.btn.btn-default {
                display: block;
                overflow: hidden;
                width: 100%;
            }
    </style>    

<body>
    <div align = "left" class="row">
        <h1>Failure after Success</h1>
        <button id="fas_excel" name="fas_excel" style="float:right;" class="btn btn-primary">Download Excel</button>        
    </div>
    <br><br>
    
    <form id="fas_form" name ="fas_form" method="POST">
        
    <div class="row">
        <div class ="col-lg-2">
        <label for="fas_from">From </label> 
        <input type="text" class="form-control" style='width:180px;float:left;'  id="fas_from" name="fas_from" value="<?php if (!empty($from_date)) echo $from_date; ?>">
        </div>
        <div class ="col-lg-2">
        <label for="fas_to">To  </label> 
        <input type="text" class="form-control" style='width:180px;float:left;'  id="fas_to" name="fas_to" value="<?php if (!empty($to_date)) echo $to_date; ?>">
        </div>
        <div class ="col-lg-2">
        <label for="fas_txn_id">Txn Id </label> 
        <input type="text" class="form-control" style='width:180px;float:left;'  id="fas_txn_id" name="fas_txn_id" value="<?php if (!empty($ftxn_id)) echo $ftxn_id; ?>">
        </div>
        <div class ="col-lg-2 type">
        <label for="fas_vendors">Vendors </label> 
        <select class="form-control" style='width:300px;float:left' id="vendor_id" name="vendor_id[]"  multiple="multiple">
            <?php foreach ($vendors as $v): ?>
                <option value="<?php echo $v['vendors']['id'] ?>" <?php if (in_array($v['vendors']['id'], $fvendor)) echo "selected" ?> >
                    <?php echo $v['vendors']['company'] ?>
                </option>
            <?php endforeach ?>
        </select>
        </div>
        <div class ="col-lg-2 type">
        <label for="fas_operators">Operators </label> 
        <select class="form-control" style='width:10px;' id="product_id" name="product_id[]"  multiple="multiple">
            <?php foreach ($products as $p): ?>
                <option value="<?php echo $p['products']['id'] ?>" <?php if (in_array($p['products']['id'], $foperator)) echo "selected" ?> >
                    <?php echo $p['products']['name'] ?>
                </option>
            <?php endforeach ?>
        </select>
        </div>
        <input type="hidden" name="fasexcel_fld" id ="fasexcel_fld">                  
    </div>
        <div class="row">
            <div class="col-lg-2">
                <label style="margin:5px 0px 6px 0px;">Show:</label>              
                    <div class="checkbox">
                              <label><input id="modem_flag" name ="modem_flag" type="checkbox" value="1" <?php if($modem_flag == '1') echo "checked" ?>>Modem</label> &nbsp;
                              <label><input id="api_flag" name="api_flag" type="checkbox" value="1" <?php if($api_flag == '1') echo "checked" ?>>API</label>
             </div></div>
            <div class="col-lg-2 type">
                <label style="margin:5px 0px 6px 0px;" for="fas_Services">Services </label> 
                <select class="form-control" style='width:300px;float:left' id="service_id" name="service_id[]"  multiple="multiple">
                    <?php foreach ($services as $s): ?>
                        <option value="<?php echo $s['services']['id']; ?>" <?php if (in_array($s['services']['id'], $fservices)) echo "selected" ?> >
                            <?php echo $s['services']['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>                
            </div>
          <div class ="col-lg-2">        
            <button id="fas_btn" name="fas_btn" class="btn btn-primary" style="margin-top: 30px;float:left;">Search </button>                      
          </div></div>
        <br><br><br><br>    
    <div class="container">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Sr no</th>
                <th>Trans Id</th>
                <th>Vendor Id</th>
                <th>Vendor</th>
                <th>Operator</th>
                <th>Amount</th> 
                <th>Prev Status</th>
                <th>Pay1 Status</th>
                <th>Vendor Status</th>
                <th>Date</th>
                <th>Action</th>
                <th>Action Taken on</th>
                <th>Action Taken by </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            foreach($fas_data as $fas) { ?>
        <tr>
            <td> <?php echo $i;?> </td>
            <td> <a href="/panels/transaction/<?php echo $fas['tms']['txn_id']; ?>" target="_blank">
                <?php echo $fas['tms']['txn_id'];?></a></td>
            <td> <?php echo $fas['va']['vendor_refid']; ?> </td>
            <td> <?php echo $fas['v']['company'];?> </td>
            <td> <?php echo $fas['p']['name'];?> </td>
            <td> <?php echo $fas['va']['amount'];?> </td> 
            <td> <?php echo $status[$fas['va']['prevStatus']];?> </td>
            <td> <?php echo $status[$fas['va']['status']];?> </td>
            <td> <?php echo ($fas['tms']['type'] == 1)?"failure":'';?> </td>
            <td> <?php echo $fas['tms']['added_on'];?> </td>
            
            <td id="fmf_<?php echo $fas['tms']['txn_id'] ?>"> <a href="javascript:fasmanualFailure('<?php echo $fas['tms']['txn_id'] ?>');" class="btn btn-sm btn-default" <?php if($fas['tms']['user_id'] != '') { ?> disabled <?php } ?> >
                       <span class="glyphicon glyphicon-thumbs-down" style="color:red"></span>
                 </a>
            </td>
            
            <td> <?php echo $fas['tms']['handled_on'];?> </td>
            <td> <?php echo $username[$fas['tms']['user_id']]; ?></td>
            
            <?php $i++;             
            } ?> 
        </tr>
        <tbody>
    </table>
    </div>
    </form>
</body>
</html>

    <script>

var loader = "<img src='/img/ajax-loader-1.gif' />";
    // When the document is ready
        $(document).ready(function () {
            $('#fas_from, #fas_to').datepicker({
                format: "yyyy-mm-dd",    
                endDate: "1d",
                multidate: false,
                autoclose: true,
                todayHighlight: true
            });
        }); 

    $(document).ready(function () {
        $('#vendor_id').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true
        });
    });

    $(document).ready(function () {
        $('#product_id').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true
        });
    });
    $(document).ready(function () {
        $('#service_id').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true
        });
    });        
        $('#fas_excel').click(function (e) {
            e.preventDefault();
            var a = $('#fasexcel_fld').val('1');
            console.log(a);
            $('#fas_btn').click();
//            $('#fasexcel_fld').val('');
        });
    function fasmanualFailure(id){

        var r = confirm("Confirm?");
	if(r){
		$('#fmf_' + id).html(loader);
		var url = '/panels/fasManualFailure';
		var params = {'id' : id};

		$('#fmf_' + id).load(url, params);
	}	
}


       </script>
       
       