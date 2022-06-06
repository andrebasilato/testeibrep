<?php
$config["formulario_cursos"] = array(
	array(
		"fieldsetid"=>"dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
		"legendaidioma"=>"legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
		"campos"=>array( // Campos do formulario
			array(
				"id"=>"form_matricula_liberada",
				"nome"=>"matricula_liberada",
				"nomeidioma"=>"form_matricula_liberada",
				"tipo"=>"select",
				"array"=>"sim_nao", // Array que alimenta o select
				"class"=>"span2",
				"valor"=>"matricula_liberada",
				// "validacao" => array("required" => "matricula_liberada_vazio"),
				"banco"=>true,
				"banco_string"=>true
			),
			array(
				"id"=>"data_inicio_aula",
				"nome"=>"data_inicio_aula",
				"nomeidioma"=>"form_data_inicio_aula",
				"tipo"=>"input",
				"valor"=>"data_inicio_aula",
				"valor_php"=>'return formataData("%s", "br", 0)',
				"class"=>"span2",
				"mascara"=>"99/99/9999",
				"datepicker"=>true,
				"banco"=>true,
				"banco_php"=>'return formataData("%s", "en", 0)',
				"banco_string"=>true
			),
			array(
				"id"=>"form_certificado",
				"nome"=>"certificado",
				"nomeidioma"=>"form_certificado",
				"tipo"=>"select",
				"array"=>"sim_nao", // Array que alimenta o select
				"class"=>"span2",
				"valor"=>"certificado",
				// "validacao" => array("required" => "certificado_vazio"),
				"banco"=>true,
				"banco_string"=>true
			),
			array(
				"id"=>"data_inicio_acesso_ava",
				"nome"=>"data_inicio_acesso_ava",
				"nomeidioma"=>"form_data_inicio_acesso_ava",
				"tipo"=>"input",
				"valor"=>"data_inicio_acesso_ava",
				"valor_php"=>'return formataData("%s", "br", 0)',
				"class"=>"span2",
				"mascara"=>"99/99/9999",
				"datepicker"=>true,
				"banco"=>true,
				"banco_php"=>'return formataData("%s", "en", 0)',
				"banco_string"=>true
			),
			array(
				"id"=>"data_fim_acesso_ava",
				"nome"=>"data_fim_acesso_ava",
				"nomeidioma"=>"form_data_fim_acesso_ava",
				"tipo"=>"input",
				"valor"=>"data_fim_acesso_ava",
				"valor_php"=>'return formataData("%s", "br", 0)',
				"class"=>"span2",
				"mascara"=>"99/99/9999",
				"datepicker"=>true,
				"banco"=>true,
				"banco_php"=>'return formataData("%s", "en", 0)',
				"banco_string"=>true
			),
			array(
				"id"=>"idoferta_curso", // Id do atributo HTML
				"nome"=>"idoferta_curso", // Name do atributo HTML
				"tipo"=>"hidden", // Tipo do input
				"valor"=>'return $this->url["5"];',
				"banco"=>true
			)
		)
	)
);

