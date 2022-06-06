<?php
include('../api/include/webzone.php');

$category_id = $_GET['category_id'];

$t1 = new Template_class_admin();
$t1->setPageName('Stores list');
$t1->selectedMenu = 2;
$t1->setMetaTags(array('title'=>'', 'description'=>''));
$t1->displayHeader();

//prepare categories for select list
$c1 = new Store_locator_category();
$list = $c1->selectAll(array('order'=>'id DESC'));
for($i=0; $i<count($list); $i++) {
	$list_tab[$list[$i]['id']] = $list[$i]['name'];
}

$s1 = new Store_locator();
$sql = 'SELECT s.*, c.name category_name 
FROM '.$GLOBALS['db_table_name'].' s
LEFT JOIN '.$GLOBALS['db_table_name_category'].' c
on s.category_id=c.id
WHERE 1';
if($category_id!='') $sql .= ' AND s.category_id="'.$s1->escape($category_id).'"';
$sql .= ' ORDER BY s.id DESC';
$list = $s1->customQuery($sql);

//categories filter
if(count($list_tab)>0) {
	echo '<form name="form" method="get">';
		echo '<p style="padding-bottom:5px; text-align:right;">';
		echo 'Filter by category: ';
		echo '<select name="category_id" onchange="form.submit();">';
		echo '<option value=""></option>';
		foreach($list_tab as $ind=>$value) {
			if($ind==$category_id) echo '<option value="'.$ind.'" selected>'.$value.'</option>';
			else echo '<option value="'.$ind.'">'.$value.'</option>';
		}
		echo '</select></p>';
	echo '</form><br>';
}

for($i=0; $i<count($list); $i++) {
	$lat = $list[$i]['lat'];
	$lng = $list[$i]['lng'];
	$address = $list[$i]['address'];
	$category_name = $list[$i]['category_name'];
	
	echo '<div style="padding-bottom:10px;">';
	echo '<b>'.$list[$i]['name'].'</b>';
	if($category_name!='') echo ' <small>(<font color="#b71414">'.$category_name.'</font>)</small>';
	if($address!='') echo '<br><small>Address: '.$address.' <font color="blue">(Lat: '.$lat.', Lng: '.$lng.')</font></small>';
	echo '<p style="float:right;">';
	echo '<a href="./edit.php?id='.$list[$i]['id'].'">Edit</a> - ';
	echo '<a href="./delete.php?id='.$list[$i]['id'].'">Delete</a></p>';
	echo '</div>';
	echo '<hr>';
}

if(count($list)==0) echo 'No store have been found.';

$t1->displayFooter();
?>