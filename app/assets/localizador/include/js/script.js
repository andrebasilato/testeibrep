var map;
var markers = [];
var infoWindow;
var panorama;
var img_loading = '<img src="' + Store_locator.ajaxurl + '/include/graph/icons/ajax-loader.gif">';
//var js_call;

function detectLocation() {
	if (navigator.geolocation) {
  		navigator.geolocation.getCurrentPosition(detectionSuccess, detectionError, {maximumAge:600000});
	}
}

function detectionSuccess(position) {
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	Store_locator.lat = lat;
	Store_locator.lng = lng;
	//if(js_call==1) init_locations2();
	
	/*
	var criteria = $('body').data('search_criteria');
	if(criteria===null) criteria={};
	criteria.lat = lat;
	criteria.lng = lng;
	$('body').data('search_criteria', criteria);
	init_locations2();
	*/
}

function detectionError(msg) {
	//alert(msg);
}

function strpos(haystack, needle, offset) {
    var i = (haystack + '').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}

function init_locations() {
	
	if(Store_locator.autodetect_location=='1') detectLocation();
	
	var lat='';
	var lng='';
	
	if ($("#address").length>0 && $("#address").val()!='') {
		var address = $('#address').val();
		//alert(address);
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( {'address': address}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
		  	//lat = results[0].geometry.location.Ha;
		  	//lng = results[0].geometry.location.Ia;
		  	
		  	var latLng = String(results[0].geometry.location);
		  	latLng = latLng.substr(1);
		  	var pos = strpos(latLng, ',');
		  	lat = latLng.substr(0,pos);
		  	var pos2 = strpos(latLng, ')');
		  	latLng = latLng.substr(0,pos2);
		  	lng = latLng.substr((pos+2));
		  	
		  	//alert(lat + '||' + lng + '||' + latLng);
		  	//alert(results[0].geometry.location);
		  	
		  	init_locations2(address, lat, lng);
		  }
		});
	}
	else {
		var address = '';
		init_locations2(address, lat, lng);
	}
}

function init_locations2(address, lat, lng) {
	
	init_map();
	
	if ( $("#category_id").length > 0 ) var category_id = $('#category_id').val();
	else var category_id = '';
	
	if ( $("#max_distance").length > 0 ) var max_distance = $('#max_distance').val();
	else var max_distance = '';
	
	var criteria = {};
	criteria.address = address;
	criteria.lat = lat;
	criteria.lng = lng;
	criteria.page_number = 1;
	criteria.nb_display = Store_locator.nb_display;
	criteria.category_id = category_id;
	criteria.max_distance = max_distance;
	criteria.display_type = Store_locator.display_type;
	
	$('body').data('search_criteria', criteria);
	display_locations(criteria);
}

function init_map() {
	
	map = new google.maps.Map(document.getElementById("map"), {
		zoom: 4,
		mapTypeId: 'roadmap',
		//scrollwheel: false,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
	});
	
	infoWindow = new google.maps.InfoWindow();
}

function init_location(lat,lng,zoom) {
	if(lat===undefined) lat=40;
	if(lng===undefined) lng=-100;
	if(zoom===undefined) zoom=4;
	
	map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(lat, lng),
		zoom: zoom,
		mapTypeId: 'roadmap',
		//scrollwheel: false,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
	});
}

function setStreetView(latlng) {
    panorama = map.getStreetView();
    panorama.setPosition(latlng);
    panorama.setPov({
      heading: 265,
      zoom:1,
      pitch:0}
    );
}

$("#displayStreetView").live('click', function(event) {
	event.preventDefault();
	panorama.setVisible(true);
});

$("#store_locator_next").live('click', function(event) {
	event.preventDefault();
	clearLocations();
	$('#pagination_loading').html(img_loading);
	var criteria = $('body').data('search_criteria');
	criteria.page_number = (criteria.page_number+1);
	$('body').data('search_criteria', criteria);
	display_locations(criteria);
});

$("#store_locator_previous").live('click', function(event) {
	event.preventDefault();
	clearLocations();
	$('#pagination_loading').html(img_loading);
	var criteria = $('body').data('search_criteria');
	criteria.page_number = (criteria.page_number-1);
	$('body').data('search_criteria', criteria);
	display_locations(criteria);
});

function clearLocations() {
	//infoWindow.close();
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers.length = 0;
}

function createSidebarEntry(markerNum, sidebarContent, markerContent, lat, lng) {
	var div = document.createElement('div');
	div.innerHTML = sidebarContent;
	div.style.cursor = 'pointer';
	div.style.marginBottom = '5px';
	
	div.onclick = function(){
		//infoWindow.setContent(markerContent);
		//infoWindow.open(map, markers[markerNum]);
		init_location(lat,lng,16);
		var latlng = new google.maps.LatLng(
			parseFloat(lat),
			parseFloat(lng)
		);
		clearLocations();
		createMarker(latlng, lat, lng, markerContent);
		//streetView(lat,lng);
	}
	
	div.onmouseover = function(){
		div.style.backgroundColor = '#eee';
	}
	
	div.onmouseout = function(){
		div.style.backgroundColor = '#fff';
	}
	
	return div;
}

