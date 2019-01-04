
<script>
function addDocuments(){
    alert("Hello");
  // $("#documents").append('<label >Document</label><input type="file" name="documents" accept="image/*" capture /><br/>');
   return false;
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>&v=3.exp&sensor=false"></script>
<style>
	.ui-content.ui-scrollview-clip > .ui-listview.ui-scrollview-view {margin: 0;}
    #locationDiv{
      width: 100%;
      font: 1.8em;
    }
    #map-canvas{ 
        height:200px;
    }
    form-group{
    	padding-top: 10px;
    }
    .ui-field-contain, .ui-controlgroup-label {
vertical-align:top;
display:inline-block;
width:80%;
margin:0 2% 0 0
}

label.ui-input-text {
	font-size:16px;
	line-height:1.4;
	display:block;
	font-weight:normal;
	margin:0 0 .3em
}
input.ui-input-text {
	background-image:none;
	padding:.4em;
	line-height:1.4;
	font-size:17px;
	display:block;
	width:97%; color:#000;
	-webkit-appearance:none
}

.ui-body-d .ui-content {
    padding: 0;
}

.ui-body-d .ui-content {
    background: none repeat scroll 0 50% transparent;
    padding: 10px;
}

.ui-corner-all {
	-webkit-background-clip:padding-box;
	-moz-background-clip:padding;
	background-clip:padding-box
}

.ui-br {
	padding-bottom:10px;
}

.ui-mobile {
	height:100%
}

.mainClass { 
overflow-x: hidden; overflow-y: hidden; background:#fff;
}
    
</style>
<script>
mapLocation = {lat:0,lng:0};
var geocoder;
var map;
var mobile = "<?php echo $mobile ?>"; 
if(mobile==''){
document.observe("dom:loaded", function() {
   
    
    function geoSuccess(position){
    	var lat = position.coords.latitude ;
        var lng = position.coords.longitude ;
        
         getAreaByLatLng(lat,lng);
          var latlng = new google.maps.LatLng(lat, lng);
          var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeControl: false,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };
           map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
          var marker = new google.maps.Marker({
              position: latlng, 
              map: map, 
              title:"You are here! (at least within a "+position.coords.accuracy+" meter radius)"
          });
           //alert("MyMap2");
          google.maps.event.addListener(map, 'click', function(event) {
          	  mapLocation.lat = event.latLng.k;
                  mapLocation.lng = event.latLng.B;
              
              marker.setPosition(new google.maps.LatLng(event.latLng.k, event.latLng.B));
              getAreaByLatLng(mapLocation.lat , mapLocation.lng);
              
          });
           //alert("MyMap3");
        //--------------------------------
    }
    
    function geoError(msg){
       
        google.maps.event.addDomListener(window, 'load', initialize);
    }
     navigator.geolocation.getCurrentPosition(geoSuccess,geoError);
    
     /*$('#save_location').on('click', function (event) {
        event.preventDefault();
        //var ret =  getAreaByLatLng(mapLocation.lat , mapLocation.lng); 
        
        if($("#mobile").val().trim()==""){
            alert("Mobile can not be empty .");
            return;
        }else if($("#addLine").val().trim()==""){
            alert("Address can not be empty .");
            return;
        }else if($("#area").val().trim()==""){
            alert("Area can not be empty .");
            return;
            
        }else if($("#city").val().trim()==""){
            alert("City can not be empty .");
            return;
            
        }else if($("#state").val().trim()==""){
            alert("State can not be empty .");
            return;
            
        }
       
        
        $.ajax({
                url: PayOne.config.urls.api,
                type: "POST",
                data: {
                    method:"updateRetailerAddress",
                    mobile:$("#mobile").val(),
                    address:$("#addLine").val(),
                    area:$("#area").val(),
                    city:$("#city").val(),
                    state:$("#state").val(),
                    pincode:$("#pincode").val(),
                    longitude:mapLocation.lng,
                    latiitude:mapLocation.lat                   
                },
                dataType: 'jsonp',
                jsonp: 'root',
                timeout: 50000,
                success: function(data, status){ 
                    alert("fkkf");
                    $('#mobBillPaymentSubmit').button('reset');
                    $.each(data, function(i,item){				
                        if(item.status == 'failure'){                       
                            PayOne.core.failChk(item.code,item.description,true);

                        }else{
                            if(item.status == 'success'){
                              
                                PayOne.core.cookie.set("street", item.description.address, 1);
                                PayOne.core.cookie.set("city", item.description.city, 1);
                                PayOne.core.cookie.set("pincode", item.description.pincode, 1);
                                PayOne.core.cookie.set("state", item.description.state, 1);
                                PayOne.core.cookie.set("area", item.description.area, 1);
                                PayOne.core.cookie.set("area_id", item.description.area_id, 1);
                                PayOne.core.cookie.set("lat",item.description.latitude, 1);
                                PayOne.core.cookie.set("lng",item.description.longitude, 1);
                                mapLocation.lat = item.description.latitude;
                                mapLocation.lng = item.description.longitude;
                                alert("Address updated successfully .");
                                                                
                            }
                        }
                    });
                },
                error: function(){
                    $('#mobBillPaymentSubmit').button('reset');
                }
            });
     });*/
     
});
}

