<html>
    <head><title> IRCTC Upload Panel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>               
    </head>    
    
    <body>
        <h2> IRCTC Data Upload </h2>
        <div class="container">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading">IRCTC Booking Data</div>
                <div class="panel-body">
            <form id="irctc_upload" name="irctc_upload" method="post" enctype="multipart/form-data" >
                <div class="col-md-4">
                  <input type="file" name="irctcexcel" id ="irctcexcel" class="form-control" accept=".csv"  >
                </div>    
                <div class="col-md-2">
                    <input type="button" class="btn btn-primary" id="irctc_upd" name="irctc_upd" value="Upload" </input>
                </div>
            </form>       
         </div>   
        <div class="row">            
            <div class="col-md-5" id="msg_holder"></div>
        </div>
        </div>  
        </div>
        <h2> IRCTC Refund Data Upload </h2>       
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading">IRCTC Refund Data</div>
                <div class="panel-body">
            <form id="irctcref_upload" name="irctcref_upload" method="post" enctype="multipart/form-data" >
                <div class="col-md-4">
                  <input type="file" name="irctcrefexcel" id ="irctcrefexcel" class="form-control" accept=".csv"  >
                </div>    
                <div class="col-md-2">
                    <input type="button" class="btn btn-primary" id="irctcref_upd" name="irctcref_upd" value="Upload" </input>
                </div>
            </form>                    
        </div> 
        <div class="row">            
            <div class="col-md-5" id="msg_refund_holder"></div>
        </div>    
        </div>
        </div>
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading">IRCTC Refund Data IInd </div>
                <div class="panel-body">
            <form id="irctcref2_upload" name="irctcref2_upload" method="post" enctype="multipart/form-data" >
                <div class="col-md-4">
                  <input type="file" name="irctcref2excel" id ="irctcref2excel" class="form-control" accept=".csv"  >
                </div>    
                <div class="col-md-2">
                    <input type="button" class="btn btn-primary" id="irctcref2_upd" name="irctcref2_upd" value="Upload" </input>
                </div>
            </form>                    
        </div> 
        <div class="row">            
            <div class="col-md-5" id="msg_refund2_holder"></div>
        </div>    
        </div>
        </div>
    </body>
    
    
    
</html>


