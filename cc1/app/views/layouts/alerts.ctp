<html>
<head>
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/style.css">


<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.tablesorter.js"></script>
<!-- <script type="text/javascript" src="/boot/js/bootstrap-confirmation.js"></script> -->
<script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
<style>
body {
	font-size: 12px !important;
}

.level2 {
	margin-left: 30px;
	cursor: pointer;
}

.apidiv {
	margin-left: 30px;
	cursor: pointer;
}

td.level1 {
	cursor: pointer
}

table.table-condensed {
	font-size: 12px !important;
}

.dropdown-menu {
	font-size: 12px !important;
}

.glyphicon-minus:before {
	margin-right: 4px;
}

.glyphicon-plus:before {
	margin-right: 4px;
}

.btn-group .active {
	border-color: #adadad;
	background-color: #5FBD5F;
	color: white;
}

.btn-default {
	text-shadow: 0 0px 0 #fff !important;
}
</style>
<title>Pay1 (Retailer Monitoring Panel)</title>
</head>

<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class = "row">	
				<div class = "col-md-2">
				<div class="navbar-header">
						 <?php echo $html->image("pay1_logo.svg?213", array("url" =>"/alerts/index/")); ?>
					</div>
				</div>
               <div class = "col-md-8" align = "center">
					<h2><b>Retailer Monitoring Panel</b></h2>
			   </div>  
           </div>
		</div>
		<!-- /.container-fluid -->
	</nav>

	<div class="container-fluid">
	
			 <?php echo $content_for_layout; ?>

	</div>
	</body>