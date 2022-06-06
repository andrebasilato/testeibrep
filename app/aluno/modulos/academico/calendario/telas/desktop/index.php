<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Conteudo -->
<div class="content">
    <div class="box-bg">
        <span class="top-box box-amarelo">
            <h1><?php echo $idioma['titulo_pagina']; ?></h1>
            <i class="icon-calendar"></i>            
        </span>
        <h2 class="ball-icon">&bull;</h2>
        <div class="clear"></div>
        <!-- Calendário --> 
        <div class="row-fluid">
            <div class="span12 abox box-item extra-align">
                <div class="row-fluid">
                    <div class="span6">
                        <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" method="get" id="form_filtro">
                            <input name="mes" id="mes" type="hidden" value="<?php echo $_GET['mes']; ?>" />
                            <input name="ano" id="ano" type="hidden" value="<?php echo $_GET['ano']; ?>" />
                            <div class="pagination">
                                <ul>
                                    <?php for($i = -1; $i < 2; $i++) { ?>
                                        <li <?php if($_GET['ano'] == ($_GET['ano'] + $i)) { echo 'class="mes-select"'; } ?>><a href="javascript:calendario('ano','<?php echo ($_GET['ano'] + $i); ?>');"><?php echo ($_GET['ano'] + $i);?></a></li>
                                    <?php } ?>
                                </ul>
                                 <ul style="float:right; margin-right:27px;">
                                        <li><a href="/<?= $url[0]; ?>/relatorios/relatorio_chat"><?=$idioma['imprimir']?></a></li>
                                </ul>
                            </div>
                            <div class="pagination">
                                <ul>
                                    <?php 
									for($i = 1; $i < 13; $i++) { 
										$i = str_pad($i, 2, "0", STR_PAD_LEFT);
										?>
                                        <li <?php if($_GET['mes'] == $i){ echo 'class="mes-select"'; } ?>><a href="javascript:calendario('mes','<?php echo $i; ?>');"><?php echo $meses_min_idioma[$config['idioma_padrao']][$i]; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </form>
                        <div>
                            <?php echo $calendarioObj->gerarCalendarioAluno($idioma, $_GET['mes'], $_GET['ano']); ?>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="box-gray" id="conteudo">
                            <div class="alert alert-info text-center"><?php echo $idioma['clique_data']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <!-- Calendário --> 
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript">
function calendario(tipo, valor) {
	document.getElementById(tipo).value = valor;
	document.getElementById('form_filtro').submit();
}

$(document).ready(function() {	
	$('a[rel*=tooltip]').tooltip({html:true});
	
	$('.conteudo').click(function(e) {
		e.preventDefault();
		$('.show-test').show();
		var url = $(this).attr('data-link');
		$.get(url, function(data) {
			$('#conteudo').html(data);
		});
	});
});



</script>
</body>
</html>