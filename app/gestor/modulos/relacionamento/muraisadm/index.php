<?php

include '../classes/murais.class.php';
include 'config.php';
include 'config.formulario.php';
include 'config.listagem.php';

//Incluimos o arquivo com variaveis padrão do sistema.
include 'idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php';

$linhaObj = new Murais();
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');

$linhaObj->Set('idusuario', $usuario['idusuario']);
$linhaObj->Set('monitora_onde', $config['monitoramento']['onde']);
if ($_POST['acao'] == 'salvar') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
    $linhaObj->Set('post', $_POST);
    if ($_POST[$config['banco']['primaria']]) {
        $salvar = $linhaObj->Modificar();
    } else {
        $salvar = $linhaObj->Cadastrar();
    }
    if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->Set('pro_mensagem_idioma', 'modificar_sucesso');
            $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4]);
        } else {
            $linhaObj->Set('pro_mensagem_idioma', 'cadastrar_sucesso');
            $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2]);
        }
        $linhaObj->Processando();
    }
    /*$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    if($_POST["acao_url"]){
        $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4];
    }else{
        $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];
    }
    $linhaObj->Set("post",$_POST);
    if($_POST[$config["banco"]["primaria"]]) $salvar = $linhaObj->Modificar();
        else $salvar = $linhaObj->Cadastrar();
    if($salvar["sucesso"]){
        if($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
            $linhaObj->Set("url",$url_redireciona);
        }
        $linhaObj->Processando();
    }*/
} elseif ($_POST['acao'] == 'salvar_imagens') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|4');
    include 'idiomas/'.$config['idioma_padrao'].'/imagens.php';
    $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
    $linhaObj->Set('id', $url[3]);
    $linhaObj->Set('files', $_FILES);
    $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
    $salvar = $linhaObj->CadastrarImagens($erros);

    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'imagem_sucesso');
        $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/imagens');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover_imagem') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|5');
    $linhaObj->Set('id', $_POST['remover']);
    $remover = $linhaObj->RemoverImagens();

    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_imagem_sucesso');
        $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/imagens');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|3');
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->Remover();

    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_sucesso');
        $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'salvar_arquivos') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|9');
    $linhaObj->Set('id', intval($url[3]));
    $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
    $linhaObj->Set('files', $_FILES);
    $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
    $salvar = $linhaObj->CadastrarArquivos($erros);

    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'arquivo_sucesso');
        $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/arquivos');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover_arquivos') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|10');
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->RemoverArquivo($erros);

    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_arquivo_sucesso');
        $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/arquivos');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'salvar_fila') {
    $linhaObj->Set('post', $_POST);
    $linhaObj->Set('id', intval($url[3]));
    $salvar = $linhaObj->salvarFila();
    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'salvar_fila_sucesso');
        $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/fila');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover_fila') {
    //$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->removerFila();
    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_fila_sucesso');
        $linhaObj->Set('url', '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/fila');
        $linhaObj->Processando();
    }
}

