<?php
include_once('include/webzone.php');

$address = $_GET['address'];
$category_id = $_GET['category_id'];
$max_distance = $_GET['max_distance'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Alfama Oráculo</title>
	<script type='text/javascript'> 
	var Store_locator = {
		ajaxurl: "<?=$GLOBALS['app_url']?>", nb_display: <?=$GLOBALS['nb_display']?>, "marker_icon":"<?=$GLOBALS['marker_icon']?>",
		"marker_icon_current":"<?=$GLOBALS['marker_icon_current']?>", "autodetect_location":"<?=$GLOBALS['autodetect_location']?>",
		"streetview_display":<?=$GLOBALS['streetview_display']?>,
		"display_type":"2"
	};
	</script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	<script src="include/js/json2.js" type="text/javascript"></script>
	<script src="include/js/script.js" type="text/javascript"></script>
	<script>
	$(document).ready(function() {
		init_locations();
		$('#address').focus();
	})
	</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
	body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
    </style>
</head>

	<body>
    <div class="container">
      <div style="width:100%; border:1px solid #dcdcdc;">
        <div style="padding:10px;">
          <div id="current_location"></div>
          <div id="map" style="overflow: hidden; width:100%; height:400px"></div>
          <div style="width:100%;">
            <div id="sidebar" style="overflow: auto;"></div>
            <div id="pagination" style="padding:5px; border-top:1px solid #dcdcdc;"></div>
            <span id="pagination_loading"></span> </div>
        </div>
      </div></div>
</body>
</html>