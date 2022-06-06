<?php
include("../classes/avas.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Ava();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


if($_POST["acao"] == "salvar"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    $linhaObj->Set("post",$_POST);
    if($_POST[$config["banco"]["primaria"]])
        $salvar = $linhaObj->Modificar();
    else
        $salvar = $linhaObj->Cadastrar();
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
} elseif($_POST["acao"] == "clonar_ava"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|60");
    $linhaObj->Set("post",$_POST);
    $linhaObj->Set("idava",$url[3]);
    $sucesso = $linhaObj->clonarAva();

    if($sucesso["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","clonado_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
        $linhaObj->Processando();
    }
}

if(isset($url[3])){

    // lista vídeos de uma pasta
    // usado na aba "Videos" do ava
    if ('api' == $url[3]) {
        if ('lista-de-videos' == $url[4]) {
            $_folderId = $_POST['pasta'] = (int) $_POST['pasta'];
            $stmt = mysql_query('SELECT * FROM videotecas WHERE ativo = "S" AND idpasta = ' . $_folderId);

            $_listOfVideos = array();
            while ($temp = mysql_fetch_assoc($stmt)) {
                $_listOfVideos[] = $temp;
            }

            echo json_encode($_listOfVideos);
            exit;
        }
    }

    if($url[3] == "cadastrar") {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
        include("idiomas/".$config["idioma_padrao"]."/formulario.php");
        include("telas/".$config["tela_padrao"]."/formulario.php");
        exit();
    } else {
        $linhaObj->Set("id",intval($url[3]));
        $linhaObj->Set("campos","*");
        $linha = $linhaObj->Retornar();

        if($linha) {
            switch ($url[4]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
                    //$linha["modulos"] = unserialize($linha["modulos"]);
                    include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                    include("telas/".$config["tela_padrao"]."/formulario.php");
                    break;
                case "clonarava":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|60");
                    //$linha["modulos"] = unserialize($linha["modulos"]);
                    include("idiomas/".$config["idioma_padrao"]."/clonarava.php");
                    include("telas/".$config["tela_padrao"]."/clonarava.php");
                    break;
                case "remover":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
                    include("idiomas/".$config["idioma_padrao"]."/remover.php");
                    include("telas/".$config["tela_padrao"]."/remover.php");
                    break;
                case "opcoes":
                    include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                    include("telas/".$config["tela_padrao"]."/opcoes.php");
                    break;
                case "conteudos":
                    include("index.conteudos.php");
                    break;
                case "objetosdivisores":
                    include("index.objetosdivisores.php");
                    break;
                case "videos":
                    include("index.videos.php");
                    break;
                case 'editor-videos':
                    include('index.conteudos.editor.videos.php');
                    break;
                case "audios":
                    include("index.audios.php");
                    break;
                case 'editor-audios':
                    include('index.conteudos.editor.audios.php');
                    break;
                case "downloads":
                    include("index.downloads.php");
                    break;
                case "links":
                    include("index.links.php");
                    break;
                case 'discovirtual':
                    include('index.discovirtual.php');
                    break;
                case 'editor-discovirtual':
                    include('index.conteudos.editor.discovirtual.php');
                    break;
                case "abrirforuns":
                    include("idiomas/".$config["idioma_padrao"]."/abrirforuns.php");
                    include("telas/".$config["tela_padrao"]."/abrirforuns.php");
                    break;
                    break;
                case "foruns":
                    include("index.foruns.php");
                    break;
                case "perguntas":
                    include("index.perguntas.php");
                    break;
                case "avaliacoes":
                    include("index.avaliacoes.php");
                    break;
                case "exercicios":
                    include("index.exercicios.php");
                    break;
                case "simulados":
                    include("index.simulados.php");
                    break;
                case "enquetes":
                    include("index.enquetes.php");
                    break;
                case "disciplinas":
                    include("index.disciplinas.php");
                    break;
                case "aulaonline":
                    include("index.aulaonline.php");
                    break;
                case "rotasdeaprendizagem":
                    include("index.rotasdeaprendizagem.php");
                    break;
                case "chats":
                    include("index.chats.php");
                    break;
                case "faqs":
                    include("index.faqs.php");
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
    $linhaObj->Set("campos","a.*");
    $dadosArray = $linhaObj->ListarTodas();
    foreach ($dadosArray as $array => $ava) {//Se em nenhum momento não encontrar espaco no "nome", sera colocado "espaco"! para evitar quebra do layout
        if (!mb_strpos($ava["nome"], ' ')) {
            $ava['nome'] = wordwrap($ava["nome"], 30, " ", true);
            $dadosArray[$array]['nome'] = $ava['nome'];
        }
    }
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}
?>