<html>

<head>
</head>

<body>

<!-- <a class="btn btn-large btn-danger" data-toggle="confirmation" data-original-title="" title="">Click to toggle confirmation</a> -->
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="list">
			<br />
			<div class="btn-group">
				<form class="form-inline" role="form" action="/circles/index/"
					method="GET">
					<div class="form-group" id="operators">
						<label for="operator">Select Operator</label>
						 <select class="btn btn-default" id="operator" onclick='loadCircles()' style = "width :150px">
							<option value="null">-- Select Operator -- </option>
							<option value="1" >Aircel</option>
							<option value="2" >Airtel</option>							
							<option value="3" >BSNL</option>							
							<option value="4" >Idea</option>
							<option value="30">MTNL</option>
							<option value="6" >MTS</option>
							<option value="7" >Reliance CDMA</option>							
							<option value="8" >Reliance GSM</option>
							<option value="9" >Tata Docomo</option>
							<option value="10">Tata Indicom</option>							
							<option value="11">Uninor</option>
							<option value="12">Videocon</option>							
							<option value="15">Vodafone</option>
							<option value="16">Airtel DTH</option>
							<option value="18">Dish TV</option>
							<option value="17">Reliance DTH</option>
							<option value="20">Tata Sky DTH</option>
							<option value="21">Videocon DTH</option>
							<option value="19">Sun TV DTH</option>
                                                        <option value="83">Reliance Jio</option>
						</select> &nbsp &nbsp &nbsp
					</div>
					<div class="form-group">
						<label for="circle"> Select Circle</label>
							 <select class="btn btn-default" id="circles" onclick='loadPlans()' style = "width: 150px;">
							</select> &nbsp &nbsp &nbsp
					</div>

					<div class="form-group" id="plans">
						<label for="plan-type">Select Plan Type </label> 
						<select class="btn btn-default" id="plan-type"  style = "width: 160px;">
							<!-- ajax code comes here -->
						</select>&nbsp &nbsp &nbsp
					</div>
                                        <div class="form-group">

										<!-- ajax code comes here -->
                                                <label for="plan-amt">Plan Amount</label>&nbsp &nbsp
                                                <input type="number" class="form-control" id="plan-amt" name="plan-amt" style = "width :150px" placeholder="Plan Amount"  value="" /> 
			
					
					</div>

					<div class = "form-group">					
						<button type="button" onclick='submitform()' class="btn btn-success">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>
					</div>


					<!-- BUTTON TRIGGER MODAL -->

					<div class = "form-group">	
						<!--<div class = "col-md-10 col-md-offset-2">		-->	
						<button type="button" class="btn btn-primary" data-toggle="modal"
							data-target="#newPlanForm">
							<span class="glyphicon glyphicon-plus"></span> Add Plan
						</button>
						<!--</div>	-->
					</div>
					<div class = "form-group">	
                                            <button type="button" class="btn btn-danger" onclick="javascript:deleteMultiplePlans();">
                                                <span class="glyphicon glyphicon-minus"></span> Delete Plans
                                            </button>
					</div>
				</form>
			</div>
			<br /> <br>
			<div class="table-responsive">
				<?php
					if(!empty($posts)){
						echo "<h5>Operator Name => <b>" . $posts [0]['circle_plans']['opr_name'] . "</b> --- Circle Name => <b>".$posts [0]['circle_plans']['c_name'];
						if($planType == "null")
							echo "</b> --- Plan Type => <b> All Plans</b>";
						else 
							echo "</b> --- Plan Type => <b>".$posts [0]['circle_plans']['plan_type']."</b></h5>";
					}	
                                        
				?>
						
				<table class="tablesorter table table-hover table-bordered" id = "plantable">
					<thead>
						<tr>
							<td class = "field-label active" style="width:5%;vertical-align: bottom;">
                                                            <span>
                                                                <input type="checkbox" id="select_all">
                                                            </span>
                                                        </td>
							<th class = "field-label active" style = "width: 5%;">#</th>
							<th class = "field-label active" style = "width: 10%;">CIRCLE NAME</th>
							<th class = "field-label active" style = "width: 10%;">OPERATOR NAME</th>
							<th class = "field-label active" style = "width: 10%;">PLAN TYPE</th>
							<th class = "field-label active" style = "width: 10%;">PLAN AMOUNT (Rs.)</th>
							<th class = "field-label active" style = "width: 10%;">PLAN VALIDITY</th>
							<th class = "field-label active" style = "width: 25%;">PLAN DESC</th>
							<th class = "field-label active" style = "width: 10%;">UPDATED ON</th>
							<th style = "width: 10%;"></th>
						</tr>
					</thead>
					<tbody>
  
	            	<?php
						foreach ( $posts as $index => $arr ) {
							echo "<tr id='plan_".$arr['circle_plans']['id']."'>";
                                                        echo '<td><input  data-plan-id ="'.$arr['circle_plans']['id'].'" id="check_'.($index+1).'" class="plan-select" type="checkbox" name="check[]"></td>';
							echo "<td>" . ($index + 1) . "</td>";
							foreach ( $arr as $circles => $keys ) {
								foreach ( $keys as $key => $value ) {
									if ($key == 'id')
										continue;
									echo "<td>" . $value . "</td>";
								}
					?>
				
					<td>
						<button type = "button" class = "btn btn-link" onclick = "editPlan(<?php echo $keys['id'];?>)">
							<span class = "glyphicon glyphicon-edit"></span>Update
						</button>
						<button type = "button" class= "btn btn-link" onclick = "deletePlan(<?php echo $keys['id'];?>)">
							<span class = "glyphicon glyphicon-trash"></span> Delete
						</button>

					</td>
			
					<?php
						echo "</tr>";
							}						
					}
					?>          
    				</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- MODAL DATA-->
	
	<div class="modal fade bs-example-modal-lg" id="newPlanForm" tabindex="-1" role="dialog" aria-labelledby="newPlanLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="newPlanLabel" align="center">New Plan
						Entry Form</h3>
				</div>
				<div class="modal-body">
					<form class="form-inline" method="post" action="/circles/newPlanEntry/" id = "addPlanForm" onsubmit="return check_filled(event)">
						<br /> <br /> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
						&nbsp &nbsp
						<div class="form-group">

							<label for="operator">Select Operator(*)</label> &nbsp &nbsp 
							<select class="form-control" name="operator" id="operator-form" style = "width :180px">
								<option value="null">Select Operator</option>
								<option value="1">Aircel</option>
								<option value="2">Airtel</option>							
								<option value="3">BSNL</option>								
								<option value="4">Idea</option>
								<option value="30">MTNL</option>
								<option value="6">MTS</option>
								<option value="7">Reliance CDMA</option>								
								<option value="8">Reliance GSM</option>
								<option value="9">Tata Docomo</option>
								<option value="10">Tata Indicom</option>																	
								<option value="11">Uninor</option>
								<option value="12">Videocon</option>								
								<option value="15">Vodafone</option>
								<option value="16">Airtel DTH</option>
								<option value="18">Dish TV</option>
								<option value="17">Reliance DTH</option>
								<option value="20">Tata Sky DTH</option>
								<option value="21">Videocon DTH</option>
								<option value="19">Sun TV DTH</option>
                                                                <option value="83">Reliance Jio</option>
							</select>
						</div>

						&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
						<div class="form-group">
							<label for="circle">Select Circle(*)</label> &nbsp &nbsp 
							<select class="form-control" name="circle" id="circles-form" style = "width :180px">
								<option value="null">Select Circle</option>
								<option value="0">No Circle (For DTH)</option>
								<option value="1">AndhraPradesh</option>
								<option value="2">Assam</option>
								<option value="4">Chennai</option>
								<option value="5">Delhi NCR</option>
								<option value="6">Gujarat</option>
								<option value="7">Haryana</option>
								<option value="8">Himachal Pradesh</option>
								<option value="9">Jammu & Kashmir</option>
								<option value="3">Jharkand</option>
								<option value="10">Karnataka</option>
								<option value="11">Kerala</option>
								<option value="12">Kolkata</option>
								<option value="14">Madhya Pradesh</option>
								<option value="13">Maharashtra</option>
								<option value="15">Mumbai</option>
								<option value="17">Orissa</option>
								<option value="18">Punjab</option>
								<option value="19">Rajasthan</option>
								<option value="20">Tamil Nadu</option>
								<option value="16">Tripura</option>
								<option value="21">Uttar Pradesh (East)</option>
								<option value="22">Uttarakhand</option>
								<option value="23">West Bengal</option>
							</select>
						</div>

						<br /> <br /> <br /> <br /> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
						&nbsp &nbsp &nbsp &nbsp

						<div class="form-group">
							<label for="planType">Select Plan Type(*)</label> &nbsp &nbsp
							<select class="form-control" name="planType" id="plan-type-form" style = "width :180px">
                                                                                                                                                                                                <option value="Topup">Topup</option>
                                                                                                                                                                                                <option value="Topup-Plans">Topup-Plans</option>
                                                                                                                                                                                                <option value="3G">3G</option>
                                                                                                                                                                                                <option value="Data_2G">Data/2G</option>
                                                                                                                                                                                                <option value="4G">4G</option>
                                                                                                                                                                                                <option value="Other">Other</option>
								<!-- 					<option value=null>Select Plan Type</option> -->
							</select>
						</div>

						&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp

						<div class="form-group">
							<label for="planAmount">Plan Amount(*)</label>&nbsp &nbsp
							<input type="number" class="form-control" id="planAmount-form" name="planAmount" placeholder="Plan Amount" style = "width :180px">
						</div>

						<br /> <br /> <br /> <br /> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
						&nbsp &nbsp &nbsp &nbsp
						<div class="form-group">
							<label for="planValidity">Plan Validity(*)</label>&nbsp &nbsp
							 <input type="text" class="form-control" id="planValidity-form" name="planValidity" placeholder="Plan Validity" style = "width :180px">
						</div>
						<br /> <br /> <br /> <br /> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
						&nbsp &nbsp &nbsp &nbsp

						<div class="form-group">
							<label for="planDescription">Plan Description(*)</label>&nbsp &nbsp
							<textarea class="form-control" id="planDescription-form" name="planDescription" rows="4" cols="70"></textarea>
						</div>
						<br /> <br /> <br /> <br />

