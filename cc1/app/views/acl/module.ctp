<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
 <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>
	<style type="text/css">
		.checkbox {
			font-size: 12px;
			line-height: 23px;
		}
	</style>

	
				

		
	<div class="container">
	<div class="row">
		<div class="row">
		<div class="col-lg-12" style="text-align: center;">
			<h3>Access Control Module 
                            <span style="padding:30px">
                                <a target="_blank" href="/acl/listUser/">Add User</a>
                            </span>
                            <span style="padding:30px">
                                <a target="_blank" href="/acl/outsideAccess/">Group Access</a>
                            </span>
                            <span style="text-align: center;">
                                <a target="_blank" href="/acl/groupUsers/">Group Users</a>
                            </span>    
                            <span style="padding:30px">
                                <a target="_blank" href="/acl/moduleAccess/">Module Access</a>
                            </span>
                        </h3>
		  
		</div>
	   </div>
	
		<div class="row" style="padding: 40px 10px 10px;">
			<div class="col-lg-12" style="text-align: center;">
			 <label class="control-label">Group Name: </label>
				<select name ="group_name" id="group_name" onchange="group();" class="">
					<option value="">---Select Group----</option>
					<?php foreach ($group as $groupval){ ?>
					<option value="<?php echo $groupval['groups']['id']; ?>"><?php echo $groupval['groups']['name']; ?></option>
					<?php } ?>

				</select>
			 <img id="loader" style="align:center;display: none;" src="/img/ajax-loader-2.gif"/>
			</div>
			
		</div>
		
		<div class="col-lg-10 col-lg-offset-3" style="line-height: 30px;">
			<div id ="Unassignedmodule" class="col-lg-4" style="box-shadow: 0px 0px 2px grey;"></div>
		<div class="col-lg-1" id="controls" style="display:none;">
			<button style="margin-bottom: 16px;" class="btn btn-default btn-sm" onclick="addModule();" type="button"><i class="glyphicon glyphicon-arrow-right"></i></button>
			<button style="margin-bottom: 16px;" class="btn btn-default btn-sm" onclick="delModule();" type="button"><i class="glyphicon glyphicon-arrow-left"></i></button>
			
			
		</div>
			<div id ="Assignedmodule" class="col-lg-4" style="box-shadow: 0px 0px 2px grey;"></div>
		</div>
		
		</div>
		</div>
	



