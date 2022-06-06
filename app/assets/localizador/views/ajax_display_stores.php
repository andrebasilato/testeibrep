<?php
$criteria = stripslashes($_GET['criteria']);
$criteria = json_decode($criteria, true);

$lat = $criteria['lat'];
$lng = $criteria['lng'];
$category_id = $criteria['category_id'];
$page_number = $criteria['page_number'];
$nb_display = $criteria['nb_display'];
$address = $criteria['address'];
$max_distance = $criteria['max_distance'];
$display_type = $criteria['display_type'];

$distance_unit = $GLOBALS['distance_unit'];

if($max_distance=='') $max_distance = $GLOBALS['max_distance'];

if($page_number=='') $page_number=1;
if($nb_display=='') $nb_display=20;

$criteria2 = array('lat'=>$lat, 'lng'=>$lng, 'address'=>$address, 'category_id'=>$category_id, 'page_number'=>$page_number, 'nb_display'=>$nb_display, 
'max_distance'=>$max_distance, 'distance_unit'=>$distance_unit);

$locations = get_locations($criteria2);
$nb_locations = count($locations['list']);
$nb_stores = $locations['nb_stores'];

if($lat==''&&$lng==''&&$address=='') {
	$no_distance_display=1;
}

$data['locations'] = $locations['list'];
$data['pagination'] = display_pagination(array('page_number'=>$page_number, 'nb_display'=>$nb_display, 'nb_stores'=>$nb_stores));
$data['markersContent'] = displayMarkersContent($locations['list']);
$data['sidebarContent'] = displaySidebarContent($locations['list'], array('no_distance_display'=>$no_distance_display, 'display_type'=>$display_type));

$data = json_encode($data);
echo $data;

?>