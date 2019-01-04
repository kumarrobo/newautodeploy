<?php
 
$statusarray = array('All','success','failure','pending');

?>

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
 <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });

    var loader = "<img src='/img/ajax-loader-1.gif' />";
    
</script>
	<style type="text/css">
		.checkbox {
			font-size: 12px;
			line-height: 23px;
		}
		.reddiv{
			background-color:#FF0000;
			/*border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
		.greendiv{
			background-color: #00FF00;
			/*border: 1px solid;*/
			box-shadow: 0 0 2px grey;
		}
	</style>
	
<div class="container">
    <h2>Api Transaction Details</h2>
    <div class="panel panel-default">
    <div class="panel-heading"><b>Upload</b></div>
    <div class="panel-body">
    <div class="row">
    <form id="api_recon_form" method="post" enctype="multipart/form-data">
        <!--<input type="hidden" name='upload' id ='upload' value="">-->
        <div class="col-lg-12">
            <div class="col-lg-2">
                    <input type="text" class="form-control" style=""  id="recon_date" name="recon_date"   value="<?php echo isset($this->params['form']['recon_date'])?$this->params['form']['recon_date']:date('Y-m-d', strtotime('-1 day')); ?>">
                </div>
                <div class="col-lg-3 col-lg-offset-1" style="">
                    <select name ="vendor_id" id="vendor_id" class="">
                            <option value="">Select Vendors</option>
                            <?php foreach ($apiVendors as $val){ ?>
                            <option value="<?php echo $val['vendors']['id']; ?>" <?php if($val['vendors']['id'] == $this->params['form']['vendor']){ echo 'selected'; } ?>><?php echo $val['vendors']['company']; ?></option>
                            <?php } ?>

                    </select>
                </div>
            <div class="col-lg-6">
                <div class="col-lg-12" style="">
                <label class="control-label col-lg-2">Upload</label>
                <div class="col-lg-8"><input type="file" name="apifile" id="apifile" /></div>
                <button type="button" id="btnuploadexcel" class="btn btn-default btn-success btn-xs pull-left" onclick="apiRecon()">Upload</button>
                </div>
            </div>
        </div>
    </form>
    </div>
    </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading"><b>Search</b></div>
        <div class="panel-body">
    <div class="row">
    <form id="search_form" method="post" enctype="multipart/form-data">
        <!--<input type="hidden" name='search' id ='search' value="">-->
    <!--<div class="row">-->
        <div class="col-lg-12" style="text-align: center;">
            <!--<div class="col-lg-8">-->
                <div class="col-lg-2">
                    <input type="text" class="form-control" style=""  id="search_date" name="search_date"   value="<?php echo isset($this->params['form']['recon_date'])?$this->params['form']['recon_date']:date('Y-m-d', strtotime('-1 day')); ?>">
                </div>
                <div class="col-lg-4" style="">
                    <select name ="vendorid" id="vendorid" class="">
                            <option value="">Select Vendors</option>
                            <?php foreach ($apiVendors as $val){ ?>
                            <option value="<?php echo $val['vendors']['id']; ?>" <?php if($val['vendors']['id'] == $this->params['form']['vendorid']){ echo 'selected'; } ?>><?php echo $val['vendors']['company']; ?></option>
                            <?php } ?>

                    </select>
                </div>
                <div class="col-lg-2" style="">
                    <select name ="vendor_status" id="vendor_status" class="">
                        <option value="">Select Vendor Status</option>
                    <?php foreach ($statusarray as $statval): ?>                            
                            <option value='<?php echo $statval ?>' <?php if($statval == $params['status']){ echo 'selected'; } ?>><?php echo $statval ?></option>
                    <?php endforeach;?>
                    </select>
                </div>
                <div class="col-lg-2" style="">
                    <select name ="server_status" id="server_status" class="">
                        <option value="">Select Server Status</option>
                    <?php foreach ($statusarray as $statval): ?>                            
                            <option value='<?php echo $statval ?>' <?php if($statval == $params['status']){ echo 'selected'; } ?>><?php echo $statval ?></option>
                    <?php endforeach;?>
                    </select>
                </div>
                <div class="col-lg-2" style="">
                    <button type="button" id="btnsearchdata" class="btn btn-default btn-primary btn-xs" onclick="getReconData()">Search</button>
                </div>
            </div>
    </form>
    </div>
    </div>
    </div>
    <table class="table table-condensed table-hover" id="api_recon_table">
        <thead>
        <tr>
        <th>Api Txn Id</th>
        <th>Tran Id</th>
        <th>Vendor RefId</th>
        <th>Vendor</th>     
        <th>Amount</th>
        <th>Vendor Status</th>
        <th>Server status</th>
        <th>Current status</th>
        <th>Action</th>
        </tr>
        </thead>
        <tbody>
            
        </tbody>		
    </table>
