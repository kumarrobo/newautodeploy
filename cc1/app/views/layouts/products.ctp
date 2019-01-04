<html>
    <head>
            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<!--            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">-->
            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
            <link rel="stylesheet" media="screen" href="/boot/css/multiple-select.css">
            <link rel="stylesheet" media="screen" href="/boot/css/style.css">
            <link rel="stylesheet" media="screen" href="/c2d/css/basic-template.css" />
            <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
            <script type="text/javascript" src="/boot/js/jquery.cookie.js"></script>
            <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
            <script type="text/javascript" src="/boot/js/jquery.multiple.select.js"></script>
            <script type="text/javascript" src="/boot/js/status.js"></script>
            <script type="text/javascript" src="/boot/js/products.js"></script>
     <style>
            body{font-size: 12px !important;}
            .level2 {margin-left: 30px;cursor: pointer;}
            .apidiv {margin-left: 30px;cursor: pointer;}
            td.level1{cursor: pointer}
            table.table-condensed{font-size: 12px !important; }              
            .dropdown-menu{font-size: 12px !important;}
            .glyphicon-minus:before{margin-right: 4px;}
            .glyphicon-plus:before{margin-right: 4px;}
            .btn-group .active{border-color: #adadad;background-color: #5FBD5F;color: white;}
            .btn-default{text-shadow: 0 0px 0 #fff !important;}
            </style>
    </head>
    <body>
        <div class="flash_message padding-top-10">
            <h5><?php echo $this->Session->flash(); ?></h5>
        </div>
          <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                       <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'shops','action'=>'view'))); ?>
                    </div>
               </div><!-- /.container-fluid -->
            </nav>  
        <div class="container">
             <?php echo $content_for_layout; ?>
        </div>
    </body>
</html>