google.maps.event.addDomListener(window, 'load', mapshow);

function mapshow(lat,lng){
        
          var lat = $("latitude").value;
          var lng = $("longitude").value;
         var latlng = new google.maps.LatLng(lat,lng);
          var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeControl: false,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };
           map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
          var marker = new google.maps.Marker({
              position: latlng, 
              map: map, 
              title:"You are here!"
          });
           //alert("MyMap2");
          google.maps.event.addListener(map, 'click', function(event) {
          	  mapLocation.lat = event.latLng.k;
                  mapLocation.lng = event.latLng.B;
              
              marker.setPosition(new google.maps.LatLng(event.latLng.k, event.latLng.B));
              getAreaByLatLng(mapLocation.lat , mapLocation.lng);
              
          });
           
    }

 function getAreaByLatLng(lat,lng){
    
 			 $('lat').value = lat;
 			 $('lng').value = lng;
             new Ajax.Request("http://maps.googleapis.com/maps/api/geocode/json?key=<?php echo GOOGLE_API_KEY;?>&latlng="+lat+","+lng+"", {
                  method:'GET',
                  contentType:"application/x-www-form-urlencoded",
                  onCreate: function(response) { 
                        var t = response.transport; 
                        t.setRequestHeader = t.setRequestHeader.wrap(function(original, k, v) { 
                            if (/^(accept|accept-language|content-language)$/i.test(k)) 
                                return original(k, v); 
                            if (/^content-type$/i.test(k) && 
                                /^(application\/x-www-form-urlencoded|multipart\/form-data|text\/plain)(;.+)?$/i.test(v)) 
                                return original(k, v); 
                            return; 
                        }); 
                    } ,
                  onSuccess: function(transport) {
                       var data = transport.responseText ;//|| "no response text";
                       data = JSON.parse(data);
                       var ret = {
                            "area_name":"",
                            "street_number":"",
                            "route" : "",
                            "city_name": "",
                            "state_name":"",
                            "country_name":"",
                            "pincode":""
                        }
                        
                        data.results[0].address_components.each(function(arr) {
  
                            if(arr.types[0] == "sublocality_level_1"){
                                ret.area_name = arr.long_name;
                            }else if(arr.types[0] == "locality"){
                                ret.city_name  = arr.long_name;

                            }else if(arr.types[0] == "administrative_area_level_1"){
                                ret.state_name = arr.long_name;
                            }
                            else if(arr.types[0] == "administrative_area_level_2"){
                                ret.extra = arr.long_name;
                            }
                            else if(arr.types[0] == "country"){
                                ret.country_name  = arr.long_name;

                            }else if(arr.types[0] == "street_number"){
                                ret.street_number  = arr.long_name;

                            }else if(arr.types[0] == "route"){
                                ret.route  = arr.long_name;

                            }else if(arr.types[0] == "postal_code"){
                                ret.pincode  = arr.long_name;

                            }

                        });

                        if(ret.area_name == ""){
                            ret.area_name = ret.city_name;
                            if(ret.extra!="")
                                ret.city_name = ret.extra;
                        }

                        ret.formatted_address = data.results[0].formatted_address;
                        ret.lat  = data.results[0].geometry.location.lat;
                        ret.lng  = data.results[0].geometry.location.lng;


                        ret.geoURL  = "https://maps.googleapis.com/maps/api/geocode/json?key=<?php echo GOOGLE_API_KEY;?>&latlng="+lat+","+lng+"&sensor=true";

                        var addLine = "",c="";
                        if(ret.street_number != "" && ret.route){
                            c=",";
                        }
                        addLine = ret.street_number +c+ret.route;
                        $("latitude").value = ret.lat;
                        $("longitude").value =  ret.lng;             
                        return ret;
                  },
                  onFailure: function() { 
                      alert('Something went wrong...'); 
                  }
             });
             
            

            
            }
