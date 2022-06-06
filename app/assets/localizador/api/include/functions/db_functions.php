<?php

function get_stores_list($criteria=array()) {
	$lat = $criteria['lat'];
	$lng = $criteria['lng'];
	$page_number = $criteria['page_number'];
	$nb_display = $criteria['nb_display'];
	$distance_unit = $criteria['distance_unit'];
	$max_distance = $criteria['max_distance'];
	$category_id = $criteria['category_id'];
	
	$table_name = $GLOBALS['db_table_name'];
	$start = ($page_number*$nb_display)-$nb_display;
	
	$s1 = new Store_locator();
	
	if($distance_unit=='miles') $distance_unit='3959'; //miles
	else $distance_unit='6371'; //km
	
	$sql = "SELECT *, 
	( $distance_unit * acos( cos( radians('".$s1->escape($lat)."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$s1->escape($lng)."') ) + sin( radians('".$s1->escape($lat)."') ) * sin( radians( lat ) ) ) ) AS distance 
	FROM ".$table_name." 
	WHERE 1";
	
	if($category_id!='') $sql .= " AND category_id='".$s1->escape($category_id)."'";
	if($max_distance!='') $sql .= " HAVING distance<='".$s1->escape($max_distance)."'";
	
	if($lat!=''&&$lng!='') $sql .= " ORDER BY distance";
	else $sql .= " ORDER BY id DESC";
	
	$sql .= " LIMIT $start, $nb_display";
	
	$locations = $s1->customQuery($sql);
	
	return $locations;
}

function get_nb_stores($criteria=array()) {
	$lat = $criteria['lat'];
	$lng = $criteria['lng'];
	$distance_unit = $criteria['distance_unit'];
	$max_distance = $criteria['max_distance'];
	$category_id = $criteria['category_id'];
	
	$table_name = $GLOBALS['db_table_name'];
	$s1 = new Store_locator();
	
	if($distance_unit=='miles') $distance_unit='3959'; //miles
	else $distance_unit='6371'; //km
	
	if($max_distance!='') {
		
		$sql = "SELECT *, 
		( $distance_unit * acos( cos( radians('".$s1->escape($lat)."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$s1->escape($lng)."') ) + sin( radians('".$s1->escape($lat)."') ) * sin( radians( lat ) ) ) ) AS distance 
		FROM ".$table_name." 
		WHERE 1";
		
		if($category_id!='') $sql .= " AND category_id='".$s1->escape($category_id)."'";
		if($max_distance!='') $sql .= " HAVING distance<='".$s1->escape($max_distance)."'";
		
		$locations = $s1->customQuery($sql);
		
		return count($locations);
	}
	
	else {
		$sql = "SELECT count(*) nb";
		$sql .= " FROM ".$table_name." WHERE 1";
		if($category_id!='') $sql .= " AND category_id='".$s1->escape($category_id)."'";
		$locations = $s1->customQuery($sql);
		return $locations[0]['nb'];
	}
	
}

function get_store($id) {
	$table_name = $GLOBALS['db_table_name'];
	$s1 = new Store_locator();
	$sql = "SELECT s.*, c.name category_name FROM ".$table_name." s 
	LEFT JOIN ".$GLOBALS['db_table_name_category']." c
	ON s.category_id=c.id
	WHERE s.id='".$s1->escape($id)."'";
	$store = $s1->customQuery($sql);
	if($store[0]['category_name']==null) $store[0]['category_name']='';
	return $store;
}

function get_categories($id) {
	$c1 = new Store_locator_category();
	$categories = $c1->selectAll(array('order'=>'name'));
	
	$sql = 'SELECT category_id, count(*) nb FROM '.$GLOBALS['db_table_name'].' GROUP BY category_id';
	$result = $c1->customQuery($sql);
	for($i=0; $i<count($result); $i++) {
		$nb_stores_by_cat[$result[$i]['category_id']] = $result[$i]['nb'];
	}
	
	for($i=0; $i<count($categories); $i++) {
		if($nb_stores_by_cat[$categories[$i]['id']]=='') $categories[$i]['nb'] = 0;
		else $categories[$i]['nb'] = $nb_stores_by_cat[$categories[$i]['id']];
	}
	
	return $categories;
}

?>