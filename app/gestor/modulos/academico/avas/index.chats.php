<?php
$config["formulario_chats"] = array(
  array(
    "fieldsetid" => "dadosdoobjeto",
    "legendaidioma" => "legendadadosdados",
    "campos" => array(
      array(
        "id" => "form_nome",
        "nome" => "nome",
        "nomeidioma" => "form_nome",
        "tipo" => "input",
        "valor" => "nome",
        "validacao" => array("required" => "nome_vazio"),
        "class" => "span6",
        "banco" => true,
        "banco_string" => true,
      ),
      array(
        "id" => "texto",
        "nome" => "descricao",
        "nomeidioma" => "texto",
        "tipo" => "text",
        "valor" => "descricao",
        "class" => 'span6" style="height: 90px',
        "validacao" => array("required" => "descricao_vazio"),
        "banco" => true,
        "banco_campo" => "descricao",
        "banco_string" => true
      ),
      array(
        "id" => "inicio_campanha",
        "nome" => "inicio_campanha",
        "nomeidioma" => "form_inicio_campanha",
        "tipo" => "input",
        "valor" => "inicio_campanha",
        // "valor_php" => 'if($dados["inicio_campanha"] && $dados["inicio_campanha"] != "0000-00-00 00:00") return formataData("%s", "br", 0)',
        "class" => "span2",
        "mascara" => "99/99/9999 99:99",
        "datepicker" => true,
        "banco" => true,
        // "banco_php" => 'return formataData("%s", "en", 0)',
        "banco_string" => true
      ),
      array(
        "id" => "form_imagem",
        "nome" => "imagem",
        "nomeidioma" => "form_imagem",
        "arquivoidioma" => "arquivo_enviado",
        "arquivoexcluir" => "arquivo_excluir",
        "tipo" => "file",
        "extensoes" => 'jpg|jpeg|gif|png|bmp',
        "largura" => 350,
        "altura" => 180,
        "validacao" => array("formato_arquivo" => "arquivo_invalido"),
        "class" => "span6",
        "pasta" => "avas_chats_imagem",
        "download" => true,
        "excluir" => true,
        "banco" => true,
        "banco_campo" => "imagem",
        "ignorarsevazio" => true
      ),
      array(
        "id" => "inicio_entrada_aluno",
        "nome" => "inicio_entrada_aluno",
        "nomeidioma" => "form_inicio_entrada_aluno",
        "tipo" => "input",
        "valor" => "inicio_entrada_aluno",
        "validacao" => array("required" => "inicio_entrada_vazio"),
#        "valor_php" => 'if($dados["inicio_entrada_aluno"] && $dados["inicio_entrada_aluno"] != "0000-00-00") return formataData("%s", "br", 0)',
        "class" => "span2",
        "mascara" => "99/99/9999 99:99",
        "datepicker" => true,
        "banco" => true,
#        "banco_php" => 'return formataData("%s", "en", 0)',
        "banco_string" => true
      ),
      array(
        "id" => "fim_entrada_aluno",
        "nome" => "fim_entrada_aluno",
        "nomeidioma" => "form_fim_entrada_aluno",
        "tipo" => "input",
        "valor" => "fim_entrada_aluno",
        "validacao" => array("required" => "fim_entrada_vazio"),
#        "valor_php" => ' return formataData("%s", "br", 0)',
        "class" => "span2",
        "mascara" => "99/99/9999 99:99",
        "datepicker" => true,
        "banco" => true,
#        "banco_php" => 'return formataData("%s", "en", 0)',
        "banco_string" => true
      ),
      array(
        "id" => "form_exibir_ava",
        "nome" => "exibir_ava",
        "nomeidioma" => "form_exibir_ava",
        "tipo" => "select",
        "array" => "sim_nao",
        "class" => "span2",
        "valor" => "exibir_ava",
        "validacao" => array("required" => "exibir_ava_vazio"),
        "ajudaidioma" => "form_ativo_ajuda",
        "banco" => true,
        "banco_string" => true
      ),
      array(
        "id" => "idava_chat",
        "nome" => "idava",
        "tipo" => "hidden",
        "valor" => 'return $this->url["3"];',
        "banco" => true
      ),
    )
  )
);

