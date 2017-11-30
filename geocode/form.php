<style>
	.pac-container { z-index: 10000 !important; }
</style>

<div class="geocode-attr" id="geocode-<?php echo $akid ?>">

	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
			    <?php echo $form->label($this->field('lat'), t('Latitude'))?>
			    <?php echo $form->text($this->field('lat'), $lat, array('class' => 'lat'))?>
			</div>
		</div>
		<div class="col-md-6">

			<div class="form-group">
			    <?php echo $form->label($this->field('lng'), t('Longitude'))?>
			    <?php echo $form->text($this->field('lng'), $lng, array('class' => 'lng'))?>
			</div>
		</div>
	</div>

	<div class="map-wrap"> 
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				    <?php echo $form->label($this->field('search'), t('Search Address'))?>
				    <input type="text" id="autocomplete-places" class="form-control" placeholder="Search Address">
				    <p class="help-block">Don't know geocoordinates? Search and manually set pin location. Coordinates above will be automatically updated.</p>
				</div>
			</div>
		</div>
		<div class="map-cont">
			<div class="map" id="map-attr"></div>
		</div>
	</div>

</div>


<script>
var handleApiReady = null;
(function() {
	 
	var map;
	var marker;


	var $wrap = $(".geocode-attr");
	var $lat = $wrap.find('.lat');
	var $lng = $wrap.find('.lng');
	var $checkbox = $wrap.find('.checkbox');
	var $mapWrap = $wrap.find('.map-cont');
	

	$checkbox.change(function(){

		$mapWrap.toggleClass('active');
	});


	$lat.blur(function() {

		updateMarkerMapPosition();

	});

	$lng.blur(function() {

		updateMarkerMapPosition();

	});


	function updateMarkerMapPosition() {

		var latlng = new google.maps.LatLng($lat.val(), $lng.val());
	    marker.setPosition(latlng);
	    map.setCenter(latlng);
	}





	function appendBootstrap() {
	    if (typeof google === 'object' && typeof google.maps === 'object') {
	        handleApiReady();
	    } else {
	        var script = document.createElement("script");
	        script.type = "text/javascript";
	        script.src = "http://maps.google.com/maps/api/js?key=<?php echo Config::get('app.api_keys.google.maps') ?>&libraries=places&callback=handleApiReady";
	        document.body.appendChild(script);
	    }
	}

	var map;
	handleApiReady = function() {


		//given this a bit of a delay or else the map doesn't render correctly
		setTimeout(function(){
			

			var latlng = new google.maps.LatLng(49.887952, -119.496011);
		    map = new google.maps.Map(document.getElementById('map-attr'),{
		        center: latlng,
		        scrollwheel: false,
		        zoom: 15
		    });

		    <?php if(!empty($lat) && !empty($lng)): ?>
		    	var latlngM = new google.maps.LatLng(<?php echo $lat ?>, <?php echo $lng ?>);
		    <?php else : ?>
		    	var latlngM = new google.maps.LatLng(49.887952, -119.496011);
		   	<?php endif ?>

				
				marker = new google.maps.Marker({
			        position: latlngM,
			        map: map,
			        draggable:true
			    });



			    map.setCenter(latlngM);
			    setTimeout(function(){

			    	
			    	map.setZoom(15);
			    },1000);

			

		    google.maps.event.addListener(marker, 'dragend', function() {

		    	$lat.val(marker.position.lat());
		    	$lng.val(marker.position.lng());

		    });

		},1500);

		var searchInput = document.getElementById('autocomplete-places');
	    var searchBox = new google.maps.places.SearchBox(searchInput);
		searchBox.addListener('places_changed', function() {

	         var places = searchBox.getPlaces();

	         if(places.length < 1) return;

	         var mLocation = places[0].geometry.location;
	         marker.setPosition(mLocation);
	         map.setCenter(mLocation);

	        $lat.val(mLocation.lat());
		    $lng.val(mLocation.lng());
   
	    });		
	}


	appendBootstrap();

})();
</script>