function resizeMap(width,height) {
	$('#map').animate({width: width, height:height}, 
	function() { 
		google.maps.event.trigger(map, 'resize');
		map.setCenter(map.getCenter());
	});
}

function streetView(lat,lng) {
	var dom = 'streetview';
	panorama = new google.maps.StreetViewPanorama(document.getElementById(dom));
	displayStreetView(lat,lng, dom);
	if($('#map').height()==600) {
		resizeMap(850,300);
		$('#streetview').height(300);
	}
}

function set_current_location_marker(latlng) {
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': latlng}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
	    if (results[1]) {
	    	var address = results[0].formatted_address;
	    	
	    	var marker_text = '<b>Current location</b><br/>'+address;
	    	if(Store_locator.streetview_display=='1') marker_text += '<br/><a href="#" id="displayStreetView">Street View</a>';
	    	
	    	createMarker(latlng, Store_locator.lat, Store_locator.lng, marker_text, Store_locator.marker_icon_current);
	    	$('#current_location').html('<div style="background:#eae9e9; padding:5px; margin-bottom:10px;"><b>Your current location:</b> '+address+'</div>');
	    } 
	    else {
	    	//alert("No results found");
	    }
	  } 
	  else {
	    //alert("Geocoder failed due to: " + status);
	  }
	});
}

function display_locations(criteria) {
	clearLocations();
	
	$.ajax({
	  type: 'GET',
	  url: Store_locator.ajaxurl + '/process.php?p=displayStores&criteria='+JSON.stringify(criteria),
	  dataType: 'json',
	  success: function(msg) {
	  	
		var sidebar = document.getElementById('sidebar');
		sidebar.innerHTML = '';
	  	$('#pagination_loading').html('');
	  	
	  	var locations = msg.locations;
	  	var markersContent = msg.markersContent;
	  	var sidebarContent = msg.sidebarContent;
		var bounds = new google.maps.LatLngBounds();
		
		//set marker with current location
		if(Store_locator.lat!==undefined&&Store_locator.lng!==undefined) {
			var latlng_current = new google.maps.LatLng(
				parseFloat(Store_locator.lat),
				parseFloat(Store_locator.lng)
			);
			set_current_location_marker(latlng_current);
		}
		
       	for (var i = 0; i < locations.length; i++) {
			var name = locations[i]['name'];
			var address = locations[i]['address'];
			var distance = parseFloat(locations[i]['distance']);
			var latlng = new google.maps.LatLng(
				parseFloat(locations[i]['lat']),
				parseFloat(locations[i]['lng'])
			);
			
			var sidebarEntry = createSidebarEntry(i, sidebarContent[i], markersContent[i], locations[i]['lat'], locations[i]['lng']);
			sidebar.appendChild(sidebarEntry);
			
			createOption(name, distance, i);
			createMarker(latlng, locations[i]['lat'], locations[i]['lng'], markersContent[i]);
      		
			bounds.extend(latlng);
       	}
       	
       	if(Store_locator.display_type=='2') $('#sidebar').prepend('<table style="width:100%; padding:5px; margin-top:10px; font-size:12px; border-bottom:1px solid #eeeeee;"><tr><td width="220" style="padding-right:15px;"><b>Store</b></td><td style="padding-right:15px;"><b>Address</b></td><td width="140" style="padding-right:15px;"><b>Phone</b></td><td width="80"><b>Distance</b></td></tr></table>');
       	
       	if(locations.length>0) $('#pagination').html(msg.pagination);
		map.fitBounds(bounds);
	  }
	});
	
	//resizeMap(850,600);
	//$('#streetview').html('').height(0);
}

function createMarker(latlng, lat, lng, html, icon, display_infowindow) {
	if(icon===undefined) {
		var icon = Store_locator.marker_icon;
	}
	var marker = new google.maps.Marker({
		map: map,
		position: latlng,
		icon: icon,
		animation: google.maps.Animation.DROP
	});
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
		if(Store_locator.streetview_display=='1') setStreetView(latlng);
	});
	
	if(display_infowindow=='1') {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	}
	
	markers.push(marker);
}

function createOption(name, distance, num) {
	var option = document.createElement("option");
	option.value = num;
	option.innerHTML = name + "(" + distance.toFixed(1) + ")";
}

function displayStreetView(lat,lng, dom) {
	var latlng = new google.maps.LatLng(lat,lng);
	
	var panoramaOptions = {
	  position: latlng,
	  pov: {
	    heading: 270,
	    pitch: 0,
	    zoom: 1
	  }
	};
	panorama = new google.maps.StreetViewPanorama(document.getElementById(dom),panoramaOptions);
	map.setStreetView(panorama);
}
