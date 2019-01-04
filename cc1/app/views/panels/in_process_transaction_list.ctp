<html>
<head>
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
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
<title>Pay1 (Recharge Plans)</title>
<script>
    function pagination(val) {
        link = window.location.pathname.split('/');
        window.location = '/'+link[1]+'/'+link[2]+'/'+link[3]+'/'+link[4]+'/'+link[5]+'/'+link[6]+'/'+link[7]+'/'+link[8]+'/'+link[9]+'/'+val+'/<?php echo $recs ?>';
    }
    
    function recordsPage(val) {
        link = window.location.pathname.split('/');
        window.location = '/'+link[1]+'/'+link[2]+'/'+link[3]+'/'+link[4]+'/'+link[5]+'/'+link[6]+'/'+link[7]+'/'+link[8]+'/'+link[9]+'/1/'+val;
    }
</script>
</head>

<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class = "row">	
				<div class = "col-md-2">
				<div class="navbar-header">
                                    <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'c2d','action'=>'clickToCallListing'))); ?>
                                </div>
				</div>
               <div class = "col-md-8" align = "center">
					<h2><b>In Process Transactions</b></h2>
			   </div>  
           </div>
		</div>
	</nav>

	<div>
	
			 <html>

<head>
</head>

<body>
    <span>
        Page : <select onchange="pagination(this.value);" style="margin-right: 25px;">
                    <?php for($i=1;$i<=$totalpages;$i++) { ?>
                    <option <?php if($page == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
        Records / Page : <select onchange="recordsPage(this.value);" style="margin-right: 25px;">
                            <option <?php if($recs == 100) { echo "selected"; } ?>>100</option>
                            <option <?php if($recs == 500) { echo "selected"; } ?>>500</option>
                            <option <?php if($recs == 1000) { echo "selected"; } ?>>1000</option>
                            <option <?php if($recs == 5000) { echo "selected"; } ?>>5000</option>
                        </select>
        <strong>Total Records : <span style="color: blue;"><?php echo $totalrecords; ?></span></strong>
    </span><br /><br />
	<div class="tab-content">
		<div class="tab-pane active" id="list">
			<div class="table-responsive">
				<table class="tablesorter table table-hover table-bordered" id = "plantable">
					<thead>
						<tr>
							<th class = "field-label active" style = "width: 2%;">#</th>
							<th class = "field-label active" style = "width: 7%;">TRANSACTION NO</th>
							<th class = "field-label active" style = "width: 7%;">MODEM</th>
							<th class = "field-label active" style = "width: 5%;">OPERATOR</th>
							<th class = "field-label active" style = "width: 4%;">STATUS</th>
							<th class = "field-label active" style = "width: 12%;">IN-PROCESS CLEARED BY</th>
							<th class = "field-label active" style = "width: 5%;">DATE</th>
							<th class = "field-label active" style = "width: 10%;">TRANSACTION TIME</th>
							<th class = "field-label active" style = "width: 10%;">TXN CLEARED TIME</th>
							<th class = "field-label active" style = "width: 9%;">PROCESS TIME</th>
						</tr>
					</thead>
                                        <tbody>
                                                <?php $transaction_status = Configure::read('transaction_status'); ?>
                                                <?php $i = ($page-1)*$recs+1; ?>
                                                <?php if(!empty($inprocessdata)) { ?>
                                                <?php foreach($inprocessdata as $list) { ?>
                                                <?php  ?>
                                                <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><a href="/panels/transaction/<?php echo $list['t']['txn_id']; ?>" target="_blank"><?php echo $list['t']['txn_id']; ?></a></td>
                                                        <td><?php echo $list['t']['company']; ?></td>
                                                        <td><?php echo $list['t']['name']; ?></td>
                                                        <td><?php echo $transaction_status[$list['t']['status']]; ?></td>
                                                        <td><?php echo $list['t']['username']." (".$list['t']['email'].")"; ?></td>
                                                        <td><?php echo date('d-M-Y', strtotime($list['t']['date'])); ?></td>
                                                        <td><?php echo date('d-M-Y', strtotime($list['t']['vatimestamp'])).' <b>at</b> '.date('h:i:s A', strtotime($list['t']['vatimestamp'])); ?></td>
                                                        <td><?php echo date('d-M-Y', strtotime($list['t']['vmtimestamp'])).' <b>at</b> '.date('h:i:s A', strtotime($list['t']['vmtimestamp'])); ?></td>
                                                        <td><?php echo floor($list['t']['processtime']/3600).' hrs, '.floor(($list['t']['processtime']/60)%60).' mins, '.($list['t']['processtime']%60).' secs'; ?></td>
                                                </tr>
                                                <?php $i++; } } else { ?>
                                                <tr>
                                                        <td colspan="10"><div style="text-align: center; width: 100%"><strong>No Data Found</strong></div></td>
                                                </tr>
                                                <?php } ?>
                                        </tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</div>
</body>