<!-- 						<div class="form-group"> -->
<!-- 							<div class="col-sm-offset-10 col-sm-10"> -->
<!-- 								<button type="submit" class="btn btn-success">Submit</button> -->
<!-- 							</div> -->
<!-- 						</div>  -->

												
						<div class="modal-footer">
						     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						     <button type="submit" class="btn btn-success" >Submit</button>
						</div> 
					
					</form>

				</div>

			</div>
		</div>
	</div>

</body>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- <script src="js/jquery.min.js"></script> -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="js/bootstrap.min.js"></script> -->


<script type="text/javascript">    

/**
 *  returns the result based on the operator and its circle with plan_type selected
 */
      function submitform(){
          var circle = $('#circles').val();
          var operator = $('#operator').val();
       var plan_type = $('#plan-type').val();
        var plan_amt = $('#plan-amt').val();
         /* $("#circles").val(circle);
          $("#operator").val(operator);
         $("#plan-type").val(plan_type);*/




          if (operator == "null"){
            	var result = confirm("Please select a operator");
            	if(result == true || result == false){
              	}
// 				if (result == true && operator != "null"){ 
// 				}			
       	  }
          if (circle == "null"){
              	var result = confirm("Please select a circle.");
              	if(result == true || result == false){
              	}
            }
        /* if (plan_type == "null"){
              	var result = confirm("Please select a plan type.");
              	if(result == true || result == false){
              	} */
// 				if (result == true && circle != "null"){
// // 					window.location.href = '/circles/index/'+circle+'/'+operator+'/'+plan_type; 
// 				}			
          
          if(circle != "null" && circle != null){
        	  window.location.href = '/circles/index/'+circle+'/'+operator+'/'+plan_type;
          }   
         if(plan_amt != "null" && plan_amt != null){
        	  window.location.href = '/circles/index/'+circle+'/'+operator+'/'+plan_type+'/'+'0'+'/'+plan_amt;
          }
      }

