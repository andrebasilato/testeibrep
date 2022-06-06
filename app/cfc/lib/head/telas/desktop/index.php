<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="/assets/min/aplicacao.desktop.min.css">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?> </title>
<link rel="shortcut icon" href="/assets/img/favicon.png" type="image/x-icon" />


<meta name="description" content="<?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?>" />
<meta name="keywords" content="Sistema Online de Inteligencia Imobiliária" />
<meta name="author" content="Alfama Web" />

<link rel="apple-touch-icon-precomposed" href="/assets/mobile/Icone_57x57.png"/>
<link rel="apple-touch-icon" href="/assets/mobile/Icone_57x57.png">
<link rel="apple-touch-icon" sizes="72x72" href="/assets/mobile/Icone_72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="/assets/mobile/Icone_114x114.png">
<link rel="apple-touch-startup-image" href="/assets/mobile/startup.jpg" /> 
<link rel="apple-touch-startup-image" media="(max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2)" href="/assets/mobile/startup@2x.jpg" />

<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" /> 
<meta name="viewport" content = "width = device-width, initial-scale = 1, minimum-scale = 1, maximum-scale = 1" /> 

<style>
.logo {
	display:block;
	margin-top:5px;
	margin-bottom:15px;
	margin-left: 30px;
	height:50px;
	width:135px;
	background:transparent url(<?php echo URL_LOGO_PEGUENA; ?>) 0 0 no-repeat;
	background-size: cover;
}
</style>
<a href=" <?php echo $config["link_whatsapp_cfc"]; ?> " style="position:fixed;width:60px;height:60px;bottom:40px;right:40px;background-color:#25d366;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 2px 2px 3px #999;z-index:100;" target="_blank">
<svg style="margin-top:16px;" xmlns="http://www.w3.org/2000/svg" width="24" fill="white" height="24" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
</a>
<!-- 
<script type="text/javascript"> 
	(function(){ 
		var node = document.createElement("script"); 
		node.setAttribute("type", "text/javascript"); 
		node.setAttribute("src", "https://app.bugmuncher.com/js/bugMuncher.min.js"); 
		document.getElementsByTagName("head")[0].appendChild(node); 
	})(); 

	var bugmuncher_options = {
		language:'en',
		position:'right',
		show_intro:false,
		show_preview:true,
		label_text:'Feedback',
		api_key:'3d49baa3345b706e04907b5dc6806b8a4eafbce9'
	};
    -->