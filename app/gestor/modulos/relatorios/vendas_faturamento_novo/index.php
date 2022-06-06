<?php
	//echo pow(0.5, -1);exit;
	include("config.php");
	include("classe.class.php");
	
	include("../classes/relatorios.class.php");
	$relatoriosObj = new Relatorios();
	$relatoriosObj->Set("idusuario",$usuario["idusuario"]);
	
	$relatorioObj = new Relatorio();
	$relatorioObj->Set("idusuario",$usuario["idusuario"]);
	$relatorioObj->Set("monitora_onde",1);
	$relatorioObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
	
	if ($_POST['acao'] == 'salvar_relatorio') {		
		$relatoriosObj->Set("post",$_POST);		
		$salvar = $relatoriosObj->salvarRelatorio();
		if($salvar['sucesso']) {
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
		
		case "ajax_cursos":	
			if ($_REQUEST['idsindicato']) {
				$relatorioObj->Set("id",(int)$_REQUEST['idsindicato']);
				$relatorioObj->RetornarCursosSindicato();
				exit();
			}
		
		case "html":
			$relatoriosObj->atualiza_visualizacao_relatorio();
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","asc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","p.nome");
			$relatorioObj->Set("campos","p.*, e.nome as estado, c.nome as cidade");		
			$dadosArray = $relatorioObj->gerarRelatorio();

            require_once '../classes/phplot/phplot.php';
            $plot = new PHPlot();

            $pastaRelatorios = $_SERVER['DOCUMENT_ROOT']."/storage/relatorios_gerenciais";

            if (!is_dir($pastaRelatorios))
                mkdir($pastaRelatorios, 0777);

            $raiz = "../storage/relatorios_gerenciais";
            apagar_recursividade($raiz, $raiz);
            
            include 'telas/' . $config['tela_padrao'] . '/graficos_faturamento.php';
            gerarGraficoEstadosMatriculas($dadosArray);
            gerarRelatorioEstatosFaturamento($dadosArray);
            gerarGraficoMatriculasMetas($dadosArray);
            gerarGraficoFaturamentoMetas($dadosArray);
            gerarGraficoAcumuladoMatriculas($dadosArray);
            gerarGraficoAcumuladoFaturamento($dadosArray);
            gerarGraficoMatriculasRelacaoAMeta($dadosArray);
            gerarGraficoFaturamentoRelacaoAMeta($dadosArray);

			include("idiomas/".$config["idioma_padrao"]."/html.php");
			include("telas/".$config["tela_padrao"]."/html.php");
			break;
		case "pdf":
			$relatoriosObj->atualiza_visualizacao_relatorio();
			$relatorioObj->Set("pagina",1);
			$relatorioObj->Set("ordem","asc");
			$relatorioObj->Set("limite",-1);
			$relatorioObj->Set("ordem_campo","p.nome");
			$relatorioObj->Set("campos","p.*, e.nome as estado, c.nome as cidade");		
			$dadosArray = $relatorioObj->gerarRelatorio();

            require_once DIR_APP . '/classes/phplot/phplot.php';

            include 'telas/' . $config['tela_padrao'] . '/grafico_estados_matriculas.php';
            include 'telas/' . $config['tela_padrao'] . '/grafico_estados_faturamento.php';
            include 'telas/' . $config['tela_padrao'] . '/grafico_matriculas_metas.php';
            include 'telas/' . $config['tela_padrao'] . '/grafico_faturamento_metas.php';
            include 'telas/' . $config['tela_padrao'] . '/grafico_acumulado_matriculas.php';
            include 'telas/' . $config['tela_padrao'] . '/grafico_acumulado_faturamento.php';
            include 'telas/' . $config['tela_padrao'] . '/grafico_matriculas_relacao_meta.php';
            include 'telas/' . $config['tela_padrao'] . '/grafico_faturamento_relacao_meta.php';
			
            include DIR_APP . "/assets/plugins/MPDF54/mpdf.php";

            $marginLeft = $marginRight = $marginHeader = $marginFooter = 1;
			$mpdf = new mPDF('P', 'A4', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');

            ob_start();

            include 'idiomas/' . $config['idioma_padrao'] . '/html.php';
            include 'telas/' . $config['tela_padrao'] . '/relatorio_gerencial_pdf.php';

            $saida = ob_get_contents();
            ob_end_clean();

            $pastaRelatorios = DIR_APP . "/storage/relatorios_gerenciais";

            $arquivo_nome = $pastaRelatorios."/relatorio_gerencial_".$_GET['ano']."_".$_GET['mes']."_".$_GET['dia'].".pdf";

            $mpdf->simpleTables = true;
            $mpdf->packTableData = true;
            set_time_limit(120);
            $mpdf->WriteHTML($saida);

            $mpdf->Output($arquivo_nome);

            header("Content-type: " . filetype($arquivo_nome));
            header('Content-Disposition: attachment; filename="' . basename($arquivo_nome) . '"');
            header('Content-Length: ' . filesize($arquivo_nome));
            header('Expires: 0');
            header('Pragma: no-cache');
            readfile($arquivo_nome);

            exit;
			break;				
		default:
			include("idiomas/".$config["idioma_padrao"]."/index.php");
			include("telas/".$config["tela_padrao"]."/index.php");
	}
	
?>	