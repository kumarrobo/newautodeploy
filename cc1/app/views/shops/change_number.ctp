<script type="text/javascript" src="/min/b=js&f=lib/jquery-1.9.0.min.js"></script> 
<script type="text/javascript" src="/min/b=js&f=lib/bootstrap/js/bootstrap.min.js"></script> 
<div>
	<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'activity'));?>
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_activities',array('side_tab' => 'changeNumber'));?>
    		<div id="innerDiv">
                    <div id ="number">
                        <span style="font-weight:bold;margin-right:10px;">Enter Mobile Number: </span><input type="text"  name="mobile_no"  placeholder="Enter Mobile Number" id="mobile_no" value="">
					<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="ChangeretNumber();"></span>
    </div>
                <div id="checkdata" style="display: none;">
                    <fieldset class="fields">
			<legend>Change Number</legend>
				<div class="field" style="padding-top:10px;">
               		<div class="fieldLabelMand leftFloat">
						<label>*</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="pass1"> OTP</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="1" type="password" id="otp" name="otp" autocomplete="off"/>
                           
                         </div>                     
                 	</div>
            	 </div>
                 
                 <div class="field">
               		<div class="fieldLabelMand leftFloat">
						<label>*</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="pass2">New Mobile</label></div>
                         <div class="fieldLabelSpace">
                            <input tabindex="2" type="new_mob" id="new_mob" name="new_mob"  />
                            
                         </div>
                    </div>
            	 </div>
            	 
            	 <div class="field">
               		<div class="fieldLabelMand leftFloat">
						<label>*</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat"><label for="pass3">Old Mobile</label></div>
                         <div class="fieldLabelSpace">
                             <input tabindex="3" value="" disabled="disabled" type="old_mob" id="old_mob" name="old_mob"/>
                            
                         </div>
                    </div>
            	 </div>
                 
                 <div class="field">
               		<div class="fieldLabelMand leftFloat">
						<label>&nbsp;</label>
					</div>
                    <div class="fieldDetail">
                         <div class="fieldLabel leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace" id="cp_sub_sutt">
                         	
                           <input type="button" class="retailBut enabledBut" value="submit" onclick="submit();">
                         </div>
                    </div>
            	 </div>			
		</fieldset>
                </div>
 </div>
</div>
 </div>
<br class="clearRight" />
</div>
<script>

  function ChangeretNumber(){
      
        var mobileNo = $('#mobile_no').val();
        if(mobileNo == ''){
        alert("Please Enter Mobile Number");
        return false;
        }
       
        $.ajax({
            url: '/shops/changeNumber/',
            type: "POST",
            data: {'mobileNo':mobileNo},
            dataType: "json",
            success: function(data) {
                if(data.result=="success"){
                    $("#old_mob").val(data.number);
                    $("#number").hide();
                    $("#checkdata").show();
                } else {
                    alert(data.desc);
                }
               
        }
        });
   
  }
  
  function submit(){
      
       var otp = $('#otp').val();
       var newMob = $("#new_mob").val();
       var oldMob = $("#old_mob").val();
      
        if(otp == ''){
        alert("Please Enter OTP");
        return false;
        }
        else if(newMob==''){
        alert("Please Enter New Mobile Number");
        return false;
        }
        else if(oldMob==''){
        alert("Please Enter Old Mobile Number");
        return false;
        }
       
        $.ajax({
            url: '/shops/ChangeNumber/',
            type: "POST",
            data: {'otp':otp,'newMob':newMob,'oldMob':oldMob},
            dataType: "json",
            success: function(data) {
               alert(data.desc);
        }
        });
      
  }
</script>