function getaddress() {
  //geocoder = new google.maps.Geocoder();
  //var add  = document.getElementById('address').value;
 // var city  = document.getElementById('city').value;
 // var state  = document.getElementById('state').value;
  //var area = document.getElementById('area').value;
  //var address = add+','+area+','+city+','+state;

    var lat = $("latitude").value;
    var lng = $("longitude").value;
   var ret = {
                "area_name":"",
                "street_number":"",
                "route" : "",
                "city_name": "",
                "state_name":"",
                "country_name":"",
                "pincode":""
             }

 new Ajax.Request("http://maps.googleapis.com/maps/api/geocode/json?key=<?php echo GOOGLE_API_KEY;?>&latlng="+lat+","+lng+"", {
                  method:'GET',
                  contentType:"application/x-www-form-urlencoded",
                  onCreate: function(response) { 
                        var t = response.transport; 
                        t.setRequestHeader = t.setRequestHeader.wrap(function(original, k, v) { 
                            if (/^(accept|accept-language|content-language)$/i.test(k)) 
                                return original(k, v); 
                            if (/^content-type$/i.test(k) && 
                                /^(application\/x-www-form-urlencoded|multipart\/form-data|text\/plain)(;.+)?$/i.test(v)) 
                                return original(k, v); 
                            return; 
                        }); 
                    } ,
                  onSuccess: function(transport) {
                       var data = transport.responseText ;//|| "no response text";
                       data = JSON.parse(data);
                       var ret = {
                            "area_name":"",
                            "street_number":"",
                            "route" : "",
                            "city_name": "",
                            "state_name":"",
                            "country_name":"",
                            "pincode":""
                        }
                        
                        data.results[0].address_components.each(function(arr) {
  
                            if(arr.types[0] == "sublocality_level_1"){
                                ret.area_name = arr.long_name;
                            }else if(arr.types[0] == "locality"){
                                ret.city_name  = arr.long_name;

                            }else if(arr.types[0] == "administrative_area_level_1"){
                                ret.state_name = arr.long_name;
                            }
                            else if(arr.types[0] == "administrative_area_level_2"){
                                ret.extra = arr.long_name;
                            }
                            else if(arr.types[0] == "country"){
                                ret.country_name  = arr.long_name;

                            }else if(arr.types[0] == "street_number"){
                                ret.street_number  = arr.long_name;

                            }else if(arr.types[0] == "route"){
                                ret.route  = arr.long_name;

                            }else if(arr.types[0] == "postal_code"){
                                ret.pincode  = arr.long_name;

                            }

                        });

                        if(ret.area_name == ""){
                            ret.area_name = ret.city_name;
                            if(ret.extra!="")
                                ret.city_name = ret.extra;
                        }

                        ret.formatted_address = data.results[0].formatted_address;
                        ret.lat  = data.results[0].geometry.location.lat;
                        ret.lng  = data.results[0].geometry.location.lng;


                        ret.geoURL  = "https://maps.googleapis.com/maps/api/geocode/json?key=<?php echo GOOGLE_API_KEY;?>&latlng="+lat+","+lng+"&sensor=true";

                        var addLine = "",c="";
                        if(ret.street_number != "" && ret.route){
                            c=",";
                        }
                        addLine = ret.street_number +c+ret.route;
                       
                        $("address").value = addLine;
                        $("area").value = ret.area_name;
                        $("city").value = ret.city_name;
                        $("state").value = ret.state_name;
                        $("pincode").value = ret.pincode;
                       // $("latitude").value = ret.lat;
                        //$("longitude").value =  ret.lng; 
                        mapshow(lat,lng);            
                        return ret;
                  },
                  onFailure: function() { 
                      alert('Something went wrong...'); 
                  }
             });
             
}

function getolddata()
{
    $("address").value = "<?php echo $address; ?>";
    $("area").value = "<?php echo $area; ?>";
    $("city").value = "<?php echo $city; ?>";
    $("state").value = "<?php echo $state; ?>";
    $("pincode").value = "<?php echo $pincode; ?>";
    $("latitude").value = "<?php echo $latitude; ?>";
    $("longitude").value =  "<?php echo $longitude; ?>";
    mapshow("<?php echo $latitude; ?>","<?php echo $longitude; ?>");
}

