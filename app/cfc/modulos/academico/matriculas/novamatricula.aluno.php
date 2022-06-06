<?php
$url[6] = intval($url[6]);
$_SESSION["matricula"]["idoferta_curso_escola"] = $url[6];
$_SESSION["matricula"]["idturma"] = $url[7];
include("../classes/ofertas.class.php");
$ofertaObj = new Ofertas();
$ofertaObj->Set("idoferta_curso_escola",$_SESSION["matricula"]["idoferta_curso_escola"]);
$escola = $ofertaObj->retornarCursoEscola($url[6]);
$_SESSION["matricula"]["idescola"] = $escola['idescola'];

$turma = $ofertaObj-> retornarDadosTurma($_SESSION["matricula"]["idturma"]);

if($_GET) {
  if($_GET["cpf"]){
	include_once("../includes/validation.php");
	$regras = array();

	$_GET["cpf"] = str_replace(array(".","-"),"",$_GET["cpf"]);

	$regras[] = "required,cpf,cpf_vazio";
	$regras[] = "valida_cpf,cpf,cpf_invalido";

	$matricula["erros"] = validateFields($_GET, $regras);
	if(!count($matricula["erros"])) {
	  include("../classes/pessoas.class.php");
	  $pessoaObj = new Pessoas();
	  $pessoaObj->Set("campos","p.*, pa.nome as pais");
	  $pessoa = $pessoaObj->RetornarPorCPF($_GET["cpf"]);

		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][0]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][1]);
	  	unset($matriculaObj->config['formulario_pessoas'][0]['campos'][2]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][3]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][4]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][5]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][6]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][7]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][8]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][15]);
		unset($matriculaObj->config['formulario_pessoas'][1]['campos'][3]);
		unset($matriculaObj->config['formulario_pessoas'][1]['campos'][4]);
		unset($matriculaObj->config['formulario_pessoas'][1]['campos'][5]);
		unset($matriculaObj->config['formulario_pessoas'][1]['campos'][6]);
		unset($matriculaObj->config['formulario_pessoas'][1]['campos'][7]);
		unset($matriculaObj->config['formulario_pessoas'][0]['campos'][15]);

		//print_r2($matriculaObj->config['formulario_pessoas']); exit();

	if($pessoa["idpessoa"]) {
		$core = new Core();
		$core->sql = "select
						count(m.idmatricula) as total
					  from
						matriculas m
						inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao
					  where
						mw.inativa <> 'S' and mw.cancelada <> 'S' and m.ativo = 'S' and
						m.idpessoa = ".$pessoa["idpessoa"]." and
						m.idoferta = ".$_SESSION["matricula"]["idoferta"]." and
						m.idcurso = ".$_SESSION["matricula"]["idcurso"]." and
						m.idescola = ".$_SESSION["matricula"]["idescola"]." ";

		$verificaMatriculado = $core->retornarLinha($core->sql);
		if($verificaMatriculado["total"] > 0) {
		  $matricula["erros"][] = "aluno_matriculado";
		}
	  }
	}

  } else {
	$matricula["erros"][] = "cpf_vazio";
  }
}

require("novamatricula.seguranca.php");

include("idiomas/".$config["idioma_padrao"]."/novamatricula.aluno.php");
include("telas/".$config["tela_padrao"]."/novamatricula.aluno.php");
exit;
