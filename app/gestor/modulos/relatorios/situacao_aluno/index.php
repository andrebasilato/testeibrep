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
        set_time_limit(0);
        
        $relatorioObj->set('pagina', 1)
            ->set('ordem', 'DESC')
            ->set('limite', -1)
            ->set('ordem_campo', 'ma.idmatricula')
            ->set('campos', 'ma.*, ve.nome as vendedor, pe.*, IF(ma.porcentagem_manual > ma.porcentagem, ma.porcentagem_manual, ma.porcentagem) AS porcentagem');

        $matriculas = $relatorioObj->gerarRelatorio();
    }

$linha["de"] = date("d/m/Y", mktime(0, 0, 0, date("m"), date("d")-7, date("Y")));
$linha["ate"] = date("d/m/Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));


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
    case "ajax_vendedores":
        ($_REQUEST['idsindicato']) 
         ?  
            $relatorioObj->RetornarVendedoresSindicato(mysql_real_escape_string($_REQUEST['idsindicato']))
         : 
            $relatorioObj->RetornarVendedoresSindicato((int)$url[5]);
        exit();
        break;
    default:
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
}