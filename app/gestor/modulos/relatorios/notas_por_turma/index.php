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
		$relatorioObj->Set("ordem_campo","pe.nome, mn.data_cad");
		$relatorioObj->Set("campos",'mn.idmatricula_nota,
									pe.nome as aluno, 
									mnt.nome as tipo,
									mp.nome as modelo,
									mn.nota,
									cbd.nota_conceito');

		$dadosArray = $relatorioObj->gerarRelatorio();
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
			}
			$relatorioObj->RetornarCursosOferta();
			exit();
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