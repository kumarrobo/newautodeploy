<!DOCTYPE html>
<html>
<head>
  <title>Service Partner</title> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
  <link rel="stylesheet" href="/boot/css/serviceintegration.css">
  <script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="/boot/js/bootstrap-3.3.7.min.js"></script>    
  <script type="text/javascript" src="/boot/js/serviceintegration.js"></script>
  <style>
      label{
          margin-top       : 10px;
          margin-bottom    : 5px;
      }
  </style>

</head>
<body>
    <div class="row">
          <a type="button"  href="/serviceintegration/servicesForm" id='serviceIntegration' name="serviceIntegration" class="btn btn-primary" >Home</a>          
    </div>
    <h2> Add Service Partner</h2>
    <form id='InsServicePartner' name="InsServicePartner" method="POST">
    <div class="row">
    <div class="col-md-7">
        <label for='servcPartKey'> Key <font color="red">*</font> :</label>
        <input type="text" id='servcPartKey' name="servcPartKey"  class="form-control" required></input>
    </div> 
    </div>
    <div class="row">
    <div class="col-md-7">
        <label for='servcPartSecKey'> Secret Key :</label>
        <input type="text" id='servcPartSecKey' name="servcPartSecKey"  class="form-control" required></input>
    </div> 
</div>        
    <div class="row">
    <div class="col-md-6">
        <label for='servcPartname'> Name <font color="red">*</font> :</label>
        <input type="text"  id='servcPartname' name="servcPartname"  class="form-control" required></input>
    </div>
</div> 
    <div class="row">
    <div class="col-md-7">
        <label for='servcPartsalt'> Salt <font color="red">*</font> :</label>
        <input type="text"  id='servcPartsalt' name="servcPartsalt" class="form-control" required></input>
    </div>
    </div>
    <div class="row">
    <div class="col-md-7">
        <label for='servcPartcallback'> Callback :</label>
        <textarea  id='servcPartcallback' name="servcPartcallback"  class="form-control" required></textarea>
    </div>
    </div>         
    <div class="row">
    <div class="col-md-7">
        <label for='servcPartredirect'> Redirect :</label>
        <textarea  id='servcPartredirect' name="servcPartredirect"  class="form-control" required></textarea>
    </div>
    </div>         
    <div class="row">
    <div class="col-md-7">
        <label for='servcPartparams'> Params :</label>
        <textarea  id='servcPartParams' name="servcPartParams"  class="form-control" required></textarea>
    </div>
</div>                         
<div class="row">
            <div class="col-md-7">
            <button type="button" style="margin-top:18px;" id="InsservcPartnerbtn" name="InsservcPartnerbtn" class="btn btn-primary" onclick="Insproduct()">Submit</button>
        </div>
        </div>
    
</body>
</html>

<script>
    function  Insproduct(){
       var datas = $( "form" ).serialize();
               
        if($('#servcPartKey').val() == '' || $('#servcPartname').val() == '' || $('#servcPartsalt').val() == ''){
        
            alert("Please Enter Mandatory Field");
        }
        else {

       
        $.ajax({
            url: '/serviceintegration/InsServicePartner',
            type: 'post',
            dataType: 'json',
            
            success: function(data) {                                
                alert("Service Partner Created Successfully");
                location.reload();
            },
            data: datas,
            failure: function(data){                
            alert("Service Partner not got Created");
        },
        });
    }

    }
</script>