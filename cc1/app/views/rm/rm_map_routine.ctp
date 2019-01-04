<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/boot/css/font-awesome.min.css">
<link rel="stylesheet" href="/boot/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/boot/css/fixedHeader.dataTables.min.css">

<style>
    .tab {
        overflow: hidden;
        border: 1px solid #428bca;
        background-color: #f1f1f1;
        height: 40px;
        border-radius: 10px;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
        line-height: 0.8em;
        color: gray;
    }

    .tab button:hover {
        background-color: #428bca;
        color: #fff;
    }

    .tab button.active {
        background-color: #fff;
        color: #428bca;
        font-weight: 600;
    }
    
  html, body, #map {height: 100%; min-height: 386px; margin: 0px; xpadding: 0px; xpadding-top: 30px; } #panel {position: relative; top: 5px; left: 50%; margin-left: -180px; z-index: 5; background-color: #fff; padding: 5px; border: 1px solid #999; } #directions-panel {height: auto; float: right; width: 100%; overflow: auto; background: #fff; } #map {xmargin-right: 400px; } #control {background: #fff; padding: 5px; font-size: 14px; font-family: Arial; border: 1px solid #ccc; box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4); display: none; }.xgmnoprint{top: 54px!important;} @media print {#map {height: 500px; margin: 0; } #directions-panel {float: none; width: auto; }  } 
</style>
<div class="tab">

    <ul class="nav nav-tabs">
        <li><button class="tablinks" onclick="window.location='/rm/rmAttendance'">Attendance</button></li>
        <li><button class="tablinks" onclick="window.location='/rm/rmTask'">Task</button></li>
        </li>
        <li><button class="tablinks" onclick="window.location='/rm/rmRoutine'">Routine</button></li>
        </li>
    </ul>

</div><br/>

<form class="form-inline" method="get" action="/rm/rmMapRoutine/">
<?php echo $this->Session->flash(); ?>
  <div class="form-group col-md-5">
    <label for="report_date">Halt time:</label>
    <!--select class="form-control" id="halt_minute" name="halt_minute">
        <option value="">--Halt time--</option>
        <option <?php echo ($halt_minute=="00:00:00")?"selected":"";?> value="00:00:00">All</option>
        <option <?php echo ($halt_minute=="00:05:00")?"selected":"";?> value="00:05:00">5</option>
        <option <?php echo ($halt_minute=="00:10:00")?"selected":"";?> value="00:10:00">10</option>
        <option <?php echo ($halt_minute=="00:15:00")?"selected":"";?> value="00:15:00">15</option>
        <option <?php echo ($halt_minute=="00:30:00")?"selected":"";?> value="00:30:00">30</option>
        <option <?php echo ($halt_minute=="01:00:00")?"selected":"";?> value="01:00:00">More than 1 hr</option>
    </select-->
    <label class="radio-inline">
      <input type="radio" name="halt_minute" value="00:00:00" <?php echo ($halt_minute=="00:00:00")?"checked":"";?>>All
    </label>
    <label class="radio-inline">
      <input type="radio" name="halt_minute" value="00:05:00" <?php echo ($halt_minute=="00:05:00")?"checked":"";?>>5
    </label>
    <label class="radio-inline">
      <input type="radio" name="halt_minute" value="00:10:00" <?php echo ($halt_minute=="00:10:00")?"checked":"";?>>10
    </label>
    <label class="radio-inline">
      <input type="radio" name="halt_minute" value="00:15:00" <?php echo ($halt_minute=="00:15:00")?"checked":"";?>>15
    </label>
    <label class="radio-inline">
      <input type="radio" name="halt_minute" value="00:30:00" <?php echo ($halt_minute=="00:30:00")?"checked":"";?>>30
    </label>
    <label class="radio-inline">
      <input type="radio" name="halt_minute" value="01:00:00" <?php echo ($halt_minute=="01:00:00")?"checked":"";?>>More than 1 hr
    </label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <input type="hidden" name="rm_user_id" value="<?php echo $_GET['rm_user_id']?>">
  <input type="hidden" name="date" value="<?php echo $_GET['date']?>">
