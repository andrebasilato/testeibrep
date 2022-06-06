<?php

include("../classes/sindicatos.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Sindicatos();
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

    $linhaObj->Set("post",$_POST);
    if ($_POST[$config["banco"]["primaria"]]) {
        $salvar = $linhaObj->Modificar();
    } else {
        $salvar = $linhaObj->Cadastrar();
    }

    if ($salvar["sucesso"]){
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
} elseif($_POST["acao"] == "adicionar_arquivo") {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|6');
    $adicionar = $linhaObj->set('id', $url[3])
        ->set('post', $_POST)
        ->adicionarArquivo();
    //var_dump($adicionar);
    if($adicionar["sucesso"]){
        $linhaObj->set("pro_mensagem_idioma", $adicionar["mensagem"]);
        $linhaObj->set("url", "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif($_POST["acao"] == "remover_arquivo") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

    $linhaObj->Set("id", $url[3]);
    $linhaObj->Set("idarquivo", $_POST["idarquivo"]);
    $remover = $linhaObj->removerPastaVirtual();
    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $remover["mensagem"];
    }
} elseif ($_POST['acao'] == 'salvar_valores_curso') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|8');
    $linhaObj->config['formulario'] = $config['formulario_valores_curso'];
    $linhaObj->config['banco'] = $config['banco_valores_cursos'];
    $linhaObj->monitora_onde = $config['monitoramento']['onde_valores_cursos'];

    $salvar = $linhaObj->salvarValoresCursos((int) $url[3], $_POST['valores']);

    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'salvar_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/valores_cursos');
        $linhaObj->Processando();
    }
}

if(isset($url[3])){
    if($url[4] == "ajax_cidades"){

        if($_REQUEST['idestado']) {
            $linhaObj->RetornarJSON('cidades', mysql_real_escape_string($_REQUEST['idestado']), 'idestado', 'idcidade, nome', 'ORDER BY nome');
        } else {
            $linhaObj->RetornarJSON('cidades', $url[5], 'idestado', 'idcidade, nome', 'ORDER BY nome');
        }
        exit;
    } elseif($url[3] == "cadastrar" && $linhaObj->config['cadastrar_sindicato']) {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
        include("idiomas/".$config["idioma_padrao"]."/formulario.php");
        include("telas/".$config["tela_padrao"]."/formulario.php");
        exit;
    } else {
        $linhaObj->Set("id", (int) $url[3]);
        $linhaObj->Set('campos', 'i.*, m.nome_fantasia as mantenedora');
        $linha = $linhaObj->Retornar();

        if($linha) {
            switch ($url[4]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
                    include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                    include("telas/".$config["tela_padrao"]."/formulario.php");
                    break;
                case "remover":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
                    include("idiomas/".$config["idioma_padrao"]."/remover.php");
                    include("telas/".$config["tela_padrao"]."/remover.php");
                    break;
                case "acesso_ava":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    include("idiomas/" . $config["idioma_padrao"] . "/acesso_ava.php");
                    include("telas/" . $config["tela_padrao"] . "/acesso_ava.php");
                    break;
                case "opcoes":
                    include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                    include("telas/".$config["tela_padrao"]."/opcoes.php");
                    break;
                case "download":
                    include("telas/".$config["tela_padrao"]."/download.php");
                    break;
                case "pastavirtual":
                    if ($url[5] == 'downloadarquivo') {
                        $linhaObj->Set("iddocumento", intval($url[6]));
                        $download = $linhaObj->retornarArquivo();
                        include("telas/".$config["tela_padrao"]."/download.arquivos.php");
                        exit;
                    } else if ($url[5] == 'visualizararquivo') {
                        $download = $linhaObj->set('iddocumento', (int) $url[6])
                            ->retornarArquivo();
                        include("telas/".$config["tela_padrao"]."/visualizar.arquivos.php");
                        exit;
                    }
                    $arquivos = $linhaObj->retornarListaArquivos();
                    include("idiomas/".$config["idioma_padrao"]."/pastavirtual.php");
                    include("telas/".$config["tela_padrao"]."/pastavirtual.php");
                    break;
                case 'valores_cursos':
                    $formasPagamento = $forma_pagamento_faturas[$config['idioma_padrao']];
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|8');
                    $cursos = $linhaObj
                        ->set('id', $url[3])
                        ->listarSindicatosValoresCursos(

                            'c.idcurso,
                                    c.nome,
                                    svc.idsindicato,
                                    svc.idvalor_curso,
                                    svc.parcelas,
                                    svc.max_parcelas,
                                    svc.valor_por_matricula,
                                    svc.valor_por_matricula_2,
                                    svc.quantidade_faturas_ciclo,
                                    svc.quantidade_matriculas,
                                    svc.quantidade_matriculas_2,
                                    svc.valor_excedente'
                        );

                    $formasPagamentoCurso = [];

                    foreach ($cursos as $curso) {
                        $formasPagamentoUsadas = $linhaObj->retornarFormasPagamentoSindicatoCurso(
                            $url[3],
                            $curso['idcurso'],
                            'forma_pagamento'
                        );

                        $formasPagamentoCurso[$curso['idcurso']] = array_column(
                            $formasPagamentoUsadas,
                            'forma_pagamento'
                        );
                    }

                    include 'idiomas/' . $config['idioma_padrao'] . '/valores_cursos.php';
                    include 'telas/' . $config['tela_padrao'] . '/valores_cursos.php';
                    break;
                case "excluir":
                    include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
                    $linhaObj->RemoverArquivo($url[2], $url[5], $linha, $idioma);
                    break;
                case "json":
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                default:
                    header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
                    exit;
            }
        } else {
            header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
            exit;
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
    $linhaObj->Set("campos","i.*, m.nome_fantasia as mantenedora");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}