</div>
<script>
	

        // When the document is ready
        $(document).ready(function () {
            $('#recon_date').datepicker({
                format: "yyyy-mm-dd",
                //startDate: "-365d",
                endDate: "1d",
                multidate: false,
                autoclose: true,
                orientation: 'top right',
                todayHighlight: true
            });  
            $('#search_date').datepicker({
                format: "yyyy-mm-dd",
                //startDate: "-365d",
                endDate: "1d",
                multidate: false,
                autoclose: true,
                orientation: 'top right',
                todayHighlight: true
            });  
        });
        
	function refund(ref_code,id){
		var r=confirm("Are you sure you want to reverse the transaction?");
		if(r==true){
			var url = '/panels/reverseTransaction/'+ref_code;
			var pars   = "";
			$('#ajax_loader_'+id).show();
			$('#refund_button_'+id).remove();
			$('#success_button_'+id).remove();
			$.post(url, pars, function(response){
				$('#ajax_loader_'+id).hide();
				$('#message_'+id).html('Refunded');
			});
		}
		
	}
	
	function success(id){
		var r=confirm("Are you sure you want to success this transaction?");
		if(r==true){
			var url = '/panels/apiReconSuccessTxn/'+id;
			var pars   = "";
			$('#ajax_loader_'+id).show();
			$('#refund_button_'+id).remove();
			$('#success_button_'+id).remove();
			$.post(url, pars, function(response){
				$('#ajax_loader_'+id).hide();
				$('#message_'+id).html('Success Txn');
			});
		}
		
	}
        
	function resolveTxn(id){
		var r=confirm("Are you sure you want to ignore this transaction?");
		if(r==true){
			var url = '/panels/resolveApiReconTxn/'+id;
			var pars   = "";
			$('#ajax_loader_'+id).show();
			$('#resolve_button_'+id).remove();
			$.post(url, pars, function(response){
				$('#ajax_loader_'+id).hide();
                                $('table#api_recon_table > tbody >tr#'+id).hide();
//				$('#message_'+id).html('Resolved');
			});
		}
		
	}
        
        function apiRecon()
        {
            var form = $("#api_recon_form")[0];
            var form_data = new FormData(form); 
            var loader_html = $('#btnuploadexcel');
            var btn_html = $('#btnuploadexcel').html();
            var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
            
            $.ajax({
                url : "/panels/getApiReconData",
                type: "POST",
                data : form_data,
                processData: false,
                contentType: false,
                dataType:'json',
                beforeSend: function () {
                    loader_html.html(loading_gif);
                },
                success:function(res){ 
                    loader_html.html(btn_html);
                    $('table#api_recon_table > tbody').empty();
                    if((res.status == 'success') && (res.type == 0))
                    { 
                        showReconData(res);
                    }
                    else if(res.status == 'failure' && res.type == 1) 
                    {
                        var flag = confirm("Api file already exists. Do you want to replace the existing one?")
                        if(flag == true)
                        {
                            showReconData(res);                             
                        }
                    }
                    else
                    {
                        alert(res.description);
                        return false;
                    }
                },
                error: function() {
                    loader_html.html(btn_html);
                    alert('Error');
                }
            });
        }
        
        function showReconData(response)
        {
            var form = $("#api_recon_form")[0];
            var form_data = new FormData(form); 
            var loader_html = $('#btnuploadexcel');
            var btn_html = $('#btnuploadexcel').html();
            var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
            
            $.ajax({
                        url : "/panels/uploadReconExcel",
                        type: "POST",
                        data : form_data,
                        processData: false,
                        contentType: false,
                        dataType:'json',
                        beforeSend: function () {
                            loader_html.html(loading_gif);
                            $('#btnsearchdata').prop('disabled', true);
                        },
                        success : function(res){
                            loader_html.html(btn_html);
                            
                            if((res.status == 'success') && (res.type == 0))
                            {
                                $('#btnsearchdata').prop('disabled', false);
                                console.log('length'+Object.keys(res.data).length);
                                if(Object.keys(res.data).length > 0)
                                {
                                    $.each(res.data,function(api_txn_id,txn_data){                            
                                        var flag = txn_data.flag;
                                        var row = "<tr id = '"+api_txn_id+"'>";
                                        row += "<td>"+txn_data.at_id+"</td>";
                                        row += "<td><a target='_blank' href='/panels/transaction/"+txn_data.txn_id+"'>"+txn_data.txn_id+"</a></td>";
                                        row += "<td>"+txn_data.vendor_refid+"</td>";
                                        row += "<td>"+txn_data.vendor_name+"</td>";
                                        row += "<td>"+txn_data.amount+"</td>";
                                        row += "<td>"+txn_data.vendor_status+"</td>";
                                        row += "<td>"+txn_data.server_status+"</td>";
                                        row += "<td>"+txn_data.current_status+"</td>";
                                        if(flag == 11)
                                        {
                                            row += "<td><img src='/img/ajax-loader-1.gif' id='ajax_loader_"+txn_data.at_id+"' style='display: none;' /><button id='resolve_button_"+txn_data.at_id+"' type='button' class='btn btn-danger btn-xs' onclick=\"resolveTxn('"+txn_data.at_id+"')\">Hide</button><span id='message_"+txn_data.at_id+"'></span></td>";
                                        }
                                        else
                                        {
                                            row += "<td><img src='/img/ajax-loader-1.gif' id='ajax_loader_"+txn_data.at_id+"' style='display: none;' /><button id='refund_button_"+txn_data.at_id+"' type='button' class='btn btn-danger btn-xs' onclick=\"refund('"+txn_data.txn_id+"','"+txn_data.at_id+"')\">Refund</button><button id='success_button_"+txn_data.at_id+"' type='button' class='btn btn-success btn-xs' onclick=\"success('"+txn_data.at_id+"')\">Success</button><span id='message_"+txn_data.at_id+"'></span></td>";
                                        }
                                        row += "</tr>";
                                        $('table#api_recon_table > tbody').append(row);
                                    });
                                }
                                else
                                {
                                    alert('No records found!');
                                    return false;
                                }
                            }
                            else if(res.status=='failure' && res.type == 2)
                            {
                                alert(res.description);
                                return false;
                            }
                        }
                    });
        }
        
        function getReconData()
        {   
            $("#search").val('search');            
            var form = $("#search_form")[0];
            var form_data = new FormData(form); 
            var loader_html = $('#btnsearchdata');
            var btn_html = $('#btnsearchdata').html();
            var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
            
            $.ajax({
                        url : "/panels/apiReconData",
                        type: "POST",
                        data : form_data,
                        processData: false,
                        contentType: false,
                        dataType:'json',
                        beforeSend: function () {
                            loader_html.html(loading_gif);
                            $('#btnuploadexcel').prop('disabled', true);
                        },
                        success : function(res){
                            loader_html.html(btn_html);
                            $('#btnuploadexcel').prop('disabled', false);
                            $('table#api_recon_table > tbody').empty();
                            
                            if((res.status == 'success') && (res.type == 0))
                            {
                                console.log('length'+Object.keys(res.data).length);
                                if(Object.keys(res.data).length > 0)
                                {
                                    $.each(res.data,function(id,txn_data){ 
                                        var flag = txn_data.flag;
                                        var row = "<tr id = '"+txn_data.id+"'>";
                                        row += "<td>"+txn_data.id+"</td>";
                                        row += "<td><a target='_blank' href='/panels/transaction/"+txn_data.txn_id+"'>"+txn_data.txn_id+"</a></td>";
                                        row += "<td>"+txn_data.ref_code+"</td>";
                                        row += "<td>"+txn_data.vendor_name+"</td>";
                                        row += "<td>"+txn_data.amount+"</td>";
                                        row += "<td>"+txn_data.vendor_status+"</td>";
                                        row += "<td>"+txn_data.server_status+"</td>";
                                        row += "<td>"+txn_data.current_status+"</td>";
                                        if(flag == 11)
                                        {
                                            row += "<td><img src='/img/ajax-loader-1.gif' id='ajax_loader_"+txn_data.id+"' style='display: none;' /><button id='resolve_button_"+txn_data.id+"' type='button' class='btn btn-danger btn-xs' onclick=\"resolveTxn('"+txn_data.id+"')\">Hide</button><span id='message_"+txn_data.id+"'></span></td>";
                                        }
                                        else
                                        {
                                            row += "<td><img src='/img/ajax-loader-1.gif' id='ajax_loader_"+txn_data.id+"' style='display: none;' /><button id='refund_button_"+txn_data.id+"' type='button' class='btn btn-danger btn-xs' onclick=\"refund('"+txn_data.txn_id+"','"+txn_data.at_id+"')\">Refund</button><button id='success_button_"+txn_data.at_id+"' type='button' class='btn btn-success btn-xs' onclick=\"success('"+txn_data.at_id+"')\">Success</button><span id='message_"+txn_data.at_id+"'></span></td>";
                                        }
                                        row += "</tr>";
                                        $('table#api_recon_table > tbody').append(row);
                                    });
                                }
                                else
                                {
                                    alert('No records found!');
                                    return false;
                                }
                            }
                            else if(res.status=='failure' && res.type == 2)
                            {
                                alert(res.description);
                                return false;
                            }
                        }
                    });
        }
        

</script>