</form>

<div class="col-md-12" style="margin-top:2%;">

  <div id="map"></div>
  <input type="hidden" id="start" value="<?php echo $start[0];?>,<?php echo $start[1];?>">
  <input type="hidden" id="end" value="<?php echo $end[0];?>,<?php echo $end[1];?>">
  <div id="directions-panel"></div>
</div>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>

<script type="text/javascript">
var geocoder;
var map;
var directionsDisplay;

var rm_lat_long = [
   <?php
    $rm_array = "";
    $r = 0;
    foreach($rm_lat_long as $rm){
      if($r==0){
        $text = "Checkin Time";
      }else{
        $text = "Time";
      }

      $rm_array .= "['".$text." : ".$rm['time'];
      if($rm['duration_spent']!=''){
        $rm_array .= "<br> Duration : ".$rm['duration_spent'];
      }
      $rm_array .= "',".$rm['lat'].",".$rm['lng'].",".$rm['show_marker']."],";
      $r++;
    }
    echo rtrim($rm_array,",");
    ?>
];

var distributor_lat_long = [
    <?php
    $distributor_array = "";
    foreach($distributor_lat_long as $dist){
      $distributor_array .= "['Distributor Location : <br>".str_replace("'","",$dist['company'])."',".$dist['lat'].",".$dist['lng']."],";
    }
    echo rtrim($distributor_array,",");
    ?>
];
function initialize() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: new google.maps.LatLng(<?php echo $start[0];?>, <?php echo $start[1];?>),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer({
            suppressMarkers: true
        });
        directionsDisplay.setMap(map);
        var infowindow = new google.maps.InfoWindow();

        var marker, i;
        var request = {
          travelMode: google.maps.TravelMode.DRIVING
        };
        
        for (i = 0; i < rm_lat_long.length; i++) {
          if(rm_lat_long[i][3]){
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(rm_lat_long[i][1], rm_lat_long[i][2]),
              map:map
            });

            var infowindow = new google.maps.InfoWindow({
              content: rm_lat_long[i][0],
              maxWidth: 160
            });
            infowindow.open(map, marker);



            google.maps.event.addListener(marker, 'click', (function(marker, i) {
              return function() {
                infowindow.setContent(rm_lat_long[i][0]);
                infowindow.open(map, marker);
              }
            })(marker, i));
          }
          

          if (i == 0) request.origin = new google.maps.LatLng(rm_lat_long[i][1], rm_lat_long[i][2]);
          else if (i == rm_lat_long.length - 1) request.destination = new google.maps.LatLng(rm_lat_long[i][1], rm_lat_long[i][2]);
          else {
            if (!request.waypoints) request.waypoints = [];
            request.waypoints.push({
              location: new google.maps.LatLng(rm_lat_long[i][1], rm_lat_long[i][2]),
              stopover: true
            });
          }
        }

        for (i = 0; i < distributor_lat_long.length; i++) {
          marker = new google.maps.Marker({
            position: new google.maps.LatLng(distributor_lat_long[i][1], distributor_lat_long[i][2]),
            icon:'/img/marker-256.png',
            map: map
          });

          var infowindow = new google.maps.InfoWindow({
            content: distributor_lat_long[i][0],
            maxWidth: 160
          });
          infowindow.open(map, marker);

          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              infowindow.setContent(distributor_lat_long[i][0]);
              infowindow.open(map, marker);
            }
          })(marker, i));
            
        }

        /*google.maps.event.addListenerOnce(map,"projection_changed", function() {
            map_recenter(map, occ, 0, -100);
         });*/

        //window.setTimeout(function() {map.setCenter(rm_lat_long[0][0]);map.setZoom(13);},1000);
        
        

        directionsService.route(request, function(result, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);
            var route = result.routes[0];
            var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                  '</b><br>';
              summaryPanel.innerHTML += route.legs[i].start_address + ' <br><b>to</b> <br>';
              summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              summaryPanel.innerHTML += "Distance : "+route.legs[i].distance.text + '<br><br>';
            }
          }
        });





}





</script>




<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>&callback=initialize"></script>