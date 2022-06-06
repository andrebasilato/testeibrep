<?php

	include("config.php");
	include("classe.class.php");
	
	include("../classes/relatorios.class.php");
	$relatoriosObj = new Relatorios();
	$relatoriosObj->Set("idusuario",$usuario["idusuario"]);
	
	$relatorioObj = new Relatorio();
	$relatorioObj->Set("idusuario",$usuario["idusuario"]);
	$relatorioObj->Set("monitora_onde",1);
	$situacoes["pt_br"] = $relatorioObj->retornaSituacoes();
	
	$relatorioObj->Set("config",$config);
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
	
	if($url[3] == "html" || $url[3] == "xls"){
		$relatorioObj->Set("pagina",1);
		$relatorioObj->Set("ordem","asc");
		$relatorioObj->Set("limite",-1);
		$relatorioObj->Set("ordem_campo","ate.idatendimento");
		$relatorioObj->Set("campos","ate.*, p.nome as cliente, aw.nome as situacao_atendimento, aa.nome as assunto, asub.nome as subassunto, usu.nome as usuario, ah.data_cad as datacad_situacao");		
		$dadosArray = $relatorioObj->gerarRelatorio();
	}
	
	switch ($url[3]) {
		case "html":
			$relatoriosObj->atualiza_visualizacao_relatorio();
			include("idiomas/".$config["idioma_padrao"]."/html.php");
			include("telas/".$config["tela_padrao"]."/html.php");
			break;			
		case "xls":		
			include("idiomas/".$config["idioma_padrao"]."/xls.php");
			include("telas/".$config["tela_padrao"]."/xls.php");
			break;
		case "script":		
			include("idiomas/".$config["idioma_padrao"]."/script.php");
			include("telas/".$config["tela_padrao"]."/script.php");
			break;	
		case "json": 
			include("telas/".$config["tela_padrao"]."/json.php");
			break;
		default:
			include("idiomas/".$config["idioma_padrao"]."/index.php");
			include("telas/".$config["tela_padrao"]."/index.php");
	}
	
?>	