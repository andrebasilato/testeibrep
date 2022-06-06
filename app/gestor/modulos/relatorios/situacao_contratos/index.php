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
		case "html":
			$relatoriosObj->atualiza_visualizacao_relatorio();
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","asc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","p.nome");
			$relatorioObj->Set("campos","m.idmatricula,m.numero_contrato contrato,cursos.nome as curso,p.nome as nome_aluno,p.email as email_aluno,v.nome as vendedor,mw.nome situacao,m.data_matricula as data_matricula,DATEDIFF(curdate(), m.data_matricula) dias_matricula, DATE_FORMAT(mc.assinado,'%d-%m-%Y %T') as assinado, DATE_FORMAT(mc.validado,'%d-%m-%Y %T') as validado, DATE_FORMAT(mc.assinado_devedor,'%d-%m-%Y %T') as devedor");		
			$dadosArray = $relatorioObj->gerarRelatorio();		
			include("idiomas/".$config["idioma_padrao"]."/html.php");
			include("telas/".$config["tela_padrao"]."/html.php");
			break;			
		case "xls":
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","asc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","p.nome");
			$relatorioObj->Set("campos","m.idmatricula,m.numero_contrato contrato,cursos.nome as curso,p.nome as nome_aluno,p.email as email_aluno,v.nome as vendedor,mw.nome situacao,m.data_matricula as data_matricula,DATEDIFF(curdate(), m.data_matricula) dias_matricula, DATE_FORMAT(mc.assinado,'%d-%m-%Y %T') as assinado, DATE_FORMAT(mc.validado,'%d-%m-%Y %T') as validado");		
			$dadosArray = $relatorioObj->gerarRelatorio();			
			include("idiomas/".$config["idioma_padrao"]."/xls.php");
			include("telas/".$config["tela_padrao"]."/xls.php");
			break;			
		default:
			include("idiomas/".$config["idioma_padrao"]."/index.php");
			include("telas/".$config["tela_padrao"]."/index.php");
	}
				
	
	
?>	