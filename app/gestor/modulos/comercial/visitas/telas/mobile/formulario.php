<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<style>.hidden{margin-top:-75px;}</style>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
<? incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <? if($url[4] == "editar") { ?>
            <li class="active"><?php echo $linha["nome"]; ?></li>
        <? } else { ?>
            <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } ?>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
        <div class="box-conteudo">
            <? if($_POST["msg"]) { ?>
                <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                </div>
            <? } ?>
            <? if(count($salvar["erros"]) > 0){ ?>
                <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                      <strong><?= $idioma["form_erros"]; ?></strong>
                          <?php foreach($salvar["erros"] as $ind => $val) { ?>
                              <br />
                              <?php echo $idioma[$val]; ?>
                          <?php } ?>
                      </strong>
                    </div>
            <? } ?>
            <form method="post" onsubmit="return validateFields(this, regras)" class="form-horizontal">
                <input name="acao" type="hidden" value="salvar" />
                <? if($url[4] == "editar") {
                    echo '<input type="hidden" name="'.$config["banco"]["primaria"].'" id="'.$config["banco"]["primaria"].'" value="'.$linha[$config["banco"]["primaria"]].'" />';
                    echo '<input type="hidden" name="idpessoa" id="idpessoa" value="'.$linha["idpessoa"].'" />';
                    foreach($config["banco"]["campos_unicos"] as $campoid => $campo) {
                    ?>
                        <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
                    <?
                    }

                        $linhaObj->GerarFormulario("formulario_editar",$linha,$idioma);

                    } else {
                        $linhaObj->GerarFormulario("formulario",$_POST,$idioma);
                    }
                ?>

                <div class="control-group">
                    <label class="control-label" for="idlocal"></label>
                    <div class="controls">
                         <div id="map-canvas" style="display:none; margin-top: 25px;width: 700px; height: 350px"></div>
                    </div>
                </div>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>

    <script type="text/javascript">
        var geocoder;
        var map;
        var infowindow = new google.maps.InfoWindow();
        var marker;

        function initialize(position) {
            geocoder = new google.maps.Geocoder();
            var mapOptions = {
                zoom: 10,
                panControl: true,
                zoomControl: true,
                scaleControl: true
            };

            map = new google.maps.Map(
                document.getElementById('map-canvas'),
                mapOptions
            );

            var pos = new google.maps.LatLng(
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
                        $('#geolocalizacao_endereco').val(endereco_completo);
                        $('#geolocalizacao_cep').val(cep);
						$('#geolocalizacao_cidade').val(cidade);
						$('#geolocalizacao_estado').val(estado);
                    } else {
                        alert('Nenhum resultado foi encontrado.');
                        $("#geolocalizacao_cep").prop("readonly",false);
                        $("#geolocalizacao_endereco").prop("readonly",false);
                    }
                  } else {
                    alert('Geocoder failed due to: ' + status);
                    $("#geolocalizacao_cep").prop("readonly",false);
                    $("#geolocalizacao_endereco").prop("readonly",false);
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

            if ('' === document.getElementById('geolocation').value) {
                 if (navigator.geolocation) {
                    currentPosition = navigator.geolocation
                        .getCurrentPosition(function(position) {

                        document.getElementById('geolocation').value =
                            position.coords.latitude
                            + ','
                            + position.coords.longitude;

                            setTimeout(function(){
                                initialize(position);
                            }, 1000);
                    });
                }
            } else {
                pos = document.getElementById('geolocation').value;
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
            }
        }


        createMap();
    </script>
    <style type="text/css">
    #map-canvas img{
        max-width: inherit !important;
    }
    </style>
    <div class="form-actions">
                    <p class="help-block"><?php echo $idioma["ajuda_formulario"]; ?></p>
                    <br />
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
        </div>
            </form>
        </div>
    </div>
  </div>
<? incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>

<script src="/assets/js/ajax.js"></script>

