<?php
include('../api/include/webzone.php');

$t1 = new Template_class_admin();
$t1->setPageName('Home');
$t1->selectedMenu = 0;
$t1->setMetaTags(array('title'=>'', 'description'=>''));
$t1->displayHeader();

$s1 = new Store_locator();
$result = $s1->selectAll();
$nb_stores = count($result);

echo '<p>There is <a href="./list.php"><b>'.$nb_stores.' store(s)</b></a> in the database.</b> <a href="./add.php">Add a new one?</a></p><br>';

?>

This is a simple but powerfull interface enabling you to manage all your stores:<br>
- You can add new stores, edit and delete existing ones.<br>
- You can manage stores categories.<br>
- The store address you set is automatically geocoded and stored in the database.<br>
- This backend is automatically controlling the front end display of the store locator.<br>

<?php

$s1 = new Store_locator();

$t1->displayFooter();
?>