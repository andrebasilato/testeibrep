<?php

include("../classes/categorias.class.php");
$categoriasObj = new Categorias();
$categoriasObj->Set("idusuario", $usuario["idusuario"]);
$categoriasObj->Set("pagina", 1);
$categoriasObj->Set("ordem", "ASC");
$categoriasObj->Set("limite", -1);
$categoriasObj->Set("funcionalidade", $url[2]);
$categoriasObj->Set("ordem_campo", "categoria ASC, subcategoria ASC, idsubcategoria");
$categoriasObj->Set("campos", "c.idcategoria, c.nome as categoria, c.idcategoria AS idsubcategoria, '- -' AS subcategoria, c.ativo_painel, c.data_cad, 'C' AS tipo");
$categoriasObj->Set("campos_2", "c.idcategoria, c.nome as categoria, cs.idsubcategoria, cs.nome AS subcategoria, cs.ativo_painel, cs.data_cad, 'S' AS tipo");
$categoriasArray = $categoriasObj->ListarTodas();


include("../classes/sindicatos.class.php");
$sindicatoObj = new Sindicatos();
$sindicatoObj->Set("idusuario", $usuario["idusuario"]);
$sindicatoObj->Set("pagina", 1);
$sindicatoObj->Set("ordem", "desc");
$sindicatoObj->Set("limite", -1);
$sindicatoObj->Set("ordem_campo", "idsindicato");
$sindicatoObj->Set("campos", "i.*, m.nome_fantasia as mantenedora");
$sindicatosArray = $sindicatoObj->ListarTodas();

include("../classes/orcamentosplanejados.class.php");
include("config.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Orcamentos_Planejados();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);

if ($_POST["acao"] == "salvar") {
    //print_r2($_POST,true);
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->CadastrarModificar();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "cadastrar_modificar_sucesso");
        $get = "";
        if ($_GET["de_mes"] || $_GET["de_ano"] || $_GET["ate_mes"] || $_GET["ate_ano"]) {
            $get = "?idsindicato=" . $_GET["idsindicato"] . "&de_mes=" . $_GET["de_mes"] . "&de_ano=" . $_GET["de_ano"] . "&ate_mes=" . $_GET["ate_mes"] . "&ate_ano=" . $_GET["ate_ano"];
        }
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . $get);
        $linhaObj->Processando();
    }
}

if (!$_GET["de_mes"]) $_GET["de_mes"] = date("m", mktime(0, 0, 0, date("m") - 2, 1, date("Y")));
if (!$_GET["de_ano"]) $_GET["de_ano"] = date("Y", mktime(0, 0, 0, date("m") - 2, 1, date("Y")));
if (!$_GET["ate_mes"]) $_GET["ate_mes"] = date("m", mktime(0, 0, 0, date("m") + 1, 1, date("Y")));
if (!$_GET["ate_ano"]) $_GET["ate_ano"] = date("Y", mktime(0, 0, 0, date("m") + 1, 1, date("Y")));

if ($_GET["idsindicato"]) {

    $sindicatoObj->Set("id", (int)$_GET["idsindicato"]);
    $sindicatoObj->Set('campos', 'i.*, m.nome_fantasia as mantenedora');
    $sindicatoSelecionada = $sindicatoObj->Retornar();

    $linhaObj->Set("idsindicato", $_GET["idsindicato"]);
    $orcamentos = $linhaObj->ListarTodas();

    $aux = array();
    foreach ($orcamentos as $ind => $orcamento) {


        $dataDados = explode("-", $orcamento["mes"]);
        $dataIndice = $dataDados[0] . "-" . $dataDados[1];
        $aux[$orcamento["idcategoria"]][$dataIndice] = $orcamento;

    }

    $orcamentos = $aux;

}

$mesesArray = array();
$dataInicio = date("m/Y", mktime(0, 0, 0, $_GET["de_mes"], 1, $_GET["de_ano"]));
$dataFim = date("m/Y", mktime(0, 0, 0, $_GET["ate_mes"] + 1, 1, $_GET["ate_ano"]));
$deMes = $_GET["de_mes"];
for ($data = $dataInicio; $data != $dataFim; $data = date("m/Y", mktime(0, 0, 0, ++$deMes, 1, $_GET["de_ano"]))) {
    $mesesArray[] = $data;
}


include("idiomas/" . $config["idioma_padrao"] . "/index.php");
include("telas/" . $config["tela_padrao"] . "/index.php");
