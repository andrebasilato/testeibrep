<?php
$erro = array();

if($url[8]) {

  if (!$ofertaObj instanceof Ofertas) {
    include("../classes/ofertas.class.php");
    $ofertaObj = new Ofertas();
  }

  $ofertaObj->Set("idoferta_curso_escola",$_SESSION["matricula"]["idoferta_curso_escola"]);
  $verificarNumeroMatriculas = $ofertaObj->verificarMatriculasCursoEscola($url[4], $_SESSION["matricula"]["idcurso"], $_SESSION["matricula"]["idescola"], $url[7]);
  if($verificarNumeroMatriculas['total'] == $verificarNumeroMatriculas['maximo_turma']) {
    $erro[] = "numero_aluno_turma_atingido";
  }

  if(!$_SESSION["matricula"]["idoferta"]) {
    $erro[] = "oferta_vazio";
  }
  if(!$_SESSION["matricula"]["idoferta_curso"]) {
    $erro[] = "oferta_curso_vazio";
  }
  if(!$_SESSION["matricula"]["idoferta_curso_escola"]) {
    $erro[] = "oferta_curso_escola_vazio";
  }
  if(!$_SESSION["matricula"]["idturma"]) {
    $erro[] = "turma_vazio";
  }
  if($url[8] == "atendente") {
    if(!$_SESSION["matricula"]["pessoa"]["documento"] && !$_SESSION["matricula"]["pessoa"]["idpessoa"]) {
      $erro[] = "pessoa_vazio";
    }
  } elseif($url[8] == "financeiro") {
    if(!$_SESSION["matricula"]["pessoa"]["documento"] && !$_SESSION["matricula"]["pessoa"]["idpessoa"]) {
      $erro[] = "pessoa_vazio";
    }
    if(!$_SESSION["matricula"]["idvendedor"]) {
      $erro[] = "vendedor_vazio";
    }
  } elseif($url[8] == "finalizar") {
    if(!$_SESSION["matricula"]["pessoa"]["documento"] && !$_SESSION["matricula"]["pessoa"]["idpessoa"]) {
      $erro[] = "pessoa_vazio";
    }
    if(!$_SESSION["matricula"]["idvendedor"]) {
      $erro[] = "vendedor_vazio";
    }
    if($_SESSION["matricula"]["possui_financeiro"] == "S"){

        if(!$_SESSION["matricula"]["financeiro"]["bolsa"]) {
            $erro[] = "bolsa_vazio";
        } elseif($_SESSION["matricula"]["financeiro"]["bolsa"] == "N") {


        } elseif($_SESSION["matricula"]["financeiro"]["bolsa"] == "S") {
            if(!$_SESSION["matricula"]["financeiro"]["idsolicitante"]) {
                $erro[] = "idsolicitante_vazio";
            }
        }
    }
  }
}

if(count($erro) > 0){
  unset($_SESSION["matricula"]);

  include("idiomas/".$config["idioma_padrao"]."/novamatricula.erro.php");
  include("telas/".$config["tela_padrao"]."/novamatricula.erro.php");
  exit;
}
?>