$config["listagem_chats"] = array(
  array(
    "id" => "idchat",
    "variavel_lang" => "tabela_idchat",
    "tipo" => "banco",
    "coluna_sql" => "idchat",
    "valor" => "idchat",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 1,
    "tamanho" => 60
  ),
  array(
    "id" => "nome",
    "variavel_lang" => "tabela_nome",
    "tipo" => "banco",
    "evento" => "maxlength='100'",
    "coluna_sql" => "c.nome",
    "valor" => "nome",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
  ),
  array(
    "id" => "exibir_ava",
    "variavel_lang" => "tabela_exibir_ava",
    "tipo" => "php",
    "coluna_sql" => "c.exibir_ava",
    "valor" => 'if($linha["exibir_ava"] == "S") {
                  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
                } else {
                  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
                }',
    "busca" => true,
    "busca_tipo" => "select",
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_array" => "ativo",
    "busca_metodo" => 1,
    "tamanho" => 60
  ),
  array(
    "id" => "inicio_entrada_aluno",
    "variavel_lang" => "tabela_open",
    "coluna_sql" => "c.inicio_entrada_aluno",
    "tipo" => "php",
    "valor" => 'return formataData($linha["inicio_entrada_aluno"],"br",1);',
    "tamanho" => "140"
  ),
  array(
    'id' => 'fim_entrada_aluno',
    'variavel_lang' => 'tabela_close',
    'coluna_sql' => 'c.fim_entrada_aluno',
    'tipo' => 'php',
    'valor' => 'return formataData($linha["fim_entrada_aluno"],"br",1);',
    'tamanho' => '140'
  ),
  array(
    'id' => 'data_cad',
    'variavel_lang' => 'tabela_datacad',
    'coluna_sql' => 'c.data_cad',
    'tipo' => 'php',
    'valor' => 'return formataData($linha["data_cad"],"br",1);',
    "tamanho" => "140"
  ),
  array(
    'id' => 'opcoes',
    'variavel_lang' => 'tabela_opcoes',
    'tipo' => 'php',
    'valor' => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idchat"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
    'busca_botao' => true,
    'tamanho' => '80'
  )
 );

$linhaObj->Set('config', $config);
include '../classes/avas.chats.class.php';

$linhaObj = new Chats();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde_chats"]);
$linhaObj->Set("idava", (int) $url[3]);

$linhaObj->config["banco"] = $config["banco_chats"];
$linhaObj->config["formulario"] = $config["formulario_chats"];

if ($_POST["acao"] == "salvar_chat") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");

    if($_FILES) {
        foreach($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }

    if ($_POST['inicio_campanha'] ) {
        $_POST['inicio_campanha'] = str_replace('/', '-', $_POST['inicio_campanha']);
        $_POST['inicio_campanha'] = date('Y-m-d H:i:00', strtotime($_POST['inicio_campanha']));
    }

    if ($_POST['fim_entrada_aluno']) {
        $_POST['fim_entrada_aluno'] = str_replace('/', '-', $_POST['fim_entrada_aluno']);
        $_POST['fim_entrada_aluno'] = date('Y-m-d H:i:00', strtotime($_POST['fim_entrada_aluno']));
    }

    if ($_POST['inicio_entrada_aluno']) {
        $_POST['inicio_entrada_aluno'] = str_replace('/', '-', $_POST['inicio_entrada_aluno']);
        $_POST['inicio_entrada_aluno'] = date('Y-m-d H:i:00', strtotime($_POST['inicio_entrada_aluno']));
    }

    $linhaObj->Set('post', $_POST);

    if ($_POST[$config["banco_chats"]["primaria"]]) {
        $salvar = $linhaObj->ModificarChat();
    } else{
        $salvar = $linhaObj->CadastrarChat();
    }

  if($salvar["sucesso"]){
    if($_POST[$config["banco_chats"]["primaria"]]) {
      $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
      $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
    } else {
      $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
      $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    }
    $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_chat") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverChat();
  if($remover["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  }
}

if(isset($url[5])){
  if($url[5] == "cadastrar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
    include("idiomas/".$config["idioma_padrao"]."/formulario.chats.php");
    include("telas/".$config["tela_padrao"]."/formulario.chats.php");
    exit();
  } else {
    $linhaObj->Set("id",intval($url[5]));
    $linhaObj->Set("campos","c.*,
    date_format(c.inicio_entrada_aluno, '%d/%m/%Y %H:%i') inicio_entrada_aluno,
    date_format(c.fim_entrada_aluno, '%d/%m/%Y %H:%i') fim_entrada_aluno,
    date_format(c.inicio_campanha, '%d/%m/%Y %H:%i') inicio_campanha,
    a.nome as ava");
    $linha = $linhaObj->RetornarChat();

    if($linha) {
      switch($url[6]) {
        case "editar":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
          include("idiomas/".$config["idioma_padrao"]."/formulario.chats.php");
          include("telas/".$config["tela_padrao"]."/formulario.chats.php");
        break;
        case "remover":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
          include("idiomas/".$config["idioma_padrao"]."/remover.chats.php");
          include("telas/".$config["tela_padrao"]."/remover.chats.php");
        break;
        case "opcoes":
          include("idiomas/".$config["idioma_padrao"]."/opcoes.chats.php");
          include("telas/".$config["tela_padrao"]."/opcoes.chats.php");
        break;
        case "download":
          include("telas/".$config["tela_padrao"]."/download.php");
        break;
        case "excluir":
          $linhaObj->RemoverArquivo($url[2], $url[4], $url[7], $linha, $idioma);
        break;
        default:
          header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
          exit();
      }
    } else {
      header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
      exit();
    }
  }
} else {
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_chats"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos", "c.*, a.nome as ava");
  $dadosArray = $linhaObj->ListarTodasChat();
  include("idiomas/".$config["idioma_padrao"]."/index.chats.php");
  include("telas/".$config["tela_padrao"]."/index.chats.php");
}
