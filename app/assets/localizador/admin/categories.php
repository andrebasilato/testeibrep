<?php
include('../api/include/webzone.php');

$t1 = new Template_class_admin();
$t1->setPageName('Categories');
$t1->selectedMenu = 3;
$t1->setMetaTags(array('title'=>'', 'description'=>''));
$t1->displayHeader();

if(isset($_POST['add'])) {
	$s1 = new Store_locator_category();
	$s1->setName($_POST['name']);
	$s1->insert();
	echo '<script>';
	echo 'window.location = "./categories.php";';
	//echo 'location.reload(true);';
	echo '</script>';
}

echo '<p style="padding-bottom:5px;"><b>Add a new category</b></p>';
echo '<form method="post">';
echo '<p style="padding-bottom:5px;"><input type="text" name="name" style="width:320px;"></p>';
echo '<p style="padding-bottom:5px;"><input type="submit" name="add" value="&nbsp;Add category&nbsp;"></p>';
echo '</form>';

$s1 = new Store_locator_category();

$sql = 'SELECT category_id, count(*) nb FROM '.$GLOBALS['db_table_name'].' GROUP BY category_id';
$result = $s1->customQuery($sql);
for($i=0; $i<count($result); $i++) {
	$nb_stores_by_cat[$result[$i]['category_id']] = $result[$i]['nb'];
}

$list = $s1->selectAll(array('order'=>'id DESC'));

echo '<br><br>';

echo '<p style="padding-bottom:15px;"><b>List</b></p>';

for($i=0; $i<count($list); $i++) {
	echo '<div style="padding-bottom:10px;">';
	echo ''.$list[$i]['name'].'';
	if($nb_stores_by_cat[$list[$i]['id']]=='') echo ' <small>(0)</small>';
	else echo ' <small>('.$nb_stores_by_cat[$list[$i]['id']].')</small>';
	
	echo '<p style="float:right;">';
	echo '<a href="./delete-category.php?id='.$list[$i]['id'].'">Delete</a></p>';
	echo '</div>';
	echo '<hr>';
}

if(count($list)==0) echo 'No categories has been added yet.';

$t1->displayFooter();
?>