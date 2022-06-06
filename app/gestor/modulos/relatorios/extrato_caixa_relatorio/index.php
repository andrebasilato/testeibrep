<?php

	include("config.php");
	include("classe.class.php");
	
	include("../classes/relatorios.class.php");	
	$relatoriosObj = new Relatorios();
	$relatoriosObj->Set("idusuario",$usuario["idusuario"]);
	
	$relatorioObj = new Relatorio();
	$relatorioObj->Set("idusuario",$usuario["idusuario"]);
	$relatorioObj->Set("monitora_onde",1);
	//$relatorioObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
	
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
				
		case "ajax_contas_correntes":
		if ($_REQUEST['idbanco']) {
	  		$relatorioObj->RetornarContasCorrentesBanco((int) $_REQUEST['idbanco']);
	  		exit();
		}
  		break;
		case "html":
			$relatoriosObj->atualiza_visualizacao_relatorio();
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","desc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","c.tipo");
			$relatorioObj->Set("campos","cc.conta, cc.conta_dig, c.tipo, c.forma_pagamento, sum(c.valor) as valor");	
			$dadosArray = $relatorioObj->gerarRelatorio();		
			include("idiomas/".$config["idioma_padrao"]."/html.php");
			include("telas/".$config["tela_padrao"]."/html.php");
			break;			
		case "xls":
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","desc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","c.tipo");
			$relatorioObj->Set("campos","cc.conta, cc.conta_dig, c.tipo, c.forma_pagamento, b.nome as banco,i.nome_abreviado as sindicato, fc.data_cad as data_cadastro, DATE_FORMAT(data_vencimento,'%d-%m-%Y') as data_vencimento, DATE_FORMAT(data_pagamento,'%d-%m-%Y') as data_pagamento, sum(c.valor) as valor");	
			$dadosArray = $relatorioObj->gerarRelatorio();			
			include("idiomas/".$config["idioma_padrao"]."/xls.php");
			include("telas/".$config["tela_padrao"]."/xls.php");
			break;			
		default:
			include("idiomas/".$config["idioma_padrao"]."/index.php");
			include("telas/".$config["tela_padrao"]."/index.php");
	}
	
?>	