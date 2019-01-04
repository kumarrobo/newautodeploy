<div style="width:500px;"><?php echo $form->create('details'); ?>
     	<!--<form id="UserLoginForm" method="post" accept-charset="utf-8" action="/users/login">-->			
		<fieldset class="fields">
			<legend>Personal Details</legend>
				<div class="field" style="padding-top:10px;">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="username"> Name </label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="1" type="text" id="username" name="data[User][name]" autocomplete="off" value="<?php echo $_SESSION['Auth']['User']['name']; ?>"/>
                         </div>                     
                 	</div>
            	 </div>
                 <?php //print_r($_SESSION);?>
                 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="emailid">Email ID</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="2" type="text" id="emailid" name="data[User][email]" value="<?php echo $_SESSION['Auth']['User']['email']; ?>"/>
                         </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="dob">Date of birth</label></div>
                         <?php 
                         	if(!empty($_SESSION['Auth']['User']['dob'])){
	                         	$dob_arr = explode("-",$_SESSION['Auth']['User']['dob']);
	                         	$year = $dob_arr[0];
	                         	$month = $dob_arr[1];
	                         	$day = $dob_arr[2];               
                         	}
                         	$month_arr = array("Jan","Feb","Mar","Apr","May","Jun",
										"Jul","Aug","Sep","Oct","Nov","Dec");		
                         ?>
                         <div class="fieldLabelSpace">
                            <select tabindex="3" id="UserDobMonth" name="data[User][dob][month]">
                            <?php $i = 0; while($i<12) {
                            	$i++;
                            	$str = '';
	
								if($i < 10){
									if($month == "0$i")
                            			$str = 'selected="selected"';
									echo '<option value="0'.$i.'" '.$str.'>'.$month_arr[$i-1].'</option>';				
								}
								else {
									if($month == $i)
                            			$str = 'selected="selected"';
									echo '<option value="'.$i.'" '.$str.'>'.$month_arr[$i-1].'</option>';
								}
							}
							?>
							</select>
							
							<select tabindex="4" id="UserDobDay" name="data[User][dob][day]">
							<?php $i=0; 
								while($i < 31){
									$i++;
									
									$str = '';
	
									if($i < 10){
										if($day == "0$i")
	                            			$str = 'selected="selected"';
										echo '<option value="0'.$i.'" '.$str.'>0'.$i.'</option>';				
									}
									else {
										if($day == $i)
	                            			$str = 'selected="selected"';
										echo '<option value="'.$i.'" '.$str.'>'.$i.'</option>';
									}
							
								}	
							?>
							</select>
							
							<select tabindex="5" id="UserDobYear" name="data[User][dob][year]">
							<option selected="selected" value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
							<?php $i=date('Y'); 
								while($i > 1920){
									$i--;
									$str = '';
									if($year == $i)
	                            		$str = 'selected="selected"';
									echo '<option value="'.$i.'" '.$str.'>'.$i.'</option>';
								}	
							?>
							</select>
                    	</div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="city">City</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="6" type="text" id="city" name="data[User][city]" value="<?php echo $_SESSION['Auth']['User']['city']; ?>"/>
                            </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="gender">Gender</label></div>
                         <div class="fieldLabelSpace">
                         	<input tabindex="7" type = 'Radio' name="data[User][gender]" value= '0' <?PHP if($_SESSION['Auth']['User']['gender'] == '0') echo 'checked' ?>>Male

							<input tabindex="7" type = 'Radio' name="data[User][gender]" value= '1'
							<?PHP if($_SESSION['Auth']['User']['gender'] == '1') echo 'checked' ?>
							>Female
        
                          </div>
                    </div>
            	 </div>
                 
                 <div class="field">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" id="sub_butt">
                            <?php echo $ajax->submit('spacer.gif', array('id' => 'sub', 'tabindex'=>'8','url'=> array('controller'=>'users', 'action'=>'changeDetails'), 'class' => 'otherSprite oSPos7', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         <!-- <div style="display:block"><img src="/img/loader2.gif" /></div> -->
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" style="color:#004B91">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>			
		</fieldset>
<?php echo $form->end(); ?>
</div>
<script>
if($('username'))
	$('username').focus();	
</script>