<footer>
	<div class="container-fluid">
    	<div class="row-fluid">
            <div class="pull-right">
            <a href="http://www.alfamaoraculo.com.br/" target="_new" title="Alfama Oráculo" style="padding:5px;"><img src="/assets/img/logo_pequena.png" width="135" height="50" alt="Alfama Oráculo" /></a> 
            </div>
        </div>
    </div>
</footer>
<script src="<?= $config["urlSistema"]; ?>/assets/js/jquery.1.7.1.min.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/plugins/facebox/src/facebox.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/js/jquery.maskMoney.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/js/validation.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/js/jquery.maskedinput_1.3.js"></script>

<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-transition.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-modal.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-dropdown.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-scrollspy.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-tab.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-popover.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-button.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-collapse.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-carousel.js"></script>
<script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-typeahead.js"></script>

<script src="<?= $config["urlSistema"]; ?>/assets/js/oraculo.js"></script>
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
	

});
</script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
<?php 
// usado no log do php
echo bl_debug(_debug); 
echo $config["script_rodape_geral"]; 
echo $config["script_rodape_professor"]; 
?> 