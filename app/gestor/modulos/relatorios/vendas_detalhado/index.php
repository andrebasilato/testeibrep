<?php

include 'config.php';
include 'classe.class.php';
include '../classes/relatorios.class.php';

$relatoriosObj = new Relatorios();
$relatoriosObj->set("idusuario",$usuario["idusuario"]);

$relatorioObj = new Relatorio();
$relatorioObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', 1);

if($_POST['acao'] == 'salvar_relatorio') {
    $salvar = $relatoriosObj->set('post', $_POST)
        ->salvarRelatorio();

    if($salvar['sucesso']){
        $mensagem_sucesso = 'salvar_relatorio_sucesso';
    } else {
        $mensagem_erro = $salvar['erro_texto'];
    }
}


    $relatorioObj->set('config', $config);
    $relatorioObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');

    $camposExtra = null;
    if(empty($_GET['data_cancelamento_de'])){ //Campos adicionados quando a solicitação não vier do RELATÓRIO GERENCIAL DE MOTIVOS DE CANCELAMENTO
        $camposExtra = '
            ,COALESCE(SUM(cvv.valor_por_matricula), SUM(svv.valor_por_matricula), po.valor_por_matricula)  AS escola_valor_por_matricula
            ,co.valor_juros as valor_juros';
    }

    if ($url[3] == 'html' || $url[3] == 'xls') {
        $relatorioObj->set('pagina', 1)
            ->set('ordem', 'DESC')
            ->set('limite', -1)
            ->set('ordem_campo', 'ma.idmatricula')
            ->set(
                'campos',
                'ma.*, inst.nome_abreviado as sindicato, pe.nome as cliente,
                pe.email as emailCli, pe.idcidade, pe.idestado, tu.nome as turma,
                cid.nome as cidade, est.nome as estado,
                ve.nome as vendedor, cu.nome as curso, o.nome as oferta,
                mw.nome as situacao_wf_nome,
                mw.sigla as situacao_wf_sigla,
                po.nome_fantasia as escola,
                sb.nome as solicitante
                '. $camposExtra
                );


        $dadosArray = $relatorioObj->gerarRelatorio();

        // Fazemos os selects para poupar
        $sql = " SELECT idsituacao FROM matriculas_workflow WHERE ativo = 'S' and fim = 'S' ";
        $wf_vendida = $relatorioObj->retornarLinha($sql);

        // Buscamos qual é o workflow que não é inativa e cancelada.
        $sql = " SELECT idsituacao FROM matriculas_workflow WHERE ativo = 'S' and fim <> 'S' and inativa <> 'S' and cancelada <> 'S' ";
        $seleciona = mysql_query($sql);
        $status = array();
        while($linha = mysql_fetch_assoc($seleciona)){
            $wf_statusLiberados[] = $linha["idsituacao"];
        }
    }

/*$linha["de"] = date("m/Y");
$linha["ate"] = date("m/Y");*/

$vendedorPadrao = $relatorioObj->retornarVendedorPadrao();

switch ($url[3]) {
    case "ajax_cursos":
        if ($_REQUEST['idoferta']) {
            $relatorioObj->set("id",intval($_REQUEST['idoferta']));
            $relatorioObj->RetornarCursosOferta();
            exit();
        }
        break;
    case "ajax_solicitantes":
        if ($_REQUEST['bolsa'] && $_REQUEST['bolsa'] != 'N') {
            $relatorioObj->RetornarSolicitantesBolsas();
            exit();
        } else {
            $vazio = array();
            echo json_encode($vazio);
            exit;
        }
        break;
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