<script>
 
  $(document).ready(function(){           
        $('#irctc_upd').on('click', function(){
            var loader_html = $('#irctc_upd');
            var btn_html = $('#irctc_upd').html();
            var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
            var file = $('#irctcexcel').prop('files')[0];   
            var form = new FormData();
            form.append('irctcexcel', file);            
            $.ajax({
                url : '/irctc/index',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data : form,
                dataType : 'json',                
                beforeSend: function () {
                    loader_html.html(loading_gif);
                },
                success: function(data){
                    if(data.status == 'success'){
                        var msg = '<div class="alert alert-success"><strong>'+data.msg+'</strong></div>';
                        loader_html.html(btn_html);                            ;        
                    $("#irctc_upd").html('Uploaded'); 
                    $('#msg_holder').html(msg);
                    } else {
                        var irctc_failed_template_body = '<div class="alert alert-danger"><strong>'+data.description+'</strong></div>';                        
            if((data.failed_txns) && Object.keys(data.failed_txns).length > 0){                            
                
                var irctc_failed_template_body = '<div class="alert alert-danger"><strong>'+data.description+'</strong></div><table class="table table-bordered table-stripped" id="failuretb" name="failuretb">  <thead> <th>Dist id</th> <th> Ret id </th> <th> Agent Id </th> <th> Txn ID </th><th> Amount </th> <th> Curr Balance </th> <th> Reason </th> </thead><tbody>';
                $.each(data.failed_txns, function (index,field) {                
        
                var tr = '<tr>';
                var distId  = '';
                var retId   = '';
                var currBal ='';
                var agentId ='';
                var txnId   ='';
                var amount  ='';
                var Status  ='';
                
                if(typeof field["user_details"] != 'undefined'){
                    distId  = field["user_details"]["r"]["parent_id"];  
                    retId   = field["user_details"]["r"]["id"];
                    currBal = 'Rs. ' +field["user_details"]["u"]["balance"];  
                    agentId = field["unique_id"];  
                    txnId   = field["txn_id"];  
                    amount  = field["amount"];                  
                    Status  = field["reason"];  

                }
                  
                var agentId = field["unique_id"];  
                var txnId   = field["txn_id"];  
                var amount  = field["amount"];                                  
                var Status  = field["reason"];  


                 tr += '<td>'   + distId + '</td>';  
                 tr += '<td>'   + retId + '</td>';  
                 tr += '<td>'   + agentId + '</td>';  
                 tr += '<td>'   + txnId + '</td>';  
                 tr += '<td> Rs. ' + amount + '</td>';  
                 tr += '<td>' + currBal + '</td>';  
                 tr += '<td>' + Status + '</td>';            
                    tr +='</tr>';
                 irctc_failed_template_body += tr ;                 
                 

            });
                    irctc_failed_template_body += '</tbody></table>';
                }
                
                $('#msg_holder').html(irctc_failed_template_body);
                
                }
                },
                error: function (err) {
                    var msg = '<div class="alert alert-danger"><strong>Something went wrong. Please try again</strong></div>';
                    $('#msg_holder').html(msg);
                }                                
                
            });
        });
    });

      $(document).ready(function(){           
        $('#irctcref_upd').on('click', function(){
        var loader_html = $('#irctcref_upd');
        var btn_html = $('#irctcref_upd').html();
        var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
        var file = $('#irctcrefexcel').prop('files')[0];   
        var form = new FormData();
        form.append('irctcref_upload', file);            
        $.ajax({
            url : '/irctc/refundtxn',
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data : form,
            dataType : 'json',                
            beforeSend: function () {
                loader_html.html(loading_gif);
            },
            success: function(data){                                                      
                if(data.status == 'success'){
                    var msg = '<div class="alert alert-success"><strong>'+data.msg+'</strong></div>';
                    loader_html.html(btn_html);                            ;        
                    $("#irctcref_upd").html('Uploaded');
                    
                } else {                  
                    if(typeof(data.failed_txns)  === "undefined" && data.failed_txns !== null) {                          
                    var msg = '<div class="alert alert-danger"><strong>'+data.description+'</strong>';                            
                    }
                    else if(typeof(data.failed_txns)  !== "undefined" && data.failed_txns !== null) {                            
                    var msg = '<div class="alert alert-danger"><strong>'+data.description+'</strong><br><strong>'+data.failed_txns+'</strong>';
                    } 
                    msg +='</div>';
                }
                $('#msg_refund_holder').html(msg);
            },
            error: function (err) {
                var msg = '<div class="alert alert-danger"><strong>Something went wrong. Please try again</strong></div>';
                $('#msg_refund_holder').html(msg);
            }                                

        });    
    });
    });
    
      $(document).ready(function(){           
        $('#irctcref2_upd').on('click', function(){
        var loader_html = $('#irctcref2_upd');
        var btn_html = $('#irctcref2_upd').html();
        var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
        var file = $('#irctcref2excel').prop('files')[0];   
        var form = new FormData();
        form.append('irctcref2_upload', file);            
        $.ajax({
            url : '/irctc/refundtxnTwo',
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data : form,
            dataType : 'json',                
            beforeSend: function () {
                loader_html.html(loading_gif);
            },
            success: function(data){                                                      
                if(data.status == 'success'){
                    var msg = '<div class="alert alert-success"><strong>'+data.msg+'</strong></div>';
                    loader_html.html(btn_html);                            ;        
                    $("#irctcref2_upd").html('Uploaded');
                    
                } else {                  
                    if(typeof(data.failed_txns)  === "undefined" && data.failed_txns !== null) {                          
                    var msg = '<div class="alert alert-danger"><strong>'+data.description+'</strong>';                            
                    }
                    else if(typeof(data.failed_txns)  !== "undefined" && data.failed_txns !== null) {                            
                    var msg = '<div class="alert alert-danger"><strong>'+data.description+'</strong><br><strong>'+data.failed_txns+'</strong>';
                    } 
                    msg +='</div>';
                }
                $('#msg_refund2_holder').html(msg);
            },
            error: function (err) {
                var msg = '<div class="alert alert-danger"><strong>Something went wrong. Please try again</strong></div>';
                $('#msg_refund2_holder').html(msg);
            }                                

        });    
    });
    });    
 </script>