<script type="text/javascript">
    var regras = new Array();
    <?php
    foreach($config["formulario"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
                    if($campo["mascara"]){ ?>
				<?php if($campo["mascara"] == "99/99/9999") { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
					$('#<?= $campo["id"]; ?>').change(function() {
						if($('#<?= $campo["id"]; ?>').val() != '') {
							valordata = $("#<?= $campo["id"]; ?>").val();
							date= valordata;
							ardt= new Array;
							ExpReg= new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
							ardt=date.split("/");
							erro=false;
							if ( date.search(ExpReg)==-1){
								erro = true;
							}
							else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
								erro = true;
							else if ( ardt[1]==2) {
								if ((ardt[0]>28)&&((ardt[2]%4)!=0))
									erro = true;
								if ((ardt[0]>29)&&((ardt[2]%4)==0))
									erro = true;
							}
							if (erro) {
								alert("\"" + valordata + "\" não é uma data válida!!!");
								$('#<?= $campo["id"]; ?>').focus();
								$("#<?= $campo["id"]; ?>").val('');
								return false;
							}
							return true;
						}
					});
				<?php } elseif($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") { ?>
					$('#<?= $campo["id"]; ?>').focusout(function(){
						var phone, element;
						element = $(this);
						element.unmask();
						phone = element.val().replace(/\D/g, '');
						if(phone.length > 10) {
							element.mask("(99) 99999-999?9");
						} else {
							element.mask("(99) 9999-9999?9");
						}
					}).trigger('focusout');
				<?php } else { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
				<?php } ?>
            <? 
            }
                    if($campo["datepicker"]){ ?>
                        $( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
                    <?
                    }
                    if($campo["numerico"]){ ?>
                        $("#<?= $campo["id"]; ?>").keypress(isNumber);
                        $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
                    <?
                    }
                    if($campo["decimal"]){ ?>
                        $("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});
                    <?
                    }

            if(is_array($campo["validacao"])){
                    foreach($campo["validacao"] as $tipo => $mensagem) {
                      if($campo["tipo"] == "file"){
    ?>
                        regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
    <?                }else{ ?>
                        regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
    <?
                      }
                    }
                }

            if($campo["json"]){ ?>
                        $('#<?=$campo["json_idpai"];?>').change(function(){
                            if($(this).val()){
                                $.getJSON('<?=$campo["json_url"];?>',{<?=$campo["json_idpai"];?>: $(this).val(), ajax: 'true'}, function(json){
                                    var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';
                                    for (var i = 0; i < json.length; i++) {
                                        var selected = '';
                                        if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                                            var selected = 'selected';
                                        options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                                    }
                                    $('#<?=$campo["id"];?>').html(options);
                                });
                            } else {
                                $('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
                            }
                        });

                        $.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>', function(json){//if(json == null)json = 0;
                            var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
                            for (var i = 0; i < json.length; i++) {
                                var selected = '';
                                if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                                    var selected = 'selected';
                                options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                            }
                            $('#<?=$campo["id"];?>').html(options);
                        });
                        <?
                    }

        }
    }
    ?>

</script>

<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#map-canvas').toggle('fadeIn');
    createMap();
    $("#form_cursos").fcbkcomplete({
      width: 600,
      width_options: 600,
      width_dialog: 600,
      json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/cursos/" + document.getElementById('idvendedor').value,
      addontab: true,
      height: 10,
      maxshownitems: 10,
      cache: true,
      maxitems: 10,
      input_min_size: 1,
      filter_selected: true,
      firstselected: true,
      complete_text: "<?= $idioma["mensagem_select"]; ?>",
      addoncomma: true,
    });
    $("#form_cursos").hide();

    <?php if(count($cursos_associados)) {
        foreach($cursos_associados as $curso) { ?>
            $("#form_cursos").trigger("addItem",[{"title": "<?=$curso["nome"]?>", "value": "<?=$curso["idcurso"]?>"}]);
    <?php } } ?>

    $("#idvendedor").change(function() {
        $("#form_cursos").trigger("destroy");
        $("#form_cursos").fcbkcomplete({
          width: 600,
          width_options: 600,
          width_dialog: 600,
          json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/cursos/" + document.getElementById('idvendedor').value,
          addontab: true,
          height: 10,
          maxshownitems: 10,
          cache: true,
          maxitems: 10,
          input_min_size: 1,
          filter_selected: true,
          firstselected: true,
          complete_text: "<?= $idioma["mensagem_select"]; ?>",
          addoncomma: true,
        });
    });
  });
</script>

</div>
</body>
</html>
