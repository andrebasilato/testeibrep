<?php
include("../classes/aulasonline.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new AulasOnLine();
$linhaObj->Set("idprofessor",$usu_professor["idprofessor"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if($_POST["acao"] == "salvar"){
    $linhaObj->Set("post",$_POST);
    $linhaObj->Set("config",$config);
    if($_POST[$config["banco"]["primaria"]])

        $salvar = $linhaObj->editar();
    else
        $salvar = $linhaObj->cadastrar();
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
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->remover();
    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
        $linhaObj->Processando();
    }
}

if(isset($url[3])){
    if($url[3] == "cadastrar") {
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
                    $linhaObj->verificaPermissaoProfessor($linha);
                    include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                    include("telas/".$config["tela_padrao"]."/formulario.php");
                    break;
                case "remover":
//                    $linhaObj->verificaPermissaoProfessor($linha);
//                    include("idiomas/".$config["idioma_padrao"]."/remover.php");
//                    include("telas/".$config["tela_padrao"]."/remover.php");
                    header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
                    exit();
                    break;
                case "opcoes":
                    include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                    include("telas/".$config["tela_padrao"]."/opcoes.php");
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
    $linhaObj->Set("campos","ao.*, d.nome as disciplina, p.nome as professor");
    $dadosArray = $linhaObj->listarTodasProfessor();
    //var_dump($dadosArray);
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}
