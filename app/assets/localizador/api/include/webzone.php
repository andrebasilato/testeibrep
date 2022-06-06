<?php
include_once('config.php');
include_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['app_base_path'].'include/functions/db_functions.php');
include_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['app_base_path'].'include/functions/Forms.php');
include_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['app_base_path'].'include/db/db_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['app_base_path'].'include/library/gmap/Gmap_class.php');

//Template
include_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['app_base_path'].'include/templates/Template_engine.php');
include_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['app_base_path'].'include/templates/Template_seo.php');
include_once($_SERVER['DOCUMENT_ROOT'].$GLOBALS['app_base_path'].'include/templates/admin/Template_class.php');

?>