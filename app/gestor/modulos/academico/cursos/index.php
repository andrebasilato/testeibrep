<?php
include("../classes/cursos.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Cursos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    $linhaObj->Set("post",$_POST);
    //print_r2($linhaObj->config['formulario'],true);
    if ($_POST[$config["banco"]["primaria"]])
        $salvar = $linhaObj->Modificar();
    else
        $salvar = $linhaObj->Cadastrar();
    if ($salvar["sucesso"]) {
        if ($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
        }
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->Remover();
    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_area") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
    $salvar = $linhaObj->AssociarAreas(intval($url[3]), $_POST["areas"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","associar_area_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_area") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->RemoverAreas();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","remover_area_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
    $salvar = $linhaObj->AssociarSindicato(intval($url[3]), $_POST["sindicatos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","associar_sindicato_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->RemoverSindicato();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","remover_sindicato_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    }
}elseif ($_POST["acao"] == "salvar_email_boasvindas") {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
    $linhaObj->Set("id",$url[3]);
    //$linha = $linhaObj->Retornar($sql);

    $linhaObj->Set("post",$_POST);
    $salvar = $linhaObj->alterarEmailBoasVindas();

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","alterar_email_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/emailboasvindas");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "salvar_ava") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
    $linhaObj->config["formulario"] = $config["formulario_ava"];
    if ($_FILES) {
        foreach($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }
    $linhaObj->Set("post",$_POST);
    if ($_POST[$config["banco"]["primaria"]])
        $salvar = $linhaObj->Modificar();
    else
        $salvar = $linhaObj->Cadastrar();
    if ($salvar["sucesso"]) {
        if ($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
        }
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "salvar_certificado_diploma") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
    $linhaObj->Set("post",$_POST);
    $salvar = $linhaObj->salvarCertificado();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma","salvar_certificado_diploma_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    }
}  elseif ($_POST["acao"] == "editar_dados_cursosindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

    $linhaObj->config['formulario'] = $config['formulario_cursosindicato'];
    $linhaObj->config["banco"] = $config["banco_cursosindicato"];

    $linhaObj->set("idusuario",$usuario["idusuario"]);
    $linhaObj->set("monitora_onde",$config["monitoramento_cursosindicato"]["onde"]);
    //$linhaObj->set("config",$config);
    $linhaObj->set("post",$_POST);
    $salvar = $linhaObj->modificarCursoSindicato();

    if ($salvar["sucesso"]) {
        $linhaObj->set("pro_mensagem_idioma","editar_dados_cursosindicato_sucesso");
        $linhaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->processando();
    } else {
        $mensagem["erro"] = "editar_dados_cursosindicato_erro";
    }
}


if (isset($url[3])) {
    if ($url[3] == "cadastrar") {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
        include("idiomas/".$config["idioma_padrao"]."/formulario.php");
        include("telas/".$config["tela_padrao"]."/formulario.php");
        exit();
    } else {
        $linhaObj->Set("id",intval($url[3]));
        $linhaObj->Set("campos","c.*");
        $linha = $linhaObj->Retornar();

        if ($linha) {
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
                case "opcoes":
                    include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                    include("telas/".$config["tela_padrao"]."/opcoes.php");
                    break;
                case "areas":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");

                    $linhaObj->Set("id",intval($url[3]));
                    $linhaObj->Set("ordem","asc");
                    $linhaObj->Set("limite",-1);
                    $linhaObj->Set("ordem_campo","nome");
                    $linhaObj->Set("campos","ca.idcurso_area, ca.idcurso, a.idarea, a.nome");
                    $associacoesArray = $linhaObj->ListarAreasAssociadas();

                    include("idiomas/".$config["idioma_padrao"]."/areas.php");
                    include("telas/".$config["tela_padrao"]."/areas.php");
                    break;
                case "sindicatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

                    if ($url[5] == 'editarsindicato') {

                        $linhaObj->config['formulario_cursosindicato'] = $config['formulario_cursosindicato'];
                        $linhaObj->config["banco"] = $config["banco_cursosindicato"];

                        $linhaObj->Set("idusuario",$usuario["idusuario"]);
                        $linhaObj->Set("campos","ci.*");
                        $linhaObj->Set("id",intval($url[6]));
                        $curso_sindicato = $linhaObj->RetornarCursoSindicato();

                        include("idiomas/".$config["idioma_padrao"]."/editar.cursosindicato.php");
                        include("telas/".$config["tela_padrao"]."/editar.cursosindicato.php");
                        exit;

                    }

                    $linhaObj->Set("id",intval($url[3]));
                    $linhaObj->Set("ordem","asc");
                    $linhaObj->Set("limite",-1);
                    $linhaObj->Set("ordem_campo","i.nome");
                    $linhaObj->Set("campos","ci.*, i.nome_abreviado as nome");
                    $associacoesArray = $linhaObj->ListarSindicatosAssociadas();

                    $certificadosObj = new Certificados;
                    $certificadosObj->Set("ordem","asc");
                    $certificadosObj->Set("limite",-1);
                    $certificadosObj->Set("ordem_campo","nome");
                    $certificadosObj->Set("campos","*");
                    $certificados = $certificadosObj->ListarTodas();

                    include("idiomas/".$config["idioma_padrao"]."/sindicatos.php");
                    include("telas/".$config["tela_padrao"]."/sindicatos.php");
                    break;
                case "emailboasvindas":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
                    include("idiomas/".$config["idioma_padrao"]."/email_boas_vindas.php");
                    include("telas/".$config["tela_padrao"]."/email_boas_vindas.php");
                    break;
                case "json":
                    include("idiomas/".$config["idioma_padrao"]."/json.php");
                    include("telas/".$config["tela_padrao"]."/json.php");
                    break;
                case "ava":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
                    include("idiomas/".$config["idioma_padrao"]."/ava.php");
                    include("telas/".$config["tela_padrao"]."/ava.php");
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
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem",$_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite",intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo",$_GET["cmp"]);
    $linhaObj->Set("campos","c.*");
    $dadosArray = $linhaObj->ListarTodas();
    foreach ($dadosArray as $array => $curso){//Se em nenhum momento não encontrar espaco no "nome", sera colocado "espaco"! para evitar quebra do layout
     if (!mb_strpos($curso["nome"], ' ')) {
      $curso['nome'] =  wordwrap($curso["nome"], 30, " ", true);
      $dadosArray[$array]['nome'] = $curso['nome'];
     }
    }
    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}
?>