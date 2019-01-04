<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta content="width=device-width,initial-scale=1" name=viewport>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/footable.bootstrap.min.css">

	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/boot/js/footable.min.js"></script>

	<style type="text/css">
		.col-md-3{margin-bottom: 10px;}
	</style>
</head>
<body>
	<div class="container">
		<span style="display: block;text-align: center;"><h4>Retailer List</h4></span>
                                                     <form action="getRetailerList" method="post">
		<div class="col-md-3">
		<label for="pay1_status">Pay1 Status</label>
			<select class="form-control" id="pay1status" name="pay1_status">
                                                                                  <option value="" <?php if(trim($pay1_status)==""){ echo "selected"; }?>>All</option>
                                                                                  <option value="0" <?php if($pay1_status==0 && trim($pay1_status)!=""){ echo "selected"; }?>>Pending</option>
                                                                                  <option value="1" <?php if($pay1_status==1){ echo "selected"; }?>>Approved</option>
                                                                                  <option value="2" <?php if($pay1_status==2){ echo "selected"; }?>>Rejected</option>
			</select>
		</div>
		<div class="clearfix"></div>
		<div class="col-md-3">
			<label for="bank_status">Bank Status</label>
			<select class="form-control" id="bankstatus" name="bank_status">
			  <option value="" <?php if(trim($pay1_status)==""){ echo "selected"; }?>>All</option>
			  <option value="0" <?php if($bank_status==0 && trim($bank_status)!=""){ echo "selected"; }?>>Pending</option>
			  <option value="1" <?php if($bank_status==1){ echo "selected"; }?>>Approved</option>
			  <option value="2" <?php if($bank_status==2){ echo "selected"; }?>>Rejected</option>
			</select>
		</div>
		<div class="clearfix"></div>
		<div class="col-md-3">
			<label for="retailer_no">Retailer No.</label>
			<input type="text" class="form-control" name="retailer_no" placeholder="Enter Retailer No." value="<?php echo isset($mobile)?$mobile:"";?>">
		</div>
		<div class="clearfix"></div>
		<div class="col-md-3">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
                                                     </form>
		<div class="col-md-12">
			<div class="table-responsive">
			  <table class="table table-bordered table-hover" data-sorting="true">
                              <thead class="bg-primary">
			    		<th>Retailer No.</th>
			    		<th>Shop Name</th>			    		
			    		<th>Pay1 Status</th>
                                                                                                                                     <th>Bank Status</th>
			    		<th>App Comment</th>
			    		<th>Internal Comment</th>
			    		<th>Date/Time</th>
			    	</thead>
			    	<tbody>
                                                                                                            <?php foreach ($retailerdata as $retailer):?>
			    		<tr>                                                                                                                                            
                                                                                                                                            <td><a href="/smartpay/getUserServices/<?php echo $retailer['mobile'];?>" target="_blank"><?php echo $retailer['mobile'];?></a></td>
                                                                                                                                            <td><?php echo $retailer['shopname'];?></td>                                                                                                                                            
                                                                                                                                            <td><?php echo ($retailer['pay1_status']==0)?"Pending":($retailer['pay1_status']==1?"Approved":"Rejected");?></td>
                                                                                                                                            <td><?php echo ($retailer['bank_status']==0)?"Pending":($retailer['bank_status']==1?"Approved":"Rejected");?></td>
                                                                                                                                            <td><?php echo $retailer['bank_comment'];?></td>
                                                                                                                                            <td><?php echo $retailer['pay1_comment'];?></td>
                                                                                                                                            <td><?php echo $retailer['created_at'];?></td>
			    		</tr>
                                                                                                            <?php endforeach;?>
			    	</tbody>
			  </table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(function($){
			$('.table').footable();
		});
	</script>
</body>
</html>