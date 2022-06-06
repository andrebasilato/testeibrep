<footer>
	<div class="container-fluid">
    	<div class="row-fluid">
            <div class="pull-right">
            <a href="http://www.alfamaoraculo.com.br/" target="_new" title="Or&aacute;culo"><img src="/assets/img/logo_pequena.png" width="135" height="50" alt="Or&aacute;culo" /></a> 
            </div>
        </div>
    </div>
</footer>
<script src="/assets/min/aplicacao.desktop.min.js"></script>
<script src="/assets/plugins/select2/js/select2.min.js"></script>
<script>
$(document).ready(function(){ 

    $(window).scroll(function(){
        if ($(window).scrollTop() > 70) {            
			$('#nomeAlfamaConstrutor').fadeOut('slow', function() {
			  $("#menu-topbar").fadeIn('slow');
			});
        }else{
			$('#menu-topbar').fadeOut('fast', function() {
			  $("#nomeAlfamaConstrutor").fadeIn('fast');
			});		
        }
    });
	
	$("span[rel*=tooltip]").tooltip({
		// live: true
    });
	
	$("a[rel*=confirmaSaida]").click(function() {
	  var confirma = confirm('<?= $idioma['confirma_saida']; ?>');
	  if(confirma) return true;
		else return false;
	});	
	
	$("a[rel*=tooltip]").tooltip({
		// live: true
    });
	
	$("button[rel*=tooltip]").tooltip({
		// live: true
    });

	$("img[rel*=tooltip]").tooltip({
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
jQuery(document).ready(function($) {
		$('.select2').select2({
			placeholder: ''
		}
			
  		);

		$(".idcurso").prop("disabled", true);
		$('#idoferta').change(function(){
			$(".idcurso").prop("disabled", false);
		});

	});

</script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
<?php 
// usado no log do php
//echo bl_debug(_debug); 
?> 