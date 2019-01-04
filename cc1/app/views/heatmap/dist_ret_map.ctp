<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Distributor Retailer Map</title>
 
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
        overflow: auto;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      
      
      .rider-panel {
                position: absolute;
                right: 0;
                top: 0;
                z-index: 10;
                background: rgba(255, 255, 255, 0.8);
                width: 28%;               
                padding: 1px 10px 0 10px;
                margin: 0;
            }
            
            table.table{
                height : 200px;
                display: block;
                overflow: auto;
                border: 1px solid;
            }      
            table th,tr,td{
                border: 1px solid black;
                align-content: center;
                
            }
    </style>
    

 <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
  </head>
  <body>
      
      <div id="map"></div>
    
    
        <div class="rider-panel">
            
            <div style="margin: 10px 0 0 10px; line-height: 2em;"><b><u>Filter</u> :-</b></div>
            <div id="state">
                <span><strong>Select State : </strong></span>
                <select style="border-radius: 5px; width: 150px; height: 30px;" >
                    <option value=''>All</option>
                    <?php foreach($states as $drstate) { ?>
                    <option value="<?php echo $drstate['locator_state']['id']; ?>" <?php if($drstate['locator_state']['id'] == $center[3]) { echo "selected"; }   ?> ><?php echo $drstate['locator_state']['name']; ?></option>
                    <?php } ?>   
                </select>
              
                
            </div>
            <div id="city">
            	<span><strong>Select City : </strong></span>
                <select style="border-radius: 5px; width: 150px; height: 30px;" >
                    <option value=''>All</option>
                    <?php foreach($cities as $drstate) { ?>
                    <option value="<?php echo $drstate['locator_city']['id']; ?>" <?php if($drstate['locator_city']['id'] == $center[4]) { echo "selected"; }   ?> ><?php echo $drstate['locator_city']['name']; ?></option>
                    <?php } ?>   
                </select>
            </div>
            <div id="area">
            	<span><strong>Select Area : </strong></span>
                <select style="border-radius: 5px; width: 150px; height: 30px;" >
                    <option value=''>All</option>
                    <?php foreach($areas as $drstate) { ?>
                    <option value="<?php echo $drstate['locator_area']['id']; ?>" <?php if($drstate['locator_area']['id'] == $center[5]) { echo "selected"; }   ?> ><?php echo $drstate['locator_area']['name']; ?></option>
                    <?php } ?>   
                </select>
            </div>
            <div id="pincode">
                <span><strong>Select Pincode : </strong></span>
                <input type="text" style="margin: 10px 0 0 10px;border-radius: 5px; width: 150px; height: 20px;" name = "pincode" id="pincode" 
                       value ="<?php echo !empty($pinvalue)?$pinvalue :" ";?>" >
                </input>
            </div>
            <br />
            <input type="button" id="submit" value="Search" style="border-radius: 3px; border: 1px solid gray; margin-left: 50px;" />
            <input type="button" id="filter" value="Select City" style="border-radius: 2px; border: 1px solid gray;" />
            <br /><br />
            
            
                
            <?php    if($type == 'retailer') {   ?>
            
            <div id ="distdata">
                <span><strong> Distributor Data <?php if(!empty($dist_id)) echo " - " . $dist_name; ?></strong> </span>                
                <br><br>
            <table class="table">
                   <thead>
                       <tr>
                       		<?php if(!empty($dist_id)) { ?>
                           <th>Pincode</th>
                           <th>City</th>
                           <th>State</th>
                           <?php } else { ?>
                           <th>Distributor</th>
                           <?php } ?>
                           <th>No of Retailers</th>
                       </tr>
                   </thead> 
                   <?php foreach ($dist_det as $key=>$val) {
                   			if(!empty($dist_id)) {
                   			   $str = "<a href='/heatmap/distRetMap/0/0/0/".$key."'>".$key ."</a>";
                            }
                   			else {
                   			   $str = "<a href='/heatmap/distRetMap/0/0/0/0/".$key."'>".$val['name'] ."</a>";
                   			}
                   ?>
                   <tbody>
                       <tr>
                           <td> <?php echo $str; ?> </td>
                           <?php if(!empty($dist_id)) { ?>
                           <td> <?php echo $val['city']; ?> </td>
                           <td> <?php echo $val['state']; ?> </td>
                           <?php } ?>     
                           <td> <?php echo $val['rets']; ?> </td>     
                       </tr>
                      </tbody>
                   <?php } ?>
               </table>
            </div>
            <?php } else if($type == 'overall') { ?>
            
             <div id ="distdata">
                <span><strong> Overall Retailer Data</strong> </span>                
                <br><br>
            <table class="table">
                   <thead>
                       <tr>
                           <th>State</th>
                           <th>City</th>
                           <th>No of Distributors</th>
                           <th>No of Retailers</th>
                       </tr>
                   </thead> 
                   <?php foreach ($dist_det as $id => $dt) {
                            
                   ?>
                   <tbody>
                       <tr>
                       	   <td> <a href="/heatmap/distRetMap/<?php echo $dt['state_id']; ?>"><?php echo $dt['state']; ?> </a></td>
                           <td> <a href="/heatmap/distRetMap/<?php echo $dt['state_id']; ?>/<?php echo $dt['city_id']; ?>"><?php echo $dt['city']; ?></a> </td>     
                           <td> <?php echo $dt['dists']; ?> </td>     
                           <td> <?php echo $dt['rets']; ?> </td>     
                       </tr>
                      </tbody>
                   <?php } ?>
               </table>
            </div>
            <?php } else if($type == 'citywise') { ?>
            
             <div id ="distdata">
                <span><strong> Overall Retailer Data</strong> </span>                
                <br><br>
            <table class="table">
                   <thead>
                       <tr>
                           <th>Pincode</th>
                           <th>No of Retailers</th>
                           <th>No of Distributors</th>
                           
                       </tr>
                   </thead> 
                   <?php foreach ($area_details as $id => $dt) {
                            
                   ?>
                   <tbody>
                       <tr>
                       	   <td> <a href="/heatmap/distRetMap/0/0/0/<?php echo $dt['pincode']; ?>"><?php echo $dt['pincode']; ?></a> </td>     
                           <td> <?php echo $dt['rets']; ?> </td>     
                           <td> <?php echo $dt['dists']; ?> </td>     
                       </tr>
                      </tbody>
                   <?php } ?>
               </table>
               
               <br><br>
            <table class="table">
                   <thead>
                       <tr>
                           <th>Distributor</th>
                           <th>No of Retailers</th>
                           
                       </tr>
                   </thead> 
                   <?php 
                   foreach ($dist_det as $id => $dt) {
                   			$str = "";
                   			$name = "";
                            foreach($dt as $pincode=>$rets){
                            	$str .= "<a href='/heatmap/distRetMap/0/0/0/".$pincode."'>".$pincode . "(".$rets['rets'].")</a>, ";
                            	$name = $rets['name'];
                            }
                   ?>
                   <tbody>
                       <tr>
                           <td> <a href="/heatmap/distRetMap/0/0/0/0/<?php echo $id; ?>"><?php echo $name; ?></a> </td>     
                           <td> <?php echo $str; ?> </td>
                       </tr>
                      </tbody>
                   <?php } ?>
               </table>
            </div>
            <?php } ?>
        </div>    

    <script>



          var marker;
          var markers = [
        <?php foreach($ret_det as $ret) {    ?>
            {
                "title" : <?php echo json_encode(preg_replace('/[[:^print:]]/', '', $ret['title'])); ?>,                  
                "lat"   : <?php echo json_encode($ret['lat']);?>,
                "long"  : <?php echo json_encode($ret['long']);?>, 
                "description" : <?php echo json_encode(preg_replace('/[[:^print:]]/', '', $ret['desc']));?>,
                "type"  : "<?php echo $ret['type'];?>"
            },
        <?php } ?>
          ];
  
     
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
        zoom: <?php echo $center[0] != '23.3302095' ? $center[2] : '5'; ?>,
        center: {<?php echo 'lat: ' . $center[0] . ', lng: ' . $center[1]; ?>},       
        });
        
        var infoWindow = new google.maps.InfoWindow();
        var latlngbounds = new google.maps.LatLngBounds();
        var i = 0;
        var interval = setInterval(function () {
        var data = markers[i];
        if(data === undefined) return;
        var myLatlng = new google.maps.LatLng(data.lat,data.long);
        var icon = "red";
        switch (data.type) {
            case "top_a": 
            	icon = "green";
            	break;
            case "top_b": 
            	icon = "blue";
            	break;   
        }
        icon = "http://maps.google.com/mapfiles/ms/icons/" + icon + ".png";
        
        marker = new google.maps.Marker({
          position : myLatlng,
          map: map,
          title : data.title,
          draggable: true,
         // animation: google.maps.Animation.DROP,
          icon: new google.maps.MarkerImage(icon) });
      
        
        (function (marker,data){
         google.maps.event.addListener(marker, "click", function (e) {
                infoWindow.setContent(data.description);
                infoWindow.open(map, marker);
            });
        })(marker, data);
        latlngbounds.extend(marker.position);
        i++;
        if (i === markers.length) {
            clearInterval(interval);
            var bounds = new google.maps.LatLngBounds();
//            map.setCenter(latlngbounds.getCenter());
          //  map.fitBounds(latlngbounds);
        }
        
    },0);
    
           

        function json_latlng(arr) {
            var coordinates = [];
            for (var i = 0; i < arr.length; i++) {
                 var lat = arr[i].lat;
                 var lng = arr[i].lng;
                    coordinates.push(new google.maps.LatLng(lat, lng));
               }
                return coordinates;
            }
    //marker.addListener('click', toggleBounce);
    
    }    
        

      function toggleBounce() {
        if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }
      }
   
                    
            $('#filter').click(function() {

                search = String($('#filter').val().substring(7));

                if(search === 'City') {
                    data = {search: search, location: $('#state select').val()};
                    tableName = "locator_city";
                } else{
                    data = {search: search, location: $('#city select').val()};
                    tableName = "locator_area";
                } 
                $.post('/heatmap/filterCityArea', data, function(e) {

                
                    $('#' + search.toLowerCase()).html('');
                    str = "<br><span><strong>Select " + (search == 'City' ? search + '&nbsp;' : search) + " : </strong></span>";
                    str += "<select style='border-radius: 5px; width: 150px; height: 30px;'>";
                    str += "<option >All</option>";
                    
                    locate = (search == 'City' ? '<?php echo $center[4] ?>' : '<?php echo $center[5] ?>');
                    for (var x in e) {
                        selection = (e[x][tableName]['id'] == locate) ? "selected" : "";
                        str += "<option value='" + e[x][tableName]['id'] + "' " + selection + ">" + e[x][tableName]['name'] + "</option>";
                    }
                    str += "</select>";
                    
                    $('#' + search.toLowerCase()).html(str);
//                  
                    if(search == 'City') {
                        $('#filter').val('Select Area');
                    }
                    else {
                        $('#filter').hide();
                    }
                    if('<?php echo $center[5] ?>'  != 'All') {
                        $('#filter').click();
                    }
                }, 'json');
            });

            $('#submit').click(function() {

                var state   = typeof $('#state select').val() == 'undefined' ? '0' : $('#state select').val();
                var city    = typeof $('#city select').val() == 'undefined' ? '0' : $('#city select').val();
                var area    = typeof $('#area select').val() == 'undefined' ? '0' : $('#area select').val();
                var pincode = $('#pincode input').val();
	                pincode = pincode.trim();
	                
	                if(state.trim() == '')state = 0;
	                if(city.trim() == '')city = 0;
	                if(area.trim() == '')area = 0;
	                if(pincode == '')pincode = 0;
                window.location = '/heatmap/distRetMap/' + state + '/' + city + '/' + area + '/' + pincode;
            });
                
            /*$(function() {
                
                if('<?php echo $center[4] ?>' != 'All') {
                    $('#filter').click();
                }
            });*/
        </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLjOsyEYa-A2n_M_BMviNoh5ozspe1le4&callback=initMap">
    </script>
  </body>
</html>