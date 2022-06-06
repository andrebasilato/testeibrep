<?php
require '../classes/videoteca/videoteca.class.php';
require '../classes/videoteca/videoteca.youtube.class.php';
require '../classes/videoteca/videoteca.vimeo.class.php';
require '../classes/videoteca/videoteca.pastas.class.php';
require '../classes/videoteca/videoteca.tags.class.php';
require '../classes/filesystem.class.php';
require '../classes/http/response.php';
require 'config.php';
require 'config.formulario.php';
require 'config.listagem.php';
require 'idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php';

if($_POST['variavel'] == 'youtube' || $_POST['variavel'] == 'vimeo'){
    $config['formulario'] = $config['formulario_youtube_editar'];
}

$linhaObj = new Videoteca(new Core);
$videotecaPastas = new VideotecaPastas(new Core);
$videotecaYoutube = new VideotecaYoutube(new Core);
$videotecaVimeo = new VideotecaVimeo(new Core);
$videotecaTags = new VideotecaTags(new Core);
$listaDePastas = $videotecaPastas->ListarTodas();

if ('download' == $url[4]) {
    $linhaObj->downloadVideo((int) $url[3]);
    exit;
}

// HTTP Response
$httpFoundation = Response::getInstance();
$httpFoundation->setStatusCode(200)
    ->setMessage('OK!')
    ->send();

$urlBase = "/{$url[0]}/{$url[1]}/{$url[2]}";

$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');
$linhaObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde']);

if ('criarpasta' == $url[3]) {
    echo $videotecaPastas->save();
    exit;
}

if ('removerpasta' == $url[3]) {
    echo $videotecaPastas->remover();
    exit;
}

if ('renomearpasta' == $url[3]) {
    echo $videotecaPastas->renomear();
    exit;
}

if ('removermultiplos' == $url[3]) {
    echo $linhaObj->removermultiplos($_POST['idvideos']);
    exit;
}

if ('youtube' == $_POST['type']) {
    echo $videotecaYoutube->tryRegistre($_POST['url']);
    exit;
}

if ('vimeo' == $_POST['type']) {
    echo $videotecaVimeo->tryRegistre($_POST['url']);
    exit;
}

if ('atualizaInformacoes' == $_POST['acao']) {
    $arquivo = $videotecaVimeo->RetornaArquivo($_POST['url']);
    $url = $videotecaVimeo->ResgataUri($arquivo['arquivo']);
    echo $videotecaVimeo->updateRegistre($arquivo['idvideo'],$url);
    exit;
}

if ('htmlcode' == $_POST['type']) {
    echo print_r($_POST, 1);
    exit;
}

if ('opcoes' == $url[3]) {
    include 'idiomas/'.$config['idioma_padrao'].'/opcoes.php';
    include 'telas/'.$config['tela_padrao'].'/opcoes.php';
    exit;
}

if ('salvar' == $_POST['acao']) {

    $tags = array();
    if ($_POST['tags']) {
        $videotecaTags->registerTagsIfNotExists($_POST['tags']);
        $tags = $_POST['tags'];
    }
    unset($_POST['tags']);

    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');

    if (isset($_SESSION['video_tmp']['info']['duration'])) {
        $_POST['duracao'] = substr(
            trim($_SESSION['video_tmp']['info']['duration']),
            0,
            8
        );
    }

    if (null == $_POST['variavel']) {
        $_POST['variavel'] = 'html5';
    }

    $filename = end(explode('/', $_SESSION['video_tmp']['nr']));
    $filename = trim($filename);

    $_POST['arquivo'] = ($_POST['arquivo'])
                        ? md5($_POST['arquivo'])
                        : md5($filename);

    $_POST['imagem'] = trim($_POST['imagem']);
    $_POST['imagem'] = (null != $_POST['imagem'])
                        ? $_POST['imagem']
                        : '1';
    
    $salvar = $linhaObj->Set('post', $_POST)
        ->SalvarDados();


    if (file_exists($_SESSION['video_tmp']['hd'])) {
        $slugPath = $videotecaPastas->getPathNameById($_POST['idpasta']);
        $videoPath = FileSystem::getBasePath('/storage/videoteca/'). $slugPath->caminho;

        $filename = md5(end(explode('/', $_SESSION['video_tmp']['nr']))).'_hd.mp4';

        $videoPath = $videoPath .'/'. $salvar['id'];
        mkdir($videoPath);

        copy($_SESSION['video_tmp']['hd'], $videoPath . '/' . $filename);
    }

    if (file_exists($_SESSION['video_tmp']['nr'])) {
        $slugPath = $videotecaPastas->getPathNameById($_POST['idpasta']);
        $videoPath = FileSystem::getBasePath('/storage/videoteca/'). $slugPath->caminho;

        $videoPath = $videoPath .'/'. $salvar['id'];
        $filename = md5(end(explode('/', $_SESSION['video_tmp']['nr']))).'.mp4';

        copy($_SESSION['video_tmp']['nr'], $videoPath. '/'.$filename);
    }

    foreach ($_SESSION['video_tmp']['images'] as $image) {
        $filename = end(explode('/', $image));

        copy($image, $videoPath . '/'. $filename);
    }

    unset($_SESSION['video_tmp']);

    $videotecaTags->toTheVideo($salvar['id'])
        ->registerTags($tags);

    if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->Set('pro_mensagem_idioma','modificar_sucesso');
            $linhaObj->Set('url', $urlBase . '/'.$url[3].'/'.$url[4]);
        } else {
            $linhaObj->Set('pro_mensagem_idioma','cadastrar_sucesso')
                ->Set('url', $urlBase);
        }
        $linhaObj->Processando();
    }


} elseif ('remover' == $_POST['acao']) {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|3');
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->Remover();

    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_sucesso')
            ->Set('url', $urlBase)
            ->Processando();
    }
}

