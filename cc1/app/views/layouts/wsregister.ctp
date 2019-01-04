<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A basic Hello World example for bootstrap">
        <meta name="author" content="Your Name">
        <title>Wholesaler Registration</title>
        <link href="/boot/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/c2d/css/basic-template.css" rel="stylesheet" />
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="flash_message padding-top-10"><h5>
            <?php echo $this->Session->flash(); ?>
        </h5></div>
        <div class="container padding-top-10">
            <?php echo $content_for_layout; ?>
        </div>
        
        <script src="/boot/js/jquery-2.0.3.min.js"></script>
        <script src="/boot/js/bootstrap.min.js"></script>
        <script src="/c2d/js/wholesaler_register.js"></script>
    </body>
</html>