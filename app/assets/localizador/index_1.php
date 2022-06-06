<?php
include_once('include/webzone.php');

$address = $_GET['address'];
$category_id = $_GET['category_id'];
$max_distance = $_GET['max_distance'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Advanced Store Locator - Yougapi Technology</title>
    
    <link rel="stylesheet" href="include/css/blueprint/grid.css" />
    <link rel="stylesheet" href="include/css/style.css" />
    
	<script type='text/javascript'> 
	var Store_locator = {
		ajaxurl: "<?=$GLOBALS['app_url']?>", nb_display: <?=$GLOBALS['nb_display']?>, "marker_icon":"<?=$GLOBALS['marker_icon']?>",
		"marker_icon_current":"<?=$GLOBALS['marker_icon_current']?>", "autodetect_location":"<?=$GLOBALS['autodetect_location']?>",
		"streetview_display":<?=$GLOBALS['streetview_display']?>,
		"display_type":""
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
	
</head>

<body>

<div class="container">
	<br>
	<a href="http://codecanyon.net/user/yougapi/portfolio"><img src="./include/graph/advanced-store-locator-mini.png" align="left" style="margin-right:30px;"></a>
	<h1 style="margin-bottom:5px;">Advanced Store Locator</h1>
	<br>
	<br>
</div>

<div class="container">
	
	<div style="width:100%; border:1px solid #dcdcdc; margin-bottom:20px;">
		<div style="padding:10px;">
			Type any city name, address or zip code to find the closest location:</br>
			<?php
			display_search(array('address'=>$address, 'category_id'=>$category_id, 'max_distance'=>$max_distance));
			?>
		</div>
	</div>
	
	<div style="width:100%; border:1px solid #dcdcdc;">
		<div style="padding:10px;">
			<div id="current_location"></div>
			<div style="float:left; width:340px;">
				<div id="sidebar" style="overflow: auto;"></div>
				<div id="pagination" style="padding:5px; border-top:1px solid #dcdcdc;"></div>
				<span id="pagination_loading"></span>
			</div>
			
			<div style="margin-left:350px;">
			    <div id="map" style="overflow: hidden; width:580px; height:460px"></div>
			    <div id="streetview" style="overflow: hidden;"></div>
			</div>
		</div>
	</div>

	<br><br><hr>
	Powered by <a href="http://yougapi.com">Yougapi Technology</a>
	
</div>

</body>
</html>