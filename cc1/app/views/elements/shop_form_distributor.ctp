<?php echo $form->create('shop'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
            
        <div class="row">
            <div class="col-sm-6">
		<div class="appTitle">New Distributor</div>
                   <div>
		    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="map_lat" class="compulsory">Lat</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex="1" type="text" id="map_lat" name="data[Distributor][map_lat]"  value="<?php if(isset($data))echo $data['Distributor']['map_lat']; ?>"/>
                            </div>                     
                        </div>
                        <div class="fieldDetail">
                             <div class="fieldLabel1 leftFloat"><label for="map_long" class="compulsory">Long</label></div>
                             <div class="fieldLabelSpace1">
                                <input tabindex="2" type="text" id="map_long" name="data[Distributor][map_long]" value="<?php if(isset($data))echo $data['Distributor']['map_long']; ?>"/>
                                <input tabindex="3" type="button" value="Show Location" onclick="Initialize()">
                             </div> 
                             
                        </div>
                    </div>
            	   </div>
		   <div>
		    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="username" class="compulsory">Name</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex="4" type="text" id="username" name="data[Distributor][name]"  value="<?php if(isset($data))echo $data['Distributor']['name']; ?>"/>
                            </div>                     
                        </div>
                        <div class="fieldDetail">
                             <div class="fieldLabel1 leftFloat"><label for="company" class="compulsory">Company Name</label></div>
                             <div class="fieldLabelSpace1">
                                <input tabindex="5" type="text" id="company" name="data[Distributor][company]" value="<?php if(isset($data))echo $data['Distributor']['company']; ?>"/>
                             </div>                     
                        </div>
            	    </div>
            	   </div>
                 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Mobile</label></div>
                         <div class="fieldLabelSpace1">
                             <input tabindex="6" type="text" maxlength="10" id="mobile" name="data[Distributor][mobile]" value ="<?php if(isset($data))echo $data['Distributor']['mobile']; ?>"/>
                         </div>                     
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="7" type="text" id="email" name="data[Distributor][email]" value="<?php if(isset($data))echo $data['Distributor']['email']; ?>"/>
                        &nbsp;&nbsp;<label for="dob" class="compulsory">DOB </label>
                         <input tabindex="8" type="text" name="data[Distributor][dob]" id="data[Distributor][dob]"  onmouseover="fnInitCalendar(this, 'data[Distributor][dob]','close=true')" value="<?php if(isset($dob))echo $dob;?>" />
                         </div>
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state" class="compulsory"> State </label></div>
                    	<div class="fieldLabelSpace1">
                        <input type="text" id="state" name="data[Distributor][state]" value="<?php if(isset($data))echo $data['Distributor']['state']; ?>"  readonly/>
                        </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="city" class="compulsory">City</label></div>
                        <div class="fieldLabelSpace1">
                        <div id="cityDD">
                        <input type="text" id="city" name="data[Distributor][city]" value="<?php if(isset($data))echo $data['Distributor']['city']; ?>" readonly/>    
                        </div>
                        </div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="area" class="compulsory"> Area Range </label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="9" type="text" id="area" name="data[Distributor][area_range]" value="<?php if(isset($data))echo $data['Distributor']['area_range']; ?>"/>
                         </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address" class="compulsory">Company Address</label></div>
                         <div class="fieldLabelSpace1">
                            <textarea tabindex="10" id="address" name="data[Distributor][address]" style="width:180px;height:55px;"><?php if(isset($data))echo $data['Distributor']['address']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat">
                         <div class="fieldLabel1 leftFloat"><label for="pan" class="compulsory">PAN Number </label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="11" type="text" id="pan" name="data[Distributor][pan_number]" value="<?php if(isset($data))echo $data['Distributor']['pan_number']; ?>"/>
                         </div>                    
                 	</div>
                 	<!--div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="tds" class="compulsory">TDS Authorized</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="12" type="checkbox" id="tds" name="data[Distributor][tds_flag]" <?php if(isset($data['Distributor']['tds_flag']) && $data['Distributor']['tds_flag'] == 'on') echo "checked";?>>
                         </div>
                    </div-->
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="login" class="compulsory">SMS Login Details</label></div>
                         <div class="fieldLabelSpace1">
                            <input type="checkbox" tabindex="13" id="login" name="data[login]" <?php if(isset($data['login']) && $data['login'] == 'on') echo "checked"; else if(!isset($data['login'])) echo "checked";?>/>
                         </div>
                    </div>  
                    <?php if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){?>         	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab" class="compulsory">Assign Slab</label></div>
                         <div class="fieldLabelSpace1">
                            <select tabindex="14" id="slab" name="data[Distributor][slab_id]" >
                            <?php foreach($slabs as $slab) {?>
                                <option value="<?php echo $slab['Slab']['id'];?>" <?php if(isset($data) && $slab['Slab']['id'] == $data['Distributor']['slab_id']) echo "selected";?>><?php echo $slab['Slab']['name']; ?></option>
                            <?php } ?>
                            </select>
                         </div>
                      </div>
                      <?php } ?>
            	 </div>
            	 </div>
                <?php
