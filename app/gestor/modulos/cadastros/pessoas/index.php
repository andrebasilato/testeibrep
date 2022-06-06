<?php
include("../classes/pessoas.class.php");
include("config.php");
require realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/config.formulario.php');
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Pessoas();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if($_POST["acao"] == "salvar"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
  if($_FILES) {
    foreach($_FILES as $ind => $val) {
      $_POST[$ind] = $val;
    }
  }
  if($_POST[$config["banco"]["primaria"]]) {

    $sindicatos_associadas = $linhaObj->verificaSindicatosUsuario($url[3]);

    if($sindicatos_associadas) {
        unset($config["formulario"][1]["campos"][0]);
        unset($config["formulario"][1]["campos"][1]);
        unset($config["formulario"][1]["campos"][2]);
        $linhaObj->Set("config",$config);
        $linhaObj->Set("post",$_POST);
        $salvar = $linhaObj->Modificar();
    }
  } else {
    if($_POST["documento_tipo"] == "cnpj") {
      $config["formulario"][1]["campos"][1]["validacao"] = $config["formulario"][1]["campos"][2]["validacao"];
      $_POST["documento"] = $_POST["documento_cnpj"];
      unset($_POST["documento_cnpj"]);
      unset($config["formulario"][1]["campos"][2]);
    } else {
      unset($_POST["documento_cnpj"]);
      unset($config["formulario"][1]["campos"][2]);
    }
    $linhaObj->Set("config",$config);
    $linhaObj->Set("post",$_POST);
    $salvar = $linhaObj->Cadastrar();
    if ($salvar["sucesso"] && $salvar["id"]) {
        require '../classes/emailsautomaticos.class.php';
        $emailAutomaticoObj = new Emails_Automaticos();
        $emailAutomatico = $emailAutomaticoObj->retornaEmailPorTipo('cadal');

        if(! empty($emailAutomatico)){
            $coreObj = new Core();

            $linhaObj->set('id', $salvar["id"]);
            $linhaObj->set('campos', 'p.*');
            $pessoaEmail = $linhaObj->retornar();

            if(! empty($pessoaEmail)){
                $emailAutomaticoObj->enviarEmailAutomaticoPessoa($emailAutomatico, $pessoaEmail, $linhaObj, $coreObj);
            }
        }
    }
  }
  if($salvar["sucesso"]){
    if($_POST[$config["banco"]["primaria"]]) {
      $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
      $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    } else {
      $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
      $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
    }
    $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->Remover();
  if($remover["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
    $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "resetar_senha"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->ResetarSenha();

  if(!$salvar["sucesso"]){
    if($salvar["tela_senha"]) {
      $linhaObj->Set("pro_mensagem_idioma","sucesso_senha_tela");
    } else {
      $linhaObj->Set("pro_mensagem_idioma","sucesso");
    }
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/resetar_senha");
    $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "associar_pessoa"){

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

    $salvar = $linhaObj->AssociarPessoas(intval($url[3]), $_POST["pessoas"], $_POST["tipo_associacao"]);

    if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/associacoes");
        $linhaObj->Processando();
    }
} elseif($_POST["acao"] == "remover_associacao_pessoa"){

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");

    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->DesassociarPessoas();

    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/associacoes");
        $linhaObj->Processando();
    }
} elseif($_POST["acao"] == "adicionar_contato"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");

    $linhaObj->Set("id",intval($url[3]));
    $linhaObj->Set("post",$_POST);
    $salvar = $linhaObj->adicionarContato();

    if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/contatos");
        $linhaObj->Processando();
    }

} elseif($_POST["acao"] == "remover_contato"){

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");

    $linhaObj->Set("id",intval($url[3]));
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->RemoverContato();

    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/contatos");
        $linhaObj->Processando();
    }

} elseif($_POST["acao"] == "associar_sindicato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->AssociarSindicato();
  if($salvar["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma","associar_sindicato_sucesso");
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_sindicato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->DesassociarSindicato();

  if($remover["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma","remover_sindicato_sucesso");
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  }
}

if(isset($url[3])){
  if($url[4] == "ajax_cidades"){
    if($_REQUEST['idestado']) {
      $linhaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
    } else {
      $linhaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
    }
    exit;
  } elseif($url[4] == "ajax_cidades_curso_anterior"){
    if($_REQUEST['curso_anterior_idestado']) {
      $linhaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['curso_anterior_idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
    } else {
      $linhaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
    }
    exit;
  } elseif($url[3] == "cadastrar") {
    if($url[4] == "json") {
      include("idiomas/".$config["idioma_padrao"]."/json.php");
      include("telas/".$config["tela_padrao"]."/json.php");
      exit;
    } else {
      $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
      include realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/idiomas/'.$config["idioma_padrao"].'/formulario.php');
      include("telas/".$config["tela_padrao"]."/formulario.php");
      exit();
    }
  } else {
    $linhaObj->Set("id",intval($url[3]));
    $linhaObj->Set("campos","p.*, pa.nome as pais");
    $linha = $linhaObj->Retornar();

    if($linha) {
      switch ($url[4]) {
        case "editar":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

          $sindicatos_associadas = $linhaObj->verificaSindicatosUsuario($url[3]);

          include realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/idiomas/'.$config["idioma_padrao"].'/formulario.php');
          include("telas/".$config["tela_padrao"]."/formulario.php");
        break;
        case "acessarcomo":

          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");

          $_SESSION["cliente_email"]            = $linha["email"];
          $_SESSION["cliente_senha"]            = $linha["senha"];
          $_SESSION["cliente_idpessoa"]         = $linha["idpessoa"];
          $_SESSION["cliente_nome"]             = $linha["nome"];
          $_SESSION["cliente_ultimoacesso"]     = $linha["ultimo_acesso"];
          $_SESSION["cliente_gestor"]           = $usuario["idusuario"];
          unset($_SESSION["cliente_professor"]);

          $linhaObj->Set("monitora_oque","9");
          $linhaObj->Set("monitora_qual",$linha["idpessoa"]);
          $linhaObj->Set("monitora_dadosnovos",$linhaNova);
          $linhaObj->Monitora();

          $linhaObj->Set("url","/aluno");
          $linhaObj->Processando();

        break;
        case "remover":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
          include("idiomas/".$config["idioma_padrao"]."/remover.php");
          include("telas/".$config["tela_padrao"]."/remover.php");
        break;
        case "desativar_login":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
          include("idiomas/".$config["idioma_padrao"]."/desativar_login.php");
          include("telas/".$config["tela_padrao"]."/desativar_login.php");
        break;
        case "resetar_senha":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
          include("idiomas/".$config["idioma_padrao"]."/resetar_senha.php");
          include("telas/".$config["tela_padrao"]."/resetar_senha.php");
        break;
        case "contatos":
            $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
            $linhaObj->Set("id",intval($url[3]));
            $linhaObj->Set("ordem","asc");
            $linhaObj->Set("limite",-1);
            $linhaObj->Set("ordem_campo","tc.nome");
            $linhaObj->Set("campos","c.*, tc.nome as tipo");
            $associacoesArray = $linhaObj->ListarContatos();
            $tiposArray = $linhaObj->ListarTiposContatos();
            include("idiomas/".$config["idioma_padrao"]."/contatos.php");
            include("telas/".$config["tela_padrao"]."/contatos.php");
            break;
        /*case "associacoes":
            $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");

            $linhaObj->sql = "select * from tipos_associacoes where ativo = 'S' and ativo_painel = 'S'";
            $tiposAssociacoesArray = $linhaObj->retornarLinhas($linhaObj->sql);

            $linhaObj->Set("id",intval($url[3]));
            $linhaObj->Set("ordem","asc");
            $linhaObj->Set("limite",-1);
            $linhaObj->Set("ordem_campo","nome");
            $linhaObj->Set("campos","pa.idpessoa_associacao, pa.idpessoa_associada, pp.nome, pp.documento, pta.nome AS tipo");
            $associacoesArray = $linhaObj->ListarPessoasAss();

            include("idiomas/".$config["idioma_padrao"]."/associacoes.php");
            include("telas/".$config["tela_padrao"]."/associacoes.php");
            break;*/
        case "sindicatos":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
          $linhaObj->Set("id",intval($url[3]));
          $linhaObj->Set("campos","*");
          $sindicatos = $linhaObj->ListarSindicatosAssociadas();
          include("idiomas/".$config["idioma_padrao"]."/sindicatos.php");
          include("telas/".$config["tela_padrao"]."/sindicatos.php");
        break;
        case "opcoes":
          include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
          include("telas/".$config["tela_padrao"]."/opcoes.php");
        break;
        case "json":
          include("idiomas/".$config["idioma_padrao"]."/json.php");
          include("telas/".$config["tela_padrao"]."/json.php");
        break;
        case "download":
          include("telas/".$config["tela_padrao"]."/download.php");
        break;
        case "excluir":
          include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
          $linhaObj->RemoverArquivo($url[2], $url[5], $linha, $idioma);
        break;
        default:
          header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
          exit();
      }
    } else {
      header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
      exit();
    }
  }
} else {
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","p.*, pa.nome as pais");
  $dadosArray = $linhaObj->ListarTodas();
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}
