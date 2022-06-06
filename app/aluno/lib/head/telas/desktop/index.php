<?php
// CORS HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!--<meta name="viewport" content="width=device-width"> Site sem responsive -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- site com responsivo -->

<!-- Core Meta Data -->
<meta name="author" content="AlfamaWeb">

<!-- Humans -->
<link rel="author" href="humans.txt" />

<!-- Favicon -->
<link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/assets/img/favicon.png" type="image/png">

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/assets/min/aplicacao.aluno.min.css">

<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<!--[if IE 7]>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css">
<![endif]-->
<style> a.dt-style-button {
    font-family: 'Lato','Helvetica', sans-serif!important;
    font-size: 18px!important;
    text-decoration: none!important;
    border-radius: 6px!important;
    background-image: url('https://ibb.co/JnvzPwW') !important;
    color: #ffffff!important;
    font-weight: 400!important;
    text-align: center!important;
    cursor: pointer!important;
    line-height: 1.33!important;
    display: inline-block!important;
    min-width: 35px!important;
}
img.background {
    width:130px;
    height:130px;
}
</style>
<script type="text/javascript">
var cont = 0;
function mudaPosicao(){
    //CONTAINER
    $('#hi-chat-container').hide();
    $('#hi-chat-container').attr("class","BottomLeft None chatMinimized");
    $('#hi-chat-container').show();
}
</script>
<script src="/assets/aluno_novo/js/jquery-1.10.2.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-1.9.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery.cycle2.min.js"></script>
<script src="/assets/aluno_novo/js/vendor/modernizr-2.6.2.min.js"></script>
<a href=" <?php echo $config["link_whatsapp"]; ?>" style="position:fixed;width:60px;height:60px;bottom:40px;right:40px;background-color:#25d366;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 2px 2px 3px #999;z-index:100;" target="_blank">
<svg style="margin-top:16px;" xmlns="http://www.w3.org/2000/svg" width="24" fill="white" height="24" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
</a>
<!-- Styles -->
