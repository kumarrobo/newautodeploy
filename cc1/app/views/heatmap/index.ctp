<!DOCTYPE html>
<html>
    <head>
        <title>Heat Map</title>
    </head>

    <body>
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #map {
                height: 100%;
            }
            .rider-panel {
                position: absolute;
                right: 0;
                top: 0;
                z-index: 10;
                background: rgba(255, 255, 255, 0.8);
                max-width: 320px;
                width: 32%;
                padding: 1px 10px 0 10px;
                margin: 0;
            }
        </style>
        
        <div id="map"></div>
        <div class="rider-panel">
            <div style="margin: 10px 0 0 10px; line-height: 2em;"><b><u>Filter</u> :-</b></div>
            <div id="state">
                <span><strong>Select State : </strong></span>
                <select style="border-radius: 5px; width: 150px; height: 30px;" >
                    <option>All</option>
                    <?php foreach($states as $pro) { ?>
                    <option value="<?php echo $pro['locator_state']['id']; ?>" <?php if($pro['locator_state']['id'] == $center[3]) { echo "selected"; } ?>><?php echo $pro['locator_state']['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div id="city"></div>
            <div id="area"></div>
            <br />
            <input type="button" id="submit" value="Search" style="border-radius: 3px; border: 1px solid gray; margin-left: 50px;" />
            <input type="button" id="filter" value="Select City" style="border-radius: 2px; border: 1px solid gray;" />
            <br /><br />
        </div>

        <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>        
        <script type="text/javascript">
            var map, heatmap;
			
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: <?php echo $center[0] != '23.3302095' ? $center[2] : '5'; ?>,
                    center: {<?php echo 'lat: ' . $center[0] . ', lng: ' . $center[1]; ?>},
//                    mapTypeId: google.maps.MapTypeId.SATELLITE,
                    mapTypeId: google.maps.MapTypeId.HYBRID,
                    radius: 20
                });

                heatmap = new google.maps.visualization.HeatmapLayer({
                    data: json_latlng(<?php echo $data; ?>),
                    map: map
                });
            }
			
            function json_latlng(arr) {
                var coordinates = [];
                for (var i = 0; i < arr.length; i++) {
                    var lat = arr[i].lat;
                    var lng = arr[i].lng;
                    coordinates.push(new google.maps.LatLng(lat, lng));
                }
                return coordinates;
            }
            
                
            $('#filter').click(function() {

                search = String($('#filter').val().substring(7));

                if(search === 'City') {
                    data = {search: search, location: $('#state select').val()};
                    tableName = "locator_city";
                } else {
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
                    if(search == 'City') {
                        $('#filter').val('Select Area');
                    } else {
                        $('#filter').hide();
                    }
                    if('<?php echo $center[5] ?>'  != 'All') {
                        $('#filter').click();
                    }
                }, 'json');
            });

            $('#submit').click(function() {

                var state   = $('#state select').val();
                var city    = typeof $('#city select').val() == 'undefined' ? 'All' : $('#city select').val();
                var area    = typeof $('#area select').val() == 'undefined' ? 'All' : $('#area select').val();

                window.location = '/heatmap/index/' + state + '/' + city + '/' + area;
            });
                
            $(function() {
                
                if('<?php echo $center[4] ?>' != 'All') {
                    $('#filter').click();
                }
            });
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>&signed_in=true&libraries=visualization&callback=initMap"></script>
    </body>
</html>