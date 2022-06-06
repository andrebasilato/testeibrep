<?php
include('../api/include/webzone.php');

$t1 = new Template_class_admin();
$t1->setPageName('Add a new store');
$t1->selectedMenu = 1;
$t1->setMetaTags(array('title'=>'', 'description'=>''));
$t1->displayHeader();

$c1 = new Store_locator_category();
$list = $c1->selectAll(array('order'=>'id DESC'));
for($i=0; $i<count($list); $i++) {
	$list_tab[$list[$i]['id']] = $list[$i]['name'];
}

echo '<div id="geocode_section">';
echo '<form>';
echo 'Please type your full address: <input type="text" id="location2geocode" style="width:360px;">
<br><br><input id="geocode_address_btn" type="submit" value="Geocode and continue">';
echo '</form>';
echo '</div>';

echo '<div id="address_thumbnail" style="margin-bottom:10px;"></div>';

echo '<div id="form_section" style="display:none;">';

//form
$criteria['fields'][] = array('name'=>'category_id', 'title'=>'Category:', 'type'=>'select', 'select_values'=>$list_tab);
$criteria['fields'][] = array('name'=>'name', 'title'=>'Name:');
$criteria['fields'][] = array('name'=>'address', 'title'=>'Address:');
$criteria['fields'][] = array('name'=>'logo', 'title'=>'Logo url:');
$criteria['fields'][] = array('name'=>'url', 'title'=>'URL:');
$criteria['fields'][] = array('name'=>'description', 'title'=>'Description:', 'type'=>'textarea', 'rows'=>'5');
$criteria['fields'][] = array('name'=>'tel', 'title'=>'Tel:');
$criteria['fields'][] = array('name'=>'email', 'title'=>'Email:');
$criteria['fields'][] = array('name'=>'lat', 'title'=>'Latitude:');
$criteria['fields'][] = array('name'=>'lng', 'title'=>'Longitude:');
$criteria['submit'] = array('name'=>'add', 'value'=>'Add store');

echo '<div>';

if($_POST[$criteria['submit']['name']]) {
	
	$values = get_post_values($criteria['fields'], $_POST);
	
	$s1 = new Store_locator();
	$s1->setCategory_id($values['category_id']);
	$s1->setName($values['name']);
	$s1->setAddress($values['address']);
	$s1->setLogo($values['logo']);
	$s1->setUrl($values['url']);
	$s1->setDescription($values['description']);
	$s1->setTel($values['tel']);
	$s1->setEmail($values['email']);
	$s1->setLat($values['lat']);
	$s1->setLng($values['lng']);
	$s1->setCreated(date('Y-m-d H:i:s'));
	$s1->insert();
	
	echo '<script>';
	echo 'window.location="./list.php";';
	echo '</script>';
	
	//echo '<p>You store has been added.</p>';
	//echo '<a href="./list.php">Stores list</a>';
}

else {
	echo '<div style="width:600px;">';
	display_forms($criteria);
	echo '</div>';
}

$t1->displayFooter();
?>