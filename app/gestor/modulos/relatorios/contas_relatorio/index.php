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
  case "ajax_turmas":
	($_REQUEST['idoferta']) 
	? 	
	$relatorioObj->RetornarJSON("ofertas_turmas", mysql_real_escape_string($_REQUEST['idoferta']), "idoferta", "idturma, nome", "order by nome")
	: 
	$relatorioObj->RetornarJSON("ofertas_turmas", $url[5], "idoferta", "idturma, nome", "order by nome");
	exit();
  break;
  case "ajax_subcategorias":
    include("../classes/categorias.class.php");
    $linhaCatObj = new Categorias();
    if ($_REQUEST['idcategoria']) {
        $linhaCatObj->Set("id", (int)$_REQUEST['idcategoria']);
    } else {
        $linhaCatObj->Set("id", (int)$url[5]);
    }
    $linhaCatObj->retornarSubcategoriasCategoria();
    exit();
	/*($_REQUEST['idcategoria']) 
	? 	
	$relatorioObj->RetornarJSON("categorias_subcategorias", mysql_real_escape_string($_REQUEST['idcategoria']), "idcategoria", "idsubcategoria, nome", "order by nome")
	: 
	$relatorioObj->RetornarJSON("categorias_subcategorias", $url[5], "idcategoria", "idsubcategoria, nome", "order by nome");
	exit();*/
  break;
  case "html":
        $relatoriosObj->atualiza_visualizacao_relatorio();
        $relatorioObj->Set("pagina",1);
        $relatorioObj->Set("ordem","asc");
        $relatorioObj->Set("limite",-1);
        $relatorioObj->Set("ordem_campo","c.data_vencimento");
        $relatorioObj->Set("campos","c.*,
                                    c.nome AS descricao,
                                    cw.nome AS situacao,
                                    cw.sigla AS situacao_sigla,
                                    po.nome_fantasia as escola,
                                    p.nome AS cliente,
                                    p.documento_tipo AS documento_tipo_cliente,
                                    p.documento AS documento_cliente,
                                    p.telefone AS telefone_cliente,
                                    p.email AS email_cliente,
                                    f.nome AS fornecedor,
                                    f.documento_tipo AS documento_tipo_fornecedor,
                                    f.documento AS documento_fornecedor,
                                    f.telefone AS telefone_fornecedor,
                                    f.email AS email_fornecedor,
                                    p.nome AS pessoa,
                                    e.nome AS empresa,
                                    p.documento_tipo AS documento_tipo_pessoa,
                                    p.documento AS documento_pessoa,
                                    p.telefone AS telefone_pessoa, 
                                    p.email AS email_pessoa, mp.nome AS aluno,
                                    mp.documento_tipo AS documento_tipo_aluno,
                                    mp.documento AS documento_aluno,
                                    mp.telefone AS telefone_aluno,
                                    mp.email AS email_aluno,
                                    m.idcurso, m.valor_contrato, po.razao_social, po.documento as cnpj_cfc,
                                    v.nome AS vendedor");		
        $dadosArray = $relatorioObj->gerarRelatorio();

        $eventosFinanceiros = $relatorioObj->retornarEventosFinanceiros();
        $centrosDeCustos = $relatorioObj->retornarCentrosDeCustos();
        include("idiomas/".$config["idioma_padrao"]."/html.php");
        include("telas/".$config["tela_padrao"]."/html.php");
  break;			
  case "xls":
	$relatorioObj->Set("pagina",1);
	$relatorioObj->Set("ordem","asc");
	$relatorioObj->Set("limite",-1);
	$relatorioObj->Set("ordem_campo","c.data_vencimento");
	$relatorioObj->Set("campos","c.*,
                                c.nome AS descricao, 
                                cw.nome AS situacao,
                                cw.sigla AS situacao_sigla,
                                po.nome_fantasia as escola,
                                f.nome AS fornecedor,
                                f.documento_tipo AS documento_tipo_fornecedor,
                                f.documento AS documento_fornecedor,
                                f.telefone AS telefone_fornecedor,
                                f.email AS email_fornecedor,
                                p.nome AS pessoa,
                                e.nome AS empresa,
                                p.documento_tipo AS documento_tipo_pessoa,
                                p.documento AS documento_pessoa,
                                p.telefone AS telefone_pessoa,
                                p.email AS email_pessoa,
                                mp.nome AS aluno,
                                mp.documento_tipo AS documento_tipo_aluno,
                                mp.documento AS documento_aluno,
                                mp.telefone AS telefone_aluno,
                                mp.email AS email_aluno,
                                m.idcurso, m.valor_contrato, po.razao_social, po.documento as cnpj_cfc,
                                v.nome AS vendedor");
	$dadosArray = $relatorioObj->gerarRelatorio();
	
	$eventosFinanceiros = $relatorioObj->retornarEventosFinanceiros();
	$centrosDeCustos = $relatorioObj->retornarCentrosDeCustos();	
	include("idiomas/".$config["idioma_padrao"]."/xls.php");
	include("telas/".$config["tela_padrao"]."/xls.php");
  break;			
  default:
	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");
}