<?php
include("../classes/contas.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Contas();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);
$linhaObj->Set("modulo", $url[0]);

if ($_POST["acao"] == "salvar" && (($_POST['parcelas'] > 1 && !$_POST['parcelas_definidas']) || ($_POST['idcentro_custo'] == -100 && !$_POST['parcelas_definidas']))) {
    $_POST['valor'] = str_replace(array('.',','), array('','.'), $_POST['valor']);
    $_POST['valor_pago'] = str_replace(array('.',','), array('','.'), $_POST['valor_pago']);
    $_POST['valor_juros'] = str_replace(array('.',','), array('','.'), $_POST['valor_juros']);
    $_POST['valor_multa'] = str_replace(array('.',','), array('','.'), $_POST['valor_multa']);
    $_POST['valor_outro'] = str_replace(array('.',','), array('','.'), $_POST['valor_outro']);
    $_POST['valor_desconto'] = str_replace(array('.',','), array('','.'), $_POST['valor_desconto']);

    $_POST['valor_liquido'] = ($_POST['valor'] + $_POST['valor_juros'] + $_POST['valor_multa'] + $_POST['valor_outro'] - $_POST['valor_desconto']);

    include("../classes/centrosdecustos.class.php");
    $linhaCentroObj = new Centros_Custos();
    $linhaCentroObj->Set("limite", -1);
    $linhaCentroObj->Set("ordem_campo", 'idcentro_custo');
    $linhaCentroObj->Set("campos", "*");
    $centros_custos = $linhaCentroObj->retornarCentroSindicato($_POST['idsindicato']);

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $campos_remover = array('parcela','total_parcelas');
    $linhaObj->config['formulario'] = $linhaObj->alterarConfigFormulario($linhaObj->config['formulario'], $campos_remover);

    $config['formulario'] = $linhaObj->config['formulario'];
    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
    include("telas/" . $config["tela_padrao"] . "/formulario.php");
    exit();
} elseif ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);

    if ($_POST[$config["banco"]["primaria"]]) {
        $salvar = $linhaObj->Modificar();
    } else{
        $salvar = $linhaObj->Cadastrar();
    }

    if ($salvar["sucesso"]) {
        if ($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma", "modificar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma", "cadastrar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2]);
        }
        $linhaObj->Processando();
    } else {
        $_POST['valor'] = str_replace(array('.',','), array('','.'), $_POST['valor']);
        $_POST['valor_pago'] = str_replace(array('.',','), array('','.'), $_POST['valor_pago']);
        $_POST['valor_juros'] = str_replace(array('.',','), array('','.'), $_POST['valor_juros']);
        $_POST['valor_multa'] = str_replace(array('.',','), array('','.'), $_POST['valor_multa']);
        $_POST['valor_outro'] = str_replace(array('.',','), array('','.'), $_POST['valor_outro']);
        $_POST['valor_desconto'] = str_replace(array('.',','), array('','.'), $_POST['valor_desconto']);

        $_POST['valor_liquido'] = ($_POST['valor'] + $_POST['valor_juros'] + $_POST['valor_multa'] + $_POST['valor_outro'] - $_POST['valor_desconto']);

        include("../classes/centrosdecustos.class.php");
        $linhaCentroObj = new Centros_Custos();
        $linhaCentroObj->Set("limite", -1);
        $linhaCentroObj->Set("ordem_campo", 'idcentro_custo');
        $linhaCentroObj->Set("campos", "*");
        $centros_custos = $linhaCentroObj->retornarCentroSindicato($_POST['idsindicato']);
    }
} elseif ($_POST["acao"] == "remover") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->Remover();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "removerFaturas") {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->RemoverFaturas();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso_faturas");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }

} else if ($_POST["acao"] == "alterarSituacao") {
    $linhaObj->Set("id", intval($url[4]));
    $linhaObj->Set("campos", "c.*");
    $conta = $linhaObj->Retornar();
    if ($conta["situacao"]["visualizacoes"][1]) {
        $alterar = $linhaObj->AlterarSituacao($conta["idsituacao"], $_POST["situacao_para"]);
    } else {
        $alterar["sucesso"] = false;
        $alterar["mensagem"] = "mensagem_permissao_workflow";
    }
    if ($alterar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", $alterar["mensagem"]);
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
        //$linhaObj->Set("ancora","situacao_conta");
        $linhaObj->Processando();
    } else {
        $salvar["erro"] = true;
        $salvar["erros"][] = $alterar["mensagem"];
    }
} elseif ($_POST["acao"] == "associar_centros_custos") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $salvar = $linhaObj->AssociarCentrosCustos(intval($url[4]), $_POST["centros_custos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_centros_custos_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_centro_custo") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->RemoverCentroCusto($url[4]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_centro_custo_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "salvar_porcentagens_centros_custos") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->SalvarPorcentagensCentrosCustos($url[4]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "salvar_porcentagens_centros_custos_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
        $linhaObj->Processando();
    }
} elseif($_POST["acao"] == "adicionar_arquivo") {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|4');
    $adicionar = $linhaObj->set('id', $url[4])
        ->set('post', $_POST)
        ->adicionarArquivo();

    if($adicionar["sucesso"]){
        $linhaObj->set("pro_mensagem_idioma", $adicionar["mensagem"]);
        $linhaObj->set("url", "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif($_POST["acao"] == "remover_arquivo") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

    $linhaObj->Set("id", $url[4]);
    $linhaObj->Set("idarquivo", $_POST["idarquivo"]);
    $remover = $linhaObj->removerArquivo();

    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $remover["mensagem"];
    }
}

if (isset($url[3]) && $url[3] != "apagar" && $url[3] != "areceber") {

    if ($url[3] == "cadastrar") {
        if ($url[4] == "ajax_subcategorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idcategoria']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idcategoria']));
                $linhaCatObj->Set("idsindicato", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarSubcategoriasSindicato();
            }
            exit();
        } elseif ($url[4] == "ajax_categorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idsindicato']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarCategoriaSindicato();
            }
            exit();
        } elseif ($url[4] == "ajax_centros_custos") {
            include("../classes/centrosdecustos.class.php");
            $linhaCentroObj = new Centros_Custos();
            if ($_REQUEST['idsindicato']) {
                $linhaCentroObj->retornarCentroSindicato($_REQUEST['idsindicato'], true);
            }
            exit();
        } elseif ($url[4] == "ajax_produtos") {
            include("../classes/fornecedores.class.php");
            $linhaForObj = new Fornecedores();
            if ($_REQUEST['idfornecedor']) {
                $linhaForObj->Set("id", intval($_REQUEST['idfornecedor']));
                $linhaForObj->RetornarProdutosFornecedor();
            }
            exit();
        } elseif ($url[4] == 'ajax_escolas') {
            require_once('../classes/escolas.class.php');
            $linhaEscolaObj = new Escolas();
            if ($_REQUEST['idsindicato']) {
                $_GET['q']['1|p.idsindicato'] = (int)$_REQUEST['idsindicato'];
                $linhaEscolaObj->Set('ordem_campo','p.razao_social');
                $linhaEscolaObj->Set('ordem','asc');
                $linhaEscolaObj->Set('limite',-1);
                $linhaEscolaObj->Set('campos','p.idescola,p.razao_social');
                echo json_encode($linhaEscolaObj->ListarTodas());
            }
            exit();
        }

        $campos_remover = array('parcela','total_parcelas');
        $linhaObj->config['formulario'] = $linhaObj->alterarConfigFormulario($linhaObj->config['formulario'], $campos_remover);
        $config['formulario'] = $linhaObj->config['formulario'];
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit();
    } else {

        if ($url[4] == "ajax_subcategorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idcategoria']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idcategoria']));
                $linhaCatObj->Set("idsindicato", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarSubcategoriasSindicato();
            }
            exit;
        } else if ($url[4] == "ajax_categorias") {
            include("../classes/categorias.class.php");
            $linhaCatObj = new Categorias();
            if ($_REQUEST['idsindicato']) {
                $linhaCatObj->Set("id", intval($_REQUEST['idsindicato']));
                $linhaCatObj->retornarCategoriaSindicato();
            }
            exit();
        } else if ($url[4] == "ajax_centros_custos") {
            include("../classes/centrosdecustos.class.php");
            $linhaCentroObj = new Centros_Custos();
            if ($_REQUEST['idsindicato']) {
                $linhaCentroObj->retornarCentroSindicato($_REQUEST['idsindicato'], true);
            }
            exit();
        } else if ($url[4] == "ajax_produtos") {
            include("../classes/fornecedores.class.php");
            $linhaForObj = new Fornecedores();
            if ($_REQUEST['idfornecedor']) {
                $linhaForObj->Set("id", intval($_REQUEST['idfornecedor']));
                $linhaForObj->RetornarProdutosFornecedor();
            } else {
                $linhaCatObj->Set("id", intval($url[5]));
                $linhaCatObj->RetornarSubcategoriasCategoria();
            }
            exit;
        } elseif ($url[4] == 'ajax_escolas') {
            require_once('../classes/escolas.class.php');
            $linhaEscolaObj = new Escolas();
            if ($_REQUEST['idsindicato']) {
                $_GET['q']['1|p.idsindicato'] = (int)$_REQUEST['idsindicato'];
                $linhaEscolaObj->Set('ordem_campo','p.razao_social');
                $linhaEscolaObj->Set('ordem','asc');
                $linhaEscolaObj->Set('limite',-1);
                $linhaEscolaObj->Set('campos','p.idescola,p.razao_social');
                echo json_encode($linhaEscolaObj->ListarTodas());
            }
            exit();
        }

        if ($url[3] == 'idconta') {
            $linhaObj->Set("id", intval($url[4]));
            $linhaObj->Set("campos", "c.*, cw.cancelada, IF(c.tipo = 'despesa', (c.valor*-1), c.valor) as valor");
            $linha = $linhaObj->Retornar();

            if (($linha['idpagamento_compartilhado'] || $linha['idmatricula']) && $url[5] == 'editar') {
                header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
            }
//            elseif ($linha['fatura'] == 'S' && $url[5] != 'historico') {
//                header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
//            }

            if ($linha) {
                switch ($url[5]) {
                    case "editar":
                        $linha['parcelas'] = 1;
                        //MODIFICAR TAMBÉM NA INCLUSÃO AO SALVAR O FORMULÁRIO ACIMA - LINHA 47
                        $config['formulario'][0]['campos'][17]['evento'] = "readonly";

                        $config_remover = array(
                            'idcentro_custo',
                            'quantidade_centro_custo'
                        );
                        $config['formulario'] = $linhaObj->alterarConfigFormulario($config['formulario'], $config_remover);
                        $linhaObj->Set("config", $config);

                        $situacaoWorkflow = $linhaObj->RetornarSituacoesWorkflow();
                        $situacaoWorkflowRelacionamento = array();
                        foreach ($linhaObj->RetornarRelacionamentosWorkflow($linha['idsituacao']) as $sit)
                            $situacaoWorkflowRelacionamento[] = $sit['idsituacao_para'];

                        $situacao_pago = $linhaObj->retornarSituacaoPago();
                        $associacoesArray = $linhaObj->ListarCentrosAssociadas($url[4]);
                        //print_r2($associacoesArray);exit;

                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                        include("telas/" . $config["tela_padrao"] . "/formulario.php");
                        break;
                    case "visualizacompartilhada":
                        $arrayContasMatriculas = $linhaObj->RetornarMatriculasContaCompartilhada($linha['idpagamento_compartilhado']);
                        include("idiomas/" . $config["idioma_padrao"] . "/contas.compartilhadas.php");
                        include("telas/" . $config["tela_padrao"] . "/contas.compartilhadas.php");
                        break;
                    case "remover":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                        include("../classes/motivoscancelamentocontas.class.php");

                        $motivosCancelamentoObj = new Motivos_Cancelamento_Conta();

                        $motivosCancelamentoObj->Set("ordem", 'asc');
                        $motivosCancelamentoObj->Set("limite", -1);
                        $motivosCancelamentoObj->Set("ordem_campo", 'nome');
                        $motivosCancelamentoObj->Set("campos", "*");
                        $_GET['q']['1|ativo_painel'] = 'S';
                        $motivosCancelamento = $motivosCancelamentoObj->ListarTodas();
                        unset($_GET['q']['1|ativo_painel']);
                        include("idiomas/" . $config["idioma_padrao"] . "/remover.php");
                        include("telas/" . $config["tela_padrao"] . "/remover.php");
                        break;
                    case "historico":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                        $historicoArray = $linhaObj->RetornarHistoricos();
                        include("idiomas/" . $config["idioma_padrao"] . "/historico.php");
                        include("telas/" . $config["tela_padrao"] . "/historico.php");
                        break;
                    /*case "administrarmatricula":
                      $linhaObj->Set("url","/".$url[0]."/academico/matriculas/".$url[4]."/administrar/");
                      $linhaObj->Processando();
                    break;*/
                    case "opcoes":
                        include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                        include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                        break;
                    case "json":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                        include("telas/" . $config["tela_padrao"] . "/json.php");
                        break;

                    case "centros_custos":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

                        $associacoesArray = $linhaObj->ListarCentrosAssociadas($url[4]);

                        include("idiomas/" . $config["idioma_padrao"] . "/centros_custos.php");
                        include("telas/" . $config["tela_padrao"] . "/centros_custos.php");
                        break;
                    case "pastavirtual":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                        if ($url[6] == 'downloadarquivo') {
                            $linhaObj->Set("iddocumento", intval($url[7]));
                            $download = $linhaObj->retornarArquivo();
                            include("telas/".$config["tela_padrao"]."/download.arquivos.php");
                            exit;
                        } else if ($url[6] == 'visualizararquivo') {
                            $download = $linhaObj->set('iddocumento', (int) $url[7])
                                ->retornarArquivo();
                            include("telas/".$config["tela_padrao"]."/visualizar.arquivos.php");
                            exit;
                        }

                        $arquivos = $linhaObj->retornarListaArquivos();
                        include("idiomas/".$config["idioma_padrao"]."/pastavirtual.php");
                        include("telas/".$config["tela_padrao"]."/pastavirtual.php");
                        break;
                    default:
                        header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                        exit();
                }
            } else {
                header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                exit();
            }
        } else if ($url[3] == 'dia') {
            $linha['data_vencimento'] = $url[4];

            switch ($url[5]) {
                case 'visualizafacebox':
                    $listagem = 'listagem_matriculas';

                    $linhaObj->Set('campos', 'c.*, p.nome as aluno, m.idmatricula, cw.nome as situacao, cw.cor_bg as situacao_cor_bg, cw.cor_nome as situacao_cor_nome');
                    $arrayContas = $linhaObj->RetornarMatriculasDia($url[4]);

                    include('idiomas/' . $config['idioma_padrao'] . '/contas.detalhes.php');
                    include('telas/' . $config['tela_padrao'] . '/contas.detalhes.php');
                    break;

                case 'faturas':
                    $listagem = 'listagem_faturas';

                    $linhaObj->Set('campos', 'c.idconta,
                                            c.data_vencimento,
                                            c.valor,
                                            e.nome_fantasia AS escola,
                                            cw.nome AS situacao,
                                            cw.cor_bg AS situacao_cor_bg,
                                            cw.cor_nome AS situacao_cor_nome');
                    $linhaObj->Set('ordem_campo', 'c.idconta');
                    $linhaObj->Set('ordem', 'ASC');
                    $linhaObj->Set('limite', -1);

                    $_GET['q']['1|c.data_vencimento'] = $url[4];
                    $_GET['q']['5|cw.renegociada'] = 'S';
                    $_GET['q']['5|cw.transferida'] = 'S';
                    $_GET['q']['5|cw.cancelada'] = 'S';

                    $arrayContas = $linhaObj->listarTodasFaturas();

                    unset($_GET['q']['1|c.data_vencimento']);
                    unset($_GET['q']['5|cw.renegociada']);
                    unset($_GET['q']['5|cw.transferida']);
                    unset($_GET['q']['5|cw.cancelada']);

                    include('idiomas/' . $config['idioma_padrao'] . '/contas.detalhes.php');
                    include('telas/' . $config['tela_padrao'] . '/contas.detalhes.php');
                    break;

                case 'historico':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');
                    $historicoArray = $linhaObj->RetornarHistoricos();

                    include('idiomas/' . $config['idioma_padrao'] . '/historico.php');
                    include('telas/' . $config['tela_padrao'] . '/historico.php');
                    break;
                default:
                    header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
                    exit();
            }
        } else {
            header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
            exit();
        }
        /**/
    }
} else {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = -1;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "c.*, cw.nome as situacao, cw.cor_bg as situacao_cor_bg, cw.cor_nome as situacao_cor_nome, cw.pago");
    $linhaObj->Set("campos2", "c.*, cw.nome as situacao, cw.cancelada, cw.renegociada, cw.transferida, cw.cor_bg as situacao_cor_bg, cw.cor_nome as situacao_cor_nome, cat.nome as categoria, forn.nome as fornecedor, cw.pago");
    $linhaObj->Set("tipo_conta", $url[3]);

    $dadosArray = $linhaObj->listarAgrupadas();

    //print_r2($dadosArray);exit;

    $linhaSindicatoObj = new Sindicatos();
    $sindicatos_usuario = $linhaSindicatoObj->retornarSindicatosUsuario($usuario["idusuario"]);

    $valorReceita = 0;
    $valorDespesa = 0;
    foreach ($dadosArray as $ind => $linha) {
        if ($linha["qtde_contas"]) {
            $valorReceita += $linha["total"];
        } else {
            if ($linha["cancelada"] <> 'S' && $linha["renegociada"] <> 'S' && $linha["transferida"] <> 'S') {
                if ($linha["valor"] < 0) $valorDespesa += ($linha["valor"] * -1);
                else $valorReceita += $linha["valor"];
            }
        }
    }


    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
?>