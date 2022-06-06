<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php /*
<link href="<?= $config["urlSistema"]; ?>/assets/css/oraculo.css" rel="stylesheet">
<link href="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?= $config["urlSistema"]; ?>/assets/css/menu.css" rel="stylesheet">
<link href="<?= $config["urlSistema"]; ?>/assets/css/jquery_ui_1.8.16.custom.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/plugins/facebox/src/facebox.css" type="text/css" media="screen" />
*/ ?>

<link rel="stylesheet" type="text/css" href="/assets/min/aplicacao.desktop.min.css">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?> </title>
<link rel="shortcut icon" href="/assets/img/favicon.png" type="image/x-icon" />

<meta name="description" content="<?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?>" />
<meta name="keywords" content="Sistema acadêmico" />
<meta name="author" content="Alfama Web" />

<?php
echo $config["script_cabecalho_geral"]; 
echo $config["script_cabecalho_professor"]; 
?>