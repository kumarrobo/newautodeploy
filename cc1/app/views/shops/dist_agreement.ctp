<?php if ($this->Session->read('Auth.is_agreed') == 0) { ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Distributor Agreement</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#myModal").modal('show');

                });                   
            </script>
            
            <style>
                .modal-lg {
                 width: 90%;
                }
            </style>
        </head>
     <body>
            <form id ="dist_agreementform" method="POST" > 
                <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><center>Distributor Agreement</center></h4>
                            </div>
                            <div class="modal-body">

                                <iframe src="/users/distAgreementData" style="zoom:0.40" width="95%" height="850" frameborder="0"></iframe>
                            </div>
                            <div class="modal-footer">
                                <div class="col-lg-6">
                                 <div class ="row">    
                                <button  class="btn btn-primary" onclick="submitAgree()"  data-dismiss="modal"> I Accept</button>
                                </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    

            </form>       
            <script>

                function submitAgree() {
                    var id = "1";

                    $.ajax({
                        type: "POST",
                        url: '/users/distAgreementVal/',
                        data: {val: id},
                        dataType: "json",

                        success: function (data) {

                            alert("Agreement Accepted");
                            if (data == 1) {
                                window.location = '/shops/view';
                            }
                        },

                        error: function () {
                            alert("data");
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