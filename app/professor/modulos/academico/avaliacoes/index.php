<?php
include("../classes/avaliacoes.class.php");
include("config.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Avaliacoes();
//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	

$linhaObj->Set("idprofessor",$usu_professor["idprofessor"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


if($_POST["acao"] == "corrigir_avaliacao") {
    if($_FILES) {
        foreach($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }
    $linhaObj->Set("post",$_POST);
    $linhaObj->Set('id', $url[3]);
    $salvar = $linhaObj->CorrigirProva();
    if($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","corrigir_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);

        $linhaObj->Processando();
    }
}

if(isset($url[3])){

    $linhaObj->Set("id",intval($url[3]));
    $linhaObj->Set("campos","ma.*, 
    p.nome as aluno, 
    d.nome as disciplina, 
    aa.nome as avaliacao, 
    prof.nome as professor_correcao, 
    c.nome as curso, ma.nota");
    $linha = $linhaObj->Retornar();

    if($linha) {
        switch ($url[4]) {
            case "opcoes":
                include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                include("telas/".$config["tela_padrao"]."/opcoes.php");
                break;
            case "visualizar":
                $linhaObj->Set("id",intval($url[3]));
                $prova = $linhaObj->retornarProvaRespondida($url[3]);
                $historico = $linhaObj->RetornarHistorico();
                include("idiomas/".$config["idioma_padrao"]."/visualiza.php");
                include("telas/".$config["tela_padrao"]."/visualiza.php");
                break;
            case "corrigir":
                $linhaObj->Set("id",intval($url[3]));
                $prova = $linhaObj->retornarProvaRespondida($url[3]);
                $historico = $linhaObj->RetornarHistorico();
                include("idiomas/".$config["idioma_padrao"]."/visualiza.php");
                include("telas/".$config["tela_padrao"]."/visualiza.php");
                break;
            case "download_imagem_pergunta":
                $arquivo = $linhaObj->retornaArquivoPerguntaDownload($url[5]);
                include("telas/".$config["tela_padrao"]."/download_imagem_pergunta.php");
                break;
            case "download_arquivo_aluno":
                $arquivo = $linhaObj->retornaArquivoPerguntaAlunoDownload($url[5]);
                include("telas/".$config["tela_padrao"]."/download_arquivo_aluno.php");
                break;
            case "download_arquivo_professor":
                $arquivo = $linhaObj->retornaArquivoPerguntaProfessorDownload($url[5]);
                include("telas/".$config["tela_padrao"]."/download_arquivo_professor.php");
                break;
            default:
                header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
                exit();
        }
    } else {
        header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
        exit();
    }

} else {
    $linhaObj->Set("pagina",$_GET["pag"]);
    if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem",$_GET["ord"]);
    if(!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite",intval($_GET["qtd"]));
    if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo",$_GET["cmp"]);
    $linhaObj->Set("campos","ma.*, 
    p.nome as aluno,
    d.nome as disciplina, 
    aa.nome as avaliacao, 
    prof.nome as professor_correcao");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}
?>