<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<style>
:-moz-placeholder {
    color: blue;
    opacity: 0.4;
}
 
::-webkit-input-placeholder {
    color: blue;
    opacity: 0.4;
}
*:focus {
	outline: none;
}
form {
	font: 14px/21px "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
}
.lead_form h2, .lead_form label {
	font-family:Georgia, Times, "Times New Roman", serif;
}
.form_hint, .required_notification {
	font-size: 11px;
}
.lead_form ul {
    width:750px;
    list-style-type:none;
    list-style-position:outside;
    margin:0px;
    padding:0px;
}
.lead_form li{
    padding:12px; 
    border-bottom:1px solid #eee;
    position:relative;
}
.lead_form li:first-child, .lead_form li:last-child {
    border-bottom:1px solid #777;
}
.contact_form h2 {
    margin:0;
    display: inline;
}
.required_notification {
    color:#d45252; 
    margin:10px 0 0 0; 
    display:inline;
    float:right;
}
.lead_form label {
    width:150px;
    margin-top: 3px;
    display:inline-block;
    float:left;
    padding:3px;
}
.lead_form input {
    height:20px; 
    width:220px; 
    padding:5px 8px;
}
.lead_form textarea {padding:8px; width:300px;}
.lead_form button {margin-left:156px;}
.lead_form input, .lead_form textarea { 
    border:1px solid #aaa;
    box-shadow: 0px 0px 3px #ccc, 0 10px 15px #eee inset;
    border-radius:2px;
    -moz-transition: padding .25s; 
    -webkit-transition: padding .25s; 
    -o-transition: padding .25s;
    transition: padding .25s;
    padding-right:30px;
}
.lead_form input:focus, .lead_form textarea:focus {
    background: #fff; 
    border:1px solid #555; 
    box-shadow: 0 0 3px #aaa; 
    padding-right:70px;
}
button.submit {
    background-color: #68b12f;
    background: -webkit-gradient(linear, left top, left bottom, from(#68b12f), to(#50911e));
    background: -webkit-linear-gradient(top, #68b12f, #50911e);
    background: -moz-linear-gradient(top, #68b12f, #50911e);
    background: -ms-linear-gradient(top, #68b12f, #50911e);
    background: -o-linear-gradient(top, #68b12f, #50911e);
    background: linear-gradient(top, #68b12f, #50911e);
    border: 1px solid #509111;
    border-bottom: 1px solid #5b992b;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    -ms-border-radius: 3px;
    -o-border-radius: 3px;
    box-shadow: inset 0 1px 0 0 #9fd574;
    -webkit-box-shadow: 0 1px 0 0 #9fd574 inset ;
    -moz-box-shadow: 0 1px 0 0 #9fd574 inset;
    -ms-box-shadow: 0 1px 0 0 #9fd574 inset;
    -o-box-shadow: 0 1px 0 0 #9fd574 inset;
    color: white;
    font-weight: bold;
    padding: 6px 20px;
    text-align: center;
    text-shadow: 0 -1px 0 #396715;
}
button.submit:hover {
    opacity:.85;
    cursor: pointer; 
}
button.submit:active {
    border: 1px solid #20911e;
    box-shadow: 0 0 10px 5px #356b0b inset; 
    -webkit-box-shadow:0 0 10px 5px #356b0b inset ;
    -moz-box-shadow: 0 0 10px 5px #356b0b inset;
    -ms-box-shadow: 0 0 10px 5px #356b0b inset;
    -o-box-shadow: 0 0 10px 5px #356b0b inset;
     
}
input:required, textarea:required {
    background: #fff url(/img/required_asterisk.png) no-repeat 98% center;
}
::-webkit-validation-bubble-message {
    padding: 1em;
}
.lead_form input:focus:invalid, .lead_form textarea:focus:invalid { 
    background: #fff url(/img/img_required.png) no-repeat 98% center;
    box-shadow: 0 0 5px #d45252;
    border-color: #b03535
}
.lead_form input:required:valid, .lead_form textarea:required:valid { 
    background: #fff url(/img/green_circle_check14x14.png) no-repeat 98% center;
    box-shadow: 0 0 5px #5cd053;
    border-color: #28921f;
}
.form_hint {
    background: #d45252;
    border-radius: 3px 3px 3px 3px;
    color: white;
    margin-left:8px;
    padding: 1px 6px;
    z-index: 999; 
    position: absolute; 
    display: none;
}
.form_hint::before {
    content: "\25C0"; /* left point triangle in escaped unicode */
    color:#d45252;
    position: absolute;
    top:1px;
    left:-6px;
}
.lead_form input:focus + .form_hint {display: inline;}
.lead_form input:required:valid + .form_hint {background: #28921f;}
.lead_form input:required:valid + .form_hint::before {color:#28921f;}

.symbol {
    font-size: 0.9em;
    font-family: Times New Roman;
    border-radius: 1em;
    padding: .1em .6em .1em .6em;
    font-weight: bolder;
    color: white;
    background-color: #4E5A56;
}
.icon-tick { background: #13c823; }
.icon-tick:before { content: '\002713'; }
.notify {
    background-color:#e3f7fc; 
    color:#555; 
    border:.1em solid;
    border-color: #8ed9f6;
    border-radius:10px;
    font-family:Tahoma,Geneva,Arial,sans-serif;
    font-size:1.1em;
    padding:10px 10px 10px 10px;
    margin:10px;
    cursor: default;
    width:700px;
}
.notify-green { background: #e9ffd9; border-color: #D1FAB6; }
</style>
<?php if(isset($notif) && !$error):?>
<div class="notify notify-green"><span class="symbol icon-tick"></span>   <?php echo $notif?></div>
<?php elseif(isset($notif) && $error):?>
<div class="notify notify-green"><span class=""></span>   <?php echo $notif?></div>
<?php endif ?>
<form class="lead_form" action="" method="post" name="lead_form">
<ul>
    <li>
         <h2>New Lead</h2>
         <span class="required_notification">* Required </span>
    </li>
    <li>
        <label for="name">Name:</label>
        <input type="text" name="name" placeholder="Steve Jobs" required/>
    </li>
    <li>
        <label for="shop_name">Shop Name:</label>
        <input type="text" name="shop_name" placeholder="Dreamworks"/>
    </li>
    <li>
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="steve_jobs@apple.com"/>
    </li>
     <li>
        <label for="email">Phone:</label>
        <input type="number" name="phone" placeholder="9876543210" min="1111111111" max="9999999999" required/>
    </li>
    <li>
        <label for="state">State:</label>
        <input type="text" name="state" placeholder="Nagaland"/>
    </li>
    
    <li>
        <label for="city">City:</label>
        <input type="text" name="city" placeholder="Kohima"/>
    </li>
    
    <li>
        <label for="pin_code">Pincode:</label>
        <input type="text" name="pin_code" placeholder=""/>
    </li>
    
    <li>
        <label for="interest">Partner interest:</label>
        <select id="interest" name="interest" id="interest" required="" onchange="getInterest(this.value)">
	<option>Select</option>
        <option <?php if($interest == "Retailer") echo "selected='selected'" ?> value="Retailer">Retailer</option>
        <option <?php if($interest == "Distributor") echo "selected='selected'" ?> value="Distributor">Distributor</option>
        </select> 
   </li>
   <li>
       <label for="currentbusiness" id="lblcurrbusiness"  style="display:none;">Current business:</label>
   <div class="form-group login-user-current-business" id="currentbusiness" style="display:none;">
                                          <div class="input-group">   									 
                                              <select class="form-control" id="currbusiness" placeholder="Enter your Current Bussiness *" name="signupcurrbusiness" onchange='currBussines(this.value)'>
                                                  <option>Select your Current Bussiness</option>
                                                  <option value="Mobile/Mobile Accessories">Mobile/Mobile Accessories</option>
                                                  <option value="Kirana Shop/Grocery">Kirana Shop/Grocery</option>
                                                  <option value="Restaurant">Restaurant</option>
                                                  <option value="Novelty Store/Gift Items">Novelty Store/Gift Items</option>
                                                  <option value="Medical">Medical</option>
                                                  <option value="Paan Shop">Paan Shop</option>
                                                  <option value="Bags/Footwear">Bags/Footwear</option>
                                                  <option value='Others'>Others</option>
                                              </select>                                              
                                              <div class="input-group-addon"><i class="fa fa-chevron-circle-down" style="font-size:17px" aria-hidden="true"></i></div>
                                          </div>                                                                         
                                      </div>
                                      <div class="form-group login-user-current-business-others" id="currentbusinessothers">
                                          <div class="input-group">
                                               <!--<input type="text" class="form-control" id="currbusinessothers" placeholder="Please Specify *" name="signupcurrbusinessothers" style="display:none">-->
                                               <input type="text" class="form-control" id="currbusinessothers" placeholder="Please Specify if any other business*" name="signupcurrbusinessothers" style="display:none">
                                              <div class="input-group-addon"><i class="fa  fa-building" style="font-size:17px" aria-hidden="true"></i></div>
                                          </div>
                                      </div>
   </li>
    <li>
        <label for="interest">Lead Source:</label>
        <select id="lead_source" name="lead_source" required="">
	<option>Select</option>
	<option <?php if($lead_source == $lead_source_mapping['cc']) echo "selected='selected'" ?> value="cc">CC</option>
	<option <?php if($lead_source == $lead_source_mapping['orm']) echo "selected='selected'" ?> value="orm">ORM</option>
	<option <?php if($lead_source == $lead_source_mapping['app_store']) echo "selected='selected'" ?> value="app_store">AppStore</option>
	<option <?php if($lead_source == $lead_source_mapping['rmpanel']) echo "selected='selected'" ?> value="rmpanel">RM</option>
	<option <?php if($lead_source == $lead_source_mapping['channeloperations']) echo "selected='selected'" ?> value="channeloperations">Channel Ops</option>
        </select> 
   </li>

     <li>
        <label for="message">Message:</label>
        <textarea name="message" cols="40" rows="6" placeholder="A phone recharge business" ></textarea>
    </li>
   <!--  <li>
        <label for="comment">Comment:</label>
        <textarea name="comment" cols="40" rows="6" required></textarea>
    </li> -->
     <li>
         <button class="submit" type="submit" id="btnsubmitlead">Submit Form</button>
    </li>
</ul>
</form>

<script>
    
 function currBussines(val){
    var currbussiness = val; 
    document.getElementById("currentbusinessothers").value = currbussiness;
    if(currbussiness == 'Others')
    {                          
        $('#currentbusinessothers').show();
        $('#currbusinessothers').show();
    }
    else 
    {
        $('#currentbusinessothers').hide();
        $('#currbusinessothers').hide();
    }            
}      

$('button#btnsubmitlead').on('click',function(){
     var interest = $('#interest').val();
     var curr_business = $('#currbusiness').val();
     var currbusinessothers = $('#currbusinessothers').val();
     var lead_source = $('#lead_source').val();
     
     if(interest == 'Select')
     {
         alert('Please select your interest'); 
         return false;
     }
     
     if(interest == 'Distributor' && curr_business == 'Select your Current Bussiness' && currbusinessothers.length < 1)
     {
         alert('Please select your Current Bussiness');
         return false;
     }
     
     if(lead_source == 'Select')
     {
         alert('Please select lead_source'); 
         return false;
     }
     $('#lead_form').submit();
 });

function getInterest(interest)
{
    var others   = $('#currbusiness').val();
    var currbussiness = $('#currentbusinessothers').val(); 
    
    if(interest == 'Distributor'){
        $('#lblcurrbusiness').show();
        $('#currentbusiness').show();                              
     }
     else {
        $('#lblcurrbusiness').hide();
        $('#currentbusiness').hide();               
     }  
     if(interest == 'Distributor' && currbussiness == 'Others'){
         $('#currentbusinessothers').show();                
     }
     else {
             $('#currentbusinessothers').hide();  
       }
}

</script>