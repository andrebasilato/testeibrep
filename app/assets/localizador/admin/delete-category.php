<?php
include('../api/include/webzone.php');

$id = $_GET['id'];

$t1 = new Template_class_admin();
$t1->setPageName('Delete a category');
$t1->setMetaTags(array('title'=>'', 'description'=>''));
$t1->displayHeader();

$c1 = new Store_locator_category();
$result = $c1->loadByFields('id',$id);

if(count($result)>0) {
	
	$s1 = new Store_locator();
	$result2 = $s1->loadByFields('category_id',$id);
	
	if(count($result2)>0) {
		echo "You cannot delete this category because it's still containing some stores.<br>";
		echo '<a href="./categories.php">Back to categories list</a>';
	}
	
	else {

		if($_GET['confirm']==1) {
			
			$c1->delete($id);
			
			echo '<script>';
			echo 'window.location="./categories.php";';
			echo '</script>';
			
		}
		else {
			echo '<p>Are you sure you want to delete this category?</p>';
			echo '<a href="?id='.$id.'&confirm=1">Yes, delete this category</a> - <a href="./categories.php">Cancel</a>';
		}
		
	}
}

else {
	echo '<p>No category to delete here !</p>';
	echo '<a href="./categories.php">Categories list</a>';
}

$t1->displayFooter();
?>