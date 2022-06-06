<?php

class Store_locator extends MySqlTable
{

var $id;
var $user_id;
var $post_id;
var $category_id;
var $name;
var $logo;
var $address;
var $lat;
var $lng;
var $url;
var $description;
var $tel;
var $email;
var $created;

function Store_locator() {
	parent::MySqlTable($GLOBALS['db_table_name']);
}

function loadIntoArray() {
	$array = array();
	$array["id"] = $this->id;
	$array["user_id"] = $this->user_id;
	$array["post_id"] = $this->post_id;
	$array["category_id"] = $this->category_id;
	$array["name"] = $this->name;
	$array["logo"] = $this->logo;
	$array["address"] = $this->address;
	$array["lat"] = $this->lat;
	$array["lng"] = $this->lng;
	$array["url"] = $this->url;
	$array["description"] = $this->description;
	$array["tel"] = $this->tel;
	$array["email"] = $this->email;
	$array["created"] = $this->created;
	return $array;
}


// ##### SET PUBLIC METHODS ##### //

function setId($id) {
	$this->id = $id;
}
function setUser_id($user_id) {
	$this->user_id = $user_id;
}
function setPost_id($post_id) {
	$this->post_id = $post_id;
}
function setCategory_id($category_id) {
	$this->category_id = $category_id;
}
function setName($name) {
	$this->name = $name;
}
function setLogo($logo) {
	$this->logo = $logo;
}
function setAddress($address) {
	$this->address = $address;
}
function setLat($lat) {
	$this->lat = $lat;
}
function setLng($lng) {
	$this->lng = $lng;
}
function setUrl($url) {
	$this->url = $url;
}
function setDescription($description) {
	$this->description = $description;
}
function setTel($tel) {
	$this->tel = $tel;
}
function setEmail($email) {
	$this->email = $email;
}
function setCreated($created) {
	$this->created = $created;
}

} // end of class

?>