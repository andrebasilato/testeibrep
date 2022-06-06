<?php

function display_search($criteria) {
	$address = $criteria['address'];
	$category_id = $criteria['category_id'];
	$max_distance = $criteria['max_distance'];
	
	echo '<form method="GET">';
		
		echo '<input type="text" id="address" name="address" value="'.$address.'" style="width:410px; margin-right:5px;" />';
		
		//display the categories filter
		if($GLOBALS['categories_filter']=='1') {
			
			$categories = get_categories();
			
			echo '&nbsp;&nbsp;<select id="category_id" name="category_id">';
			echo '<option value=""></option>';
			for($i=0; $i<count($categories); $i++) {
				if($categories[$i]['id']==$category_id) echo '<option selected value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].' ('.$categories[$i]['nb'].')</option>';
				else echo '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].' ('.$categories[$i]['nb'].')</option>';
			}
			echo '</select>';
		}
		
		if($GLOBALS['max_distance_filter']=='1') {
			
			$distance_tab = array('50'=>'50 '.$GLOBALS['distance_unit'], '100'=>'100 '.$GLOBALS['distance_unit'], '500'=>'500 '.$GLOBALS['distance_unit']);
			
			echo '&nbsp;&nbsp;<select id="max_distance" name="max_distance">';
			echo '<option value=""></option>';
			foreach($distance_tab as $ind=>$value) {
				if($ind==$max_distance) echo '<option selected value="'.$ind.'">'.$value.'</option>';
				else echo '<option value="'.$ind.'">'.$value.'</option>';
			}
			echo '</select>';
		}
		
		echo '<input type="submit" value="Search" />';
		
	echo '</form>';
}

function displaySidebarContent($locations, $criteria) {
	$no_distance_display = $criteria['no_distance_display'];
	$display_type = $criteria['display_type'];
	
	if($display_type==2) {
		$markers = displaySidebarContent_type2($locations, $criteria);
	}
	
	else {
		for($i=0; $i<count($locations);$i++) {
			$name = $locations[$i]['name'];
			$logo = $locations[$i]['logo'];
			$url = $locations[$i]['url'];
			$address = $locations[$i]['address'];
			$tel = $locations[$i]['tel'];
			$email = $locations[$i]['email'];
			$category_id = $locations[$i]['category_id'];
			$distance = round($locations[$i]['distance'],1);
			
			$markers[$i] = '<div style="padding:5px; margin-bottom:10px; overflow:hidden;">';
				
				if($logo!='') {
					$markers[$i] .= '<img src="'.$logo.'" style="padding-right:5px;" align="left"> ';
				}
				
				if($url=='') $markers[$i] .= '<b>'.$name.'</b>';
				else $markers[$i] .= '<b>'.$name.'</b>';
				
				$markers[$i] .= '<br>'.$address.'';
				
				if($no_distance_display!=1) $markers[$i] .= ' (<font color="red"><b>'.$distance.' '.$GLOBALS['distance_unit'].'</b></font>)';
				
			$markers[$i] .= '</div>';
		}
	}
	
	return $markers;
}

function displaySidebarContent_type2($locations, $criteria) {
	$no_distance_display = $criteria['no_distance_display'];
	$display_type = $criteria['display_type'];
	
	for($i=0; $i<count($locations);$i++) {
		$name = $locations[$i]['name'];
		$logo = $locations[$i]['logo'];
		$url = $locations[$i]['url'];
		$address = $locations[$i]['address'];
		$tel = $locations[$i]['tel'];
		$email = $locations[$i]['email'];
		$category_id = $locations[$i]['category_id'];
		$distance = round($locations[$i]['distance'],1);
		
		$markers[$i] .= '<table width="100%" style="font-size:12px; padding:5px; margin-top:10px; margin-bottom:10px;">';
		
		$markers[$i] .= '<tr>';
		
			$markers[$i] .= '<td width="220" style="padding-right:15px; vertical-align:top;">';
			
				if($logo!='') {
					$markers[$i] .= '<img src="'.$logo.'" style="padding-right:5px;" align="left"> ';
				}
				$markers[$i] .= $name;
			
			$markers[$i] .= '</td>';
			
			$markers[$i] .= '<td style="padding-right:15px; vertical-align:top;">'.$address.'</td>';
			
			$markers[$i] .= '<td width="140" style="padding-right:15px; vertical-align:top;">'.$tel.'</td>';
			
			$markers[$i] .= '<td width="80" style="vertical-align:top;">';
			if($no_distance_display!=1) $markers[$i] .= ''.$distance.' '.$GLOBALS['distance_unit'].'';
			else $markers[$i] .= 'N/A';
			$markers[$i] .= '</td>';
		
		$markers[$i] .= '</tr>';

		$markers[$i] .= '</table>';
	}
	
	return $markers;
}

function displayMarkersContent($locations) {
	for($i=0; $i<count($locations);$i++) {
		$name = $locations[$i]['name'];
		$logo = $locations[$i]['logo'];
		$url = $locations[$i]['url'];
		$address = $locations[$i]['address'];
		$tel = $locations[$i]['tel'];
		$email = $locations[$i]['email'];
		$category_id = $locations[$i]['category_id'];
		$distance = round($locations[$i]['distance'],1);
		
		$markers[$i] = '';
		$markers[$i] .= '<div class="map_infowindow" style="width:380px;">';
		
		if($logo!='') {
			//$markers[$i] .= '<img src="'.$logo.'" style="margin-bottom:5px;" width=80><br>';
		}
		
		if($url!='') $markers[$i] .= '<a href="'.$url.'" target="_blank"><b>'.$name.'</b></a>';
		else $markers[$i] .= '<b>'.$name.'</b>';
		
		$markers[$i] .= '<br/>'.$address.'';
		
		if($tel!='') $markers[$i] .= '<br/>Tel: '.$tel.'';
		if($email!='') $markers[$i] .= '<br/>'.$email.'';
		
		if($GLOBALS['streetview_display']==1) $markers[$i] .= '<br/><a href="#" id="displayStreetView">Street View</a>';
		
		if($GLOBALS['get_directions_display']=='1') {
			
			$address = str_replace('<br />', ' ', $address);
			
			$markers[$i] .= '<br/>Get directions: 
			<a href="http://maps.google.com/maps?f=d&z=13&daddr='.urlencode($address).'" target="_blank">To here</a> - 
			<a href="http://maps.google.com/maps?f=d&z=13&saddr='.urlencode($address).'" target="_blank">From here</a>';
		}
		
		$markers[$i] .= '<div>';
	}
	return $markers;
}

function display_pagination($criteria) {
    $page_number = $criteria['page_number'];
    $nb_display = $criteria['nb_display'];
    $nb_stores = $criteria['nb_stores'];

    $pagination = '<div style="padding-top:10px; width:100%;">';

    if($page_number==1 && $nb_stores>($page_number*$nb_display)) {
        $pagination .= '<a href="#" id="store_locator_next">Next</a>';
    }
    else if($page_number>1) {
        $pagination .= '<a href="#" id="store_locator_previous">Previous</a> - ';
        $pagination .= '<b>'.$page_number.'</b>';
        if(($page_number*$nb_display) < $nb_stores) $pagination .= ' - <a href="#" id="store_locator_next">Next</a>';
    }

    $pagination .= '</div>';
    return $pagination;
}

?>