<style>
#umap, #vmap {
	height:300px;
}
.top-left {
	position: absolute;
	top: 0px;
}
.top-left0 {
	position: absolute;
	top: 30px;
}
figcaption {
	width: 120px;
}	
img {
	max-width: 220px;
}
.rotate90 {
    -ms-transform: rotate(90deg); /* IE 9 */
    -webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
    transform: rotate(90deg);
    transform-origin: 50% 50%;
}
.rotate180 {
    -ms-transform: rotate(180deg); /* IE 9 */
    -webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
    transform: rotate(180deg);
    transform-origin: 50% 50%;
}
.rotate270 {
    -ms-transform: rotate(270deg); /* IE 9 */
    -webkit-transform: rotate(270deg); /* Chrome, Safari, Opera */
    transform: rotate(270deg);
    transform-origin: 50% 50%;
}
.rotate0 {
    -ms-transform: rotate(0deg); /* IE 9 */
    -webkit-transform: rotate(0deg); /* Chrome, Safari, Opera */
    transform: rotate(0deg);
    transform-origin: 50% 50%;
}
img.full-size {
	max-width: none;
	max-height: 500px;
}
</style>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>"></script>
<script>
function initializeU() {
	var myLatLng = {lat: <?php echo round($retailer['ur']['latitude'], 5) ?>, lng: <?php echo round($retailer['ur']['longitude'], 5) ?>};

	  var map = new google.maps.Map(document.getElementById('umap'), {
	    zoom: 8,
	    center: myLatLng
	  });
	
	  var marker = new google.maps.Marker({
	    position: myLatLng,
	    map: map,
	    title: '<?php echo $retailer['ur']['shop_name'] ?>'
	  });

	  var geocoder = new google.maps.Geocoder;
	  var infowindow = new google.maps.InfoWindow;
		$('#fetch_address0').click(function(){
			geocodeLatLng(geocoder, map, infowindow, 0);
		});  
}
google.maps.event.addDomListener(window, 'load', initializeU);

function initializeV() {
	var myLatLng = {lat: <?php echo round($retailer['up']['latitude'], 5) ?>, lng: <?php echo round($retailer['up']['longitude'], 5) ?>};

	  var map = new google.maps.Map(document.getElementById('vmap'), {
	    zoom: 8,
	    center: myLatLng
	  });
	
	  var marker = new google.maps.Marker({
	    position: myLatLng,
	    map: map,
	    title: '<?php echo $retailer['r']['shopname'] ?>'
	  });

	  var geocoder = new google.maps.Geocoder;
	  var infowindow = new google.maps.InfoWindow;
		$('#fetch_address1').click(function(){
			geocodeLatLng(geocoder, map, infowindow, 1);
		}); 
}
google.maps.event.addDomListener(window, 'load', initializeV);

function geocodeLatLng(geocoder, map, infowindow, verify_flag) {
	var latlng = getLatLng(verify_flag);	
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        map.setZoom(11);
        var marker = new google.maps.Marker({
          position: latlng,
          map: map
        });
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);
		var area = city = state = postal_code = extra = "";
        results[0]['address_components'].forEach(function(element){
			switch(element['types'][0]){
				case "sublocality_level_1":
					area = element['long_name'];
					break;
				case "locality":
					city = element['long_name'];
					break;	
				case "administrative_area_level_1":
					state = element['long_name'];
					break;
				case "administrative_area_level_2":
					extra = element['long_name'];	
				case "postal_code":
					postal_code = element['long_name'];
					break;		
			}
        });
        if(area == ""){
			area = city;
			if(extra != ""){
				city = extra;
			}	
        }    
        setAddress(verify_flag, area, city, state, postal_code);
      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}

function setAddress(verify_flag, area, city, state, postal_code){
	$('#area' + verify_flag).val(area);
	$('#city' + verify_flag).val(city);
	$('#state' + verify_flag).val(state);
	$('#postal_code' + verify_flag).val(postal_code);		
}

function getLatLng(verify_flag){
	var latLng = {lat: parseFloat($('#lat' + verify_flag).val()), lng: parseFloat($('#long' + verify_flag).val())};
	return latLng;
}

var loader = "<img src='/img/ajax-loader-1.gif' />";

function removeDocument(src){
	var reason = prompt("Provide a valid reason for unverification. This message will be sent to the retailer.");
	if(reason != null && reason != ""){
		var url = "/panels/deleteDocument";
		var params = {"src" : src, "reason" : reason};
		$.post(url, params, function(response){
			alert(response);
			window.location.reload(true);
		});
	}	
}

