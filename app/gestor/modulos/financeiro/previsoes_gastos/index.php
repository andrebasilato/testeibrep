<?php
include("../classes/previsoesgastos.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Previsoes_Gastos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);
$linhaObj->Set("modulo", $url[0]);

if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);

    if ($_POST[$config["banco"]["primaria"]]) {
        $salvar = $linhaObj->Modificar();
    } else{
        $salvar = $linhaObj->Cadastrar();
    }
    if ($salvar["sucesso"]) {
        if ($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma", "modificar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma", "cadastrar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2]);
        }
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->Remover();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }
}

if (isset($url[3])) {

    if ($url[3] == "cadastrar") {
        if ($url[4] == "ajax_subcategorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idcategoria']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idcategoria']));
                $linhaCatObj->Set("idsindicato", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarSubcategoriasSindicato();
            }
            exit();
        } else if ($url[4] == "ajax_categorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idsindicato']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarCategoriaSindicato();
            }
            exit();
        }

		$config['formulario'] = $linhaObj->config['formulario'];
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit();
    } else {

        if ($url[4] == "ajax_subcategorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idcategoria']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idcategoria']));
                $linhaCatObj->Set("idsindicato", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarSubcategoriasSindicato();
            }
            exit;
        } else if ($url[4] == "ajax_categorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idsindicato']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarCategoriaSindicato();
            }
            exit();
	
		}

        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("campos", "pg.*");
        $linha = $linhaObj->Retornar();

        if ($linha) {
            switch ($url[4]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.php");
                    break;
                case "remover":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");                    
                    include("idiomas/" . $config["idioma_padrao"] . "/remover.php");
                    include("telas/" . $config["tela_padrao"] . "/remover.php");
                    break;
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                    break;
                case "json":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
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
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = -1;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "pg.*, cat.nome as categoria");
    
    $dadosArray = $linhaObj->ListarTodas();
    
    $valorDespesa = 0;
    foreach ($dadosArray as $ind => $linha) {
        $valorDespesa += $linha["valor"];        
    }

    $linhaSindicatoObj = new Sindicatos();
    $sindicatos_usuario = $linhaSindicatoObj->retornarSindicatosUsuario($usuario["idusuario"]);

    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}