if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                ?>
                <div>
		    <div class="field" style="padding:10px 0px;">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="DistReference" class="compulsory">Dist Reference</label></div>
                            <div class="fieldLabelSpace1">
                                <select tabindex="15" id="dist_reference_select" name="data[Distributor][dist_reference]" onchange="dist_RefSelectChange()">
                                        <option selected="selected" value="RM">RM</option>
                                        <option value="Online (App/Web/Social Media)">Online (App/Web/Social Media)</option>
                                        <option value="Customer Care">Customer Care</option>
                                        <option value="Manual Referral">Manual Referral</option>
                                        <option value="In/Out-bound Calling">In/Out-bound Calling</option>
                                </select>
                            </div>                     
                        </div>
                        <div class="fieldDetail" id="dist_reference_code_div" style="display:none">
                            <div class="fieldLabel1 leftFloat"><label for="DistReferenceCode" style="padding-left: 20px;">Refernce Code</label></div>
                             <div class="fieldLabelSpace1">
                                <input type="text" id="dist_reference_code" name="data[Distributor][dist_reference_code]" value=" "/>
                             </div>                     
                        </div>
            	    </div>
            	</div><br/>
                <?php
                }
                ?>
                
                <div class="altRow" style="padding-bottom: 10px;">
                    <?php if($this->Session->read('Auth.commission_type') == 2) { ?>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="DistReference" class="compulsory">Commission Type</label></div>
                            <div class="fieldLabelSpace1">
                                <label><input tabindex="16" type="radio" onclick="return commissionType(0);" id="ct1" class="ct" name="data[Distributor][commission_type]" value="0" checked="checked">Primary</label>
                                <label><input tabindex="17" type="radio" id="ct2" class="ct" name="data[Distributor][commission_type]" value="1">Tertiary</label>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;margin-top: -10px;">
                            <div class="fieldLabel1 leftFloat"><label for="GSTNo" class="compulsory">GST No</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex="18" type="text" name="data[Distributor][gst_no]" />
                            </div>
                        </div>
                    </div>
                    <br/>
                    <?php
                    if($_SESSION['Auth']['User']['group_id'] != SUPER_DISTRIBUTOR){
                    ?>
                 <div>  
                 <div class="field">
                     <div class="fieldDetail leftFloat" style="width:350px;">
                      <div class="fieldLabel1 leftFloat"><label for="distributor_type" class="compulsory">Distributor Type</label></div>
                       <div class="fieldLabelSpace1">
                         <select  id="dist_type" name="data[Distributor][dist_type]">
                           <option value="1">New</option>
                           <option value="0">Replacement</option>
                         </select>
                       </div>                     
                     </div>  
                      <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="lead_type" class="compulsory">Lead Source</label></div>
                        <div class="fieldLabelSpace1">
                        <select id="lead_type" name="data[Distributor][lead_type]">
                            <option value="1">Online</option>
                            <option value="0">Offline</option>
                        </select>
                        </div>                     
                      </div>                             
                 </div>
                 </div>       
                <?php }
                 if($this->Session->read('Auth.commission_type') == 2) { ?>
                <div>
                    <?php $as = $this->Session->read('Auth.active_services'); ?>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="Services">Services</label></div>
                            <div class="fieldLabelSpace1">
                                <select tabindex="19" id="demo" multiple="multiple" style="width:200px;">
                                    <?php foreach($services  as $service) { ?>
                                    <option onclick="updateServices(<?php echo $service['id']; ?>)" <?php if(in_array($service['id'], explode(',', $as))) { echo "selected"; } ?>><?php echo $service['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" id="active_services" name="data[Distributor][active_services]" value="<?php echo $as; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="commission_per">Commission %</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex="20" type="text" name="data[Distributor][margin]" />
                            </div>

                        </div>
                    </div>
                    <div class="clearLeft">&nbsp;</div>
                </div>
                <?php } ?>
                
                 <div class="field"  style="padding-top:20px">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Create Distributor', array('id' => 'sub', 'tabindex'=>'21','url'=> array('controller'=>'shops', 'action'=>'createDistributor'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <div class="field">    
                    <div class="fieldDetail">                         
                         <div class="inlineErr1">
                            <?php echo $this->Session->flash();?>
                         </div>   
                    </div>
            	 </div>	
                </div>
            <div class="col-sm-6">
                <input type="button" value="Show Address" onclick="ShowAddress()">
                <div id="googleMap" style="width:1000px;height:200px;"></div></div>    
            </div>
		</fieldset>          
<?php echo $form->end(); ?>
<script>
if($('map_lat'))
	$('map_lat').focus();
    
        function Initialize()
        {

            if(document.getElementById('map_lat').value == ""){
                var map_lat = 24.251494;  
                var map_long = 79.231538; 
            }else{
                var map_lat = document.getElementById('map_lat').value;  
                var map_long = document.getElementById('map_long').value; 
            }
           
          
          var mapProp = {
            center: new google.maps.LatLng(map_lat,map_long),
            zoom:5,
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };
          
          var latlng = new google.maps.LatLng(map_lat, map_long);
            
          var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
          var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title:'Click to zoom'
            });
            marker.setMap(map); 
            
          google.maps.event.addListener(map, 'click', function(event){
          marker.setPosition(event.latLng);
          var event_latlng = event.latLng;
            document.getElementById('map_lat').value = event_latlng.lat().toFixed(6);
            document.getElementById('map_long').value = event_latlng.lng().toFixed(6); 
          });
         
        }

        function loadScript()
        {
          var script = document.createElement("script");
          script.type = "text/javascript";
          script.src = "http://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>&callback=Initialize";
          document.body.appendChild(script);
        }

        window.onload = loadScript;
   
        function httpGet(theUrl){
            var xmlHttp = null;
            xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", theUrl, false );
            xmlHttp.send( null );
            return xmlHttp.responseText;
        }
        
        function ShowAddress(){
            var lat_lng = document.getElementById('map_lat').value+'/'+document.getElementById('map_long').value;
//            19.167911/72.840986
            new Ajax.Request("/apis/getAreaUsingLatLong/"+lat_lng,
            {
                method: 'GET',
                dataType: 'json',
                onFailure: function(data) {
                    console.log('Fail Data -- '+data.responseText);
                },
                onSuccess: function(data) {
                    var JSONObject = JSON.parse(data.responseText);
//                    console.log(JSONObject); // Dump all data of the Object in the console
                    document.getElementById('area').value=  JSONObject["area_name"]; 
                    document.getElementById('city').value=  JSONObject["city_name"];
                    document.getElementById('state').value= JSONObject["state_name"];
                }
            });
        }
         
        function dist_RefSelectChange(){    
                if(document.getElementById("dist_reference_select").value === 'Manual Referral') {
                    document.getElementById('dist_reference_code_div').style.display = "block";
                }else{
                    document.getElementById('dist_reference_code_div').style.display = "none";
                }
        }
        
        function commissionType(val) {
                var a = document.getElementById('active_services').value.split(',');
                var primary_services = JSON.parse('<?php echo json_encode($primary_services); ?>');
                
                for (var ps in primary_services) {
                        var index = a.indexOf(String(primary_services[ps])); if(index != -1) { a.splice(index,1); }
                }
                if(a.length != 0) {
                        alert('Services selected are only supported in TERTIARY commission type'); return false;
                }
        }
        
        function updateServices(val) {
                if(val > 10 && document.getElementsByClassName('ct')['ct1'].checked == true) {
                        alert('SHIFTING distributor to TERTIARY commission type, as this service is available only in TERTIARY');
                        document.getElementsByClassName('ct')['ct2'].checked = true;
                }
                document.getElementById("demo").onclick = function (e) {
                        var active_services = document.getElementById('active_services').value == '' || !e.ctrlKey ? [] : document.getElementById('active_services').value.split(",");
                        var in_object = active_services.indexOf(String(val));
                        if(in_object == -1){
                                active_services.push(val);
                                active_services.sort(function (a, b) { return a - b; });
                        } else {
                                active_services.splice(in_object, 1);
                        }
                        document.getElementById('active_services').value = active_services.join();
                }
        }
        
</script>