function reject(retailer_id, section_id){
	var reason = prompt("Provide a valid reason for rejection. This message will be sent to the retailer.");
	if(reason != null && reason != ""){
		$('#v_r_' + section_id).html(loader);
		var url = "/panels/rejectSection";
		var params = {"retailer_id" : retailer_id, "section_id" : section_id, "reason" : reason};
		$.post(url, params, function(response){
			window.location.reload(true);		
		});
	}	
}

function verify(retailer_id, section_id){
	var confirmBool = false;
	confirmBool = confirm("Are you sure?");
	
	if(confirmBool){
		$('#v_r_' + section_id).html(loader);
		var url = "/panels/verifySection";
		var params = {"retailer_id" : retailer_id, "section_id" : section_id};
		$.post(url, params, function(response){
			window.location.reload(true);	
		});
	}	
}

function showLoader(type){
	$('#' + type + '_submit').hide();
	$('#' + type + '_loader').show();
}

function toggleOthersTextBox(vf){
	if($('#shop_type' + vf).val() == 8){
		$('#others' + vf).show();
	}	
	else
		$('#others' + vf).hide();
}

function updateDSN(retailer_id){
	var dsn = $('#dsn').val().trim();
	if(dsn == ""){
		alert("DSN cannot be empty");
		return false;
	}	
	
	$('#updateDSN').html(loader);
	$.post("/panels/updateDSN", {"dsn" : dsn, "retailer_id" : retailer_id}, function(response){
		if(response.trim() == "true"){
			alert("Device serial no updated");
		}
		else {
			alert("Could not update DSN. Try again.");
		}	
		window.location.reload(true);
	});
}

function activateMPOS(retailer_id, activate_flag){
	$('#activateMPOS').html(loader);
	$.post("/panels/activateMPOS", {"retailer_id" : retailer_id, "activate_flag" : activate_flag}, function(response){
		alert(response);	
		window.location.reload(true);
	});
}

function showImage(src){
	$('#modal-image').attr("src", src);
	$('#modal-image-href').attr("href", src);
	$('#modal-image').removeClass("rotate0 rotate90 rotate180 rotate270")
	$('#myModal').modal('show');
}

function rotate(){
	var rotation = $('#modal-image').data('rotation');
	if(rotation == 0){
		$('#modal-image').removeClass("rotate0 rotate180 rotate270").addClass("rotate90");
		$('#modal-image').data("rotation", "90");
	}	
	else if(rotation == 90){
		$('#modal-image').removeClass("rotate0 rotate90 rotate270").addClass("rotate180");
		$('#modal-image').data("rotation", "180");
	}
	else if(rotation == 180){
		$('#modal-image').removeClass("rotate0 rotate180 rotate90").addClass("rotate270");
		$('#modal-image').data("rotation", "270");
	}
	else if(rotation == 270){
		$('#modal-image').removeClass("rotate90 rotate180 rotate270").addClass("rotate0");
		$('#modal-image').data("rotation", "0");
	}
}
</script>
<link rel="SHORTCUT ICON" href="/img/pay1_favic.png">
<title>Retailer Verification</title>

<div class="row">
<div class="col-lg-10">
<h3>Retailer Verification </h3> 
</div>
<div class="col-lg-2">
	 
