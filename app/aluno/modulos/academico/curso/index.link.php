<?php

if(!empty($url[7])){
    if(!empty($url[8]) && intval($url[8]) && $url[7] == 'verifica'){
        $conteudoObj = new Conteudos();
        $conteudoObj->set('idava_conteudo', $url[8]);

        $naoClicados = $conteudoObj->retornarNaoClicados($matricula['idmatricula']);
        $retorno = '';

        if(!empty($naoClicados)){
            foreach ($naoClicados as $key => $value) {
                $retorno .= '- ' . mb_strtoupper($value['nome']) . PHP_EOL;
            }
        }

        echo $retorno;
        exit;
    }
}

if(empty($url[6])){
    header('Location: ../');
}

$conteudoObj = new Conteudos();
$conteudoObj->set('id', $url[6])->set('campos', 'acl.*');
$linkAcao = $conteudoObj->retornarLinkAcao();

if(empty($linkAcao)){
    header('Location: ../');
}

$conteudoObj->set('linkAcao', $linkAcao);
$conteudoObj->set('matricula', $GLOBALS['idmatricula']);
$conteudoObj->confirmarClique();

if($linkAcao['tipo'] == 'L'){
    header('Location: ' . $linkAcao['url']);
}

?>