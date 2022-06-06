<div id="footer">
	<div class="content">
		<p><?php echo $idioma['ambiente_ensino']; ?></p>
		<a target="_blank" href="/aluno"><img src="/assets/aluno_novo/img/marca_mini.png" alt="Marca"></a>
	</div>
</div>

<!-- 
Google gmap api
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"> </script>
<script>
// http://itouchmap.com/latlong.html
// http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html
     
function initializeMapa() {
    var styles = [
        {
            "featureType": "water",
            "stylers": [
                { "hue": "#ff00ee" },
                { "saturation": 90 }
            ]
        },{
            "featureType": "road",
            "stylers": [
                { "hue": "#aa00ff" },
                { "saturation": 72 }
            ]
        },{
            "featureType": "landscape",
            "elementType": "geometry.fill",
            "stylers": [
                { "hue": "#ff3300" },
                { "saturation": -100 },
                { "lightness": -75 }
            ]
        }
    ];
                    
    var mapOptions = {
        zoom: 14, // o zoom vai de 0 a 20 
        scrollwheel:false, // zoom via scroll
        center: new google.maps.LatLng(-10.987743,-37.051321),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions); // map-canvas é um id da div.
    // map.setOptions({styles: styles}); //ativar o stilo

    var image = '/assets/aluno_novo/img/icon_do_mapa.png'; // 55 x 80
    var myLatLng = new google.maps.LatLng(-10.987743,-37.051321);
    var beachMarker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image
    });
}
</script>
-->

<script src="/assets/aluno_novo/js/jquery-1.10.2.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-1.9.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery.cycle2.min.js"></script>
<script src="/assets/aluno_novo/js/prefixfree.min.js"></script>
<script src="/assets/aluno_novo/js/respond.min.js"></script>
<script src="/assets/aluno_novo/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/aluno_novo/js/main.js"></script>
<script src="/assets/aluno_novo/js/svgcheckbx.js"></script>

<!--<script src="rjminimize.php?name=jstotal.js&type=js&path=_js_scripts.php"></script>-->
<!--<script src="/assets/aluno_novo/js/respond.min.js"></script>-->
<!--<script src="/assets/aluno_novo/js/plugins.js"></script>-->

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	g.src='//www.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));

    <?php //Desabilita o botão direito do mouse do usuário?>
    $(document).bind("contextmenu",function(e){
        return false;
    });
</script>
        