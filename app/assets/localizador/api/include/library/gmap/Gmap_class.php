<?php

class Gmap_class {

	function getStaticMap($lat,$lng,$width='400',$height='325',$title='') {
		$url = 'http://maps.google.com/maps/api/staticmap?center='.$lat.','.$lng.'&zoom=15&size='.$width.'x'.$height.'&markers=color:red|label:P|'.$lat.','.$lng.'&sensor=false';
		return $url;
	}
	
	//receives: lat,lng (ex: 37.422782,-122.085099)
	function reverseGeoCode($latLng) {
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latLng&sensor=true";
		sleep(1);
		$results = file_get_contents($url);
		$results = json_decode($results,1);
		
		$addressTab['formatted_address'] = $results['results'][0]['formatted_address'];
		
		for($i=0;$i<8;$i++) {
			$tmp = $results['results'][0]['address_components'][$i]['types'][0];
			if($tmp=='street_number') $addressTab['street_number'] = $results['results'][0]['address_components'][$i]['long_name'];
			if($tmp=='route') $addressTab['street_name'] = $results['results'][0]['address_components'][$i]['long_name'];
			if($tmp=='locality') $addressTab['city'] = $results['results'][0]['address_components'][$i]['long_name'];
			if($tmp=='administrative_area_level_1') $addressTab['state'] = $results['results'][0]['address_components'][$i]['long_name'];
			if($tmp=='administrative_area_level_2') $addressTab['region'] = $results['results'][0]['address_components'][$i]['long_name'];
			if($tmp=='country') $addressTab['country'] = $results['results'][0]['address_components'][$i]['long_name'];
			if($tmp=='postal_code') $addressTab['zip'] = $results['results'][0]['address_components'][$i]['long_name'];
		}
		return $addressTab;
	}
	
	//receives: 1600 Amphitheatre Parkway Mountain View, CA
	function geoCode($address) {
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=true";
		sleep(1);
		$results = file_get_contents($url);
		$results = json_decode($results,1);
		$geoCode['lat'] = $results['results'][0]['geometry']['location']['lat'];
		$geoCode['lng'] = $results['results'][0]['geometry']['location']['lng'];
		return $geoCode;
	}
	
}

?>