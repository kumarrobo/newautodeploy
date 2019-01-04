
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
	  <form name="userfrom" method="post" id="userform" action="/acl/addUser/">
		  <input type="hidden" name="user_id" id="user_id" value="<?php echo isset($user_id) ? $user_id : "" ?>">
		
	<div class="row">
		
<div class="form-group col-lg-3">
		
  <label for="username">User Name:</label>
  <input type="text" class="form-control" name="username" id ="username" value="<?php echo isset($userData[0]['internal_users']['username']) ? $userData[0]['internal_users']['username']: $userData[0]['users']['name'];  ?>">
			
					
</div>
</div>
<div class="row">
<div class="form-group col-lg-3">
				
  <label for="mobile">Mobile Number:</label>
  <input type="text" class="form-control" name="mobile" id ="mobile" maxlength="10" value="<?php echo isset($userData[0]['users']['mobile']) ? $userData[0]['users']['mobile']: "";  ?>">
			
</div>
</div>
	<div class="row">
		
	<div class="form-group col-lg-3">
  <label for="pwd">Group:</label>
  <select name="group" class="form-control" id="group">
	  <option value="">----Select Group-----</option>
					  <?php foreach ($group as $val) {?>
				  <option value="<?php echo $val['groups']['id'] ?>" <?php if($val['groups']['id'] == $userData[0]['users']['group_id']){ echo "selected"; } ?>><?php echo $val['groups']['name'] ?></option>
					  <?php } ?>
		
			  </select>
</div>
	</div>
		
		  <?php if (!isset($user_id) && empty($user_id)) { ?>

			  <div class="row">
				  <div class="form-group col-lg-3">
					  <label for="pwd"> Enter Password:</label>
					  <input type="password" class="form-control" name="pwd" value="" id="pwd">
		</div>
    </div>
			  <div class="row">
				  <div class="form-group col-lg-3">
					  <label for="pwd">Confirm Password:</label>
					  <input type="password" class="form-control" value=""  name="con-pwd" id="con-pwd">
				  </div>
			  </div>
		  <?php } ?>
		  <button class="btn btn-success" type="button" onclick="submitAction()">Submit</button>
	

	  </form>

</div>


<script>



	function submitAction(){
		var username = $("#username").val();
		var mobile = $("#mobile").val();
		var group = $("#group").val();
		var pwd = $("#pwd").val();
		var confpwd = $("#con-pwd").val();

		if(username==''){
			alert("Please Enter Username");
			return false;
		}

		else if(mobile == ''){

			alert("Please Enter Mobile Number");
			return false;
		}
		
		else if (mobileValidate(mobile)==false)
        {
        alert("Please enter a valid mobile number.");
        return;
        }
		else if(group == ''){
			
			alert("Please Select group");
			return false;
		}
		
		else if(pwd == ''){
			
			alert("Please Enter password");
			return false;
		}
		
		else if(pwd != confpwd){
			
			alert("Password	does not match!!!");
			return false;
		}
		
		else {
			
			$("#userform").submit();
		}
		
	}
	

</script>







