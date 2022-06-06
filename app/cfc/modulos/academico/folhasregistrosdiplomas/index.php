<?php
require 'config.php';
require 'config.listagem.php';
require 'config.formulario.php';
require 'idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php';

$linhaObj = new Folhas_Registros_Diplomas();
if (empty($url[3])) {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]);
}

$linhaObj->set('idusuario', $usuario['idusuario'])
         ->set('monitora_onde', $config['monitoramento']['onde']);

/** Configurations */
$noEditableFields = array(
    'idsindicato',   'numero_ordem',
    'numero_registro', 'numero_relacao',
	'numero_livro'
);

/** Actions */
if ('removerpagina' == Request::url(5)) {
    $linhaObj->actionCancelarMatricula(Request::url(4), Request::url(6));
    exit();
}

if (Request::post('acao')) {
    $linhaObj->doAction(Request::post('acao'));
}

$_SESSION['instituteCollection'] = $_SESSION['adm_sindicatos'];

// Tela de confimação de remoção de folha de matrícula
if ('removermatriculadafolha' == Request::url(7)) {

    $matricula = new Matriculas;
    $info = (object) $matricula->getMatricula(Request::url(8));

    $pessoa = new Pessoas;
    $aluno = (object) $pessoa->set('campos', 'p.nome as nome')
        ->set('id', $info->idpessoa)
        ->retornar();

    require "idiomas/" . $config["idioma_padrao"] . "/remover.php";
    require "telas/" . $config["tela_padrao"] . "/removerpagina.php";
    exit();
}

/** Routes */
if (Request::url(4)) {    
   if($url[3] ==  "ajax_cursos" ){
        $linhaObj->Set("id",(int) ($_REQUEST['idsindicato'] ? $_REQUEST['idsindicato'] : $url[4] ));
        $linhaObj->retornarCursosSindicato();
   } elseif ('cadastrar' == Request::url(4)) {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit;
    } else {
        $linhaObj->set("id", (int) $url[3]);
        $linhaObj->set("campos", "frd.*");
        $linha = $linhaObj->Retornar();

        if ($linha) {
            switch ($url[4]) {
                case  "abrir_folhas_pdf":

                        require '../assets/plugins/MPDF54/mpdf.php';

                        $marginLeft = $marginRight = $marginHeader = $marginFooter = 1;

                        $mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
                        $mpdf->ignore_invalid_utf8 = true;
                        $mpdf->simpleTables = true;
                        set_time_limit (0);

                        $css = ".quebra_pagina {page-break-after:always;}";

                        $mpdf->defaultfooterline = 0;
                        $mpdf->SetFooter("{PAGENO}");
                        $mpdf->WriteHTML($css,1);

                        ob_start();
                        
                        $linhaObj->set("campos", "frdm.*, mat.*, pes.rg as rg, mat.data_expedicao as data_expedicao, frd.numero_relacao, pes.nome as pessoa, ofe.nome as oferta, cur.nome as curso, pol.nome_fantasia as escola, tur.nome as turma, c.*");
                        $matriculas = $linhaObj->retornarDiplomas();

                        include("idiomas/" . $config["idioma_padrao"] . "/lista_registros.php");
                        include("telas/" . $config["tela_padrao"] . "/lista_registros.php");

                        $content = ob_get_clean();

                        $mpdf->WriteHTML($content);
                        $arquivo_nome = "../storage/temp/folha_registro_preview.pdf";
                        $mpdf->Output($arquivo_nome,"F");

                        header('Content-type: application/pdf');
                        readfile($arquivo_nome);
                        exit;

                case "ficha":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");                        
                    $linhaObj->set("ordem", "ASC");
                    $linhaObj->set("limite", -1);
                    $linhaObj->set("ordem_campo", "frdm.idfolha_matricula");
                    $linhaObj->set("campos", "frdm.*, mat.*, pes.rg as rg, mat.data_expedicao as data_expedicao, frd.numero_relacao, pes.nome as pessoa, ofe.nome as oferta, cur.nome as curso, pol.nome_fantasia as escola, tur.nome as turma, c.*");
                    $matriculas = $linhaObj->retornarDiplomas();

                    include("idiomas/" . $config["idioma_padrao"] . "/lista_registros.php");
                    include("telas/" . $config["tela_padrao"] . "/lista_registros.php");
                    break;
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.php");
                    break;
                case "json":
                    echo json_encode($linhaObj->searchBy($_GET['tag'], $linha['idsindicato'], $linha['idcurso']));
                    exit;
                case "diplomas":
                    
                    if ($url[5]) {
                        $diploma = $linhaObj->RetornarDiploma($url[3], $url[5]);

                        switch ($url[6]) {
                            case "opcoes":
                                $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                                include("idiomas/" . $config["idioma_padrao"] . "/opcoes.diploma.php");
                                include("telas/" . $config["tela_padrao"] . "/opcoes.diploma.php");
                                break;
                            case "cancelar":
                                $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                                $linhaObj->actionCancelarMatricula(Request::url(4), Request::url(6));
                                break;
                            case "gerar":

                                $certificado = new Certificados;
                                $paginas = $certificado->gerarCertificado($url[5], new Matriculas);
                                $certificado->downloadPages($paginas);
                                break;
                            default:
                                header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
                                exit();
                        }
                    } else {
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                        $linhaObj->set("pagina", 1);
                        $linhaObj->set("ordem", ($_GET['ord']) ? $_GET['ord'] : "ASC");
                        $linhaObj->set("limite", -1);
                        $linhaObj->set("ordem_campo", ($_GET['cmp']) ? $_GET['cmp'] : "frdm.idfolha_matricula");
                        $linhaObj->set("campos", "frdm.*, mat.*, pes.rg as rg, mat.data_expedicao as data_expedicao, frd.numero_relacao, pes.nome as pessoa, ofe.nome as oferta, cur.nome as curso, pol.nome_fantasia as escola, tur.nome as turma, c.*");
                        $diplomasArray = $linhaObj->RetornarDiplomas();
                        include("idiomas/" . $config["idioma_padrao"] . "/diplomas.php");
                        include("telas/" . $config["tela_padrao"] . "/diplomas.php");
                    }
                    break;
                case "remover":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    include("idiomas/" . $config["idioma_padrao"] . "/remover.php");
                    include("telas/" . $config["tela_padrao"] . "/remover.php");
                    break;
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                    break;
                default:
                    header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                    exit();
            }
        } else {
            header('Location: ' . Request::url('0-3', '/'));
            exit();
        }
    }
} else {
    $linhaObj->set('pagina', $_GET['pag']);
    if (! $_GET['ordem']) {
        $_GET['ordem'] = 'desc';
    }
    $linhaObj->set('ordem', $_GET["ord"]);
    if (! $_GET["qtd"]) {
        $_GET["qtd"] = 30;
    }
    $linhaObj->set('limite', (int) $_GET["qtd"]);
    if (! $_GET["cmp"]) {
        $_GET["cmp"] = $config["banco"]["primaria"];
    }

    $dadosArray = $linhaObj->set("ordem_campo", $_GET["cmp"])
        ->set("campos", "frd.*, i.nome as sindicato")
        ->set('lista_de_sindicatos', $_SESSION['instituteCollection'])
        ->listarTodas();

    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}