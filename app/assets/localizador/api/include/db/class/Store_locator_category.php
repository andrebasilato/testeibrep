<?php

class Store_locator_category extends MySqlTable
{

var $id;
var $name;

function Store_locator_category() {
	parent::MySqlTable($GLOBALS['db_table_name_category']);
}


function loadIntoArray() {
	$array = array();
	$array["id"] = $this->id;
	$array["name"] = $this->name;
	return $array;
}


// ##### SET PUBLIC METHODS ##### //

function setId($id) {
	$this->id = $id;
}
function setName($name) {
	$this->name = $name;
}

} // end of class

?>