function showloader()
{
    $("upload").hide();
    $("loader").show();
        
}
function addmore()
{
       var html = ''
       html+=" <div class='ui-field-contain ui-body ui-br'><input type='file' name ='image[]'></div>";
       $("image").insert(html);
     
}
function showimage()
{
     $("imageshow").toggle();
}
 function showdocumentID()
{
     $("documentID").toggle();
}
 function showdocumentAddress()
{
     $("documentAddress").toggle();
}
function deleteimg(url){

new Ajax.Request("/shops/retailer/",
{
    method: 'POST',
    parameters: {"imgurl":url},
    onFailure: function(transport) {
        vJSONResp = transport.responseText;
        var JSON = eval( "(" + vJSONResp + ")" );
        updateStatus(JSON.code + ": " + JSON.message);
    },
    onSuccess: function(transport) {
        if (200 == transport.status) {
            vJSONResp = transport.responseText;
            vJSONResp = JSON.parse(vJSONResp);
           if(vJSONResp.success=="1"){
                var str = $(url).remove();
                alert(vJSONResp.filename+" Deleted Successfully");
				location.reload();
             }

        } else {
            log.value += "\n" + transport.status;
        }
     }
});


}


    </script>


<div id="locationDiv" class="ui-content ui-scrollview-clip mainClass">
    
<div id="map-canvas" ></div>
                       <form role="form" onsubmit="javascript:showloader();" id="updateform" action="/shops/retailer" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="update" value="true">
                        <!--<input type="hidden" name ="latitude" id="latitude" value="<?php echo $latitude; ?>">
                        <input type="hidden" name="longitude" id="longitude" value="<?php echo $longitude; ?>">!-->
                          <div class="ui-field-contain ui-body ui-br">
        					<label for="mobile" class="ui-input-text">Retailer Mobile</label>
        					<input type="text" data-type="number" name="mobile" id="mobile" value="<?php echo $mobile; ?>" placeholder="Enter 10 digit Mobile Number" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset">
      					  	<input type="hidden" id="lat" name="lat" value="0"/>
                            <input type="hidden" id="lng" name="lng" value="0"/>
                            <input type="hidden" name="retailer_id"  value="<?php echo $retailer_id ?>">
                            
      					  </div>
                          <div class="ui-field-contain ui-body ui-br">
        					<label for="mobile" class="ui-input-text">Distributor Mobile</label>
        					<input type="text" data-type="number" name="dist_mobile" id="dist_mobile" value="<?php echo $dist_mobile; ?>" placeholder="Enter 10 digit Mobile Number" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset">
      					  </div>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="shop" class="ui-input-text">Shop Name</label>
                            <input type="text" id="shop" name="shop" value="<?php echo $shop; ?>" placeholder=" " class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset">
                          </div>
                           <div class="ui-field-contain ui-body ui-br">
                            <label for="address" class="ui-input-text">Latitude</label> 
                            <input type="text" name ="latitude" id="latitude" value="<?php echo $latitude; ?>" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset"  placeholder=" ">
                           
                          </div>
                           <div class="ui-field-contain ui-body ui-br">
                            <label for="address" class="ui-input-text">Longitude </label>
                            <input type="text" name="longitude" id="longitude" value="<?php echo $longitude; ?>" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset"  placeholder=" ">
                           
                          </div>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="address" class="ui-input-text">Enter Address  <input type="button" value="Update Location" onclick="getaddress();" > <input type="button" value="Click to get Old data" onclick="getolddata();" ></label> 
                            <input type="text" value="<?php echo $address; ?>" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset" id="address" name="address" placeholder=" ">
                           
                          </div>
                          
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="area" class="ui-input-text">Enter Area</label>
                            <input type="text" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset" value="<?php echo $area; ?>" id="area" name="area" placeholder=" ">
                          </div>
                         
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="area" class="ui-input-text">Enter City</label>
                            <input type="text" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset" value="<?php echo $city; ?>" id="city" name="city" placeholder=" ">
                          </div>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="state" class="ui-input-text">Enter State</label>
                            <input type="text" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset" value="<?php echo $state; ?>" id="state" name="state" placeholder=" ">
                          </div>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="pincode" class="ui-input-text">Enter Pincode</label>
                            <input type="text" class="ui-input-text ui-body-d ui-corner-all ui-shadow-inset" value="<?php echo $pincode; ?>" id="pincode" name="pincode" placeholder=" ">
                          </div>
                          
                          <?php 
                          	$shop_types = $objShop->business_natureTypes();
                          	$location_types = $objShop->location_typeTypes();
                          	$struct_types = $objShop->structureTypes();
                            
                          ?>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="shop_type" class="ui-input-text">Shop Type</label>
                            <select id="shop_type" name="shop_type">
                            <?php foreach($shop_types as $key=>$shop_type) {
                            $sel = '';
						   if($shoptype == $key)
                                {
                                    $sel = 'selected=selected';							
                                }
                            	echo "<option ".$sel." value='".$key."'>$shop_type</option>";
                             } ?>
                        		
                    		</select>
                            
                          </div>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="loc_type" class="ui-input-text">Location Type</label>
                            <select id="loc_type" name="loc_type">
                        		<?php foreach($location_types as $key=>$loc_type) {
                                 $sel = '';	
						       if($loctype == $key)
                                {
                                    $sel = 'selected';							
                                }
                            	echo "<option ".$sel." value='".$key."'>$loc_type</option>";
                             } ?>
                    		</select>
                          </div>
