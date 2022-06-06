<div style="overflow: auto; max-height: 450px; width:780px"> 
	<?php 
	$msg = html_entity_decode(htmlentities(utf8_decode($linha["mensagem"])));
	if ($msg) {
		echo $msg; 
	} else {
		echo $linha["mensagem"]; 
	}
	?> 
</div>