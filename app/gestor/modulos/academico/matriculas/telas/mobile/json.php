<?php
if($url[4] == "nacionalidade") {
  include("../classes/pessoas.class.php");
  $linhaObjPessoa = new Pessoas();
  $linhaObjPessoa->Set("idusuario",$usuario["idusuario"]);
  echo $linhaObjPessoa->RetornarPaises();
  exit;	
} elseif($url[4] == "cidades") {
  include("../classes/pessoas.class.php");
  $linhaObjPessoa = new Pessoas();
  $linhaObjPessoa->Set("idusuario",$usuario["idusuario"]);
  if($_GET["idestado"]) { 
	echo $linhaObjPessoa->RetornarJSON("cidades", mysql_real_escape_string($_GET["idestado"]), "idestado", "idcidade, nome", "ORDER BY nome");
  } else { 
	echo $linhaObjPessoa->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
  }
  exit();
} elseif($url[4] == "oferta_curso") {
  include("../classes/ofertas.class.php");
  $ofertaObj = new Ofertas();
  $ofertaObj->Set("id",$_GET["idoferta"]);
  $ofertaObj->Set("ordem","asc");
  $ofertaObj->Set("limite","-1");
  $ofertaObj->Set("ordem_campo","c.nome");
  $ofertaObj->Set("campos","oc.*, c.nome as curso");
  $ofertaCursos = $ofertaObj->ListarTodasCurso();
  echo $ofertaCursos = json_encode($ofertaCursos);
  exit();
} elseif($url[4] == "oferta_curso_escola") {
  include("../classes/ofertas.class.php");
  $ofertaObj = new Ofertas();
  $ofertaObj->Set("idoferta_curso",$_GET["idoferta_curso"]);
  $ofertaObj->Set("ordem","asc");
  $ofertaObj->Set("limite","-1");
  $ofertaObj->Set("ordem_campo","p.nome_fantasia");
  $ofertaObj->Set("campos","ocp.*, p.nome_fantasia as escola");
  $ofertaCursoEscolas = $ofertaObj->ListarEscolasAssociados();
  echo $ofertaCursoEscolas = json_encode($ofertaCursoEscolas);
  exit();
}elseif($url[4] == "validaemail") {
    include("../classes/pessoas.class.php");
    $linhaObjPessoa = new Pessoas();
    echo $linhaObjPessoa->verificarEmailCadastrado();
  }
?>