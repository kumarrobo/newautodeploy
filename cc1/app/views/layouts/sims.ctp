<html>
    <head>
            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<!--            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">-->
            <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
            <link rel="stylesheet" media="screen" href="/boot/css/multiple-select.css">
            <link rel="stylesheet" media="screen" href="/boot/css/style.css">
            <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
            <script type="text/javascript" src="/boot/js/jquery.cookie.js"></script>
            <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
            <script type="text/javascript" src="/boot/js/filter.js?<?php echo time(); ?>"></script>
            <script type="text/javascript" src="/boot/js/refresh.js?<?php echo time(); ?>"></script>
            <script type="text/javascript" src="/boot/js/common.js"></script>
            <script type="text/javascript" src="/boot/js/yellow.js?<?php echo time(); ?>"></script>
            <script type="text/javascript" src="/boot/js/updateclosing.js"></script>
            <script type="text/javascript" src="/boot/js/highlights.js"></script>
            <script type="text/javascript" src="/boot/js/updatebalance.js"></script>
<!--            <script type="text/javascript" src="/boot/js/date.js"></script>-->
            <script type="text/javascript" src="/boot/js/jquery.multiple.select.js"></script>
            <script type="text/javascript" src="/boot/js/status.js"></script>
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
          <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'shops','action'=>'view'))); ?>
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