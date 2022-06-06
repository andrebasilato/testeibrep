<?php

function getAPICallUrl($criteria) {
	$url = $GLOBALS['api_url'].'?key='.$GLOBALS['api_key'];
	foreach ($criteria as $i => $v) {
		$url .= '&'.$i.'='.$v;
	}
	return $url;
}

function get_locations($criteria) {
	$lat = $criteria['lat'];
	$lng = $criteria['lng'];
	$address = $criteria['address'];
	$page_number = $criteria['page_number'];
	$nb_display = $criteria['nb_display'];
	$category_id = $criteria['category_id'];
	$max_distance = $criteria['max_distance'];
	$distance_unit = $criteria['distance_unit'];
	
	//API call
	$apiCriteria['feed'] = 'stores';
	$apiCriteria['page_number'] = $page_number;
	$apiCriteria['nb_display'] = $nb_display;
	$apiCriteria['address'] = urlencode($address);
	$apiCriteria['lat'] = $lat;
	$apiCriteria['lng'] = $lng;
	$apiCriteria['distance_unit'] = $distance_unit;
	$apiCriteria['max_distance'] = $max_distance;
	$apiCriteria['category_id'] = $category_id;
	$url = getAPICallUrl($apiCriteria);
	
	//Get data from API
	$data = getDataFromUrl($url);
	$data = json_decode($data, true);
	
	return $data;
}

function get_categories() {
	
	//API call
	$apiCriteria['feed'] = 'categories';
	$url = getAPICallUrl($apiCriteria);
	
	//Get data from API
	$data = getDataFromUrl($url);
	$data = json_decode($data, true);
	
	return $data;
}

?>