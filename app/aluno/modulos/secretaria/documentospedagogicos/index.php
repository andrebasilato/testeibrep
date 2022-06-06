<?php
require '../classes/matriculas_novo.class.php';

$matriculaObj = new Matriculas;

$linhaObj = new Matriculas;
$matriculas = $matriculaObj->set('idpessoa', $usuario['idpessoa'])
    ->set('modulo', $url[0])
    ->set('limite', -1)
    ->set('ordem_campo', 'm.idmatricula')
    ->set('ordem', 'desc')
    ->set('campos', 'm.*,
					c.nome as curso,
					c.carga_horaria_total,
					c.imagem_exibicao_servidor,
					IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) as porcentagem,
					IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) as porc_aluno')
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

$declaracaoObj = new Declaracoes();

$solicitacaoDeclaracaoObj = new SolicitacoesDeclaracoes();
$solicitacaoDeclaracaoObj->Set("idpessoa", $usuario["idpessoa"])
    ->Set("modulo", $url[0]);


if ($_POST['acao'] == 'salvar_solicitacao') {
    $solicitacaoDeclaracaoObj->Set("post",$_POST);
    $salvar = $solicitacaoDeclaracaoObj->salvarSolicitacao();
    if($salvar['sucesso']) {
        $matriculaObj->Set('pro_mensagem_idioma','cadastrar_sucesso');
        $matriculaObj->Set('url','/'.$url[0].'/'.$url[1].'/'.$url[2]);
        $declaracao = $declaracaoObj->consultarDeclaracarao((int) $_POST['iddeclaracao']);
        if($declaracao['difere_automatico'] == 'S'){

            $linhaObj->gerarDeclaracaoSolicitacao(null, (int) $salvar['id']);
        }
        $matriculaObj->Processando();
    }
}


switch ($url[3]) {
    case 'solicitar':
        require 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
        require 'telas/'.$config['tela_padrao'].'/formulario.php';
        exit;
    case 'json':
        require 'telas/'.$config['tela_padrao'].'/json.php';
        exit;
    case 'solicitacao':
        if((int) $url[4]) {
            $solicitacao = $solicitacaoDeclaracaoObj->Set('id',(int) $url[4])
                ->Set('campos','sd.*, d.nome')
                ->retornar();
            if($solicitacao["idsolicitacao_declaracao"]) {

                require 'idiomas/'.$config['idioma_padrao'].'/motivo.php';
                require 'telas/'.$config['tela_padrao'].'/motivo.php';
                exit;
            } else {
                header('Location: /'.$url[0].'/secretaria/documentospedagogicos');
                exit;
            }
        } else {
            header('Location: /'.$url[0].'/secretaria/documentospedagogicos');
            exit;
        }
    case 'declaracao':
        if((int) $url[4]) {
            $declaracao = $matriculaObj->retornarDeclaracao((int) $url[4]);
            if($declaracao["idmatriculadeclaracao"]) {
                $arquivo = "/storage/matriculas_declaracoes/" . $declaracao['arquivo_pasta'] . "/" . $declaracao["idmatricula"]."/".$declaracao["idmatriculadeclaracao"].".html";
                $arquivoServidor = $_SERVER["DOCUMENT_ROOT"].$arquivo;
                if(file_exists($arquivoServidor)) {
                    $saida = file_get_contents($arquivoServidor);
                }

                require '../assets/plugins/MPDF54/mpdf.php';
                $marginLeft = $declaracao["margem_left"] * 10;
                $marginRight = $declaracao["margem_right"] * 10;
                $marginHeader = $declaracao["margem_top"] * 10;
                $marginFooter = $declaracao["margem_bottom"] * 10;

                $mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
                $mpdf->ignore_invalid_utf8 = true;
                $mpdf->simpleTables = true;
                if ($declaracao['gerar_paginacao'] == 'S') {
                    $mpdf->SetFooter('{PAGENO}');
                }
                if($declaracao["background_servidor"]) {
                    $css = "body{font-family:Arial;background:url(../storage/declaracoes_background/".$declaracao["background_servidor"].") no-repeat;background-image-resolution:300dpi;background-image-resize:6;}";
                    $mpdf->WriteHTML($css,1);
                }

                $mpdf->defaultfooterline = 0;
                $mpdf->WriteHTML($saida);
                $arquivoNome = "../storage/temp/".$declaracao["idmatriculadeclaracao"].".pdf";
                $mpdf->Output($arquivoNome,"F");

                header('Content-type: application/pdf');
                readfile($arquivoNome);
                exit;
            } else {
                header('Location: /'.$url[0].'/secretaria/documentospedagogicos');
                exit;
            }
        } else {
            header('Location: /'.$url[0].'/secretaria/documentospedagogicos');
            exit;
        }
    default:
        require 'idiomas/'.$config['idioma_padrao'].'/index.php';
        require 'telas/'.$config['tela_padrao'].'/index.php';
        exit;
}
