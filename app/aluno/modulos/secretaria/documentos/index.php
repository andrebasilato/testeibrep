<?php
require '../classes/matriculas.class.php';

$matriculaObj = new Matriculas;
$matriculas = $matriculaObj->set('idpessoa', $usuario['idpessoa'])
    ->set('modulo', $url[0])
    ->set('limite', -1)
    ->set('ordem_campo', 'm.idmatricula')
    ->set('ordem', 'desc')
    ->set('campos', 'm.*,
					c.nome as curso,
					c.carga_horaria_total,
					c.imagem_exibicao_servidor,
                    IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porcentagem,
					IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porc_aluno')
    ->retornarMeusCursos();
foreach ($matriculas as $chave => $valor)
{
    $matriculaObj->set('id', $valor['idmatricula']);
    $matriculaObj->set('matricula', array(
        "idoferta" => $valor['idoferta'],
        "idcurso" => $valor['idcurso'],
        "idescola" => $valor['idescola'],
    ));
    $matriculas[$chave]['porcentagem'] = $matriculaObj->porcentagemCursoAtual((int)$valor['idmatricula']);
}
if ($_POST['acao'] == 'adicionar_documento') {
    //Passa os arquivos para o post, por causa da validação
    if ($_FILES) {
        foreach ($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }
    include_once("../includes/validation.php");
    $regras = array(); // stores the validation rules
    $regras[] = "required,idmatricula,matricula_vazio";
    $regras[] = "required,idtipo,idtipo_vazio";
    $regras[] = "file_required,documento,documento_vazio";
    $regras[] = "formato_arquivo,documento,jpg|jpeg|gif|png|bmp|pdf,2048,documento_invalido";
    $regras[] = "tamanho_arquivo,documento,jpg|jpeg|gif|png|bmp|pdf,2048,documento_tamanho_invalido";

    //VALIDANDO FORMULARIO
    $erros = validateFields($_POST, $regras);

    if (!empty($erros)) {
        $adicionar["erro"] = true;
        $adicionar["erros"] = $erros;
        require 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
    } else {
        $matriculaObj->set("id",intval($_POST["idmatricula"]));
        $matricula = $matriculaObj->Retornar();

        $matricula['situacao'] = $matriculaObj->RetornarSituacao($matricula['idsituacao']);

        if ($matricula["situacao"]["visualizacoes"][7]) {
            $matriculaObj->set("id",$matricula["idmatricula"]);
            $matriculaObj->Set("post", $_POST);
            $adicionar = $matriculaObj->adicionarDocumento();
        } else {
            $adicionar["sucesso"] = false;
            $adicionar["mensagem"] = "mensagem_permissao_workflow";;
        }

        if($adicionar["sucesso"]){
            $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
            $matriculaObj->Set('url','/'.$url[0].'/'.$url[1].'/'.$url[2]);
            $matriculaObj->Processando();
        } else {
            $mensagem["erro"] = $adicionar["mensagem"];
        }
    }
} elseif($_POST["acao"] == "enviar_documento") {
    //Passa os arquivos para o post, por causa da validação
    if ($_FILES) {
        foreach ($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }
    include_once("../includes/validation.php");
    $regras = array(); // stores the validation rules
    $regras[] = "required,matricula,matricula_vazio";
    $regras[] = "file_required,documento,documento_vazio";
    $regras[] = "formato_arquivo,documento,jpg|jpeg|gif|png|bmp|pdf,2048,documento_invalido";
    $regras[] = "tamanho_arquivo,documento,jpg|jpeg|gif|png|bmp|pdf,2048,documento_tamanho_invalido";

    //VALIDANDO FORMULARIO
    $erros = validateFields($_POST, $regras);

    if (!empty($erros)) {
        $adicionar["erro"] = true;
        $adicionar["erros"] = $erros;
        require 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
    } else {
        $matriculaObj->set("id",intval($_POST["matricula"]));
        $matricula = $matriculaObj->Retornar();

        $matricula['situacao'] = $matriculaObj->RetornarSituacao($matricula['idsituacao']);

        if($matricula["situacao"]["visualizacoes"][7]) {
            $matriculaObj->Set("id", $matricula["idmatricula"]);
            $adicionar = $matriculaObj->enviarDocumento((int) $_POST['iddocumento']);
        } else {
            $adicionar["sucesso"] = false;
            $adicionar["mensagem"] = "mensagem_permissao_workflow";;
        }
        if($adicionar["sucesso"]){
            $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
            $matriculaObj->Set('url','/'.$url[0].'/'.$url[1].'/'.$url[2]);
            $matriculaObj->Processando();
        } else {
            $mensagem["erro"] = $adicionar["mensagem"];
        }
    }
}

switch ($url[3]) {
    case 'adicionar':
        require 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
        require 'telas/'.$config['tela_padrao'].'/formulario.php';
        break;
    case 'json':
        require 'telas/'.$config['tela_padrao'].'/json.php';
        break;
    case "downloaddocumento":
        $matriculaObj->Set("id", intval($url[5]));//idmatricula
        $matriculaObj->Set("iddocumento", intval($url[4]));
        $download = $matriculaObj->retornarDocumento();
        include("telas/".$config["tela_padrao"]."/download.documentos.php");
        break;
    case "visualizardocumento":
        $matriculaObj->Set("id", intval($url[5]));//idmatricula
        $matriculaObj->Set("iddocumento", intval($url[4]));
        $download = $matriculaObj->retornarDocumento();
        include("telas/".$config["tela_padrao"]."/visualizar.documentos.php");
        break;
    default:
        require 'idiomas/'.$config['idioma_padrao'].'/index.php';
        require 'telas/'.$config['tela_padrao'].'/index.php';
        break;
}
