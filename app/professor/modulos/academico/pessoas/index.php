<?php
include("../classes/professores.class.php");
include("../classes/pessoas.class.php");
include("config.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Pessoas();

$linhaObj->Set("idprofessor",$usu_professor["idprofessor"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if(isset($url[3])){
    $linhaObj->Set("id",intval($url[3]));
    $linhaObj->Set("campos","p.*, pa.nome as pais");    
    $linha = $linhaObj->Retornar();

    if ($linha){
        switch ($url[4]) {
            case "acessarcomo":
                $_SESSION["cliente_email"] = $linha["email"];
                $_SESSION["cliente_senha"] = $linha["senha"];
                $_SESSION["cliente_idpessoa"] = $linha["idpessoa"];
                $_SESSION["cliente_nome"] = $linha["nome"];           
                $_SESSION["cliente_ultimoacesso"] = $linha["ultimo_acesso"];
                $_SESSION["cliente_professor"] = $usu_professor["idprofessor"];
                unset($_SESSION["cliente_gestor"]);

                $linhaObj->Set("monitora_oque","9");
                $linhaObj->Set("monitora_qual",$linha["idpessoa"]);
                $linhaObj->Set("monitora_dadosnovos",$linhaNova);
                $linhaObj->Monitora();          

                $linhaObj->Set("url","/aluno");
                $linhaObj->Processando();  

            default:
                header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
                exit();
        }
    } else{
        header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
        exit();
    }
} else {
    $linhaObj->Set("pagina",$_GET["pag"]);
    $linhaObj->Set("ordem_campo",($_GET["cmp"]) ? $_GET["cmp"] : $config["banco"]["primaria"]);
    $linhaObj->Set("ordem",($_GET["ord"]) ? $_GET["ord"] : "DESC");
    $linhaObj->Set("limite",(intval($_GET["qtd"])) ? intval($_GET["qtd"]) : 30);
    $linhaObj->Set("campos","p.*");  
    $dadosArray = $linhaObj->ListarTodasProfessor();
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}