</div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:1000px">

    <!-- Modal content-->
    <div class="modal-content" style="width:1000px;background:transparent;">
      <div class="modal-body" style="text-align: center;">
      	<a id="modal-image-href" target="_blank" href="">
        	<img src="" id="modal-image" class="full-size" data-rotation="0" />
        </a>
      </div>
      <div class="modal-footer" style="border-top:none;">
      	<a href="javascript:rotate();" class="btn btn-default"><i class="glyphicon glyphicon-repeat"></i></a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="panel-group">
  <div class="panel panel-default">
  	<div class="panel-heading">
		<div class="row">
  			<div class="col-lg-6">
  				<h4></h4>
  			</div>
  			<div class="col-lg-6">
  			
  			</div>
  		</div>
	</div>
    <div class="panel-body">
		<div class="row">
			<div class="col-lg-1">
  						
  			</div>
  			<div class="form-group col-lg-3">
			    <label for="name">Retailer mobile:</label>
			    <?php echo $retailer['r']['mobile'] ?>
			</div>
  			<div class="form-group col-lg-3">
			    <label for="name">Distributor mobile:</label>
			    <?php echo $retailer['u']['mobile'] ?>
			</div>
			<div class="form-group col-lg-3">
			    <label for="name">Distributor name:</label>
			    <?php echo $retailer['d']['name'] ?>
			</div>
  		</div>
  		<div class="row">
  			<div class="col-lg-1">
  			
  			</div>
  			<div class="col-lg-4">
		  		<div class="input-group input-group-sm">
				  <span class="input-group-addon" id="sizing-addon3">DSN</span>
				  <input id="dsn" type="text" class="form-control" placeholder="mPOS Device Serial No" 
				  	value="<?php echo $retailer['r']['device_serial_no'] ?>" aria-describedby="sizing-addon3">
				</div>
			</div>
			<div class="col-lg-2" id="updateDSN">
				<a href="javascript:updateDSN('<?php echo $retailer['r']['id'] ?>');" class="btn btn-success btn-sm" >Update</a>
			</div>
			<div class="col-lg-2" id="activateMPOS">
				<?php if(strpos($retailer[0]['service_ids'], "8") !== false): ?>
				<a href="javascript:activateMPOS('<?php echo $retailer['r']['id'] ?>', '0');" class="btn btn-warning btn-sm" >Deactivate mPOS Service</a>
				<?php else: ?>
				<a href="javascript:activateMPOS('<?php echo $retailer['r']['id'] ?>', '1');" class="btn btn-info btn-sm" >Activate mPOS Service</a>
				<?php endif ?>
			</div>
  		</div>
  	</div>
  </div>	
  
  <div class="panel panel-default">
  	<div class="panel-heading">
  		<div class="row">
  			<div class="col-lg-5">
  				<h4>ID Verification</h4>
  			</div>
  			<div class="col-lg-5" id="v_r_1">
  				<?php foreach($retailer['kyc_states'] as $ks): ?>
  					<?php if($ks['rks']['section_id'] == 1 && $ks['rks']['document_state'] == 0): ?>
  					<a class="btn btn-primary btn-sm" href="javascript:verify('<?php echo $retailer['r']['id'] ?>', '1');">Verify</a>
  					<a class="btn btn-danger btn-sm" href="javascript:reject('<?php echo $retailer['r']['id'] ?>', '1');">Reject</a>
  					<?php endif ?>
  				<?php endforeach ?>
  			</div>
  			<div class="col-lg-2">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  				<?php if($ks['rks']['section_id'] == 1): ?>
  					<?php 	
  					if($ks['rks']['document_state'] == 2)
						echo "<span class='label label-info'>Approved</span>";
					else if($ks['rks']['document_state'] == 0){
						if($ks['rks']['verified_state'] == 0)
							echo "<span class='label label-warning'>Submitted</span>";
						else 
							echo "<span class='label label-warning'>Re-submitted</span>";
					}	
					else if($ks['rks']['document_state'] == 1)
						echo "<span class='label label-danger'>Rejected</span>";
					?>
  				<?php endif ?>
  			<?php endforeach ?>	
  			</div>
  		</div>
  	</div>
    <div class="panel-body">
		<div class="row">
		
  			<div class="col-lg-6">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  			<?php if($ks['rks']['section_id'] == 1 && $ks['rks']['document_state'] != 2): ?>
  			<?php $unverified_show_1 = true; ?>
  			<?php endif ?>
  			<?php if($ks['rks']['section_id'] == 1): ?>
  			<?php $unverified_hide_1 = true; ?>
  			<?php endif ?>
			<?php endforeach ?>
			<?php if($unverified_show_1 || !$unverified_hide_1): ?>
  				<form role="form" onsubmit="showLoader('idProof0');" action="/panels/retailerVerification/<?php echo $retailer['r']['id'] ?>" method="POST" enctype="multipart/form-data">
  					<div class="row">
  						<div class="col-lg-1">
  							
  						</div>
  						<div class="col-lg-5">
  							<h5>Unverified ID</h5>
  						</div>
  						<div class="col-lg-5">
  							
  						</div>
  					</div>	
  					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Retailer name:</label>
						    <input type="text" class="form-control input-sm" name="name" value="<?php echo $retailer['ur']['name'] ?>">
						</div>
					</div>	
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
  						<div class="col-lg-11">
							<?php foreach($retailer['images'] as $im): ?>
							<?php if($im['rd']['type'] == 'PAN_CARD'): ?>
							<div class="row">
							<a href="javascript:showImage('<?php echo $im['rd']['image_name'] ?>')">
								<img src="<?php echo $im['rd']['image_name'] ?>" style="max-height:300px;">
							</a>	
							<figcaption><?php if(!empty($im['rd']['comment'])): ?><?php echo $im['rd']['comment'] ?><?php endif ?><br/>
							<?php if(!empty($im['g']['name'])): ?>Uploaded by: <?php echo $im['g']['name'] ?><?php endif ?>
							</figcaption>
							</div>
							<?php endif ?>
							<?php endforeach ?>
						</div>	
					</div>
					<div class="row">
						<br/>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
						<div class="col-lg-2">
							<label>SELECT PAN CARD (max. file size: 500KB)
								<input type="file" class="btn btn-default btn-sm" name="PAN_CARD[]" accept="image/*">
							</label>	
							<input type="hidden" name="verify_flag" value="0" >
							<input type="hidden" name="section_id" value="1" >
						</div>
  						<div class="col-lg-7">
  						
  						</div>
	  					<div class="col-lg-2">
	  						<img id="idProof0_loader" src='/img/ajax-loader-1.gif' style="display:none;" />
							<button type="submit" id="idProof0_submit" class="btn btn-success btn-sm">Update</button>
						</div>
					</div>
				</form>
			<?php endif ?>
  			</div>
  			
  			<div class="col-lg-6">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  			<?php if($ks['rks']['section_id'] == 1 && $ks['rks']['verified_state'] == 1): ?>
			<form role="form" onsubmit="showLoader('idProof1');" action="/panels/retailerVerification/<?php echo $retailer['r']['id'] ?>" method="POST" enctype="multipart/form-data">
  					<h5>Verified ID</h5>
  					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Retailer name:</label>
						    <input type="text" class="form-control input-sm" name="name" value="<?php echo $retailer['r']['name'] ?>">
						</div>
					</div>	
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
  						<div class="col-lg-11">
							<?php foreach($retailer['verified_images'] as $im): ?>
							<?php if($im['rd']['type'] == 'PAN_CARD'): ?>
							<div class="row">
							<a href="javascript:showImage('<?php echo $im['rd']['src'] ?>')">
								<img src="<?php echo $im['rd']['src'] ?>" style="max-height:300px;">
							</a>	
							<figcaption><?php if(!empty($im['rd']['comment'])): ?><?php echo $im['rd']['comment'] ?><?php endif ?><br/>
							<?php if(!empty($im['g']['name'])): ?>Uploaded by: <?php echo $im['g']['name'] ?><?php endif ?>
							</figcaption>
							</div>
							<?php endif ?>
							<?php endforeach ?>
						</div>	
					</div>
					<div class="row">
						<br/>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
						<div class="col-lg-2">
						<label>SELECT PAN CARD (max. file size: 500KB)
							<input type="file" class="btn btn-default btn-sm" name="PAN_CARD[]" accept="image/*">
						</label>	
							<input type="hidden" name="verify_flag" value="1" >
							<input type="hidden" name="section_id" value="1" >
						</div>
  						<div class="col-lg-7">
  						
  						</div>
	  					<div class="col-lg-2">
	  						<img id="idProof1_loader" src='/img/ajax-loader-1.gif' style="display:none;" />
							<button type="submit" id="idProof1_submit" class="btn btn-success btn-sm">Update</button>
						</div>
					</div>
					</form>
			<?php endif ?>
			<?php endforeach ?>		
  			</div>
  		</div>
	</div>
  </div>
  <div class="panel panel-default">
  	<div class="panel-heading">
		<div class="row">
  			<div class="col-lg-5">
  				<h4>Address Verification</h4>
  			</div>
  			<div class="col-lg-5" id="v_r_2">
  				<?php foreach($retailer['kyc_states'] as $ks): ?>
  					<?php if($ks['rks']['section_id'] == 2 && $ks['rks']['document_state'] == 0): ?>
  					<a class="btn btn-primary btn-sm" href="javascript:verify('<?php echo $retailer['r']['id'] ?>', '2');">Verify</a>
  					<a class="btn btn-danger btn-sm" href="javascript:reject('<?php echo $retailer['r']['id'] ?>', '2');">Reject</a>
  					<?php endif ?>
  				<?php endforeach ?>
  			</div>
  			<div class="col-lg-2">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  				<?php if($ks['rks']['section_id'] == 2): ?>
  					<?php 	
  					if($ks['rks']['document_state'] == 2)
						echo "<span class='label label-info'>Approved</span>";
					else if($ks['rks']['document_state'] == 0)
						echo "<span class='label label-warning'>Submitted</span>";
					else if($ks['rks']['document_state'] == 1)
						echo "<span class='label label-danger'>Rejected</span>";
					?>
  				<?php endif ?>
  			<?php endforeach ?>	
  			</div>
  		</div>
	</div>
    <div class="panel-body">
		<div class="row">
  			<div class="col-lg-6">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  			<?php if($ks['rks']['section_id'] == 2 && $ks['rks']['document_state'] != 2): ?>
  			<?php $unverified_show_2 = true; ?>
  			<?php endif ?>
  			<?php if($ks['rks']['section_id'] == 2): ?>
  			<?php $unverified_hide_2 = true; ?>
  			<?php endif ?>
			<?php endforeach ?>
			<?php if($unverified_show_2 || !$unverified_hide_2): ?>
  			<form role="form" onsubmit="showLoader('addressProof0');" action="/panels/retailerVerification/<?php echo $retailer['r']['id'] ?>" method="POST" enctype="multipart/form-data">
  				<input type="hidden" name="update" value="true">	
  					<div class="row">
  						<div class="col-lg-1">
  							
  						</div>
  						<div class="col-lg-5">
  							<h5>Unverified Address</h5>
  						</div>
  						<div class="col-lg-5">
  							
  						</div>
  					</div>	
  					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="col-lg-11" id="umap">
							
						</div>
					</div>
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="col-lg-5 form-group">
						   <label for="name">Latitude:</label>
						    <input type="text" class="form-control input-sm" id="lat0" name="latitude" value="<?php echo $retailer['ur']['latitude'] ?>">
						</div>
						<div class="col-lg-5 form-group">
						   <label for="name">Longitude:</label>
						    <input type="text" class="form-control input-sm" id="long0" name="longitude" value="<?php echo $retailer['ur']['longitude'] ?>">
						</div>
					</div>	
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
	  					<div class="col-lg-5 form-group">
						   <a id="fetch_address0" class="btn btn-default" >Get Address From Lat-Long</a>
						</div>
					</div>
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="shopname">Shop name:</label>
						    <input type="text" class="form-control input-sm" name="shopname" value="<?php echo $retailer['ur']['shopname'] ?>">
						</div>
					</div>
  					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Address:</label>
						    <input type="text" class="form-control input-sm" name="address" value="<?php echo $retailer['ur']['address'] ?>">
						</div>
					</div>	
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Area:</label>
						    <input type="text" class="form-control input-sm" id="area0" name="area" value="<?php echo $retailer['ua']['name'] ?>">
						</div>
					</div>	
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">City:</label>
						    <input type="text" class="form-control input-sm" id="city0" name="city" value="<?php echo $retailer['uc']['name'] ?>">
						</div>
					</div>
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">State:</label>
						    <input type="text" class="form-control input-sm" id="state0" name="state" value="<?php echo $retailer['us']['name'] ?>">
						</div>
					</div>
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Pin code:</label>
						    <input type="text" class="form-control input-sm" id="postal_code0" name="pincode" value="<?php echo $retailer['ur']['pin'] ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
  						<div class="col-lg-11">
							<?php foreach($retailer['images'] as $im): ?>
							<?php if($im['rd']['type'] == 'ADDRESS_PROOF'): ?>
							<div class="row">
							<a href="javascript:showImage('<?php echo $im['rd']['image_name'] ?>')">
								<img src="<?php echo $im['rd']['image_name'] ?>" style="max-height:300px;">
							</a>	
							<figcaption><?php if(!empty($im['rd']['comment'])): ?><?php echo $im['rd']['comment'] ?><?php endif ?><br/>
							<?php if(!empty($im['g']['name'])): ?>Uploaded by: <?php echo $im['g']['name'] ?><?php endif ?>
							</figcaption>
							</div>
							<?php endif ?>
							<?php endforeach ?>
						</div>	
					</div>
					<div class="row">
						<br/>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
						<div class="col-lg-2">
						<label>SELECT ADDRESS PROOF (max. file size: 500KB)
							<input type="file" class="btn btn-default btn-sm" name="ADDRESS_PROOF[]" accept="image/*">
						</label>	
							<input type="hidden" name="verify_flag" value="0" >
							<input type="hidden" name="section_id" value="2" >
						</div>
  						<div class="col-lg-7">
  						
  						</div>
	  					<div class="col-lg-2">
	  						<img id="addressProof0_loader" src='/img/ajax-loader-1.gif' style="display:none;" />
							<button type="submit" id="addressProof0_submit" class="btn btn-success btn-sm">Update</button>
						</div>
					</div>
					</form>
					<?php endif ?>
  			</div>
  			
  			<div class="col-lg-6">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  			<?php if($ks['rks']['section_id'] == 2 && $ks['rks']['verified_state'] == 1): ?>
				<form role="form" onsubmit="showLoader('addressProof1');" action="/panels/retailerVerification/<?php echo $retailer['r']['id'] ?>" method="POST" enctype="multipart/form-data">
  					<input type="hidden" name="update" value="true">
  					<h5>Verified Address</h5>
  					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="col-lg-11" id="vmap">
							
						</div>
					</div>	
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="col-lg-5 form-group">
						   <label for="name">Latitude:</label>
						    <input type="text" class="form-control input-sm" name="latitude" value="<?php echo $retailer['up']['latitude'] ?>">
						</div>
						<div class="col-lg-5 form-group">
						   <label for="name">Longitude:</label>
						    <input type="text" class="form-control input-sm" name="longitude" value="<?php echo $retailer['up']['longitude'] ?>">
						</div>
					</div>	
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
	  					<div class="col-lg-5 form-group">
						   <a id="fetch_address1" class="btn btn-default" >Get Address From Lat-Long</a>
						</div>
					</div>
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="shopname">Shop name:</label>
						    <input type="text" class="form-control input-sm" name="shopname" value="<?php echo $retailer['r']['shopname'] ?>">
						</div>
					</div>
  					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Address:</label>
						    <input type="text" class="form-control input-sm" name="address" value="<?php echo $retailer['r']['address'] ?>">
						</div>
					</div>	
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Area:</label>
						    <input type="text" class="form-control input-sm" id="area1" name="area" value="<?php echo $retailer['a']['name'] ?>">
						</div>
					</div>	
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">City:</label>
						    <input type="text" class="form-control input-sm" id="city1" name="city" value="<?php echo $retailer['c']['name'] ?>">
						</div>
					</div>
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">State:</label>
						    <input type="text" class="form-control input-sm" id="state1" name="state" value="<?php echo $retailer['s']['name'] ?>">
						</div>
					</div>
					<div class="row">
  						<div class="col-lg-1">
  						
  						</div>
	  					<div class="form-group col-lg-11">
						    <label for="name">Pin code:</label>
						    <input type="text" class="form-control input-sm" id="postal_code1" name="pincode" value="<?php echo $retailer['r']['pin'] ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
  						<div class="col-lg-11">
							<?php foreach($retailer['verified_images'] as $im): ?>
							<?php if($im['rd']['type'] == 'ADDRESS_PROOF'): ?>
							<div class="row">
							<a href="javascript:showImage('<?php echo $im['rd']['src'] ?>')">
								<img src="<?php echo $im['rd']['src'] ?>" style="max-height:300px;">
							</a>	
							<figcaption><?php if(!empty($im['rd']['comment'])): ?><?php echo $im['rd']['comment'] ?><?php endif ?><br/>
							<?php if(!empty($im['g']['name'])): ?>Uploaded by: <?php echo $im['g']['name'] ?><?php endif ?>
							</figcaption>
							</div>
							<?php endif ?>
							<?php endforeach ?>
						</div>	
					</div>
					<div class="row">
						<br/>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
						<div class="col-lg-2">
						<label>SELECT ADDRESS PROOF (max. file size: 500KB)
							<input type="file" class="btn btn-default btn-sm" name="ADDRESS_PROOF[]" accept="image/*">
						</label>	
							<input type="hidden" name="verify_flag" value="1" >
							<input type="hidden" name="section_id" value="2" >
						</div>
  						<div class="col-lg-7">
  						
  						</div>
	  					<div class="col-lg-2">
	  						<img id="addressProof1_loader" src='/img/ajax-loader-1.gif' style="display:none;" />
							<button type="submit" id="addressProof1_submit" class="btn btn-success btn-sm">Update</button>
						</div>
					</div>
					</form>
				<?php endif ?>
				<?php endforeach ?>	
  			</div>
  		</div>
	</div>
  </div>
  <div class="panel panel-default">
  	<div class="panel-heading">
		<div class="row">
  			<div class="col-lg-5">
  				<h4>Other Info Verification</h4>
  			</div>
  			<div class="col-lg-5" id="v_r_3">
  				<?php foreach($retailer['kyc_states'] as $ks): ?>
  					<?php if($ks['rks']['section_id'] == 3 && $ks['rks']['document_state'] == 0): ?>
  					<a class="btn btn-primary btn-sm" href="javascript:verify('<?php echo $retailer['r']['id'] ?>', '3');">Verify</a>
  					<a class="btn btn-danger btn-sm" href="javascript:reject('<?php echo $retailer['r']['id'] ?>', '3');">Reject</a>
  					<?php endif ?>
  				<?php endforeach ?>
  			</div>
  			<div class="col-lg-2">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  				<?php if($ks['rks']['section_id'] == 3): ?>
  					<?php 	
  					if($ks['rks']['document_state'] == 2)
						echo "<span class='label label-info'>Approved</span>";
					else if($ks['rks']['document_state'] == 0)
						echo "<span class='label label-warning'>Submitted</span>";
					else if($ks['rks']['document_state'] == 1)
						echo "<span class='label label-danger'>Rejected</span>";
					?>
  				<?php endif ?>
  			<?php endforeach ?>	
  			</div>
  		</div>
	</div>
    <div class="panel-body">
		<div class="row">
  			<div class="col-lg-6">
  			<?php foreach($retailer['kyc_states'] as $ks): ?>
  			<?php if($ks['rks']['section_id'] == 3 && $ks['rks']['document_state'] != 2): ?>
  			<?php $unverified_show_3 = true; ?>
  			<?php endif ?>
  			<?php if($ks['rks']['section_id'] == 3): ?>
  			<?php $unverified_hide_3 = true; ?>
  			<?php endif ?>
			<?php endforeach ?>
			<?php if($unverified_show_3 || !$unverified_hide_3): ?>
  				<form role="form" onsubmit="showLoader('shop0');" action="/panels/retailerVerification/<?php echo $retailer['r']['id'] ?>" method="POST" enctype="multipart/form-data">
  					<div class="row">
  						<div class="col-lg-1">
  							
  						</div>
  						<div class="col-lg-5">
  							<h5>Unverified Shop Photos</h5>
  						</div>
  						<div class="col-lg-5">

  						</div>
  					</div>	
  					<div class="row">
	  					<div class="col-lg-1">
	  			
			  			</div>
			  			<div class="form-group col-lg-5">
						    <label for="shop_type">Nature of business:</label>
						    <select id="shop_type0" onchange="toggleOthersTextBox('0');" name="shop_type">
						    	<option></option>
						    	<?php foreach($shop_types as $kst => $st): ?>
						    	<option value="<?php echo $kst ?>" <?php if($kst == $retailer['ur']['shop_type']) echo "selected" ?>><?php echo $st ?></option>
						    	<?php endforeach ?>
						    </select>
						    <input id="others0" type="text" name="shop_type_value" value="<?php echo $retailer['ur']['shop_type_value'] ?>" style="<?php if($retailer['ur']['shop_type'] != 8) echo "display:none;" ?>">
						</div>
						<div class="form-group col-lg-5">
						    <label for="location_type">Area of business:</label>
						    <select name="location_type">
						    	<option></option>
						    	<?php foreach($location_types as $klt => $lt): ?>
						    	<option value="<?php echo $klt ?>" <?php if($klt == $retailer['ur']['location_type']) echo "selected" ?>><?php echo $lt ?></option>
						    	<?php endforeach ?>
						    </select>
						</div>
	  				</div>
  					<div class="row">
  					<div class="col-lg-1">
  					
  					</div>
  						<?php $i = 0; ?>
  						<?php foreach($retailer['images'] as $im): ?>
							<?php if($im['rd']['type'] == 'SHOP_PHOTO'): ?>
							<?php $i++; ?>
							<div class="col-lg-5">
							<a href="javascript:showImage('<?php echo $im['rd']['image_name'] ?>')">
								<img src="<?php echo $im['rd']['image_name'] ?>" style="max-height:300px;">
							</a>	
							<figcaption><?php if(!empty($im['rd']['comment'])): ?><?php echo $im['rd']['comment'] ?><?php endif ?><br/>
							<?php if(!empty($im['g']['name'])): ?>Uploaded by: <?php echo $im['g']['name'] ?><?php endif ?>
							</figcaption>
							</div>
						<?php if($i == 2): ?>
							</div>
							<div class="row">
							<div class="col-lg-1">
  					
  							</div>
						<?php endif ?>
							<?php endif ?>
						<?php endforeach ?>
					</div>
					<div class="row">
						<br/>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
						<div class="col-lg-2">
						<label>SELECT SHOP PHOTO (max. file size: 500KB)
							<input type="file" class="btn btn-default btn-sm" name="SHOP_PHOTO[]" accept="image/*">
						</label>	
							<input type="hidden" name="verify_flag" value="0" >
							<input type="hidden" name="section_id" value="3" >
						</div>
  						<div class="col-lg-7">
  						
  						</div>
	  					<div class="col-lg-2">
	  						<img id="shop0_loader" src='/img/ajax-loader-1.gif' style="display:none;" />
							<button type="submit" id="shop0_submit" class="btn btn-success btn-sm">Update</button>
						</div>
					</div>
					</form>
				<?php endif ?>
			</div>
			<div class="col-lg-6">
			<?php foreach($retailer['kyc_states'] as $ks): ?>
  			<?php if($ks['rks']['section_id'] == 3 && $ks['rks']['verified_state'] == 1): ?>
				<form role="form" onsubmit="showLoader('shop1');" action="/panels/retailerVerification/<?php echo $retailer['r']['id'] ?>" method="POST" enctype="multipart/form-data">
  					<h5>Verified Shop Photos</h5>
  					<div class="row">
	  					<div class="col-lg-1">
	  			
			  			</div>
			  			<div class="form-group col-lg-5">
						    <label for="shop_type">Nature of business:</label>
						    <select name="shop_type1" onchange="toggleOthersTextBox('1');" id="shop_type1">
						    	<option></option>
						    	<?php foreach($shop_types as $kst => $st): ?>
						    	<option value="<?php echo $kst ?>" <?php if($kst == $retailer['r']['shop_type']) echo "selected" ?>><?php echo $st ?></option>
						    	<?php endforeach ?>
						    </select>
						    <input id="others1" name="shop_type_value" type="text" value="<?php echo $retailer['r']['shop_type_value'] ?>" style="<?php if($retailer['ur']['shop_type'] != 8) echo "display:none;" ?>">
						</div>
						<div class="form-group col-lg-5">
						    <label for="name">Area of business:</label>
						    <select>
						    	<option></option>
						    	<?php foreach($location_types as $klt => $lt): ?>
						    	<option value="<?php echo $klt ?>" <?php if($klt == $retailer['r']['location_type']) echo "selected" ?>><?php echo $lt ?></option>
						    	<?php endforeach ?>
						    </select>
						</div>
	  				</div>
  					<div class="row">
  						<div class="col-lg-1">
  					
  						</div>
  						<?php $i = 0; ?>
	  					<?php foreach($retailer['verified_images'] as $im): ?>
							<?php if($im['rd']['type'] == 'SHOP_PHOTO'): ?>
							<?php $i++; ?>
							<div class="col-lg-5">
							<a href="javascript:showImage('<?php echo $im['rd']['src'] ?>')">
								<img src="<?php echo $im['rd']['src'] ?>" style="max-height:300px;">
							</a>	
							<figcaption><?php if(!empty($im['rd']['comment'])): ?><?php echo $im['rd']['comment'] ?><?php endif ?><br/>
							<?php if(!empty($im['g']['name'])): ?>Uploaded by: <?php echo $im['g']['name'] ?><?php endif ?>
							</figcaption>
							</div>
						<?php if($i == 2): ?>
							</div>
							<div class="row">
							<div class="col-lg-1">
  					
  							</div>
						<?php endif ?>
							<?php endif ?>
						<?php endforeach ?>
					</div>
					<div class="row">
						<br/>
					</div>
					<div class="row">
						<div class="col-lg-1">
  						
  						</div>
						<div class="col-lg-2">
						<label>SELECT SHOP PHOTO (max. file size: 500KB)
							<input type="file" class="btn btn-default btn-sm" name="SHOP_PHOTO[]" accept="image/*">
						</label>	
							<input type="hidden" name="verify_flag" value="1" >
							<input type="hidden" name="section_id" value="3" >
						</div>
  						<div class="col-lg-7">
  						
  						</div>
	  					<div class="col-lg-2">
	  						<img id="shop1_loader" src='/img/ajax-loader-1.gif' style="display:none;" />
							<button type="submit" id="shop1_submit" class="btn btn-success btn-sm">Update</button>
						</div>
					</div>
				</form>
				<?php endif ?>
				<?php endforeach ?>
			</div>
		</div>
	</div>
  </div>			
</div>