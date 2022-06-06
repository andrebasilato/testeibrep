<?php
$config['formulario_videos'] = array(
array(
    'fieldsetid' => 'dadosdoobjeto',
    'legendaidioma' => 'legendadadosdados',
    'campos' => array(
        array(
            'id' => 'form_idpasta',
            'nome' => 'idpasta',
            'nomeidioma' => 'form_select_pasta',
            'tipo' => 'select',
            'sql' => 'SELECT idpasta,nome FROM videotecas_pastas WHERE ativo="S"',
            'sql_valor' => 'idpasta',
            'sql_label' => 'nome',
            'class' => 'span4 selectpicker" data-live-search="true',
            'valor' => 'idpasta',
            // 'validacao' => array('required' => 'exibir_ava_vazio'),
            'banco' => true,
            'banco_string' => true
        ),
        array(
            'id' => 'form_idvideo',
            'nome' => 'idvideoteca',
            'nomeidioma' => 'form_select_video',
            'tipo' => 'select',
            'sql' => '',
            'sql_valor' => 'idvideo',
            'sql_label' => 'nome',
            'class' => 'span2 selectVideo" disabled="disabled',
            'valor' => 'idvideoteca',
            // 'validacao' => array('required' => 'exibir_ava_vazio'),
            'banco' => true,
            'banco_string' => true
        ),
        array(
            'id' => 'idava_video',
            'nome' => 'idava',
            'tipo' => 'hidden',
            'valor' => 'return $this->url["3"];',
            'banco' => true
        ),
    )
  )
);

$config["listagem_videos"] = array(
array(
    "id" => "idvideo",
    "variavel_lang" => "tabela_idvideo",
    "tipo" => "banco",
    "coluna_sql" => "v.idvideo",
    "valor" => "video_id",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 1,
    "tamanho" => 60
),

array(
    "id" => "idvideoteca",
    "variavel_lang" => "tabela_idvideoteca",
    "tipo" => "php",
    "coluna_sql" => "v.idvideoteca",
    "valor" => 'return "[[video][".$linha["idvideoteca"]."]]";',
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 1,
    "tamanho" => 80
),

array(
    "id" => "nome",
    "variavel_lang" => "tabela_nome",
    "tipo" => "banco",
    "evento" => "maxlength='100'",
    "coluna_sql" => "m.titulo",
    "valor" => "titulo",
    "busca" => true,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
),
array(
    "id" => "data_cad",
    "variavel_lang" => "tabela_datacad",
    "coluna_sql" => "cadastrado_em",
    "tipo" => "php",
    "valor" => 'return formataData($linha["cadastrado_em"],"br",1);',
    "tamanho" => "140"
),
array(
    'id' => 'opcoes',
    'variavel_lang' => 'tabela_opcoes',
    'tipo' => 'php',
    'coluna_sql' => 'v.arquivo',
    'valor' => '
    if($linha["variavel"] == "html5" || $linha["variavel"] == "interno") {
      require_once("../classes/videoteca/videoteca.pastas.class.php");
      $videotecaPastas = new VideotecaPastas(new Core);
      $caminho = $videotecaPastas->getPathNameById($linha["idpasta"]);
      $dominio = $this->config["videoteca_endereco"][rand(0, (count($this->config["videoteca_endereco"]) - 1))];
      $srcVideo = $dominio."/".$caminho->caminho."/".$linha["titulo"];
      $srcImg   = $dominio."/".$caminho->caminho."/".$linha["imagem"];
      $retorno = "
      <div class=\"video\">
        <button type=\"button\" class=\"btn btn-mini useVideo video-".$linha["variavel"]."\" data-url=\"".$srcVideo."\" imagem=\"".$srcImg."\">Inserir</button>";
    } else {
      $retorno = "
      <div class=\"video\">
        <button type=\"button\" class=\"btn btn-mini useVideo video-".$linha["variavel"]."\" data-url=\"".$linha["arquivo"]."\">Inserir</button>";
    }
    $retorno .=
    "
      <a class=\"btn dropdown-toggle btn-mini\"
          data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\"
          href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".
          $this->url["3"]."/".$this->url["4"]."/".$linha["video_id"]."/remover\"
          data-placement=\"left\"
          rel=\"tooltip\">Excluir </a>
     </div>";
     return $retorno',
    'busca_botao' => true,
    'tamanho' => '120'
    )
);

require '../classes/avas.videos.class.php';
require '../classes/dataaccess/db.php';
require '../classes/dataaccess/mysql.php';

/** @var $linhaObj \Videos */
$linhaObj = new Videos(new Core, new Zend_Db_Select(new Zend_Db_MySql));

$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|7');

$linhaObj->set('config', $config)
    ->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde_videos'])
    ->set('idava', (int) $url[3]);


$linhaObj->config['banco'] = $config['banco_videos'];
$linhaObj->config['formulario'] = $config['formulario_videos'];

if ('salvar_video' == $_POST['acao']) {

    if (! empty($linhaObj)) {
        $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|8');
        $linhaObj->set('post', $_POST);
    }

  if($_POST[$config['banco_videos']['primaria']])
    $salvar = $linhaObj->ModificarVideo();
  else
    $salvar = $linhaObj->cadastrarVideo();

  if($salvar){
    if($_POST[$config["banco_videos"]["primaria"]]) {
      $linhaObj->set('pro_mensagem_idioma','modificar_sucesso');
      $linhaObj->set('url',"/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
    } else {
      $linhaObj->set("pro_mensagem_idioma","cadastrar_sucesso");
      $linhaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    }
    $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_video") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
  $linhaObj->set("post",$_POST);
  $remover = $linhaObj->RemoverVideo();
  if($remover["sucesso"]){
    $linhaObj->set("pro_mensagem_idioma","remover_sucesso");
    $linhaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  }
}

if (isset($url[5])) {
  if('cadastrar' == $url[5]) {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|8');
    include("idiomas/".$config["idioma_padrao"]."/formulario.videos.php");
    include("telas/".$config["tela_padrao"]."/formulario.conteudos.editor.videos.php");
    exit();
  } else {
    $linhaObj->set('id', (int) $url[5]);
    $linhaObj->set('campos', 'v.*, a.nome as ava');
    $linha = $linhaObj->retornarVideo();

    if ($linha) {
      switch($url[6]) {
        case "editar":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
          include("idiomas/".$config["idioma_padrao"]."/formulario.videos.php");
          include("telas/".$config["tela_padrao"]."/formulario.conteudos.editor.videos.php");
        break;
        case "remover":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
          include("idiomas/".$config["idioma_padrao"]."/remover.videos.php");
          include("telas/".$config["tela_padrao"]."/remover.conteudos.editor.videos.php");
        break;
        case "opcoes":
          include("idiomas/".$config["idioma_padrao"]."/opcoes.videos.php");
          include("telas/".$config["tela_padrao"]."/opcoes.videos.php");
          exit;
        break;
        case "download":
          include("telas/".$config["tela_padrao"]."/download.php");
        break;
        case "excluir":
          include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
          $linhaObj->RemoverArquivo($url[2]."_".$url[4], $url[7], $linha, $idioma);
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
  $linhaObj->set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_videos"]["primaria"];
  $linhaObj->set("ordem_campo",$_GET["cmp"]);
  $linhaObj->set("campos","v.*, a.nome as ava");
  $dadosArray = $linhaObj->ListarTodasVideo();
  
  include("idiomas/".$config["idioma_padrao"]."/index.videos.php");
  include("telas/".$config["tela_padrao"]."/index.conteudos.editor.videos.php");
}