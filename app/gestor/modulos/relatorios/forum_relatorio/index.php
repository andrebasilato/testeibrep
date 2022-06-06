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

$config['situacoesArray'] = array();
$sql = "SELECT idsituacao, nome, cor_nome, cor_bg FROM matriculas_workflow WHERE ativo='S'";
$seleciona = mysql_query($sql);
while($situacao = mysql_fetch_assoc($seleciona)) {
   $config['situacoesArray'][$situacao['idsituacao']] = $situacao;
}
    $relatorioObj->set('config', $config);
    $relatorioObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');

    if ($url[3] == 'html' || $url[3] == 'xls') {
        $relatorioObj->set('pagina', 1)
            ->set('ordem', 'DESC')
            ->set('limite', -1)
            ->set('ordem_campo', 'af.idforum')
            ->set('campos','af.periode_de,af.nome,aft.idtopico,aft.nome as topico'
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

$linha["de"] = date("d/m/Y", mktime(0, 0, 0, date("m"), date("d")-7, date("Y")));
$linha["ate"] = date("d/m/Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));


switch ($url[3]) {
    case "ajax_cursos":
        if ($_REQUEST['idoferta']) {
            $relatorioObj->set("id",intval($_REQUEST['idoferta']));
            $relatorioObj->RetornarCursosOferta();
            exit();
        }
        break;
    case "html":
        $relatoriosObj->atualiza_visualizacao_relatorio();		
		if($_GET['tipo'] == 'N' || empty($_GET['tipo'])){			
			unset($config['listagem'][4]);
		}
		$relatorioObj->set("config",$config);
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