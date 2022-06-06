<?php
include('config.php');
include('config.formulario.php');
include('config.listagem.php');

// Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Categorias;

$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');

$linhaObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde']);

// Rotina para salvar uma categoria ou subcategoria
if ('salvar' == $_POST['acao'] || 'salvar_subcategoria' == $_POST['acao']) {

    $modificar_sucesso = 'modificar_sucesso';
    $cadastrar_sucesso = 'cadastrar_sucesso';

    if ('salvar_subcategoria' == $_POST['acao']) {

        $modificar_sucesso = 'modificar_sucesso_subcategoria';
        $cadastrar_sucesso = 'cadastrar_sucesso_subcategoria';

        $config['banco'] = $config['banco_subcategoria'];

        $linhaObj->config['banco'] = $config['banco_subcategoria'];
        $linhaObj->config['formulario'] = $config['formulario_subcategoria'];
    }

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

    $url_redireciona = "/{$url[0]}/{$url[1]}/{$url[2]}";

    if ($_POST['acao_url']) {
        $url_redireciona .= '?' . base64_decode($_POST['acao_url']);
    }

    $linhaObj->set('post', $_POST);

    if ($_POST[$config['banco']['primaria']]) {
        $salvar = $linhaObj->modificar();
    } else {
        $salvar = $linhaObj->cadastrar();
    }

    if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->set('pro_mensagem_idioma', ($_POST[$config['banco']['primaria']]) ? $modificar_sucesso : $cadastrar_sucesso);
        }
        $linhaObj->set('url', $url_redireciona)->processando();
    }
} elseif ($_POST['acao'] == 'remover' || $_POST['acao'] == 'remover_subcategoria') {

    $remover_sucesso = 'remover_sucesso';

    if ($_POST["acao"] == "remover_subcategoria") {
        $remover_sucesso = "remover_sucesso_subcategoria";
        $config["banco"] = $config["banco_subcategoria"];
        $linhaObj->config["banco"] = $config["banco_subcategoria"];
        $linhaObj->config["formulario"] = $config["formulario_subcategoria"];
    }

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->set('post', $_POST);

    $remover = $linhaObj->remover();

    if ($remover["sucesso"]) {

        $linhaObj->set("pro_mensagem_idioma", $remover_sucesso)
            ->set("url", "/{$url[0]}/{$url[1]}/{$url[2]}")
            ->processando();
    }
} elseif ($_POST["acao"] == "associar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AssociarSindicato();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSindicato();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
}


if (isset($url[3])) {

    if ('cadastrarcategoria' == $url[3]) {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.categoria.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.categoria.php");
        exit();
    } elseif ('cadastrarsubcategoria' == $url[3]) {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        $config["banco"] = $config["banco_subcategoria"];
        $linhaObj->config["banco"] = $config["banco_subcategoria"];
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.subcategoria.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.subcategoria.php");
        exit();
    } else {
        $linhaObj->Set("id", (int)$url[3]);


        if ($url[4] == "editarcategoria" || $url[4] == "removercategoria" || $url[4] == "opcoescategoria" || $url[4] == "associar_categoria") {
            $linhaObj->Set("campos", "*");
            $linha = $linhaObj->RetornarCategoria();
        } else {
            $linhaObj->Set("campos", "c.nome as categoria, cs.*");
            $linha = $linhaObj->RetornarSubcategoria();
        }

        if ($linha) {

            switch ($url[4]) {
                case "editarcategoria":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.categoria.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.categoria.php");
                    break;
                case "editarsubcategoria":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    $config["banco"] = $config["banco_subcategoria"];
                    $linhaObj->config["banco"] = $config["banco_subcategoria"];
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.subcategoria.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.subcategoria.php");
                    break;
                case "removercategoria":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                    include("idiomas/" . $config["idioma_padrao"] . "/remover.categoria.php");
                    include("telas/" . $config["tela_padrao"] . "/remover.categoria.php");
                    break;
				case "json":
					include("idiomas/" . $config["idioma_padrao"] . "/json.php");
					include("telas/" . $config["tela_padrao"] . "/json.php");
					break;
                case "removersubcategoria":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                    $config["banco"] = $config["banco_subcategoria"];
                    $linhaObj->config["banco"] = $config["banco_subcategoria"];
                    include("idiomas/" . $config["idioma_padrao"] . "/remover.subcategoria.php");
                    include("telas/" . $config["tela_padrao"] . "/remover.subcategoria.php");
                    break;
                case "opcoescategoria":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.categoria.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.categoria.php");
                    break;
                case "opcoessubcategoria":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.subcategoria.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.subcategoria.php");
                    break;
                case 'associar_subcategoria':
 					$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    $assocIterator = $linhaObj->retornarAssociacoes(Request::url(4));

                    $content = '';
                    foreach ($assocIterator as $associacao) {
                        $content .= print_r($associacao, 1);
                    }
					$linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("campos", "*");
                    $sindicatos = $linhaObj->ListarSindicatosAssociadas();
                    require 'idiomas/' . $config['idioma_padrao'] . '/associar.php';
                    require 'telas/' . $config['tela_padrao'] . '/associar.php';
                    break;
                default:
                    header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                    exit();
            }
        } else {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
            exit();
        }
    }
} else {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) {
        $_GET["ordem"] = "ASC";
    }
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = "categoria ASC, subcategoria ASC, idsubcategoria";
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "c.idcategoria, c.nome as categoria, c.idcategoria AS idsubcategoria, '- -' AS subcategoria, c.ativo_painel, c.data_cad, 'C' AS tipo");
    $linhaObj->Set("campos_2", "c.idcategoria, c.nome as categoria, cs.idsubcategoria, cs.nome AS subcategoria, cs.ativo_painel, cs.data_cad, 'S' AS tipo");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}