
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Contest Details</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#myModal").modal('show');

                });                   
            </script>
            
            <style>
            
                .modal{                    
                    top : 30%;
                    position: absolute;
                    margin-top: -200px;
                    margin-left: -150px;
                }
                
            </style>
        </head>
     <body>
         <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false" style="padding:0px">
                    <div class="modal-dialog"> 
                        <div class="modal-content" style="width: 750px">
                            
                            <div class="modal-body">
                                <img src="/img/dist_panel_navratri_popup.png" style="height: 500px;padding: 0px;margin-left: 25px;"/>
                                <div class ="row">                                        
                                    <div style="padding:10px; text-align: center">
                                        <button  class="btn btn-primary" onclick="location.href = '/shops/view';"  data-dismiss="modal">OK</button>
                                    </div>                                
                                </div>  
                            </div>
                            
                        </div>
                    </div>
                </div>  
        </body>
    </html>                            
 