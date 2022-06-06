<?php
//include("../classes/escolas.class.php");
include('config.php');
include('config.formulario.php');
include('config.listagem.php');
include_once("../classes/detran.class.php");
$detranObj = new Detran();


//Incluimos o arquivo com variaveis padrão do sistema.
include('idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php');

$linhaObj = new Escolas();
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');

$linhaObj->Set('idusuario', $usuario['idusuario']);
$linhaObj->Set('monitora_onde', $config['monitoramento']['onde']);

if ($_POST['acao'] == 'salvar') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');
    if ($_FILES) {
        foreach ($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }

    $linhaObj->Set('post', $_POST);
    if ($_POST[$config['banco']['primaria']]) {
        $salvar = $linhaObj->modificar();
    } else {
        $salvar = $linhaObj->cadastrar();
    }
    if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->Set('pro_mensagem_idioma', 'modificar_sucesso');
            $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        } else {
            $linhaObj->Set('pro_mensagem_idioma', 'cadastrar_sucesso');
            $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        }
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|3');
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->Remover();
    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'adicionar_contato') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|4');

    $linhaObj->Set('id', intval($url[3]));
    $linhaObj->Set('post', $_POST);
    $salvar = $linhaObj->adicionarContato();

    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'cadastrar_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/contatos');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover_contato') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|4');

    $linhaObj->Set('id', intval($url[3]));
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->RemoverContato();

    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/contatos');
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'salvar_valores_curso') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|5');
    $linhaObj->config['formulario'] = $config['formulario_valores_curso'];
    $linhaObj->config['banco'] = $config['banco_valores_cursos'];
    $linhaObj->monitora_onde = $config['monitoramento']['onde_valores_cursos'];
    $salvar = $linhaObj->salvarValoresCursos((int)$url[3], $_POST['valores']);

    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'salvar_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/valores_cursos');
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "adicionar_arquivo") {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|6');
    $adicionar = $linhaObj->set('id', $url[3])
        ->set('post', $_POST)
        ->adicionarArquivo();

    if ($adicionar["sucesso"]) {
        $linhaObj->set("pro_mensagem_idioma", $adicionar["mensagem"]);
        $linhaObj->set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif ($_POST["acao"] == "remover_arquivo") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");

    $linhaObj->Set("id", $url[3]);
    $linhaObj->Set("idarquivo", $_POST["idarquivo"]);
    $remover = $linhaObj->removerArquivoPastaVirtual();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", $remover["mensagem"]);
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $remover["mensagem"];
    }
} elseif ($_POST["acao"] == "associar_contrato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AssociarContrato();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_contrato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_contrato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarContrato();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_contrato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'associar_estado_cidade') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|11');
    $linhaObj->Set('id', intval($url[3]));
    $linhaObj->Set('post', $_POST);
    $salvar = $linhaObj->associarEstadosCidades();
    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'associar_estado_cidade_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover_estado_cidade') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|12');
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->desassociarEstadosCidades();

    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_estado_cidade_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_mensagem") {
    if ($_POST["idmensagem"]) {
        $remover = $linhaObj->removerMensagem(intval($_POST["idmensagem"]));
        if ($remover["sucesso"]) {
            $linhaObj->Set("pro_mensagem_idioma", $remover["mensagem"]);
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
            $linhaObj->Set("ancora", "mensagens");
            $linhaObj->Processando();
        } else {
            $mensagem["erro"] = $remover["mensagem"];
        }
    } else {
        $mensagem["erro"] = "mensagem_remover_vazio";
    }
} elseif ($_POST["acao"] == "salvar_mensagem") {
    if ($_POST["mensagem"]) {
        $linhaObj->Set('id', intval($url[3]));
        $salvar = $linhaObj->cadastrarMensagem();
        if ($salvar["sucesso"]) {
            $linhaObj->Set("pro_mensagem_idioma", "mensagem_adicionada_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
            $linhaObj->Set("ancora", "mensagens");
            $linhaObj->Processando();
        } else {
            $mensagem["erro"] = $salvar["mensagem"];
        }
    } else {
        $salvar["sucesso"] = false;
        $salvar["erros"][] = "mensagem_vazio";
    }
}

if (isset($url[3])) {
    if ($url[4] == 'ajax_cidades') {
        $campos = 'idcidade, nome';
        if ($_REQUEST['idestado']) {
            $idestado = $_REQUEST['idestado'];
        } elseif ($_REQUEST['gerente_idestado']) {
            $idestado = $_REQUEST['gerente_idestado'];
            $campos = 'idcidade AS gerente_idcidade, nome';
        } elseif ($_REQUEST['responsavel_legal_idestado']) {
            $idestado = $_REQUEST['responsavel_legal_idestado'];
            $campos = 'idcidade AS responsavel_legal_idcidade, nome';
        } else {
            $idestado = $url[5];
        }

        $linhaObj->retornarJSON('cidades', mysql_real_escape_string($idestado), 'idestado', $campos, 'ORDER BY nome');
        exit;
    } elseif ($url[3] == 'cadastrar') {
        $estadosDetran = $detranObj->listarEstadosIntegrados();
        $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');
        include('idiomas/' . $config['idioma_padrao'] . '/formulario.php');
        include('telas/' . $config['tela_padrao'] . '/formulario.php');
        exit();
    } else {
        $linhaObj->Set('id', intval($url[3]));
        $linhaObj->Set('campos', 'p.*, i.nome as sindicato');
        $linha = $linhaObj->Retornar();
        $linha['contratos_nao_aceitos'] = $linhaObj->contratosNaoAceitos((int)$linha['idescola']);
        if ($linha) {
            switch ($url[4]) {
                case 'editar':
                    $estadosDetran = $detranObj->listarEstadosIntegrados();
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');
                    include('idiomas/' . $config['idioma_padrao'] . '/formulario.php');
                    include('telas/' . $config['tela_padrao'] . '/formulario.php');
                    break;
                case 'estados_cidades':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|10');
                    $linhaObj->Set('id', intval($url[3]));
                    $linhaObj->Set('campos', 'eec.idescola_estado_cidade, e.nome AS estado, c.nome AS cidade');
                    $estadosCidades = $linhaObj->listarEstadosCidadesAssociadas();

                    include 'idiomas/' . $config['idioma_padrao'] . '/estados_cidades.php';
                    include 'telas/' . $config['tela_padrao'] . '/estados_cidades.php';
                    break;
                case 'mensagens':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|13');
                    $linhaObj->Set('id', intval($url[3]));
                    $linhaObj->Set('campos', 'cm.*, u.nome');
                    $cfcMensagens = $linhaObj->listarMensagens();
                    include 'idiomas/' . $config['idioma_padrao'] . '/mensagens.php';
                    include 'telas/' . $config['tela_padrao'] . '/mensagens.php';
                    break;
                case 'pasta_virtual':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|6');
                    if ($url[5] == 'downloadarquivo') {
                        $linhaObj->Set("iddocumento", intval($url[6]));
                        $download = $linhaObj->retornarArquivo();
                        include("telas/" . $config["tela_padrao"] . "/download.arquivos.php");
                        exit;
                    } else if ($url[5] == 'visualizararquivo') {
                        $download = $linhaObj->set('iddocumento', (int)$url[6])
                            ->retornarArquivo();
                        include("telas/" . $config["tela_padrao"] . "/visualizar.arquivos.php");
                        exit;
                    }

                    $arquivos = $linhaObj->retornarListaArquivos();
                    include('idiomas/' . $config['idioma_padrao'] . '/pasta_virtual.php');
                    include('telas/' . $config['tela_padrao'] . '/pasta_virtual.php');
                    break;
                case 'remover':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|3');
                    include('idiomas/' . $config['idioma_padrao'] . '/remover.php');
                    include('telas/' . $config['tela_padrao'] . '/remover.php');
                    break;
                case 'acesso_bloqueado':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|9');
                    require 'idiomas/' . $config['idioma_padrao'] . '/acesso_bloqueado.php';
                    require 'telas/' . $config['tela_padrao'] . '/acesso_bloqueado.php';
                    break;
                case 'vendas_cfc':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|9');
                    require 'idiomas/' . $config['idioma_padrao'] . '/vendas_cfc.php';
                    require 'telas/' . $config['tela_padrao'] . '/vendas_cfc.php';
                    break;
                case 'opcoes':
                    include('idiomas/' . $config['idioma_padrao'] . '/opcoes.php');
                    include('telas/' . $config['tela_padrao'] . '/opcoes.php');
                    break;
                case 'contatos':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|4');
                    $linhaObj->Set('id', intval($url[3]));
                    $linhaObj->Set('ordem', 'asc');
                    $linhaObj->Set('limite', -1);
                    $linhaObj->Set('ordem_campo', 'tc.nome');
                    $linhaObj->Set('campos', 'c.*, tc.nome as tipo');
                    $associacoesArray = $linhaObj->ListarContatos();
                    $tiposArray = $linhaObj->ListarTiposContatos();
                    include('idiomas/' . $config['idioma_padrao'] . '/contatos.php');
                    include('telas/' . $config['tela_padrao'] . '/contatos.php');
                    break;
                case 'valores_cursos':
                    $formasPagamento = $forma_pagamento_faturas[$config['idioma_padrao']];

                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|5');

                    $_GET['q']['1|c.ativo_painel'] = 'S';
                    $cursoObj = new Cursos();
                    $cursos = $cursoObj
                        ->set('idcfc', $url[3])
                        ->set('limite', -1)
                        ->set(
                            'campos',
                            'c.idcurso,
                             c.nome,
                             cvc.idvalor_curso,
                             cvc.idcfc,
                             cvc.parcelas, cvc.disponivel_cfc,
                             cvc.valor_por_matricula,
                             cvc.valor_por_matricula_2,
                             cvc.quantidade_faturas_ciclo,
                             cvc.qtd_parcelas,
                             cvc.quantidade_matriculas,
                             cvc.quantidade_matriculas_2,
                             cvc.valor_excedente
                            '
                        )
                        ->listarTodas();

                    $formasPagamentoCurso = [];

                    foreach ($cursos as $curso) {
                        $formasPagamentoUsadas = $linhaObj->retornarFormasPagamentoCfcCurso(
                            $url[3],
                            $curso['idcurso'],
                            'forma_pagamento'
                        );

                        $formasPagamentoCurso[$curso['idcurso']] = array_column(
                            $formasPagamentoUsadas,
                            'forma_pagamento'
                        );
                    }

                    include 'idiomas/' . $config['idioma_padrao'] . '/valores_cursos.php';
                    include 'telas/' . $config['tela_padrao'] . '/valores_cursos.php';
                    break;
                case "contratos":
                    include("index.contratos.php");
                    break;
                case 'download':
                    include('telas/' . $config['tela_padrao'] . '/download.php');
                    break;
                case 'excluir':
                    include('idiomas/' . $config['idioma_padrao'] . '/excluir.arquivo.php');

                    $pasta = '';
                    if ($url[5] == 'avatar') {
                        $pasta = 'escolas_avatar';
                    } elseif ($url[5] == 'gerente_assinatura') {
                        $pasta = 'escolas_gerente_assinatura';
                    } elseif ($url[5] == 'responsavel_legal_assinatura') {
                        $pasta = 'escolas_responsavel_legal_assinatura';
                    } elseif ($url[5] == 'diretor_ensino_assinatura') {
                        $pasta = 'escolas_diretor_ensino_assinatura';
                    }

                    echo $linhaObj->excluirArquivoNovo($url[5], $pasta, $linha, $idioma);
                    break;
                case "acessarcomo":

                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");

                    $_SESSION['escola_email'] = $linha['email'];
                    $_SESSION['escola_senha'] = $linha['senha'];
                    $_SESSION['escola_idescola'] = $linha['idescola'];
                    $_SESSION['escola_nome'] = $linha['nome'];
                    $_SESSION['escola_ultimoacesso'] = $linha['ultimo_acesso'];

                    $linhaObj->Set("monitora_oque", "9");
                    $linhaObj->Set("monitora_qual", $linha["idescola"]);
                    $linhaObj->Monitora();

                    $linhaObj->Set("url", "/cfc");
                    $linhaObj->Processando();

                    break;
                case "json":
                    include("idiomas/" . $config["idioma_padrao"] . "/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                default:
                    header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
                    exit();
            }
        } else {
            header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
            exit();
        }
    }
} else {
    $linhaObj->set('pagina', $_GET['pag']);
    $linhaObj->set('campos', 'p.*, i.nome_abreviado as sindicato, c.nome as cidade');
    $linhaObj->set('ordem_campo', ($_GET['cmp']) ? $_GET['cmp'] : 'p.' . $config['banco']['primaria']);
    $linhaObj->set('ordem', ($_GET['ord']) ? $_GET['ord'] : 'desc');
    $linhaObj->set('limite', ((int)$_GET['qtd']) ? (int)$_GET['qtd'] : 30);
    $dadosArray = $linhaObj->listarTodas();


    include('idiomas/' . $config['idioma_padrao'] . '/index.php');
    include('telas/' . $config['tela_padrao'] . '/index.php');
}