<!--                           <div class="ui-field-contain ui-body ui-br"> -->
<!--                             <label for="struct_type" class="ui-input-text">Shop Structure</label> -->
<!--                             <select id="struct_type" name="struct_type"> -->
                        		<?php //foreach($struct_types as $key=>$stuc_type) {
//                                     $sel = '';
//                                     if($structtype==$key){
//                                       $sel = 'selected=selected';	
//                                     }
//                             	echo "<option  ".$sel." value='".$key."'>$stuc_type</option>";
//                              } ?>
<!--                     		</select> -->
<!--                           </div> -->
                          <div id="image">
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="newPinContirm" class="ui-input-text">Shop Photo</label>
                            <input type="file" name="shop[]" accept="image/*" capture="camera"> 
                            <input type="button" onclick="addmore();" value="Addmore">
                             <?php if(count($image)>0){?><a href="javascript:void(0)" onclick="showimage();" >Click to get Attachement</a><?php } ?>
                          </div>
                           </div>
                            <div id ="imageshow"  style="display:none;">
                           <?php 
                               $i=0;
                               $str = '';
                              foreach($image as $key => $value){
                                      foreach($value as $k => $v){
                                    echo $str="<div class='ui-field-contain ui-body ui-br' id=".$v."><a href='".$v."'  target='_blank'>".$v."</a>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\"><img src=\"/img/error.png\" onclick=\"deleteimg('".$v."')\" height=\"25px;\" width=\"25px;\"  alt=\"click to delete\" title=\"click to delete\"></a></div>";
                                       $i++;
                                       }
                                   }
                           ?>
                           </div>
                         <div>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="newPinContirm" class="ui-input-text">ID Proof</label>
                            <input type="file" name="idProof[]" accept="image/*" capture="camera"> 
                           <?php if(count($documentID)>0){ ?><a href="javascript:void(0)" onclick="showdocumentID();">Click to get Attachement</a><?php } ?>
                          </div>
                          </div>
                          <div id ="documentID" style="display:none;">
                            <?php 
                               $i=0;
                               $str = '';
                              foreach($documentID as $key => $value){
                                      foreach($value as $k => $v){
                                    echo $str="<div class='ui-field-contain ui-body ui-br' id=".$v."><a href='".$v."'  target='_blank'>".$v."</a>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\"><img src=\"/img/error.png\" onclick=\"deleteimg('".$v."')\" height=\"25px;\" width=\"25px;\"  alt=\"click to delete\" title=\"click to delete\"></a></div>";
                                       $i++;
                                       }
                                   }
                           ?>
                         </div>
                          <div>
                          <div class="ui-field-contain ui-body ui-br">
                            <label for="newPinContirm" class="ui-input-text">Address Proof</label>
                            <input type="file" name="addressProof[]" accept="image/*" capture="camera"> 
                            <?php if(count($documentAddress)>0){ ?><a href="javascript:void(0)" onclick="showdocumentAddress();">Click to get Attachement</a><?php } ?>
                          </div>
                           </div>
                            <div id ="documentAddress" style="display:none;">
                            <?php 
                               $i=0;
                               $str = '';
                              foreach($documentAddress as $key => $value){
                                      foreach($value as $k => $v){
                                    echo $str="<div class='ui-field-contain ui-body ui-br' id=".$v."><a href='".$v."'  target='_blank'>".$v."</a>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\"><img src=\"/img/error.png\" onclick=\"deleteimg('".$v."')\" height=\"25px;\" width=\"25px;\"  alt=\"click to delete\" title=\"click to delete\"></a></div>";
                                       $i++;
                                       }
                                   }
                           ?>
                         </div>
                          <div class="form-inline">
                            <input type="submit" name="upload" id="upload" value="Upload" />
                             <label><img id="loader" style="display:none;" src="/img/loading.gif"/></label>
                          </div>
                        </form> 

    
    
</div>