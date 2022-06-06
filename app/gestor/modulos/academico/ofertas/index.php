<?php

ini_set('memory_limit', '512M');
set_time_limit(0);

include("../classes/ofertas.class.php");
include("../classes/curriculos.class.php");
include("../classes/detran.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Ofertas();
$detranObj = new Detran();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


if($_POST["acao"] == "salvar"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    $linhaObj->Set("post",$_POST);
    if($_POST[$config["banco"]["primaria"]])
        $salvar = $linhaObj->Modificar();
    else{
        $linhaObj->Set("config",$config);
        $salvar = $linhaObj->Cadastrar();
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
} elseif($_POST["acao"] == "associar_curso"){
    if ($_POST["cursos"] && $_POST["possui_financeiro"]) {
        $linhaObj->verificaPermissao($perfil["permissoes"],$url[2]."|5");
        $salvar = $linhaObj->AssociarCursos(intval($url[3]), $_POST["cursos"], $_POST["possui_financeiro"]);

        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cursos");
            $linhaObj->Processando();
        }
    }else{
        $salvar["erros"]["msg"] = "erro_cadastrar_sem_curso";
    }
} elseif($_POST["acao"] == "associar_escola"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
    $salvar = $linhaObj->AssociarEscolas(intval($url[3]), $_POST["escolas"]);

    if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cfc");
        $linhaObj->Processando();
    }
} elseif($_POST["acao"] == "cadastrar_turma"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
    $salvar = $linhaObj->CadastrarTurma(intval($url[3]), $_POST["turma"]);

    if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/turmas");
        $linhaObj->Processando();
    }
} else if($_POST["acao"] == "alterarSituacao") {
    $linhaObj->Set("id",intval($url[3]));
    $linhaObj->Set("campos","o.*");
    $oferta = $linhaObj->Retornar();
    if($oferta["situacao"]["visualizacoes"][1]) {
        $alterar = $linhaObj->AlterarSituacao($oferta["idsituacao"],$_POST["situacao_para"]);
    } else {
        $alterar["sucesso"] = false;
        $alterar["mensagem"] = "mensagem_permissao_workflow";;
    }
    if($alterar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma",$alterar["mensagem"]);
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
        $linhaObj->Set("ancora","situacao_oferta");
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $alterar["mensagem"];
    }
}

if($url[4] == "ajax_curriculos"){
    $ofertaCurrriculoCurso = $linhaObj->ListarCurriculosCurso(intval($_REQUEST['idescola']));
    echo json_encode($ofertaCurrriculoCurso);
    exit();
}

if(isset($url[3])){
    if($url[3] == "cadastrar") {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
        $situacaoInicial = $linhaObj->RetornarSituacaoInicial();
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

                    $situacaoWorkflow = $linhaObj->RetornarSituacoesWorkflow();
                    $situacaoWorkflowRelacionamento = array();
                    foreach($linhaObj->RetornarRelacionamentosWorkflow($linha['idsituacao']) as $sit)
                        $situacaoWorkflowRelacionamento[] = $sit['idsituacao_para'];

                    include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                    include("telas/".$config["tela_padrao"]."/formulario.php");
                    break;
                case "imprimir":
                    include("idiomas/".$config["idioma_padrao"]."/index.impressao.php");
                    include("telas/".$config["tela_padrao"]."/index.impressao.php");
                    break;
                case "impressao":
                    $linhaObj->Set("id",$url[3]);
                    $linhaObj->Set("campos","*");
                    $linhaObj->Set("limite",-1);
                    $impressao['oferta'] = $linhaObj->Retornar();
                    $linhaObj->Set("campos","c.nome, c.idcurso");
                    $impressao["cursos"] = $linhaObj->ListarCursosAssociados();
                    $linhaObj->Set("campos","p.nome_fantasia as nome, p.idescola, i.nome_abreviado as sindicato");
                    $impressao["escolas"] = $linhaObj->ListarEscolasAssociados();
                    $linhaObj->Set("campos","t.nome, t.idturma");
                    $impressao["turmas"] = $linhaObj->ListarTurmas();

                    //Matriculas do Escola Curso Turma
                    $impressao["matriculas_p_c_t"] = $linhaObj->ListarMatriculasCursosEscolasTurmas();
                    // Configurações do Escola Curso
                    $impressao["configuracao_p_c"] = $linhaObj->ConfiguracaoEscolasCursos();
                    include("idiomas/".$config["idioma_padrao"]."/impressao.php");
                    include("telas/".$config["tela_padrao"]."/impressao.php");
                    break;
                case "cursos":
                    include("index.cursos.php");
                    break;
                case "cfc":
                    include("index.escolas.php");
                    break;
                case "turmas":
                    include("index.turmas.php");
                    break;
                case "turmas_sindicatos":
                    include("index.turmas.sindicatos.php");
                    break;
                case "cursos_cfc":
                    //echo "<pre>";print_r($_POST["escolas"]);exit;
                    include("index.cursos.escolas.php");
                    break;
                case "cursos_sindicatos":
                    include("index.cursos.sindicatos.php");
                    break;
                case "curriculos_avas":
                    include("index.curriculos.avas.php");
                    break;
                case "clonar":
                    include("index.clonar.php");
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
                case "json":
                    include("idiomas/".$config["idioma_padrao"]."/json.php");
                    include("telas/".$config["tela_padrao"]."/json.php");
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
    if(!$_GET["ord"]) $_GET["ord"] = "asc";
    $linhaObj->Set("ordem",$_GET["ord"]);
    if(!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite",intval($_GET["qtd"]));
    if(!$_GET["cmp"]) $_GET["cmp"] = "ow.ordem"; //$config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo",$_GET["cmp"]);
    $campos = "o.*, ow.nome as situacao, ow.ordem as ordem_situacao, ow.cor_bg as situacao_cor_bg, ow.cor_nome as situacao_cor_nome";
    $campos .= ", (select count(idturma) from ofertas_turmas ot where ot.idoferta = o.idoferta and ot.ativo = 'S' ) as turmas";
    $campos .= ", (select count(idmatricula) from matriculas m where m.idoferta = o.idoferta  and m.ativo = 'S') as matriculas";
    $linhaObj->Set("campos",$campos);
    $dadosArray = $linhaObj->ListarTodas();
    foreach ($dadosArray as $array => $oferta){//Se em nenhum momento não encontrar espaco no "nome", sera colocado "espaco"! para evitar quebra do layout
     if (!mb_strpos($oferta["nome"], ' ')) {
         $oferta['nome'] =  wordwrap($oferta["nome"], 30, " ", true);
       $dadosArray[$array]['nome'] = $oferta['nome'];
     }
    }
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}
?>