$config["listagem_cursos"] = array(
	array(
		"id"=>"idcurso",
		"variavel_lang"=>"tabela_idcurso",
		"tipo"=>"banco",
		"coluna_sql"=>"c.idcurso",
		"valor"=>"idcurso",
		"busca"=>true,
		"busca_class"=>"inputPreenchimentoCompleto",
		"busca_metodo"=>1,
		"tamanho"=>80
	),
	array(
		"id"=>"idoferta_curso",
		"variavel_lang"=>"tabela_idoferta_curso",
		"tipo"=>"banco",
		"coluna_sql"=>"oc.idoferta_curso",
		"valor"=>"idoferta_curso",
		"busca"=>true,
		"busca_class"=>"inputPreenchimentoCompleto",
		"busca_metodo"=>1,
		"tamanho"=>100
	),
	array(
		"id"=>"nome",
		"variavel_lang"=>"tabela_nome",
		"tipo"=>"banco",
		"evento"=>"maxlength='100'",
		"coluna_sql"=>"c.nome",
		"valor"=>"nome",
		"busca"=>true,
		"busca_class"=>"inputPreenchimentoCompleto",
		"busca_metodo"=>2
	),
	array(
		"id"=>"matriculas",
		"variavel_lang"=>"tabela_matriculas",
		"tipo"=>"banco",
		"valor"=>"matriculas",
		"tamanho"=>80
	),
	array(
		'id'=>'form_possui_financeiro',
		'variavel_lang'=>'tabela_possui_financeiro',
		'tipo'=>'php',
		'coluna_sql'=>'possui_financeiro',
		'valor'=>'return "<span data-original-title=\"" . 
							$GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$linha["possui_financeiro"]] . "\"
                            class=\"label\" data-placement=\"left\"
                            rel=\"tooltip\" style=\"background-color: " . 
							$GLOBALS["sim_nao_cor"][$linha["possui_financeiro"]] . ";\">
                            " . $linha["possui_financeiro"] . "
                            </span>";',
		'busca'=>true,
		'busca_tipo'=>'select',
		'busca_class'=>'inputPreenchimentoCompleto',
		'busca_array'=>'sim_nao',
		'busca_metodo'=>1,
		'tamanho'=>70
	),	
	array(
		"id"=>"porcentagem_minima_virtual",
		"variavel_lang"=>"tabela_porcentagem_minima_virtual",
		"tipo"=>"banco",
		"coluna_sql"=>"oc.porcentagem_minima_virtual",
		"valor"=>"porcentagem_minima_virtual",
		"tamanho"=>80
	),
	array(
		"id"=>"porcentagem_minima",
		"variavel_lang"=>"tabela_porcentagem_minima",
		"tipo"=>"banco",
		"coluna_sql"=>"oc.porcentagem_minima",
		"valor"=>"porcentagem_minima",
		"tamanho"=>80
	),
	array(
		"id"=>"qtde_minima_dias",
		"variavel_lang"=>"tabela_qtde_minima_dias",
		"tipo"=>"banco",
		"coluna_sql"=>"oc.qtde_minima_dias",
		"valor"=>"qtde_minima_dias",
		"tamanho"=>80
	),
	array(
		"id"=>"opcoes",
		"variavel_lang"=>"tabela_opcoes",
		"tipo"=>"php",
		"valor"=>'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idoferta_curso"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
		"busca_botao"=>true,
		"tamanho"=>"80"
	)
);

$linhaObj->Set("config",$config);

$linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|4");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_cursos"]);

$linhaObj->config["banco"] = $config["banco_cursos"];
$linhaObj->config["formulario"] = $config["formulario_cursos"];