<script>

	function group(){
		
		//alert('heello');
		var group = $("#group_name").val();
		var url = '/acl/module/';
		var html = "";
		$("#Assignedmodule").html('');
		$("#Unassignedmodule").html('');
		if(group == ''){
			alert("Please Select Group");
			return false;
		}
		
		$.ajax({
            url: url,
            type: "POST",
            data: {"group": group},
            dataType: "json",
            success: function(data) {
				if(data.status == "success"){
					
					$.each(data.response,function(key,val){
						$("#"+key).html('');
						html+="<ul style='list-style:none;'><h5 style='text-align:center;'>"+key+"</h5>"
						$.each(val,function(k,v){
							html+="<li class='active'><div class=\"span3\"> <label for=\"checkbox\" class=\"checkbox\">"
							html+="<input type='checkbox' id="+k+" value="+k+" name='module[]' class="+key.toLowerCase()+">"+v+"</label></div>"
							if(key=='Unassignedmodule'){
                            html+="<div class=\"span3\"><label for=\"checkbox\" class=\"checkbox\"><input type='radio' id='full_"+k+"' value='full_"+k+"' name='access_"+k+"' class='access' >Full access</label>\n\
									<label for=\"checkbox\" class=\"checkbox\"><input type='radio' id='partial_"+k+"' value='partial_"+k+"' name='access_"+k+"' class='access' >Partial access</label></div></li>"
			                  }
						});
						 html+="</ul>";
						$("#"+key).append(html);
						html = '';
						
					});
					$("#controls").show();
					
				}
               
            },beforeSend: function(){
               $('#loader').show();
               },
               complete: function(){
                  $('#loader').hide();
                },
               error: function (xhr,error) {
              
               }
        });
		
	}
	
	function delModule(){
	var checkedValues = $('input.assignedmodule:checked').map(function() {
		 return this.value;
     }).get();
	 var id = checkedValues.join(',');
	
	
	 var url = "/acl/module/";
	 var groupid = $("#group_name").val();
	
	
	 if(id ==''){
	    alert("Please Select Module");
		return false;
	 } else {
	 //$("#Assignedmodule").html('');
	// $("#Unassignedmodule").html('');
	 var html = "";
	 $.ajax({
            url: url,
            type: "POST",
            data: {"group": groupid,"moduleid":id},
            dataType: "json",
            success: function(data) {
				
				if(data.status == "success"){
					$.each(data.response,function(key,val){
						$("#"+key).html('');
						html+="<ul style='list-style:none;'><h5 style='text-align:center;'>"+key+"</h5>"
						$.each(val,function(k,v){
							html+="<li class='active'><div class=\"span3\"> <label for=\"checkbox\" class=\"checkbox\">"
							html+="<input type='checkbox' id="+k+" value="+k+" name='module[]' class="+key.toLowerCase()+">"+v+"</label></div>"
							if(key=='Unassignedmodule'){
                            html+="<div class=\"span3\"><label for=\"checkbox\" class=\"checkbox\"><input type='radio' id='full_"+k+"' value='full_"+k+"' name='access_"+k+"' class='access' >Full access</label>\n\
									<label for=\"checkbox\" class=\"checkbox\"><input type='radio' id='partial_"+k+"' value='partial_"+k+"' name='access_"+k+"' class='access' >Partial access</label></div></li>"
			                  }
						});
						 html+="</ul>";
						$("#"+key).append(html);
						html = '';
						
					});
					
				}
			
			},beforeSend: function(){
               $('#loader').show();
               },
               complete: function(){
                  $('#loader').hide();
                },
               error: function (xhr,error) {
              
               }
		});
		}
	
}

function addModule(){

 var accesstype = "<?php echo json_encode($accesstype) ?>";

 
  var checkedValues = $('input.unassignedmodule:checked').map(function() {
		 return this.value;
     }).get();
	 
  var id = checkedValues.join(',');
	 
  var access_id = $('input.access:checked').map(function() {
		 return this.value;
    }).get();
	 
    var access_id = access_id.join(',');
	
	 if(id ==''){
	    alert("Please Select Module");
		return false;
	 } else {
	 
	 var url = "/acl/module/";
	 var groupid = $("#group_name").val();
	 var html = "";
	 $.ajax({
            url: url,
            type: "POST",
            data: {"group": groupid,"insertid":id,"access_id" : access_id},
            dataType: "json",
            success: function(data) {
				
				
				if(data.status == "success"){
					$.each(data.response,function(key,val){
						$("#"+key).html('');
						html+="<ul style='list-style:none;'><h5 style='text-align:center;'>"+key+"</h5>"
						$.each(val,function(k,v){
							html+="<li class='active'><div class=\"span3\"> <label for=\"checkbox\" class=\"checkbox\">"
							html+="<input type='checkbox' id="+k+" value="+k+" name='module[]' class="+key.toLowerCase()+">"+v+"</label></div>"
							if(key=='Unassignedmodule'){
                            html+="<div class=\"span3\"><label for=\"checkbox\" class=\"checkbox\"><input type='radio' id='full_"+k+"' value='full_"+k+"' name='access_"+k+"' class='access' >Full access</label>\n\
									<label for=\"checkbox\" class=\"checkbox\"><input type='radio' id='partial_"+k+"' value='partial_"+k+"' name='access_"+k+"' class='access' >Partial access</label></div></li>"
			                  }
						});
						 html+="</ul>";
						$("#"+key).append(html);
						html = '';
						
					});
					
				}
			
			},beforeSend: function(){
               $('#loader').show();
               },
               complete: function(){
                  $('#loader').hide();
                },
               error: function (xhr,error) {
              
               }
		});
		}

}
	
	
	

</script>







