<?php
if($url[5] == "nacionalidade") {
  echo $linhaObj->RetornarPaises();
} elseif($url[5] == "desativar_login") {
  echo $linhaObj->AtivarLogin($_POST["ativo_login"]);
} elseif($url[5] == "resetar_senha") {
  echo $linhaObj->ResetarSenha($_POST["confirmacao"], $_POST["enviar_email"], $_POST["exibir_nova_senha"]);
}elseif($url[5] == "associar_cursos") {
	echo $linhaObj->BuscarCurso();
}elseif($url[5] == "associar_avas") {
	echo $linhaObj->BuscarAva();
}elseif($url[5] == "associar_ofertas") {
	echo $linhaObj->BuscarOferta();
}elseif($url[5] == "associar_disciplinas") {
	echo $linhaObj->BuscarDisciplina();
}
?>