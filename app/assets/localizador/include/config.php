<?php
/*
START Mandatory settings
*/

//App URL (no slash at the end). Ex: http://yougapi.com/products/advanced_store_locator
$GLOBALS['app_url'] = 'http://construtor.alfamaintra.com.br/assets/localizador';

// API full link (not to be modified)
$GLOBALS['api_url'] = $GLOBALS['app_url'].'/api/';

/*
END Mandatory settings
*/


/*
START Optional settings
*/

//Number of stores to display per page
$GLOBALS['nb_display'] = '5';

//Distance unit. Possible values: miles, km
$GLOBALS['distance_unit'] = 'km';

//Activate or no the categories filters. Possible values: 0 or 1
$GLOBALS['categories_filter'] = '1';

//Set a max distance around the searched address - Leave emty to not apply the filter
$GLOBALS['max_distance'] = '50';

//Display or no the max distance select bow filter. Possible values: 0 or 1
$GLOBALS['max_distance_filter'] = '1';

//Custom icon to use as a marker - Leave empty to use the default Google Maps icon
$GLOBALS['marker_icon'] = '';

//Custom marker for the current user location
$GLOBALS['marker_icon_current'] = '/include/graph/icons/marker-current.png';

//Autodetect user location or no. Possible values: 0 or 1
$GLOBALS['autodetect_location'] = '1';

//activate the streetview display or no. Possible values: 0 or 1.
$GLOBALS['streetview_display'] = '1';

//activate the get directions links in the markers infowindow. Possible values: 0 or 1.
$GLOBALS['get_directions_display'] = '1';

/*
END Optional settings
*/

/*
START (Not to be modified)
*/
if($GLOBALS['marker_icon']!='') $GLOBALS['marker_icon'] = $GLOBALS['app_url'].$GLOBALS['marker_icon'];
if($GLOBALS['marker_icon_current']!='') $GLOBALS['marker_icon_current'] = $GLOBALS['app_url'].$GLOBALS['marker_icon_current'];
/*
END
*/

?>