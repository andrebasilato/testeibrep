<?php
require '../classes/matriculas_novo.class.php';
$matriculaObj = new Matriculas;
$matriculas = $matriculaObj->set('idpessoa', $usuario['idpessoa'])
                            ->set('modulo', $url[0]);

if ($_POST['acao'] == 'aceitar_contrato') {
    $matriculaObj->set('id',(int) $_POST['idmatricula']);
    $salvar = $matriculaObj->aceitarContrato((int) $_POST['idmatricula_contrato']);

    if ($salvar['sucesso']) {
        $matriculaObj->alterarSituacaoContratosAceitos((int)$_POST['idmatricula']);
        $matriculaObj->set('id', (int) $_POST['idmatricula']);
        $matriculaObj->set('pro_mensagem_idioma','mensagem_aceitar_contrato_sucesso');
        $matriculaObj->set('url','/'.$url[0].'/'.$url[1].'/'.$url[2]);
        $matriculaObj->Processando();
    } else {
        $_POST['msg'] = $salvar['mensagem'];
    }
}

switch ($url[5]) {
    case "download":
        $matriculaObj->Set("id",(int) $url[3]);
        $contrato = $matriculaObj->retornarContrato((int) $url[4]);
        require 'telas/'.$config['tela_padrao'].'/contrato.download.php';
        exit;
        break;
    case "contrato":
        $matriculaObj->Set("id",(int) $url[3]);
        if ($url[6] == 'pendentes') {
            $pasta = 'matriculas_contratos_pendentes';
            $contrato = $matriculaObj->retornarContratoPendente((int) $url[4]);
        } else {
            $contrato = $matriculaObj->retornarContrato((int) $url[4]);
            $pasta = 'matriculas_contratos';
        }

        $arquivo = "/storage/". $pasta . "/" . $contrato['arquivo_pasta'] . "/" . $contrato["idmatricula"] . "/" . $contrato["idmatricula_contrato"] . ".html";
        $arquivoServidor = $_SERVER["DOCUMENT_ROOT"].$arquivo;
        if(file_exists($arquivoServidor)) {
            $saida = file_get_contents($arquivoServidor);
        }

        include("../classes/contratos.class.php");
        $contratoObj = new Contratos();
        $contratoObj->Set("id",$contrato["idcontrato"]);
        $contratoObj->Set("campos","*");
        $contratoBackground = $contratoObj->Retornar();

        include("../assets/plugins/MPDF54/mpdf.php");
        $marginLeft = $contratoBackground["margem_left"] * 10;
        $marginRight = $contratoBackground["margem_right"] * 10;
        $marginHeader = $contratoBackground["margem_top"] * 10;
        $marginFooter = $contratoBackground["margem_bottom"] * 10;

        $mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
        $mpdf->ignore_invalid_utf8 = true;
        $mpdf->simpleTables = true;
        $mpdf->SetFooter('{PAGENO}');
        if($contratoBackground["background_servidor"]) {
        $css = "body{font-family:Arial;background:url(../storage/contratos_background/".$contratoBackground["background_servidor"].") no-repeat;background-image-resolution:300dpi;background-image-resize:6;}";
        $mpdf->WriteHTML($css,1);
        }

        $mpdf->defaultfooterline = 0;
        $mpdf->WriteHTML($saida);
        $arquivoNome = "../storage/temp/".$contrato["idmatricula_contrato"].".pdf";
        $mpdf->Output($arquivoNome,"F");

        header('Content-type: application/pdf');
        readfile($arquivoNome);
        exit;
        break;
    default:
        $matriculas = $matriculaObj->set('limite', -1)
                            ->set('ordem_campo', 'm.idmatricula')
                            ->set('ordem', 'desc')
                            ->set('campos', 'm.*,
                                            c.nome as curso,
                                            c.carga_horaria_total,
                                            c.imagem_exibicao_servidor,
                                            i.acesso_ava,
                                            IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porcentagem,
                                            IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porc_aluno')
                            ->retornarMeusCursos();

        //$matriculaObj->cadastrarHistorioAluno($ava['idava'], "visualizou", "contratos");
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
        require 'idiomas/'.$config['idioma_padrao'].'/contratos.php';
        require 'telas/'.$config['tela_padrao'].'/contratos.php';
    break;
}