if (isset($url[3])) {
    if ('cadastrar' == $url[3]) {
        $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
        include('idiomas/'.$config['idioma_padrao'].'/formulario.php');
        include('telas/'.$config['tela_padrao'].'/formulario.php');
        exit;
    } else {


    if ('filtro' == $url[4]) {

        $linhaObj->Set('pagina', $_GET['pag']);

        if (! $_GET['ordem']) {
            $_GET['ordem'] = 'desc';
        }

        $linhaObj->Set('ordem',$_GET['ord']);

        if (! $_GET['qtd']) {
            $_GET['qtd'] = 30;
        }

        $linhaObj->Set('limite', (int) $_GET['qtd']);

        if(!$_GET['cmp']) {
            $_GET['cmp'] = $config['banco']['primaria'];
        }

        $linhaObj->Set('ordem_campo', $_GET['cmp'])
            ->Set('campos', '*');

        $dadosArray = $linhaObj->ListarTodas();

        include 'idiomas/'.$config['idioma_padrao'].'/index.php';
        include 'telas/'.$config['tela_padrao'].'/index.php';
		exit;
    }

  $linhaObj->Set('id', (int) $url[3])
       ->Set('campos', '*');

  $linha = $linhaObj->Retornar();

    if($linha) {
    switch ($url[4]) {

        case 'editar':
            $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
            include('idiomas/'.$config['idioma_padrao'].'/formulario.php');
            include('telas/'.$config['tela_padrao'].'/formulario.php');
            break;

        case 'remover':
            $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|3');
            include('idiomas/'.$config['idioma_padrao'].'/remover.php');
            include('telas/'.$config['tela_padrao'].'/remover.php');
            break;

        case 'opcoes':
            include('idiomas/'.$config['idioma_padrao'].'/opcoes.php');
            include('telas/'.$config['tela_padrao'].'/opcoes.php');
            exit;
            break;

        default:
            header('Location: '.$urlBase);
            exit;
    }

    } else {
        header('Location: '.$urlBase);
        exit();
    }
  }
} else {

    $linhaObj->Set('pagina', $_GET['pag']);

    if (! $_GET['ordem']) {
        $_GET['ordem'] = 'desc';
    }

    $linhaObj->Set('ordem',$_GET['ord']);

    if (! $_GET['qtd']) {
        $_GET['qtd'] = 30;
    }

    $linhaObj->Set('limite', (int) $_GET['qtd']);

    if(!$_GET['cmp']) {
        $_GET['cmp'] = $config['banco']['primaria'];
    }

    $linhaObj->Set('ordem_campo', $_GET['cmp'])
        ->Set('campos', '*');

    $dadosArray = $linhaObj->ListarTodas();
    //print_r2($listaDePastas);exit;

    include 'idiomas/'.$config['idioma_padrao'].'/index.php';
    include 'telas/'.$config['tela_padrao'].'/index.php';
}
