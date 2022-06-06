<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
echo $config["script_cabecalho_vendedor"]; 
?>

<?php if (!empty($GLOBALS['config']['link_chat_cfc'])) { ?>
    <script> window.hiChatCallback = function() { window.Hi.Chat.fillSurvey( "nome=<?php echo $GLOBALS['usuario']['nome']; ?>&email=<?php echo $GLOBALS['usuario']['email']; ?>&telefone=<?php echo $GLOBALS['usuario']['telefone']; ?>&area=parceiros" ); }; </script>
    <script id="hi-chat-script" src="<?php echo $GLOBALS['config']['link_chat_cfc']; ?>"></script>
<?php } ?>
