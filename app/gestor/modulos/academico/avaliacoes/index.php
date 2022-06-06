<?php
require '../classes/avaliacoes.class.php';
require '../classes/OptimizeSchema.class.php';
require 'config.php';
require 'config.listagem.php';
require 'idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php';

$linhaObj = new Avaliacoes();
$linhaObj->set('painel', 'gestor');

$baseUrl = sprintf('/%s/%s/%s', $url[0], $url[1] , $url[2]);

$linhaObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde']);

if ('corrigir_avaliacao' == $_POST['acao']) {

    $salvar = $linhaObj->set('post', $_POST)
        ->set('id', (int) $url[3])
        ->corrigirProva();

    if ($salvar['sucesso']) {
        $linhaObj->set('pro_mensagem_idioma', 'corrigir_sucesso')
            ->set('url', $baseUrl)
            ->processando();
    }

}

if (isset($url[3])) {

    $linha = $linhaObj->set('id', (int) $url[3])
        ->set(
            'campos',
            'ma.*, p.nome as aluno,
            d.nome as disciplina, aa.nome as avaliacao,
            prof.nome as professor_correcao,
            c.nome as curso, ma.nota'
        )->retornar();

    if ($linha) {
        switch ($url[4]) {

            case 'visualizar':

                $linhaObj->set('id', (int) $url[3]);
                $prova = $linhaObj->retornarProvaRespondida((int) $url[3]);
                $historico = $linhaObj->RetornarHistorico();

                include 'idiomas/'.$config['idioma_padrao'].'/visualiza.php';
                include 'telas/'.$config['tela_padrao'].'/visualiza.php';
                break;

            case 'download_imagem_pergunta':
                $arquivo = $linhaObj->retornaArquivoPerguntaDownload($url[5]);
                include 'telas/'.$config['tela_padrao'].'/download_imagem_pergunta.php';
            break;

            case 'download_arquivo_aluno':
                $arquivo = $linhaObj->retornaArquivoPerguntaAlunoDownload($url[5]);
                include 'telas/'.$config['tela_padrao'].'/download_arquivo_aluno.php';
                break;

            case 'download_arquivo_professor':
                $arquivo = $linhaObj->retornaArquivoPerguntaProfessorDownload($url[5]);
                include 'telas/'.$config['tela_padrao'].'/download_arquivo_professor.php';
                break;

            default:
              header('Location: '.$baseUrl);
              exit;
        }
    } else {
        header('Location: '.$baseUrl);
        exit;
    }

} else {

    if (! $_GET['ordem']) {
        $_GET['ordem'] = 'desc';
    }

    if (! $_GET['qtd']) {
        $_GET['qtd'] = 30;
    }

    if (! $_GET['cmp']) {
        $_GET['cmp'] = $config['banco']['primaria'];
    }

    $dadosArray = $linhaObj->set('pagina', $_GET['pag'])
        ->set('ordem', $_GET['ord'])
        ->set('limite', (int) $_GET['qtd'])
        ->set('ordem_campo', $_GET['cmp'])
        ->set(
            'campos',
            'ma.*, p.nome as aluno,
            d.nome as disciplina,
            aa.nome as avaliacao,
            prof.nome as professor_correcao'
        )->listarTodas();

    include 'idiomas/'.$config['idioma_padrao'].'/index.php';
    include 'telas/'.$config['tela_padrao'].'/index.php';
}
