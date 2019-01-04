<?php
 
?>

<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
 <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>

	
	<div class="container">

  <div class="panel panel-default">
    <div class="panel-body">Add User</div>
  </div>
  <div class="row" style="padding: 40px 10px 10px;">
	  <form name="groupfrom" method="post" id="groupform" action="/acl/addGroup/">
		  <input type="hidden" name="group_id" id="group_id" value="<?php echo isset($group_id) ? $group_id : "" ?>">

<div class="row">

<div class="form-group col-lg-3">
	
  <label for="username">Group Name:</label>
  <input type="text" class="form-control" name="groupname" id ="groupname" value="<?php echo isset($group[0]['groups']['name']) ? $group[0]['groups']['name']: "";  ?>">
  
	
</div>
</div>

	<div class="row">
		 
	<div class="form-group col-lg-3">
  <label for="flag">Flag:</label>
  <select name="flag" class="form-control" id="flag">
	  <option value="0" <?php if($group[0]['groups']['flag']==0){ echo "selected"; }?>>Inactive</option>
	  <option value="1" <?php if($group[0]['groups']['flag']==1){ echo "selected"; }?>>Active</option>
				 
			  </select>
</div>
	</div>
	<div class="row">
		 
<!--	<div class="form-group col-lg-3">
  <label for="source">Source:</label>
  <select name="source" class="form-control" id="flag">
	  <option value="1" <?php if($group[0]['groups']['source']==1){ echo "selected"; }?>>CC</option>
	  <option value="2" <?php if($group[0]['groups']['source']==2){ echo "selected"; }?>>Inventory</option>
			  </select>
</div>-->
	</div>

		  <button class="btn btn-success" type="button" onclick="submitAction()">Submit</button>
	 

	  </form>
 
</div>
 

<script>
	


	function submitAction(){
		var groupname = $("#groupname").val();
		var flag = $("#flag").val();
		
		
		if(groupname==''){
			alert("Please Enter Groupname");
			return false;
		}
		
		else if(flag == ''){
			
			alert("Please Enter Mobile Number");
			return false;
		}
		else {
			
			$("#groupform").submit();
		}
		
	}
	

</script>







