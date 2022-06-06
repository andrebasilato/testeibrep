<?php

$config["listagem_cursos_escolas"] = array(  
  array(
	"id" => "escola", 
	"variavel_lang" => "tabela_escola",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "p.nome_fantasia",
	"valor" => "escola",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "curso", 
	"variavel_lang" => "tabela_curso",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "c.nome",
	"valor" => "curso",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "curriculo", 
	"variavel_lang" => "tabela_curriculo",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "cur.nome",
	"valor" => "curriculo",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  /*array(
	"id" => "limite",
	"variavel_lang" => "tabela_limite", 
	"tipo" => "banco", 
	"coluna_sql" => "ocp.limite", 
	"valor" => "limite", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),*/  
  array(
	"id" => "dias_para_ava",
	"variavel_lang" => "tabela_dias_para_ava", 
	"tipo" => "banco", 
	"coluna_sql" => "ocp.dias_para_ava", 
	"valor" => "dias_para_ava", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),
    array(
        "id"=>"ordem",
        "variavel_lang"=>"tabela_ordem",
        "tipo"=>"banco",
        "coluna_sql" => "ocp.ordem",
        "valor"=>"ordem",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
  array("id" => "data_limite_ava", 
			  "variavel_lang" => "tabela_data_limite_ava", 
			  "coluna_sql" => "ocp.data_limite_ava",
			  "tipo" => "php", 
			  "valor" => 'return formataData($linha["data_limite_ava"],"br",0);',
			  "busca" => true,
			  "tamanho" => "90",
			  "busca_class" => "inputPreenchimentoCompleto",
			  "busca_metodo" => 3),   

 );
						   						   
$linhaObj->Set("config",$config);				
	
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_turmas"]);

$linhaObj->config["banco"] = $config["banco_cursos_escolas"];


if($_POST["acao"] == "salvar_cursos_escolas"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13");		
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->cadastrarCursoEscola();
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","salvar_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cursos_cfc?idescola=".$_GET['idescola']);
	$linhaObj->Processando();
  }
}

  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = -1;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_cursos_escolas"]["primaria"];
  $linhaObj->Set("ordem_campo",'idoferta_curso_escola');
  $linhaObj->Set("campos","ocp.*, p.nome_fantasia as escola, i.nome_abreviado as sindicato, c.nome as curso, cur.nome as curriculo");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("idescola",intval($_GET['idescola']));
  $dadosArray = $linhaObj->ListarCursosEscolas();
  // INICIO RETORNA OS POLOS PARA BUSCA AVANÇADA
  $linhaObj->Set("ordem_campo",'p.nome_fantasia');
  $linhaObj->Set("ordem",'ASC');
  $linhaObj->Set("campos",'i.nome_abreviado AS sindicato, p.nome_fantasia,p.idescola');
  $arrayBuscaEscolas  = $linhaObj->ListarEscolasAssociados();
  $estadosDetran = $detranObj->listarEstadosIntegrados();
  // FIM RETORNA OS POLOS PARA BUSCA AVANÇADA
  // INICIO RETORNA TODOS OS CURRICULOS
  $linhaObjCurriculo = new Curriculos();
  $linhaObjCurriculo->Set("ordem_campo",'ca.idcurriculo');
  $linhaObjCurriculo->Set("ordem",'DESC');
  $linhaObjCurriculo->Set("campos",'ca.nome,ca.idcurriculo');
  $arrayBuscaCurriculo = $linhaObjCurriculo->ListarTodas(); 
  // FIM RETORNA TODOS OS CURRICULO
  // INICIO RETORNA CURSOS
  $linhaObj->Set("ordem",'ASC');
  $linhaObj->Set("ordem_campo",'c.nome');
  $linhaObj->Set("campos","c.nome,c.idcurso");	
  $arrayBuscaCursos = $linhaObj->ListarCursosAssociados();
  //print_r2($arrayBuscaCursos,true);
  // FIM CURSOS
   
  include("idiomas/".$config["idioma_padrao"]."/index.cursos.escolas.php");
  include("telas/".$config["tela_padrao"]."/index.cursos.escolas.php");

?>