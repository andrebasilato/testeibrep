<?php
	$linhaObj->Set("get", $_GET);
	$linhaObj->Set("id", $url[3]);
	switch($url[5]){
		case "associar_pergunta":
		   echo $linhaObj->BuscarPergunta();
		   break;
		case "associar_pessoa":
		   echo $linhaObj->BuscarPessoa();
		   break;
		case "subassunto":
		   echo $linhaObj->RetornarJSON("atendimentos_assuntos_subassuntos", intval($_GET['idassunto']), "idassunto", "idsubassunto, nome", "ORDER BY nome");   
		   break;
		case "cidades":
		   echo $linhaObj->RetornarJSON("cidades", intval($_GET['idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
		   break;
		case "cursos":
		   echo $linhaObj->RetornarCursos(intval($_GET["idoferta"]), true);
		   break;
		case "escolas":
		   echo $linhaObj->RetornarCursos(intval($_GET["idoferta"]), true);
		   break;
		case "alterar_situacao":
			echo $linhaObj->AlterarSituacao($_POST["alterar_situacao"]);
			break;
		case "opcaochecklist":
		   echo $linhaObj->RetornarJSON("checklists_opcoes", intval($_GET['idchecklist']), "idchecklist", "idopcao, nome", "ORDER BY nome");   
		   break;

	}
?>