/**
 * Deletes a particular plan i.e. just make its show_flag = 0 in database
 */
      function deletePlan(planId){
          var result = confirm("Are you sure you want to delete the following plan ??")
          if(result == true){
              window.location.href = '/circles/deletePlan/'+planId+'/';
          }
      }

 
 /**
 * Edit the details of a plan
 */     
      
      function editPlan(planId) {
//           alert (planId);
//           alert('/circles/editPlanForm/'+planId+'/');
      	  window.location.href = '/circles/editPlanForm/'+planId+'/';    
	  }

 /**
 * loads the circles for a selected operator
 */
      function loadCircles(circle,plan_type){
          
      // var d=$.Deferred();
          
       	 var operator_id = $('#operator').val();     	
       	 var html = '';
         var url = '/circles/searchCircles/';

         $("#circles").html('');
         html += "<option value = 'null'>-- Select Circle --</option>";
         
          $.ajax({
              url: url,
              type: "POST",
              async:"false",
              data: {"operator_id": operator_id},
              dataType: "json",
 			  success: function(data) {
//  				console.log(data)
 				if(data.status == "success"){
//  	 				if(data.response.length > 1){
 					$.each(data.response,function(key,val)
                                        {
 	 					if(val != ' ')
                                                {
                                                    html+="<option value = '" + key + "'>" + val + "</option>"
 	 					}
                                                     
                                        });
//  	 				}
                     //console.log(html);
                                    
                    
 				}
 				if(data.status == "failure"){
 					html += "<option value = '0'> No circle (for DTH)</option>";
 	 			}
 				 $("#circles").append(html);
                                     if(typeof circle !== 'undefined')
                                                {
                                                 $("#circles").val(circle);
                                                    console.log(circle);
                                                    loadPlans(plan_type);
                                                }
                                                else
                                                    {
                                                         $("#circles").val("1");
                                        //alert("Not defind");
                                                    }
                                           
                               //  $('#circles').val(circle);
                                  
                                 // d.resolve();
                                 //alert("1");
                              /*if(typeof circle !== 'undefined'){
                                        $("#circles").val(circle);
                                        console.log(circle);
                                    }else{
                                        $("#circles").val("1");
                                        //alert("Not defind");
                                    }*/
 			 }
 		 
          });
          //alert(circle);
         
          //return d.promise();
      }

      /**
      *Loads the plan types for a selected operator and circle
      */           
      function loadPlans(plan_type){
      	 var circle_id = $('#circles').val();
      	 var operator_id = $('#operator').val();
      	 var html = '';
         var url = '/circles/searchPlans/';
         $("#plan-type").html('');
        // $("#circles").val(circle_id);

         html += "<option value='null'> (All Plans) </option>";
         $.ajax({
             url: url,
             type: "POST",
             async:"false",
             data: {"circle_id": circle_id, "operator_id": operator_id},
             dataType: "json",
			 success: function(data)
                         {
// 				 console.log(data);
				if(data.status == "success")
                                {
					$.each(data.response,function(key,val)
                                        {
						if(val != '')
                                                {
							if(val == "Data\/2G")
                                                        {
								html+="<option value = 'Data_2G'>" + val + "</option>"
							}
                                                    else{
                                                            html+="<option value = '" + val + "'>" + val + "</option>"
                                                        }    	
						}
                                                
                                              /*  if(plan_type != null)
                                                {
                                                    
                                                } */
                                                
					});

                    ///console.log(html);
                    $("#plan-type").append(html);
                    if(plan_type != "0")
                                                {
                                                     $("#plan-type").val(plan_type);
                                                    console.log(plan_type);
                                                 }  
                   // $("#plan-type").val(plan_type);
				}
			 }
		 
         });         
      }



      /**
      *loads plan for the new plan form
      */
      function loadPlansform(){
         // var circle_id = $('#circles-form').val();
       	 var operator_id = $('#operator-form').val();
       // var circle_id = $('#circles').val();

       	 var html = '';
          var url = '/circles/searchPlans/';
          $("#plan-type-form").html('');

          html += "<option value='null'> -- Select Plan Type -- </option>";
          $.ajax({
              url: url,
              type: "POST",
              data: {"operator_id": operator_id}, 
              // data: {"circle_id": circle_id, "operator_id": operator_id},
              dataType: "json",
 			  success: function(data) {
//  				 console.log(data)
 				if(data.status == "success"){
 					$.each(data.response,function(key,val){
 						if(val == "Data\/2G"){
 							html+="<option value = 'Data_2G'>" + val + "</option>"
 						}
                        else{
                         	html+="<option value = '" + val + "'>" + val + "</option>"
                        }    	
 					});

//                      console.log(html);
                     $("#plan-type-form").append(html);
 				}
 			 }
 		 
          });         
       }



      /**
      *
      */
	   function check_filled(event){
			var operator = $('#operator-form').val();
			var plan_type = $('#plan-type-form').val();
			var circle = $('#circles-form').val();
			var plan_desc = $('#planDescription-form').val();
			var plan_amt = $('#planAmount-form').val();
			var plan_validity = $('#planValidity-form').val();

			if(operator == "null" || circle == "null" || plan_type == "null" || plan_type == null || plan_desc == "" || plan_amt == "" || plan_validity == ""){
				event.preventDefault();
				alert ("Please fill in all the fields");
			}
	   }
		
	   $(document).ready(function(){
		   $("#plantable").tablesorter( {
			   headers: { 
		            // assign the secound column (we start counting zero) 
		            5: { 
		                // disable it by setting the property sorter to false 
		                sorter: false 
		            }, 
		            // assign the third column (we start counting zero) 
		            6: { 
		                // disable it by setting the property sorter to false 
		                sorter: false 
		            },
		            8: {
			            sorter: false
		            } 
			   }

			}); 
			var opr_id = '<?php echo $prod_code_pay1; ?>';
                        var circle_id = '<?php echo $c_id; ?>';
                        var plan_type = '<?php echo $planType; ?>';
                        var plan_amt = '<?php echo $plan_amt; ?>';

                        //To load circles n plan list even after form submission
                      /*  if(opr_id != "")
                            {
				
                                $("#operator").val(opr_id);
                                $("#plan-amt").val(plan_amt);
                                loadCircles();
                            }
                        if(circle_id != "")
                            {
             			  $("#circles").val(circle_id);
                                  loadPlans();

                            } */
                                
                            
                          
			if(opr_id != "")
                        {
				
                                $("#operator").val(opr_id);
                                
                                $("#plan-amt").val(plan_amt);
                                loadCircles(circle_id,plan_type);
                                //alert("0");
                               /* loadCircles(circle_id).done(function(){
                                  console.log("In here");
                                    loadPlans(plan_type);
                                    
                                }); */
                                //alert("2");
                                //alert($("#circles").html());
                                //alert($("#operator").html());

                            }
                          
//                         if(circle_id){
//                            console.log("length"+$(document).find('#circles').length);
//				  $('#circles').val('1');
//                                    loadPlans();
//                    	}
                        /*if(plan_type != ""){
				$("#plan-type").val(plan_type);
				//loadPlans();
                        }*/
                             
 			
 			var duplicate = '<?php echo $duplicateFlag;?>';
 			if(duplicate == 1){
				alert ('The plan with same amount and same plan type exists already within the same circle for entered operator');
 	 		}
                        $("#select_all").change(function(){  //"select all" change 
                            $(".plan-select").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
                        });

                        //".checkbox" change 
                        $('.plan-select').change(function(){ 
                            //uncheck "select all", if one of the listed checkbox item is unchecked
                            if(false == $(this).prop("checked")){ //if this item is unchecked
                                $("#select_all").prop('checked', false); //change "select all" checked status to false
                            }
                            //check "select all" if all checkbox items are checked
                            if ($('.plan-select:checked').length == $('.plan-select').length ){
                                $("#select_all").prop('checked', true);
                            }
                        });
 		});    
   
    function deleteMultiplePlans(){
        var allow = false;
        var plans = [];
        $('.plan-select').each(function(key,checkbox){
            if( $(checkbox).is(':checked') ){
                plans.push($(checkbox).attr('data-plan-id'));
                allow = true;
            }
        });
        if(!allow){
            alert('Select at least one plan to delete.');
            return false;
        }
        var confirm_delete = confirm("You sure ?");
	if(confirm_delete){
            $.ajax({
                type: 'POST',
                url: '/circles/deletePlans',
                data: {
                   plans: plans,
                },
                error: function() {
//                   $('#info').html('<p>An error has occurred</p>');
                },
                success: function(response) {
                    if($.trim(response) == 'success'){
                        $.each(plans,function(index,plan_id){
                            $('#plan_'+plan_id).remove();
                        });
                    } else {
                        alert('Some thing went wrong. Please try again.');
                    }
                }
            });
		
	} else {
            return false;
        }
    }
    </script>
    
    