<?php

	include("config.php");
	include("classe.class.php");
	
	include("../classes/relatorios.class.php");
	$relatoriosObj = new Relatorios();
	$relatoriosObj->Set("idusuario",$usuario["idusuario"]);
	
	$relatorioObj = new Relatorio();
	$relatorioObj->Set("idusuario",$usuario["idusuario"]);
	$relatorioObj->Set("monitora_onde",1);
	$relatorioObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
	
	if($_POST['acao'] == 'salvar_relatorio') {		
		$relatoriosObj->Set("post",$_POST);		
		$salvar = $relatoriosObj->salvarRelatorio();
		if($salvar['sucesso']){
			$mensagem_sucesso = "salvar_relatorio_sucesso";
		} else {
			$mensagem_erro = $salvar['erro_texto'];
		}
	}
	
	switch ($url[3]) {
				
		case "ajax_cidades":
			($_REQUEST['idestado']) 
			 ? 	
				$relatorioObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome")
			 : 
			 	$relatorioObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
			exit();
			break;
		case "html":
			$relatoriosObj->atualiza_visualizacao_relatorio();
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","asc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","p.nome");
			$relatorioObj->Set("campos","p.*, e.nome as estado, c.nome as cidade");		
			$dadosArray = $relatorioObj->gerarRelatorio();		
			include("idiomas/".$config["idioma_padrao"]."/html.php");
			include("telas/".$config["tela_padrao"]."/html.php");
			break;			
		case "xls":
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","asc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","p.nome");
			$relatorioObj->Set("campos","p.*, e.nome as estado, c.nome as cidade");	
			$dadosArray = $relatorioObj->gerarRelatorio();			
			include("idiomas/".$config["idioma_padrao"]."/xls.php");
			include("telas/".$config["tela_padrao"]."/xls.php");
			break;			
		default:
			include("idiomas/".$config["idioma_padrao"]."/index.php");
			include("telas/".$config["tela_padrao"]."/index.php");
	}
				
	
	
?>	