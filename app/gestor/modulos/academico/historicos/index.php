<?php
require 'config.php';
require 'config.formulario.php';
require 'config.listagem.php';
require sprintf('idiomas/%s/idiomapadrao.php', $config['idioma_padrao']);

$escolas      = new Escolas;
$matriculas = new Matriculas;
$linhaObj   = new Historicos;

//error_reporting(-1);
//ini_set('display_errors', 1);

$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');

$linhaObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde']);

/* Cadastrar nova página */
if ('cadastrar_pagina' == Request::post('acao')) {
    if ($linhaObj->registerNewPage()) {
        $_POST['msg'] = 'pagina_cadastra';
    }
}

if ('variaveis' == Request::url(4)) {

    $yaml = Yaml::getInstance();
    $tags = $yaml->loader(dirname(__FILE__).'/t.yaml');

	include ('telas/' . $config['tela_padrao'] . '/tags.php');
    exit;
}

if ('removerpagina' == Request::url(5)) {

    $certified = new Historicos;
    $page = $certified->getPageInfo(Request::url(4));

    if ('id' == Request::url(6)) {
        $certified->disablePage(Request::url(7));

        $certified->set('pro_mensagem_idioma', 'pagina_removida')
                  ->set('url', Request::url('0-4', '/') . 'paginas')
                  ->Processando();
        exit;
    }

    include ('idiomas/' . $config['idioma_padrao'] . '/remover.php');
    include ('telas/' . $config['tela_padrao'] . '/removerpagina.php');
	exit;
} else if ('downloadpagina' == Request::url(5)) {

    $certified = new Historicos;
    $page = $certified->getPageInfo(Request::url(4));

    include ('telas/' . $config['tela_padrao'] . '/download.folha.php');
	exit;
} else if ('visualizarpagina' == Request::url(5)) {

    $certified = new Historicos;
    $page = $certified->getPageInfo(Request::url(4));

    include ('telas/' . $config['tela_padrao'] . '/visualizar.folha.php');
	exit;
}

if ($_POST['acao'] == 'salvar') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');
    $linhaObj->set('post', $_POST);

    if ($_POST[$config['banco']['primaria']]) {
        $salvar = $linhaObj->Modificar();
    } else {
        $salvar = $linhaObj->Cadastrar();
    }

    if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->set('pro_mensagem_idioma', 'modificar_sucesso')
                ->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        } else {
            $linhaObj->set('pro_mensagem_idioma', 'cadastrar_sucesso')
                ->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        }
        $linhaObj->processando();
    }
} elseif ('imagens' == $_POST["acao"]) {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");


} elseif ('remover' == $_POST["acao"]) {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->Remover();
    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }
}

if (isset($url[3])) {
    if ($url[3] == "cadastrar") {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include ("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include ("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit();
    } else {
        $linhaObj->Set("id", (int) $url[3]);
        $linhaObj->Set("campos", "*");
        $linha = $linhaObj->Retornar();

        if ($linha) {
            switch ($url[4])
            {
               case 'editar' :
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');
                    include ('idiomas/' . $config['idioma_padrao'] . '/formulario.php');
                    include ('telas/' . $config['tela_padrao'] . '/formulario.php');
                    break;
              case 'paginas' :
                    $linhaObj->verificaPermissao($perfil['permissoes'], Request::url(3) . '|2');
                    include ('idiomas/' . $config['idioma_padrao'] . '/paginas.php');
                    include ('telas/' . $config['tela_padrao'] . '/paginas.php');
                    break;
              case 'imagens' :
                   if ('salvar_imagem' == Request::post('acao')) {
                         if ($linhaObj->registerNewMidia()){
                         		$_SESSION['msg'] = 'midia_cadastrada';
                         		header('Location: /'.Request::url('1-5', '/'));
								exit;
                         }
                   }

                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');
                    // Cadastrar
                    if (Request::url(6) && 'cadastro' == Request::url(6)) {
                    	include ('idiomas/' . $config['idioma_padrao'] . '/imagens.php');
                        include ('telas/' . $config['tela_padrao'] . '/formulario_imagens.php');
                        exit;
                    }

					if (Request::url(7) && 'remover_imagem' == Request::url(7)) {
                    	$remover = $linhaObj->removerMidia(intval($url[5]),intval($url[3]));

						if($remover["sucesso"]){
							$linhaObj->Set("pro_mensagem_idioma","remover_arquivo_sucesso");
							$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
							$linhaObj->Processando();
						}
                    }
					
					if (Request::url(7) && 'baixar_imagem' == Request::url(7)) {
                    	
						$sql = "select * from historico_escolar_midias where idhistorico_escolar_midia='".$url[5]."';";
						$imagem = $linhaObj->retornarLinha($sql);
						
						$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/".Historicos::PASTAMIDIAS."/".$url[3]."/".$imagem["arquivo"];
						
					   	$imagem["caracteristicas"] = getimagesize($arquivo);
						$imagem["caracteristicas"]["tamanho"] = filesize($arquivo);	
						
						header("Content-type: ".$imagem["caracteristicas"]['mime']);
						header('Content-Disposition: attachment; filename="'. basename($imagem["arquivo"]).'"');
						header('Content-Length: '.$imagem["caracteristicas"]["tamanho"]);
						header('Expires: 0');
						header('Pragma: no-cache');
						readfile($arquivo);
						
						//print_r($certificado);
						exit();
						
                    }

                    include ('idiomas/' . $config['idioma_padrao'] . '/imagens.php');
                    include ('telas/' . $config['tela_padrao'] . '/imagens.php');
                    break;
               case 'remover' :
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|3');
                    include ('idiomas/' . $config['idioma_padrao'] . '/remover.php');
                    include ('telas/' . $config['tela_padrao'] . '/remover.php');
                    break;
               case 'opcoes' :
                    include ('idiomas/' . $config['idioma_padrao'] . '/opcoes.php');
                    include ('telas/' . $config['tela_padrao'] . '/opcoes.php');
                    exit ;
                    break;	
                default :
                    header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
                    exit();
            }
        } else {
            header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
            exit();
        }
    }
} else {
    $linhaObj->Set('pagina', $_GET['pag']);
    if (! $_GET['ordem']) {
        $_GET['ordem'] = 'desc';
    }
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (! $_GET["qtd"]) {
        $_GET["qtd"] = 30;
    }
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"])
        $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "*");
    $dadosArray = $linhaObj->ListarTodas();
    include ("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include ("telas/" . $config["tela_padrao"] . "/index.php");
}
