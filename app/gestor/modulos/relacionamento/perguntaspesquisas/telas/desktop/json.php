<?php
	if($url[5] == "ativar_desativar") {
		echo $linhaObj->ativarDesativar($_POST["idpergunta"], $_POST["idopcao"]);
	}	

?>