 <link rel="stylesheet" href="/css/jquery-ui.css">
<?php echo $form->create('shop'); ?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
            
        <div class="row">
            <div class="col-sm-6">
		<div class="appTitle">New Super Distributor</div>
                   <div>
		    <div class="field" style="padding-top:5px;">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="map_lat" class="compulsory">Lat</label></div>
                            <div class="fieldLabelSpace1">
                                <input tabindex="1" type="text" id="map_lat" name="data[SuperDistributor][map_lat]"  value="<?php if(isset($data))echo $data['SuperDistributor']['map_lat']; ?>"/>
                            </div>                     
                        </div>
                        <div class="fieldDetail">
                             <div class="fieldLabel1 leftFloat"><label for="map_long" class="compulsory">Long</label></div>
                             <div class="fieldLabelSpace1">
                                <input tabindex="2" type="text" id="map_long" name="data[SuperDistributor][map_long]" value="<?php if(isset($data))echo $data['SuperDistributor']['map_long']; ?>"/>
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
                                <input tabindex="4" type="text" id="username" name="data[SuperDistributor][name]"  value="<?php if(isset($data))echo $data['SuperDistributor']['name']; ?>"/>
                            </div>                     
                        </div>
                        <div class="fieldDetail">
                             <div class="fieldLabel1 leftFloat"><label for="company" class="compulsory">Company Name</label></div>
                             <div class="fieldLabelSpace1">
                                <input tabindex="5" type="text" id="company" name="data[SuperDistributor][company]" value="<?php if(isset($data))echo $data['SuperDistributor']['company']; ?>"/>
                             </div>                     
                        </div>
            	    </div>
            	   </div>
                 <div class="altRow">         	 
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="mobile" class="compulsory">Mobile</label></div>
                         <div class="fieldLabelSpace1">
                             <input tabindex="6" type="text" maxlength="10" id="mobile" name="data[SuperDistributor][mobile]" value ="<?php if(isset($data))echo $data['SuperDistributor']['mobile']; ?>"/>
                         </div>                     
                 	</div>            	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="email">E-mail</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="7" type="text" id="email" name="data[SuperDistributor][email]" value="<?php if(isset($data))echo $data['SuperDistributor']['email']; ?>"/>
                        &nbsp;&nbsp;<label for="dob" class="compulsory">DOB </label>
                         <input tabindex="8" type="text" name="data[SuperDistributor][dob]" id="data[SuperDistributor][dob]"  onmouseover="fnInitCalendar(this, 'data[SuperDistributor][dob]','close=true')" value="<?php if(isset($data))echo $data['SuperDistributor']['dob'];?>" />
                         </div>
                 	</div>
            	 </div>
            	 </div>
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail leftFloat" style="width:350px;">
                    	<div class="fieldLabel1 leftFloat"><label for="state" class="compulsory"> State </label></div>
                    	<div class="fieldLabelSpace1">
                        <input type="text" id="state" name="data[SuperDistributor][state]" value="<?php if(isset($data))echo $data['SuperDistributor']['state']; ?>"  readonly/>
                        </div>                    
                 	</div>            	 
                    <div class="fieldDetail">
                        <div class="fieldLabel1 leftFloat"><label for="city" class="compulsory">City</label></div>
                        <div class="fieldLabelSpace1">
                        <div id="cityDD">
                        <input type="text" id="city" name="data[SuperDistributor][city]" value="<?php if(isset($data))echo $data['SuperDistributor']['city']; ?>" readonly/>    
                        </div>
                        </div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
              	 <div class="field">           	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="address" class="compulsory">Company Address</label></div>
                         <div class="fieldLabelSpace1">
                            <textarea tabindex="10" id="address" name="data[SuperDistributor][address]" style="width:180px;height:55px;"><?php if(isset($data))echo $data['SuperDistributor']['address']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <div>
              	 <div class="field">
                    <div class="fieldDetail leftFloat">
                         <div class="fieldLabel1 leftFloat"><label for="pan" class="compulsory">PAN Number </label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="11" type="text" id="pan" name="data[SuperDistributor][pan_number]" value="<?php if(isset($data))echo $data['SuperDistributor']['pan_number']; ?>"/>
                         </div>                    
                 	</div>
            	 </div>
            	 </div>
            	 <div class="altRow">
            	 <div class="field">           	 
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat"><label for="slab" class="compulsory">Assign Slab</label></div>
                         <div class="fieldLabelSpace1">
                            <select tabindex="14" id="slab" name="data[SuperDistributor][slab_id]" >
                            <?php foreach($slabs as $slab) {?>
                                <option value="<?php echo $slab['Slab']['id'];?>" <?php if(isset($data) && $slab['Slab']['id'] == $data['SuperDistributor']['slab_id']) echo "selected";?>><?php echo $slab['Slab']['name']; ?></option>
                            <?php } ?>
                            </select>
                         </div>
                      </div>
            	 </div>
            	 </div>
                
                <div>
		    <div class="field" style="padding:10px 0px;">
                        <div class="fieldDetail leftFloat" style="width:350px;">
                            <div class="fieldLabel1 leftFloat"><label for="DistReference" class="compulsory">Select RM</label></div>
                            <div class="fieldLabelSpace1">
                                <input type="text" class="form-control" id="rm_name" name="data[SuperDistributor][rm_name]" placeholder="" value="<?php if(isset($data))echo $data['SuperDistributor']['rm_name']; ?>">
                            </div> 
                            <input type="hidden" id="rm_id" name="data[SuperDistributor][rm_id]" value="<?php if(isset($data))echo $data['SuperDistributor']['rm_id']; ?>">                    
                        </div>
                        <div class="field">
                          <div class="fieldDetail leftFloat" style="width:350px;margin-top: -10px;">
                              <div class="fieldLabel1 leftFloat"><label for="GSTNo" class="compulsory">GST No</label></div>
                              <div class="fieldLabelSpace1">
                                  <input tabindex="18" type="text" name="data[SuperDistributor][gst_no]" value="<?php if(isset($data))echo $data['SuperDistributor']['gst_no']; ?>"/>
                              </div>
                          </div>
                      </div>
            	    </div>
            	</div><br/>
                
                
                 </div>       
                
                
                 <div class="field"  style="padding-top:20px">               		
                    <div class="fieldDetail">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Create Super Distributor', array('id' => 'sub', 'tabindex'=>'21','url'=> array('controller'=>'shops', 'action'=>'createSuperDistributor'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
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
                    //document.getElementById('area').value=  JSONObject["area_name"]; 
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
        
</script>
 
 <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script>
 jQuery( function() {

    <?php
    $RM_Array = '[';
     foreach($allRM as $RM){
        $RM_Array .= '{value:'.$RM['r']['id'].',label:"'.$RM['r']['name'].'"},';
    }
    $RM_Array = rtrim($RM_Array,",");
    $RM_Array .= "]";
    ?>
    var RM_Array = <?php echo $RM_Array;?>;
    jQuery( "#rm_name" ).autocomplete({
      source: function(request, response) {
            var results = jQuery.ui.autocomplete.filter(RM_Array, request.term);

            response(results.slice(0, 20));
        },
        select: function (event, ui) {
             jQuery('#rm_name').val(ui.item.label); // display the selected text
             jQuery('#rm_id').val(ui.item.value); // save selected id to hidden input
             return false;
         },
    });
  } );
  </script>
  <script>jQuery.noConflict();</script>