if (isset($url[3])) {
    if ($url[3] == 'ajax_cursos') {
        if ($_REQUEST['idoferta']) {
            $linhaObj->Set('id', (int) $_REQUEST['idoferta']);
            $linhaObj->RetornarCursosOferta();
            exit();
        }
    }
    if ($url[3] == 'ajax_turmas') {
        if ($_REQUEST['idoferta']) {
            $linhaObj->Set('id', (int) $_REQUEST['idoferta']);
            $linhaObj->RetornarTurmasOferta();
            exit();
        }
    }
    if ($url[3] == 'ajax_escolas') {
        if ($_REQUEST['idoferta']) {
            $linhaObj->Set('id', (int) $_REQUEST['idoferta']);
            $linhaObj->RetornarEscolasOferta();
            exit();
        }
    }
    if ($url[4] == 'json') {
        include 'idiomas/'.$config['idioma_padrao'].'/json.php';
        include 'telas/'.$config['tela_padrao'].'/json.php';
        exit();
    }
    if ($url[3] == 'cadastrar') {
        $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
        include 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
        include 'telas/'.$config['tela_padrao'].'/formulario.php';
        exit();
    } else {
        $linhaObj->Set('id', intval($url[3]));
        $linhaObj->Set('campos', '*');
        $linha = $linhaObj->Retornar();

        if ($linha) {
            switch ($url[4]) {
                case 'editar':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
                    $linhaObj->Set('id', intval($url[3]));
                    $linhaObj->Set('ordem', 'asc');
                    $linhaObj->Set('limite', -1);
                    $linhaObj->Set('ordem_campo', 'nome');
                    $linhaObj->Set('campos', 'idmural_imagem, nome');
                    $associacoesImagensArray = $linhaObj->ListarImagens();
                    $linhaObj->Set('limite', 5);
                    $linhaObj->Set('ordem_campo', 'idmural');
                    $linhaObj->Set('campos', '*');
                    $manualArquivosArray = $linhaObj->ListarTodosArquivos();

                    include 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
                    include 'telas/'.$config['tela_padrao'].'/formulario.php';
                    break;
                case 'remover':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|3');
                    include 'idiomas/'.$config['idioma_padrao'].'/remover.php';
                    include 'telas/'.$config['tela_padrao'].'/remover.php';
                    break;
                case 'opcoes':
                    include 'idiomas/'.$config['idioma_padrao'].'/opcoes.php';
                    include 'telas/'.$config['tela_padrao'].'/opcoes.php';
                    break;
                case 'imagens':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|4');
                    $linhaObj->Set('id', intval($url[3]));
                    $imagensArray = $linhaObj->RetornaImagens();
                    include 'idiomas/'.$config['idioma_padrao'].'/imagens.php';
                    include 'telas/'.$config['tela_padrao'].'/imagens.php';
                    break;
                case 'arquivos':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|9');
                    $linhaObj->Set('id', intval($url[3]));
                    $arquivosArray = $linhaObj->RetornaArquivos();
                    include 'idiomas/'.$config['idioma_padrao'].'/arquivos.php';
                    include 'telas/'.$config['tela_padrao'].'/arquivos.php';
                    break;
                case 'download':
                    $linhaObj->Set('id', intval($url[5]));
                    $imagem = $linhaObj->RetornarImagemDownload();
                    include 'telas/'.$config['tela_padrao'].'/download.php';
                    break;
                case 'downloadArquivo':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|11');
                    $linhaObj->Set('id', intval($url[5]));
                    $arquivo = $linhaObj->RetornarArquivoDownload();
                    include 'telas/'.$config['tela_padrao'].'/download_arquivo.php';
                    break;
                case 'visualiza_imagem':

                    $linhaObj->Set('id', intval($url[5]));
                    $imagem = $linhaObj->RetornarImagemDownload();
                    include 'telas/'.$config['tela_padrao'].'/visualiza_imagem.php';
                    break;
                case 'preview':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|7');
                    $linhaObj->Set('id', intval($url[3]));
                    $linha = $linhaObj->RetornarPreviewMural();
                    include 'idiomas/'.$config['idioma_padrao'].'/preview.php';
                    include 'telas/'.$config['tela_padrao'].'/preview.php';
                    break;
                case 'previewpopup':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|7');
                    $linhaObj->Set('id', intval($url[3]));
                    $linha = $linhaObj->RetornarPreviewMural();
                    include 'idiomas/'.$config['idioma_padrao'].'/preview.php';
                    include 'telas/'.$config['tela_padrao'].'/preview.popup.php';
                    break;
                case 'fila':
                    include 'idiomas/'.$config['idioma_padrao'].'/fila.php';
                    if ($url[5] == 'add') {
                        if ($url[6] == 'usuariosadm') {
                            $configFormulario = 'formulario_usuarios_adm';
                            $paginaTitulo = 'pagina_titulo_usuarios_adm';
                            if ($_POST['acao'] == 'listar_fila') {
                                unset($_POST['acao'], $_POST['ativo_painel']);
                                $busca = '<br />';
                                foreach ($_POST as $campo => $valor) {
                                    if (empty($valor)) {
                                        $valor = 'Todos(as)';
                                    } elseif ($campo == 'data_nasc_mes') {
                                        $valor = $meses_idioma[$config['idioma_padrao']][$valor];
                                    } elseif ($campo == 'idestado') {
                                        $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                        $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $estado['nome'];
                                    } elseif ($campo == 'idcidade') {
                                        $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                        $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $cidade['nome'];
                                    } elseif ($campo == 'idsindicato') {
                                        $linhaObj->sql = "select nome from sindicatos where idsindicato = '".$valor."'";
                                        $sindicato = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $sindicato['nome'];
                                    } elseif ($campo == 'idescola') {
                                        $linhaObj->sql = "select nome_fantasia as nome from escolas where idescola = '".$valor."'";
                                        $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $escola['nome'];
                                    } elseif ($campo == 'idperfil') {
                                        $linhaObj->sql = "select nome from usuarios_adm_perfis where idperfil = '".$valor."'";
                                        $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $escola['nome'];
                                    }
                                    $_POST['acao'] = 'listar_fila';
                                    $busca .= str_replace('%s', $valor, $idioma[$campo]);
                                    $busca .= '<br />';
                                }
                                $busca .= '<br />';
                                $linhaObj->Set('post', $_POST);
                                $linhaObj->Set('id', intval($url[3]));
                                $filaAddArray = $linhaObj->listarFilaAddMuralUsuarios();
                            }
                        } elseif ($url[6] == 'professores') {
                            $configFormulario = 'formulario_professores';
                            $paginaTitulo = 'pagina_titulo_professores';
                            if ($_POST['acao'] == 'listar_fila') {
                                unset($_POST['acao'], $_POST['ativo_painel']);
                                $busca = '<br />';
                                foreach ($_POST as $campo => $valor) {
                                    if (empty($valor)) {
                                        $valor = 'Todos(as)';
                                    } elseif ($campo == 'data_nasc_mes') {
                                        $valor = $meses_idioma[$config['idioma_padrao']][$valor];
                                    } elseif ($campo == 'idestado') {
                                        $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                        $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $estado['nome'];
                                    } elseif ($campo == 'idcidade') {
                                        $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                        $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $cidade['nome'];
                                    } elseif ($campo == 'idava') {
                                        $linhaObj->sql = "select nome from avas where idava = '".$valor."'";
                                        $ava = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $ava['nome'];
                                    } elseif ($campo == 'idoferta') {
                                        $linhaObj->sql = "select nome from ofertas where idoferta = '".$valor."'";
                                        $oferta = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $oferta['nome'];
                                    } elseif ($campo == 'idcurso') {
                                        $linhaObj->sql = "select nome from cursos where idcurso = '".$valor."'";
                                        $curso = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $curso['nome'];
                                    }///SE HOUVER CONSULTA NO POST-----------
                                    $_POST['acao'] = 'listar_fila';
                                    $busca .= str_replace('%s', $valor, $idioma[$campo]);
                                    $busca .= '<br />';
                                }
                                $busca .= '<br />';
                                $linhaObj->Set('post', $_POST);
                                $linhaObj->Set('id', intval($url[3]));
                                $filaAddArray = $linhaObj->listarFilaAddMuralProfessores();
                            }
                        } elseif ($url[6] == 'atendentes') {
                            $configFormulario = 'formulario_vendedores';
                            $paginaTitulo = 'pagina_titulo_vendedores';

                            if ($_POST['acao'] == 'listar_fila') {
                                unset($_POST['acao'], $_POST['ativo_painel']);
                                $busca = '<br />';
                                foreach ($_POST as $campo => $valor) {
                                    if (empty($valor)) {
                                        $valor = 'Todos(as)';
                                    } elseif ($campo == 'idsindicato') {
                                        $linhaObj->sql = "select nome from sindicatos where idsindicato = '".$valor."'";
                                        $imobiliaria = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $imobiliaria['nome'];
                                    } elseif ($campo == 'idescola') {
                                        $linhaObj->sql = "select nome_fantasia as nome from escolas where idescola = '".$valor."'";
                                        $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $escola['nome'];
                                    } elseif ($campo == 'idestado') {
                                        $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                        $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $estado['nome'];
                                    } elseif ($campo == 'idcidade') {
                                        $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                        $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $cidade['nome'];
                                    }
                                    $_POST['acao'] = 'listar_fila';
                                    $busca .= str_replace('%s', $valor, $idioma[$campo]);
                                    $busca .= '<br />';
                                }
                                $busca .= '<br />';
                                $linhaObj->Set('post', $_POST);
                                $linhaObj->Set('id', intval($url[3]));
                                $filaAddArray = $linhaObj->listarFilaAddMuralVendedores();
                            }
                        } elseif ($url[6] == 'pessoas') {
                            $configFormulario = 'formulario_pessoas';
                            $paginaTitulo = 'pagina_titulo_pessoas';
                            if ($_POST['acao'] == 'listar_fila') {
                                unset($_POST['acao'], $_POST['ativo_painel']);
                                $busca = '<br />';
                                foreach ($_POST as $campo => $valor) {
                                    if (empty($valor)) {
                                        $valor = 'Todos(as)';
                                    } elseif ($campo == 'situacao') {
                                        $valor = $situacao_pessoa[$config['idioma_padrao']][$valor];
                                    } elseif ($campo == 'estado_civil') {
                                        $valor = $estadocivil[$config['idioma_padrao']][$valor];
                                    } elseif ($campo == 'data_nasc_mes') {
                                        $valor = $meses_idioma[$config['idioma_padrao']][$valor];
                                    } elseif ($campo == 'idestado') {
                                        $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                        $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $estado['nome'];
                                    } elseif ($campo == 'idcidade') {
                                        $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                        $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $cidade['nome'];
                                    }
                                    $_POST['acao'] = 'listar_fila';
                                    $busca .= str_replace('%s', $valor, $idioma[$campo]);
                                    $busca .= '<br />';
                                }
                                $busca .= '<br />';
                                $linhaObj->Set('post', $_POST);
                                $linhaObj->Set('id', intval($url[3]));
                                $filaAddArray = $linhaObj->listarFilaAddMuralPessoas();
                            }
                        } elseif ($url[6] == 'atendimentos') {
                            $configFormulario = 'formulario_atendimentos';
                            $paginaTitulo = 'pagina_titulo_atendimentos';
                            if ($_POST['acao'] == 'listar_fila') {
                                unset($_POST['acao'], $_POST['ativo_painel']);
                                $busca = '<br />';
                                foreach ($_POST as $campo => $valor) {
                                    if (empty($valor)) {
                                        $valor = 'Todos(as)';
                                    } elseif ($campo == 'idassunto') {
                                        $linhaObj->sql = "select nome from atendimentos_assuntos where idassunto = '".$valor."'";
                                        $assunto = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $assunto['nome'];
                                    } elseif ($campo == 'idsubassunto') {
                                        $linhaObj->sql = "select nome from atendimentos_assuntos_subassuntos where idsubassunto = '".$valor."'";
                                        $subassunto = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $subassunto['nome'];
                                    } elseif ($campo == 'idsituacao') {
                                        $linhaObj->sql = "select nome from atendimentos_workflow where idsituacao = '".$valor."'";
                                        $situacao = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $situacao['nome'];
                                    }
                                    $_POST['acao'] = 'listar_fila';
                                    $busca .= str_replace('%s', $valor, $idioma[$campo]);
                                    $busca .= '<br />';
                                }
                                $busca .= '<br />';

                                $linhaObj->Set('post', $_POST);
                                $linhaObj->Set('id', intval($url[3]));
                                $filaAddArray = $linhaObj->listarFilaAddMuralAtendimentos();
                            }
                        } elseif ($url[6] == 'matriculas') {
                            $configFormulario = 'formulario_matriculas';
                            $paginaTitulo = 'pagina_titulo_matriculas';
                            if ($_POST['acao'] == 'listar_fila') {
                                unset($_POST['acao'], $_POST['ativo_painel']);

                                $busca = '<br />';
                                foreach ($_POST as $campo => $valor) {
                                    if (empty($valor)) {
                                        $valor = 'Todos(as)';
                                    } elseif ($campo == 'idsindicato') {
                                        $linhaObj->sql = "select nome from sindicatos where idsindicato = '".$valor."'";
                                        $sindicato = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $sindicato['nome'];
                                    } elseif ($campo == 'idoferta') {
                                        $linhaObj->sql = "select nome from ofertas where idoferta = '".$valor."'";
                                        $oferta = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $oferta['nome'];
                                    } elseif ($campo == 'idturma') {
                                        $linhaObj->sql = "select nome from ofertas_turmas where idturma = '".$valor."'";
                                        $turma = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $turma['nome'];
                                    } elseif ($campo == 'idcurso') {
                                        $linhaObj->sql = "select nome from cursos where idcurso = '".$valor."'";
                                        $curso = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $curso['nome'];
                                    } elseif ($campo == 'idescola') {
                                        $linhaObj->sql = "select nome_fantasia from escolas where idescola = '".$valor."'";
                                        $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $escola['nome_fantasia'];
                                    } elseif ($campo == 'idsituacao') {
                                        $linhaObj->sql = "select nome from matriculas_workflow where idsituacao = '".$valor."'";
                                        $situacao = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $situacao['nome'];
                                    } elseif ($campo == 'idvendedor') {
                                        $linhaObj->sql = "select nome from vendedores where idvendedor = '".$valor."'";
                                        $vendedor = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $vendedor['nome'];
                                    }
                                    $_POST['acao'] = 'listar_fila';
                                    $busca .= str_replace('%s', $valor, $idioma[$campo]);
                                    $busca .= '<br />';
                                }
                                $busca .= '<br />';

                                $linhaObj->Set('post', $_POST);
                                $linhaObj->Set('id', intval($url[3]));
                                $filaAddArray = $linhaObj->listarFilaAddMuralMatriculas();
                            }
                        } elseif ($url[6] == 'cfc') {
                            $configFormulario = 'formulario_escolas';
                            $paginaTitulo = 'pagina_titulo_matriculas';
                            if ($_POST['acao'] == 'listar_fila') {
                                unset($_POST['acao'], $_POST['ativo_painel']);

                                $busca = '<br />';
                                foreach ($_POST as $campo => $valor) {
                                    if (empty($valor)) {
                                        $valor = 'Todos(as)';
                                    } elseif ($campo == 'idsindicato') {
                                        $linhaObj->sql = "select nome from sindicatos where idsindicato = '".$valor."'";
                                        $sindicato = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $sindicato['nome'];
                                    } elseif ($campo == 'idestado') {
                                        $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                        $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $estado['nome'];
                                    } elseif ($campo == 'idcidade') {
                                        $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                        $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $cidade['nome'];
                                    } elseif ($campo == 'idescola') {
                                        $linhaObj->sql = "select nome_fantasia as nome from escolas where idescola = '".$valor."'";
                                        $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                        $valor = $escola['nome'];
                                    }
                                    $_POST['acao'] = 'listar_fila';
                                    $busca .= str_replace('%s', $valor, $idioma[$campo]);
                                    $busca .= '<br />';
                                }
                                $busca .= '<br />';

                                $linhaObj->Set('post', $_POST);
                                $linhaObj->Set('id', intval($url[3]));
                                $filaAddArray = $linhaObj->listarFilaAddEscolas();
                            }
                        }
                    } else {
                        $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|8');
                        $linhaObj->Set('pagina', $_GET['pag']);
                        if (! $_GET['ordem']) {
                            $_GET['ordem'] = 'desc';
                        }
                        $linhaObj->Set('ordem', $_GET['ord']);
                        if (! $_GET['qtd']) {
                            $_GET['qtd'] = -1;
                        }
                        $linhaObj->Set('limite', intval($_GET['qtd']));
                        if (! $_GET['cmp']) {
                            $_GET['cmp'] = $config['banco_fila']['primaria'];
                        }
                        $linhaObj->Set('ordem_campo', $_GET['cmp']);
                        $linhaObj->Set('campos', '*');
                        $filaArray = $linhaObj->listarFilaMural(intval($url[3]));
                    }
                    include 'telas/'.$config['tela_padrao'].'/fila.php';
                    break;
                default:
                   header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2].'');
                   exit();
            }
        } else {
            header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2].'');
            exit();
        }
    }
} else {
    $linhaObj->Set('pagina', $_GET['pag']);
    if (! $_GET['ordem']) {
        $_GET['ordem'] = 'desc';
    }
    $linhaObj->Set('ordem', $_GET['ord']);
    if (! $_GET['qtd']) {
        $_GET['qtd'] = 30;
    }
    $linhaObj->Set('limite', intval($_GET['qtd']));
    if (! $_GET['cmp']) {
        $_GET['cmp'] = $config['banco']['primaria'];
    }
    $linhaObj->Set('ordem_campo', $_GET['cmp']);
    $linhaObj->Set('campos', '*');
    $dadosArray = $linhaObj->ListarTodas();
    include 'idiomas/'.$config['idioma_padrao'].'/index.php';
    include 'telas/'.$config['tela_padrao'].'/index.php';
}
