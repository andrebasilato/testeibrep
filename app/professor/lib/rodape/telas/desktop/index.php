<footer>
	<div class="container-fluid">
    	<div class="row-fluid">
            <div class="pull-right">
            <a href="http://www.alfamaoraculo.com.br/" target="_new" title="Alfama Oráculo" style="padding:5px;"><img src="/assets/img/logo_pequena.png" width="135" height="50" alt="Alfama Oráculo" /></a> 
            </div>
        </div>
    </div>
</footer>

<script src="/assets/min/aplicacao.desktop.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-61Q7DH8YXN"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-61Q7DH8YXN');
</script>

<script>
$(document).ready(function(){ 
	
	$("span[rel*=tooltip]").tooltip({
		// live: true
    });
	
	$("a[rel*=tooltip]").tooltip({
		// live: true
    });
	
	$("button[rel*=tooltip]").tooltip({
		// live: true
    });
	
	$('a[rel*=facebox]').facebox();

	<?php
	/*
		Função colocada por Manzano
		Para que, o link do menu que tem submenu não funcione no Android.
		No tablet fica muito ruim de trabalhar.
	*/
	?>
	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");	
	$( ".drop" ).click(function() {
		console.log('Menu -> Clicou');
		if(isAndroid) {
			console.log('É Android, retorna falso.');
			return false;
		} else {
			console.log('Não é Android, retorna true.');
			return true;
		}
	});

});
</script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
<?php 
// usado no log do php
echo bl_debug(_debug); 
echo $config["script_rodape_geral"]; 
echo $config["script_rodape_professor"]; 
?> 