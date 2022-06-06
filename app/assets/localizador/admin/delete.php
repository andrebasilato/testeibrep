<?php
include('../api/include/webzone.php');

$id = $_GET['id'];

$t1 = new Template_class_admin();
$t1->setPageName('Delete a store');
$t1->setMetaTags(array('title'=>'', 'description'=>''));
$t1->displayHeader();

$s1 = new Store_locator();
$store = $s1->loadByFields('id',$id);

if(count($store)>0) {
	
	if($_GET['confirm']==1) {
		
		$s1->delete($id);
		
		echo '<script>';
		echo 'window.location="./list.php";';
		echo '</script>';
		
		//echo '<p>The store has been deleted</p>';
		//echo '<a href="./list.php">Stores list</a>';
	}
	else {
		echo '<p>Are you sure you want to delete this store?</p>';
		echo '<a href="?id='.$id.'&confirm=1">Yes, delete this store</a> - <a href="./list.php">Cancel</a>';
	}
}

else {
	echo '<p>No store to delete here !</p>';
	echo '<a href="./list.php">Stores list</a>';
}

$t1->displayFooter();
?>