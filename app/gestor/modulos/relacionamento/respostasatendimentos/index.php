<?php
include("../classes/respostasatendimentos.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//error_reporting(-1);
//ini_set('display_errors', 1);

include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Respostas_Atendimentos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->set('idusuario', $usuario["idusuario"]);
$linhaObj->set('monitora_onde', $config["monitoramento"]["onde"]);

if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $url_redireciona = "/{$url[0]}/{$url[1]}/{$url[2]}";
    if ($_POST["acao_url"]) {
        $url_redireciona .= "?" . base64_decode($_POST["acao_url"]);
    }
    $linhaObj->set("post", $_POST);

    if ($_POST[$config["banco"]["primaria"]]) {
        $salvar = $linhaObj->Modificar();
    } else {
        $salvar = $linhaObj->Cadastrar();
    }

    if ($salvar["sucesso"]) {
        $message = ($_POST[$config["banco"]["primaria"]]) ? 'modificar_sucesso' : 'cadastrar_sucesso';

        $linhaObj->set('pro_mensagem_idioma', $message)->set('url', $url_redireciona)->processando();
    }
} elseif ($_POST["acao"] == "associar") {

    $currentUrl = $_SERVER['REQUEST_URI'];

    if (false == isset($_POST['idescola'])) {
       $_POST['idescola'] = array();
    }
    $_SESSION["msg"] = 'associacao_editado_com_sucesso';


    $linhaObj->associarRespostaAosEscolas(new ArrayObject($_POST['idescola']), Request::url(4));

} elseif ($_POST["acao"] == "remover") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->set("post", $_POST);
    $remover = $linhaObj->Remover();
    if ($remover["sucesso"]) {
        $linhaObj->set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "salvar_assuntos") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
    $linhaObj->set("id", intval($url[3]));

    $linhaObj->set("post", $_POST);
    $salvar = $linhaObj->AssociarAssuntos();

    if ($salvar["sucesso"]) {
        $linhaObj->set("pro_mensagem_idioma", "associacao_sucesso");
        $linhaObj->set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3]. "/" . $url[4]);
        $linhaObj->Processando();
    }
}


if (isset($url[3])) {

    if ($url[3] == "cadastrar") {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit();
    } else {

        $linhaObj->set("id", intval($url[3]));
        $linhaObj->set("campos", "*");
        $linha = $linhaObj->Retornar();

        if ($linha) {

            switch ($url[4]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.php");
                    break;
                case "relacionamento":
                    /** @var Sindicatos $sindicato */
                    $sindicato = new Sindicatos();
                    $intituicaoCollection = new ArrayObject($sindicato->ListarTodas());

                    $escola = new Escolas();
                    $escolaCollection = new ArrayObject($escola->listarTodas());

                    $htmlEscolas = '';
                    foreach ($escolaCollection->getIterator() as $escola) {
                        $sindicato = $intituicaoCollection->offsetGet($escola['idsindicato']);
                        $htmlEscolas .= sprintf('<input type="checkbox" name="escolas[]" value="%s"> %s<br />', $escola['idescola'], $escola['nome'] . ' / ' . $sindicato['nome']);
                    }


                    // Seleciona os elementos
                    $checked = $linhaObj->retornarEscolasAssociados(Request::url(4));

                    $javascript = '<script>
                        window.onload = function(){';

                    foreach ($checked as $escola) {
                        $javascript .= '$(\'.form-horizontal input[value="' . $escola['idescola'] . '"]\').attr(\'checked\', \'checked\');';
                    }

                    $javascript .= '};;
                    </script>';

                    include("idiomas/" . $config["idioma_padrao"] . "/relacionamento.php");
                    include("telas/" . $config["tela_padrao"] . "/relacionamento.php");
                    break;
                case "assuntos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");

                    $linhaObj->set("id", intval($url[3]));
                    $linhaObj->set("ordem", "desc");
                    $linhaObj->set("limite", -1);
                    $linhaObj->set("ordem_campo", "nome");
                    $linhaObj->set("campos", "nome, idassunto");
                    $dadosArray = $linhaObj->ListarAssuntos();

                    $linhaObj->set("id", intval($url[3]));
                    $linhaObj->set("ordem", "desc");
                    $linhaObj->set("limite", -1);
                    $linhaObj->set("ordem_campo", "idresposta_assunto");
                    $linhaObj->set("campos", "idassunto");
                    $dadosAssoc = $linhaObj->RetornarAssuntosAssoc();

                    include("idiomas/" . $config["idioma_padrao"] . "/assuntos.php");
                    include("telas/" . $config["tela_padrao"] . "/assuntos.php");
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
                default:
                    header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
                    exit();
            }

        } else {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
            exit();
        }

    }

} else {
    $linhaObj->set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"])
        $_GET["ordem"] = "desc";
    $linhaObj->set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"])
        $_GET["qtd"] = 30;
    $linhaObj->set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"])
        $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->set("ordem_campo", $_GET["cmp"]);
    $linhaObj->set("campos", "*");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}