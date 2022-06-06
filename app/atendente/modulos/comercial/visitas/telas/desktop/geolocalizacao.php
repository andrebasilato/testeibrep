  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["geolocalizacao"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linha["idvisita"]; ?></strong></li>
  	</ul>
        <?php //print_r2($linha,true) ?>
      <ul class="nav nav-tabs nav-stacked">
      		<li>
            <div id="mapa" style="height: 300px; width: 500px">
            </div>
          </li>
          <li>
            <?php echo $idioma['nome'].'<strong>'.$linha['nome'].'</strong>'; ?></br>
            <?php echo $idioma['email'].'<strong>'.$linha['email'].'</strong>'; ?></br>
            <?php if ($linha['telefone']) { echo $idioma['telefone'].'<strong>'.$linha['telefone'].'</strong>'; }?>
          </li>
      </ul>    
  </section>
<script type="text/javascript">

        var geocoder;   
        var map;
        var infowindow = new google.maps.InfoWindow();
        var marker;
        var pos;

        function initialize(position) {
            geocoder = new google.maps.Geocoder();
            var mapOptions = {
                zoom: 10,
                panControl: true,
                zoomControl: true,
                scaleControl: true
            };

            map = new google.maps.Map(
                document.getElementById('mapa'),
                mapOptions
            );

            pos = new google.maps.LatLng(
                position.coords.latitude,
                position.coords.longitude
            );

            infowindow = new google.maps.InfoWindow({
                map: map,
                position: pos
            });

            marker = new google.maps.Marker({
                position: pos,
                map: map,
                animation: google.maps.Animation.BOUNCE,
                draggable: false
            });

            map.setCenter(pos);
            codeLatLng(pos);
        }

        function codeLatLng(latlng) {
            geocoder.geocode({'latLng': latlng}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        //Monta endereço
                        var numero = extractFromAdress(results[0].address_components, "street_number", "long_name");
                        var rua = extractFromAdress(results[0].address_components, "route", "long_name");
                        var estado = extractFromAdress(results[0].address_components, "administrative_area_level_1", "short_name");
                        var bairro = extractFromAdress(results[0].address_components, "political", "long_name");
                        var cidade = extractFromAdress(results[0].address_components, "locality", "long_name");
                        var pais = extractFromAdress(results[0].address_components, "country", "long_name");
                        var cep = extractFromAdress(results[0].address_components, "postal_code", "long_name");
                        var endereco_completo = numero+ ', ' +rua+', '+ bairro + ', ' + cidade + ' - ' + estado + ', ' + 
                                                cep + ', ' + pais;
                        marker.setPosition(latlng);
                        infowindow.setContent(endereco_completo);
                        infowindow.open(map, marker);
                    } else {
                        alert('Nenhum resultado foi encontrado.');
                    }
                  } else {
                    alert('Geocoder failed due to: ' + status);
                  }
                }
            );
        }

        function extractFromAdress(components, type, tamanho){
            for (var i=0; i<components.length; i++)
                for (var j=0; j<components[i].types.length; j++)
                    if (components[i].types[j]==type) 
                        if (tamanho == 'short_name')
                            return components[i].short_name;
                        else
                            return components[i].long_name;
            return "";
        }

        function createMap() {
            <?php if ($linha['geolocation'] != '') { ?>
                pos = "<?= $linha['geolocation'] ?>";
                pos = pos.split(',');

                position = {
                    "coords": {
                        "latitude": pos[0],
                        "longitude": pos[1]
                    }
                };

                setTimeout(function(){
                    initialize(position);
                }, 1000);
            <? } ?>
        }
    createMap();

</script>