if($_POST["acao"]=="salvar_curso"){
	$linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|7");
	
	if($_FILES){
		foreach($_FILES as $ind=>$val){
			$_POST[$ind] = $val;
		}
	}
	// print_r2($_POST,true);
	$linhaObj->Set("post",$_POST);
	if($_POST[$config["banco_cursos"]["primaria"]])
		$salvar = $linhaObj->ModificarCurso();
	else
		$salvar = $linhaObj->CadastrarCurso();
	
	if($salvar["sucesso"]){
		if($_POST[$config["banco_cursos"]["primaria"]]){
			$linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
			$linhaObj->Set("url",
				"/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		}else{
			$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		}
		$linhaObj->Processando();
	}
}elseif($_POST["acao"]=="remover_curso"){
	$linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|6");
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->RemoverCursos();
	
	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cursos");
		$linhaObj->Processando();
	}
}elseif($_POST["acao"]=="salvar_campos_prova_presencial"){
	$linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|7");
	$linhaObj->Set("post",$_POST);
	$salvar = $linhaObj->salvarInformacoesProvaPresencial((int)$url[5]);
	
	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","salvar_campos_presencial_sucesso");
		$linhaObj->Set("url",
			"/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	}
}elseif($_POST["acao"]=="salvar_campos_aval_virtual"){
	$linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|7");
	$linhaObj->Set("post",$_POST);
	$salvar = $linhaObj->salvarInformacoesAvaliacaoVirtual((int)$url[5]);
	
	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","salvar_campos_virtual_sucesso");
		$linhaObj->Set("url",
			"/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	}
}elseif($_POST["acao"]=="salvar_dados_curso"){
	$linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|7");
	$linhaObj->Set("post",$_POST);
	$salvar = $linhaObj->salvarDadosCurso((int)$url[5]);
	
	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","salvar_dados_curso_sucesso");
		$linhaObj->Set("url",
			"/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	}
}elseif ($_POST["acao"] == "salvar_financeiro_curso"){
	$linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|7");
	$linhaObj->Set("post",$_POST);
	$salvar = $linhaObj->salvarDadosCurso((int)$url[5]);
	
	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","salvar_financeiro_curso_sucesso");
		$linhaObj->Set("url",
			"/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
  	}
} elseif ($_POST["acao"] == "salvarFolhaRegistro") {  
  	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");		
  	$linhaObj->Set("post",$_POST);
  	$salvar = $linhaObj->atualizaFolhaRegistro((int)$url[5]);
  
  	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","salvar_dados_curso_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
  	}
}

if(isset($url[5])){
	$linhaObj->Set("idoferta_curso",intval($url[5]));
	$linhaObj->Set("campos","oc.*, c.nome as curso, o.nome as oferta, o.idoferta");
	$linha = $linhaObj->RetornarCurso();

	if($linha) {
	  switch($url[6]) {
		case "academico":
		  	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
		  	$_GET["q"]["1|c.idcurso"] = $linha["idcurso"];
			$folhaObj = new Folhas_Registros_Diplomas;
			$folhaObj->Set("campos","frd.idfolha,frd.nome");
			$folhas = $folhaObj->listarSelect();
			unset($_GET["q"]);
		  	include("idiomas/".$config["idioma_padrao"]."/cursos.academico.php");
		  	include("telas/".$config["tela_padrao"]."/cursos.academico.php");
		break;
		case "comercial":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
		  include("idiomas/".$config["idioma_padrao"]."/cursos.comercial.php");
		  include("telas/".$config["tela_padrao"]."/cursos.comercial.php");
		break;
		case "financeiro":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12");
		  include("idiomas/".$config["idioma_padrao"]."/cursos.financeiro.php");
		  include("telas/".$config["tela_padrao"]."/cursos.financeiro.php");
		break;
		case "remover":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");
		  include("idiomas/".$config["idioma_padrao"]."/remover.cursos.php");
		  include("telas/".$config["tela_padrao"]."/remover.cursos.php");
		break;
		case "opcoes":
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.cursos.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.cursos.php");
		break;
		case "download":
		  include("telas/".$config["tela_padrao"]."/download.php");
		break;
		case "excluir":
		  include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
		  $linhaObj->RemoverArquivo($url[2]."_".$url[4], $url[7], $linha, $idioma);
		break;
		case "json":
		  include("idiomas/".$config["idioma_padrao"]."/json.php");
		  include("telas/".$config["tela_padrao"]."/json.php");
		break;
		default:
		  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		  exit();
	  }				
	} else {
	  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	  exit();
	}			
} else {

 
  $cursosArray = $linhaObj->ListarCursosNaoAssociados($url[3]);

  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_cursos"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","c.nome, oc.*");	
  $dadosArray = $linhaObj->ListarCursosAssociados();
  foreach ($dadosArray as $array => $curso){//Se em nenhum momento não encontrar espaco no "nome", sera colocado "espaco"! para evitar quebra do layout
  if (!mb_strpos($curso["nome"], ' ')) {
    $curso['nome'] =  wordwrap($curso["nome"], 30, " ", true);
    $dadosArray[$array]['nome'] = $curso['nome'];
  }
}
  include("idiomas/".$config["idioma_padrao"]."/index.cursos.php");
  include("telas/".$config["tela_padrao"]."/index.cursos.php");
}
?>