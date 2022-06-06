<br />
<div class='section section-small'>
	<a href="?classico=true" style="text-decoration:none;">
	<div class='section-footer'>
		<font style="color:#555; text-transform:uppercase;">Acessar vers&atilde;o desktop</font>
	</div>
	</a>
</div>

<footer>
	<div class="container-fluid">
    	<div class="row-fluid" style="padding-top: 10px;">
            <div class="pull-right">
            <a href="http://www.alfamaoraculo.com.br/" target="_new" title="Alfama Oráculo" style="padding:5px;"><img src="/assets/img/logo_escudo.png" width="66" height="56" alt="Alfama Oráculo" /></a> 
          </div>        
        	<?= $informacoes["nome"]; ?><br />
			<?= $informacoes["email"]; ?> <br />
            <a href="?opLogin=sair">Sair</a>
        </div>
    </div>
</footer>

<script src="/assets/min/aplicacao.mobile.min.js"></script>

<script>
$(document).ready(function(){ 

	
	$("a[rel*=confirmaSaida]").click(function() {
	  var confirma = confirm('<?= $idioma['confirma_saida']; ?>');
	  if(confirma) return true;
		else return false;
	});	

	$('a[rel*=facebox]').facebox();

	

});
</script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
<?php 
// usado no log do php
//echo bl_debug(_debug); 
?> 