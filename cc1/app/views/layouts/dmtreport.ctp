<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <!--<title>DMT Panel</title>-->
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="/boot/css/reset.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/css/tether.min.css" rel="stylesheet">
        <link href="/boot/css/bootstrap.min.css" rel="stylesheet">
        <link href="/boot/css/bootstrap-datepicker3.min.css" rel="stylesheet">
        <link href="/boot/css/footable.bootstrap.css" rel="stylesheet">
        <script type="text/javascript" src="/boot/js/jquery-3.1.1.min.js"></script> 
<!--jquery-3.1.1.min-->
        <!-- Your custom styles (optional) -->
        <link href="/boot/css/style.css" rel="stylesheet">
        
        

         <!--Bootstrap core JavaScript--> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/boot/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/boot/js/footable.js"></script>

        <style>
      
      .pagination {
        margin: 0px;
         }
            
        </style>
    </head>
    <body>
<nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                       <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'shops','action'=>'view'))); ?>
                        <a href='/panels'><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                    </div>
                    <ul class="nav navbar-nav pull-right">
                            <li>
                                    <p class="navbar-btn">
                                          <a href="/shops/logout" class="btn btn-default btn-sm">Logout</a>
                                    </p>
                            </li>
                    </ul>
               </div><!-- /.container-fluid -->
            </nav>
        <?php echo $content_for_layout; ?>

        <script type="text/javascript">
            $('.retailer-main input').datepicker({
                autoclose: true
            });
            $('.retailer-main input').datepicker({
                autoclose: true
            });

            jQuery(function ($) {
                $('.table.demo').footable();
            });
        </script>
    </body>
</html>