<?php if ($this->Session->read('Auth.contest_flag') == 0) { ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Distributor Contest</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#myModal").modal('show');

                });                   
            </script>
                    
            <style>
            
                .modal-content{                   
                    left: 20%;                    
                    top : 30%;                    
                    margin-top: 30px;
                    margin-left: -250px;
/*                  position: absolute; old code
                    margin-top: -150px;
                    margin-left: -150px;*/
                }
                
            </style>    
        </head>

     <body>            
         <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false" style="padding:0px">
                    <div class="modal-dialog"> 
                        <div class="modal-content" style="width: 865px;">
                            
                            <div class="modal-body">
                                <img src="/img/dist_panel_navratri_banner-03.png" style="height: 550px;padding: 0px;margin-left: 25px;"/>
                                <div class ="row">    
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-4" style="padding-top:10px;">
                                        <button  class="btn btn-primary"  onclick="submitAgree()" style="margin-left: 100px;align:center;" data-dismiss="modal"> Know More </button>
                                    </div>
                                <div class="col-sm-4"></div>
                                </div>  
                            </div>
                            
                        </div>
                    </div>
                </div>                
            <script>
                function submitAgree() {
                    var id = "1";

                    $.ajax({
                        type: "POST",
                        url: '/users/distContestVal/',
                        data: {con: id},
                        dataType: "json",

                        success: function (data) {                            


                            if (data == 1) {
                              window.location = '/shops/distContestDet';
                          }
                        },

                        error: function () {
                            alert("error occured");
                        }

                    });

                }
            </script>
        </body>
    </html>                            
    <?php
} else {

    $this->render(array('controller' => 'shops', 'view'));
}
?>