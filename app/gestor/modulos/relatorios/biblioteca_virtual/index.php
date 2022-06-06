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

	$config['situacoesArray'] = array();
	$sql = "SELECT idsituacao, nome, cor_nome, cor_bg 
			FROM matriculas_workflow WHERE ativo='S'";
	$seleciona = mysql_query($sql);
	while($situacao = mysql_fetch_assoc($seleciona)) {
	   $config['situacoesArray'][$situacao['idsituacao']] = $situacao;
	}
	
	//$relatorioObj->Set("config",$config);
	
	
	if($url[3] == "html" || $url[3] == "xls"){
		$relatorioObj->Set("pagina",1);
		$relatorioObj->Set("ordem","desc");
		$relatorioObj->Set("limite",-1);
		$relatorioObj->Set("ordem_campo","ma.idmatricula");
		$relatorioObj->Set("campos","ma.*, 
		pe.nome, 
		pe.documento,
		pe.email,
		DATE_ADD( data_matricula, INTERVAL 6 MONTH ) as validade");		
		$dadosArray = $relatorioObj->gerarRelatorio();	

		// Fazemos os selects para poupar
        $sql = " SELECT idsituacao 
        		FROM matriculas_workflow 
        		WHERE ativo = 'S' AND fim = 'S' ";
        $wf_vendida = $relatorioObj->retornarLinha($sql);

        // Buscamos qual é o workflow que não é inativa e cancelada.
        $sql = " SELECT idsituacao 
        		FROM matriculas_workflow 
        		WHERE ativo = 'S' AND 
        			fim <> 'S' AND 
        			inativa <> 'S' AND 
        			cancelada <> 'S' ";
        $seleciona = mysql_query($sql);
        $status = array();
        while($linha = mysql_fetch_assoc($seleciona)){
            $wf_statusLiberados[] = $linha["idsituacao"];
        }
		
	}
	
	switch ($url[3]) {
				
		case "ajax_sindicatos":	
			if ($_REQUEST['idmantenedora']) {
				$relatorioObj->Set("id",(int)$_REQUEST['idmantenedora']);
				$relatorioObj->RetornarSindicatosMantenedoras();
				exit();
			}		
		break;
		case "ajax_cursos":	
			if ($_REQUEST['idoferta']) {
				$relatorioObj->Set("id",(int)$_REQUEST['idoferta']);
				$relatorioObj->RetornarCursosOferta();
				exit();
			}
		case "ajax_turmas":	
			if ($_REQUEST['idoferta']) {
				$relatorioObj->Set("id",(int)$_REQUEST['idoferta']);
				$relatorioObj->RetornarTurmasOferta();
				exit();
			}		
		break;
		case "html":
			$relatoriosObj->atualiza_visualizacao_relatorio();
			include("idiomas/".$config["idioma_padrao"]."/html.php");
			include("telas/".$config["tela_padrao"]."/html.php");
		break;			
		case "xls":		
			include("idiomas/".$config["idioma_padrao"]."/xls.php");
			include("telas/".$config["tela_padrao"]."/xls.php");
		break;			
		default:
			include("idiomas/".$config["idioma_padrao"]."/index.php");
			include("telas/".$config["tela_padrao"]."/index.php");
	}
	
?>	