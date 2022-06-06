<?php
//include("../classes/relacionamentocomercial.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");
    
    
//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
    
$linhaObj = new RelacionamentosComerciais();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");  
    
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);
$linhaObj->Set('modulo', $url[0]);


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
          $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$salvar["id"]."/administrar");
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
} elseif($_POST["acao"] == "salvar_mensagem") {
    if($_POST["mensagem"]) {
        $linhaObj->Set("post",$_POST);
        $linhaObj->Set('id',$url[3]);
        $salvar = $linhaObj->adicionarMensagem();
        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","mensagem_adicionada_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
            $linhaObj->Processando();
        } else {  
            $mensagem["erro"] = $salvar["mensagem"];
        }     
    } else {
        $salvar["sucesso"] = false;
        $salvar["erros"][] = "mensagem_vazia";
    }
} elseif($_POST["acao"] == "remover_mensagem") {
    if($_POST["idmensagem"]) {
        $linhaObj->Set('id',$url[3]);
        $remover = $linhaObj->removerMensagem((int) $_POST["idmensagem"]);
        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
            $linhaObj->Processando();
        } else {  
            $mensagem["erro"] = $remover["mensagem"];
        }
    } else {
        $mensagem["erro"] = "mensagem_remover_vazio";
    }
} elseif($_POST["acao"] == "alterar_dados_relacionamento") {
    $linhaObj->Set("id", (int)$url[3]);
    $linhaObj->Set("post",$_POST);
    $salvar = $linhaObj->editarDadosRelacionamento();
    if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma",'alteracao_dados_sucesso');
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $linhaObj->Set("ancora","dadosrelacionamento");
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $salvar["mensagem"];
    }
}

if(isset($url[3])){ 
    if($url[3] == "cadastrar") {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
        include("idiomas/".$config["idioma_padrao"]."/formulario.php");
        include("telas/".$config["tela_padrao"]."/formulario.php");
        exit();
    } else {

        $linhaObj->Set("id",(int) $url[3]);
        $linhaObj->Set("campos","*");   
        $linha = $linhaObj->Retornar();

        if($linha) {
    
            switch ($url[4]) {
                case "json":
                    include("telas/".$config["tela_padrao"]."/json.php");
                break;
                case "administrar":
                    $linhaObj->Set("campos","rcm.*, 
                            ua.nome as usuario, 
                            ua.idusuario, 
                            v.nome as vendedor, 
                            v.idvendedor");
                    $linhaObj->Set('ordem','desc');
                    $linhaObj->Set('groupby','rcm.idmensagem'); 
                    $linhaObj->Set('ordem_campo','rcm.idmensagem');
                    $linhaObj->Set('limite',-1);
                    $mensagensPessoa = $linhaObj->retornarMensagensRelacionamento();


                    $historicos = $linhaObj->RetornarHistoricos();
                    include("idiomas/".$config["idioma_padrao"]."/administrar.php");
                    include("telas/".$config["tela_padrao"]."/administrar.php");
                break;      
                default:
                    header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
                    exit();
            }
        } else {
            header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
            exit();
        }
    }
} else {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    $linhaObj->Set("pagina",$_GET["pag"]);
    if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem",$_GET["ord"]);
    if(!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite",(int) $_GET["qtd"]);
    if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo",$_GET["cmp"]);
    $linhaObj->Set("campos","rc.*");
    $linhaObj->Set("id",(int)$url[3]);
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
    exit();
}
?>