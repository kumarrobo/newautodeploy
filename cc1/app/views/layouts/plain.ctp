<html>
    <head>
            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
            <link rel="stylesheet" media="screen" href="/boot/css/multiple-select.css">
            <link rel="stylesheet" media="screen" href="/boot/css/pay1.css">
            <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
            <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="/boot/js/jquery.multiple.select.js"></script>
            <LINK REL="SHORTCUT ICON" HREF="/img/pay1_favic.png"/>
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
          <div class="container">
             <?php echo $content_for_layout; ?>
         </div>
    </body>
</html>