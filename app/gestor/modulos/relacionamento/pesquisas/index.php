<?

    include("../classes/pesquisas.class.php");
    include("config.php");
    include("config.formulario.php");
    include("config.listagem.php");


    //Incluimos o arquivo com variaveis padrão do sistema.
    include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

    $linhaObj = new Pesquisas();
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

    $linhaObj->Set("idusuario",$usuario["idusuario"]);
    $linhaObj->Set("modulo",$url[0]);
    $linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


    if($_POST["acao"] == "salvar"){
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
        if($_POST["acao_url"]){
            $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."?".base64_decode($_POST["acao_url"]);
        }else{
            $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4];
        }
        $linhaObj->Set("post",$_POST);
        if($_POST[$config["banco"]["primaria"]]) $salvar = $linhaObj->Modificar();
            else $salvar = $linhaObj->Cadastrar();
        if($salvar["sucesso"]){
            if($_POST[$config["banco"]["primaria"]]) {
                $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
                $linhaObj->Set("url",$url_redireciona);
            } else {
                $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
                $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
            }
            $linhaObj->Processando();
        }
    } elseif($_POST["acao"] == "remover"){
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
        $linhaObj->Set("post",$_POST);

        $respondida = $linhaObj->verificaPesquisaRespondida($url[3]);
        if (!$respondida)
            $remover = $linhaObj->Remover();
        else {
            $erros['alerta_pesquisa_respondida'] = 'alerta_pesquisa_respondida';
        }
        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
            $linhaObj->Processando();
        }
    } elseif($_POST["acao"] == "associar_pergunta"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

        $linhaObj->Set("id",intval($url[3]));
        $linhaObj->Set("post",$_POST);
        $salvar = $linhaObj->AssociarPergunta();

        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/perguntas");
            $linhaObj->Processando();
        }
    }elseif($_POST["acao"] == "remover_pergunta"){
        include("../classes/perguntaspesquisas.class.php");
        $linhaObjPerguntas = new Perguntas_Pesquisas();
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");

        $linhaObj->Set("post",$_POST);

        $respondida = $linhaObjPerguntas->verificaPerguntaRespondidaPesquisa($_POST['remover'], $url[3]);
        if (!$respondida) {
            $remover = $linhaObj->RemoverPergunta();
            if($remover["sucesso"]){
                $linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
                $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/perguntas");
                $linhaObj->Processando();
            }
        } else {
            $salvar['erros'][] = 'alerta_pergunta_respondida';
        }
    }elseif($_POST["acao"] == "associar_pessoa"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

        $linhaObj->Set("id",intval($url[3]));
        $linhaObj->Set("post",$_POST);
        $salvar = $linhaObj->AssociarPessoas();

        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/pessoas");
            $linhaObj->Processando();
        }
    }elseif($_POST["acao"] == "remover_pessoa"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");

        $linhaObj->Set("post",$_POST);
        $remover = $linhaObj->RemoverPessoas();

        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/pessoas");
            $linhaObj->Processando();
        }
    }elseif($_POST["acao"] == "salvar_layout"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");

        $linhaObj->Set("id",$url[3]);
        $linha = $linhaObj->Retornar($sql);

        $linhaObj->Set("post",$_POST);
        $salvar = $linhaObj->CadastrarLayout();

        if($salvar["sucesso"]){
            if(!$linha["layout"]){
                $linhaObj->Set("pro_mensagem_idioma","cadastrar_layout_sucesso");
            }else{
                $linhaObj->Set("pro_mensagem_idioma","modificar_layout_sucesso");
            }
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/layout");
            $linhaObj->Processando();
        }
    }elseif($_POST["acao"] == "salvar_corpo_email"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|20");

        $linhaObj->Set("id",$url[3]);
        //$linha = $linhaObj->Retornar($sql);

        $linhaObj->Set("post",$_POST);
        $salvar = $linhaObj->alterarCorpoEmail();

        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","modificar_corpo_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/corpo_email");
            $linhaObj->Processando();
        }
    }elseif ($_POST['acao'] == 'salvar_imagens') {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13");
        include("idiomas/".$config["idioma_padrao"]."/imagens.php");
        $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
        $linhaObj->Set("id",$url[3]);
        $linhaObj->Set("files",$_FILES);
        $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
        $salvar = $linhaObj->CadastrarImagens($erros);

        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","imagem_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/imagens");
            $linhaObj->Processando();
        }
    }elseif($_POST["acao"] == "remover_imagem"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|14");
        $linhaObj->Set("id",$_POST['remover']);
        $remover = $linhaObj->RemoverImagens();

        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","remover_imagem_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/imagens");
            $linhaObj->Processando();
        }
    }elseif($_POST["acao"] == "salvar_pessoas_em_bloco"){
        //$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");
        $linhaObj->Set("id",intval($url[3]));
        $salvar = $linhaObj->associarPessoasEmBloco($_POST["idempreendimento"], $_POST["situacao"]);

        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","salvar_pessoas_em_bloco_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/pessoas");
            $linhaObj->Processando();
        }
    }elseif($_POST["acao"] == "salvar_fila"){
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17");
        $linhaObj->Set("post",$_POST);
        $linhaObj->Set("id",intval($url[3]));
        $salvar = $linhaObj->salvarFila();
        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","salvar_fila_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/fila");
            $linhaObj->Processando();
        }

    }elseif($_POST["acao"] == "remover_fila"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17");
        $linhaObj->Set("post",$_POST);
        $remover = $linhaObj->removerFila();

        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","remover_fila_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/fila");
            $linhaObj->Processando();
        }

    } else if($_POST['act'] == "responder_pesquisa"){
        $linhaObj->Set("post",$_POST);

        $hash_valido = $linhaObj->verificarHashUsuarioPesquisa($_POST['idpesquisa_pessoa'], $_POST['hash']);
        if($hash_valido['hash']) {
            if (!$hash_valido['data_resposta'])
                $reponder_pesquisa = $linhaObj->responderPesquisa();
            else
                $erro['msg'] = "pesquisa_respondida";
        } else
            $erro['msg'] = "hash_invalido";

        if($reponder_pesquisa["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","responder_pesquisa_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/");
            $linhaObj->Processando();
        } else if (!$erro['msg']) {
            $erro['msg'] = "erro_processando_informacao";
        }
    } else if($_POST['act'] == "reenviar_pesquisa"){

        $reenviar_pesquisa = $linhaObj->reenviarPesquisa($url[3]);

        if($reenviar_pesquisa["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","reenviar_pesquisa_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
            $linhaObj->Processando();
        } else if (!$erro['msg']) {
            $erro['msg'] = "erro_processando_informacao";
        }
    } /*elseif($_POST["acao"] == "alterar_situacao"){
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

        $linhaObj->Set("id",intval($url[3]));
        $linhaObj->Set("post",$_POST);
        $salvar = $linhaObj->AlterarSituacao();

        if(!$salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/alterar_situacao");
            $linhaObj->Processando();
        }
    }*/

    if(isset($url[3])){

        if($url[3] == "ajax_cursos") {
            if ($_REQUEST['idoferta']) {
                $linhaObj->Set("id",(int)$_REQUEST['idoferta']);
                $linhaObj->RetornarCursosOferta();
                exit();
            }
        }
        if($url[3] == "ajax_turmas") {
            if ($_REQUEST['idoferta']) {
                $linhaObj->Set("id",(int)$_REQUEST['idoferta']);
                $linhaObj->RetornarTurmasOferta();
                exit();
            }
        }
        if($url[3] == "ajax_escolas") {
            if ($_REQUEST['idoferta']) {
                $linhaObj->Set("id",(int)$_REQUEST['idoferta']);
                $linhaObj->RetornarEscolasOferta();
                exit();
            }
        }

        if($url[3] == "cadastrar") {
            $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
            include("idiomas/".$config["idioma_padrao"]."/formulario.php");
            include("telas/".$config["tela_padrao"]."/formulario.php");
            exit();
        } else {

            $linhaObj->Set("id",intval($url[3]));
            $linhaObj->Set("campos","*");
            $linha = $linhaObj->Retornar();

            if($linha) {

                switch ($url[4]) {
                    case "clonar":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16");
                        $linhaObj->Set("id",$linha['idpesquisa']);
                        $retorno = $linhaObj->clonarPesquisa();
                        if($retorno){
                            $linhaObj->Set("pro_mensagem_idioma","pesquisa_clonada_sucesso");
                            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".intval($retorno)."/editar?clonada_sucesso");
                            $linhaObj->Processando();
                        }
                        break;
                    case "editar":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
                        include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                        include("telas/".$config["tela_padrao"]."/formulario.php");
                        break;
                    case "remover":
                        $respondida = $linhaObj->verificaPesquisaRespondida($url[3]);

                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
                        include("idiomas/".$config["idioma_padrao"]."/remover.php");
                        include("telas/".$config["tela_padrao"]."/remover.php");
                        break;
                    case "opcoes":
                        include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                        include("telas/".$config["tela_padrao"]."/opcoes.php");
                        break;
                    case "perguntas":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
                        $linhaObj->Set("id",intval($url[3]));
                        $linhaObj->Set("ordem","asc");
                        $linhaObj->Set("limite",-1);
                        $linhaObj->Set("ordem_campo","nome");
                        $linhaObj->Set("campos","pp.idpesquisa_pergunta, per.idpergunta, per.nome, per.tipo, per.multipla_escolha");
                        $associacoesArray = $linhaObj->ListarPerguntasAss();
                        $perguntasArray = $linhaObj->listarPerguntas();
                        include("idiomas/".$config["idioma_padrao"]."/perguntas.php");
                        include("telas/".$config["tela_padrao"]."/perguntas.php");
                        break;
                    case "fila":
                        include("idiomas/".$config["idioma_padrao"]."/fila.php");
                        if ($url[5] == "add") {
                          if ($url[6] == "usuariosadm") {
                             $configFormulario = "formulario_usuarios_adm";
                             $paginaTitulo = "pagina_titulo_usuarios_adm";
                             $listagem = "listagem_add_fila";
                              if ($_POST["acao"] == "listar_fila") {
                                  unset($_POST["acao"],$_POST["ativo_painel"]);
                                  $busca = "";
                                  foreach($_POST as $campo => $valor) {
                                    if(empty($valor)) {
                                      $valor = "Todos(as)";
                                    } elseif($campo == "data_nasc_mes") {
                                      $valor = $meses_idioma[$config["idioma_padrao"]][$valor];
                                    } elseif($campo == "idestado") {
                                      $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                      $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                      $valor = $estado["nome"];
                                    } elseif($campo == "idcidade") {
                                      $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                      $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                      $valor = $cidade["nome"];
                                    } elseif($campo == "idsindicato") {
                                      $linhaObj->sql = "select nome from sindicatos where idsindicato = '".$valor."'";
                                      $sindicato = $linhaObj->retornarLinha($linhaObj->sql);
                                      $valor = $sindicato["nome"];
                                    } elseif($campo == "idescola") {
                                      $linhaObj->sql = "select nome_fantasia as nome from escolas where idescola = '".$valor."'";
                                      $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                      $valor = $escola["nome"];
                                    } elseif($campo == "idperfil") {
                                      $linhaObj->sql = "select nome from usuarios_adm_perfis where idperfil = '".$valor."'";
                                      $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                      $valor = $escola["nome"];
                                    }

                                    $_POST["acao"] = "listar_fila";
                                    $busca .= str_replace("%s",$valor,$idioma[$campo]);
                                    $busca .= "<br />";
                                  }
                                  $linhaObj->Set("post",$_POST);
                                  $linhaObj->Set("id",intval($url[3]));
                                  $filaAddArray = $linhaObj->listarFilaAddPesquisaUsuarios();
                              }

                          } elseif($url[6] == "professores") {

                            $configFormulario = "formulario_professores";
                            $paginaTitulo = "pagina_titulo_professores";
                            $listagem = "listagem_add_fila";
                            if($_POST["acao"] == "listar_fila") {
                              unset($_POST["acao"],$_POST["ativo_painel"]);

                              $busca = "";
                              foreach($_POST as $campo => $valor) {
                                if(empty($valor)) {
                                  $valor = "Todos(as)";
                                } elseif($campo == "data_nasc_mes") {
                                  $valor = $meses_idioma[$config["idioma_padrao"]][$valor];
                                } elseif($campo == "idestado") {
                                  $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                  $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $estado["nome"];
                                } elseif($campo == "idcidade") {
                                  $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                  $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $cidade["nome"];
                                } elseif($campo == "idava") {
                                  $linhaObj->sql = "select nome from avas where idava = '".$valor."'";
                                  $ava = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $ava["nome"];
                                } elseif($campo == "idoferta") {
                                  $linhaObj->sql = "select nome from ofertas where idoferta = '".$valor."'";
                                  $oferta = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $oferta["nome"];
                                } elseif($campo == "idcurso") {
                                  $linhaObj->sql = "select nome from cursos where idcurso = '".$valor."'";
                                  $curso = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $curso["nome"];
                                }

                                $_POST["acao"] = "listar_fila";

                                $busca .= str_replace("%s",$valor,$idioma[$campo]);
                                $busca .= "<br />";
                              }

                              $linhaObj->Set("post",$_POST);
                              $linhaObj->Set("id",intval($url[3]));
                              $filaAddArray = $linhaObj->listarFilaAddPesquisaProfessores();
                            }

                          } elseif($url[6] == "atendentes") {

                            $configFormulario = "formulario_vendedores";
                            $paginaTitulo = "pagina_titulo_vendedores";

                            if($_POST["acao"] == "listar_fila") {
                              unset($_POST["acao"],$_POST["ativo_painel"]);

                              $busca = "<br />";
                              foreach($_POST as $campo => $valor) {
                                if(empty($valor)) {
                                  $valor = "Todos(as)";
                                } elseif($campo == "idsindicato") {
                                  $linhaObj->sql = "select nome from sindicatos where idsindicato = '".$valor."'";
                                  $imobiliaria = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $imobiliaria["nome"];
                                } elseif($campo == "idescola") {
                                  $linhaObj->sql = "select nome_fantasia as nome from escolas where idescola = '".$valor."'";
                                  $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $escola["nome"];
                                } elseif($campo == "idestado") {
                                  $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                  $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $estado["nome"];
                                } elseif($campo == "idcidade") {
                                  $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                  $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $cidade["nome"];
                                }

                                $_POST["acao"] = "listar_fila";

                                $busca .= str_replace("%s",$valor,$idioma[$campo]);
                                $busca .= "<br />";
                              }
                              $busca .= "<br />";

                              $linhaObj->Set("post",$_POST);
                              $linhaObj->Set("id",intval($url[3]));
                              $filaAddArray = $linhaObj->listarFilaAddMuralVendedores();
                            }

                          } elseif($url[6] == "pessoas") {

                            $configFormulario = "formulario_pessoas";
                            $paginaTitulo = "pagina_titulo_pessoas";
                            $listagem = "listagem_add_fila_pessoa";
                            if($_POST["acao"] == "listar_fila") {
                              unset($_POST["acao"],$_POST["ativo_painel"]);

                              $busca = "";
                              foreach($_POST as $campo => $valor) {
                                if(empty($valor)) {
                                  $valor = "Todos(as)";
                                } elseif($campo == "situacao") {
                                  $valor = $situacao_pessoa[$config["idioma_padrao"]][$valor];
                                } elseif($campo == "estado_civil") {
                                  $valor = $estadocivil[$config["idioma_padrao"]][$valor];
                                } elseif($campo == "data_nasc_mes") {
                                  $valor = $meses_idioma[$config["idioma_padrao"]][$valor];
                                } elseif($campo == "idestado") {
                                  $linhaObj->sql = "select nome from estados where idestado = '".$valor."'";
                                  $estado = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $estado["nome"];
                                } elseif($campo == "idcidade") {
                                  $linhaObj->sql = "select nome from cidades where idcidade = '".$valor."'";
                                  $cidade = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $cidade["nome"];
                                }

                                $_POST["acao"] = "listar_fila";

                                $busca .= str_replace("%s",$valor,$idioma[$campo]);
                                $busca .= "<br />";
                              }

                              $linhaObj->Set("post",$_POST);
                              $linhaObj->Set("id",intval($url[3]));
                              $filaAddArray = $linhaObj->listarFilaAddPesquisaPessoas();
                            }

                          } elseif($url[6] == "matriculas") {

                            $configFormulario = "formulario_matriculas";
                            $paginaTitulo = "pagina_titulo_matriculas";
                            $listagem = "listagem_add_fila_matricula";
                            if($_POST["acao"] == "listar_fila") {
                              unset($_POST["acao"],$_POST["ativo_painel"]);

                              $busca = "";
                              foreach($_POST as $campo => $valor) {
                                if(empty($valor)) {
                                  $valor = "Todos(as)";
                                } elseif($campo == "idsindicato") {
                                  $linhaObj->sql = "select nome from sindicatos where idsindicato = '".$valor."'";
                                  $sindicato = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $sindicato["nome"];
                                } elseif($campo == "idoferta") {
                                  $linhaObj->sql = "select nome from ofertas where idoferta = '".$valor."'";
                                  $oferta = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $oferta["nome"];
                                } elseif($campo == "idturma") {
                                  $linhaObj->sql = "select nome from ofertas_turmas where idturma = '".$valor."'";
                                  $turma = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $turma["nome"];
                                } elseif($campo == "idcurso") {
                                  $linhaObj->sql = "select nome from cursos where idcurso = '".$valor."'";
                                  $curso = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $curso["nome"];
                                } elseif($campo == "idescola") {
                                  $linhaObj->sql = "select nome_fantasia from escolas where idescola = '".$valor."'";
                                  $escola = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $escola["nome_fantasia"];
                                } elseif($campo == "idsituacao") {
                                  $linhaObj->sql = "select nome from matriculas_workflow where idsituacao = '".$valor."'";
                                  $situacao = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $situacao["nome"];
                                } elseif($campo == "idvendedor") {
                                  $linhaObj->sql = "select nome from vendedores where idvendedor = '".$valor."'";
                                  $vendedor = $linhaObj->retornarLinha($linhaObj->sql);
                                  $valor = $vendedor["nome"];
                                }

                                $_POST["acao"] = "listar_fila";

                                $busca .= str_replace("%s",$valor,$idioma[$campo]);
                                $busca .= "<br />";
                              }

                              $linhaObj->Set("post",$_POST);
                              $linhaObj->Set("id",intval($url[3]));
                              $filaAddArray = $linhaObj->listarFilaAddPesquisaMatriculas();
                            }

                          }
                        } else {

                          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17");
                          $linhaObj->Set("pagina",$_GET["pag"]);
                          if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
                          $linhaObj->Set("ordem",$_GET["ord"]);
                          if(!$_GET["qtd"]) $_GET["qtd"] = -1;
                          $linhaObj->Set("limite",intval($_GET["qtd"]));
                          if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_fila"]["primaria"];
                          $linhaObj->Set("ordem_campo",$_GET["cmp"]);
                          $linhaObj->Set("campos","pf.*");
                          $linhaObj->Set("id",intval($url[3]));
                          $filaArray = $linhaObj->listarFilaPesquisa(intval($url[3]));
                          $filtrosArray = $linhaObj->listarFiltros(intval($url[3]));
                        }

                        include("idiomas/".$config["idioma_padrao"]."/fila.php");
                        include("telas/".$config["tela_padrao"]."/fila.php");
                        break;
                    case "imagens":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12");
                        $linhaObj->Set("id",intval($url[3]));
                        $imagensArray = $linhaObj->RetornaImagens();

                        include("idiomas/".$config["idioma_padrao"]."/imagens.php");
                        include("telas/".$config["tela_padrao"]."/imagens.php");
                        break;
                    case "download":
                        $linhaObj->Set("id",intval($url[5]));
                        $imagem = $linhaObj->RetornarImagemDownload();
                        include("telas/".$config["tela_padrao"]."/download.php");
                        break;
                    case "visualiza_imagem":
                        $linhaObj->Set("id",intval($url[5]));
                        $imagem = $linhaObj->RetornarImagemDownload();
                        include("telas/".$config["tela_padrao"]."/visualiza_imagem.php");
                        break;
                    case "corpo_email":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
                        $linhaObj->Set("campos","idpesquisa_imagem, nome");
                        $associacoesImagensArray = $linhaObj->ListarImagens();

                        include("idiomas/".$config["idioma_padrao"]."/corpo_email.php");
                        include("telas/".$config["tela_padrao"]."/corpo_email.php");
                        break;
                    case "layout":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");

                        $linhaObj->Set("id",intval($url[3]));
                        $linhaObj->Set("ordem","asc");
                        $linhaObj->Set("limite",-1);
                        $linhaObj->Set("ordem_campo","nome");
                        $linhaObj->Set("campos","pp.idpesquisa_pergunta, per.idpergunta, per.nome");
                        $associacoesArray = $linhaObj->ListarPerguntasAss();

                        $linhaObj->Set("campos","idpesquisa_imagem, nome");
                        $associacoesImagensArray = $linhaObj->ListarImagens();

                        include("idiomas/".$config["idioma_padrao"]."/layout.php");
                        include("telas/".$config["tela_padrao"]."/layout.php");
                        break;
                    case "responder":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");
                        $linhaObj->Set("id",intval($url[3]));
                        $linha = $linhaObj->RetornarPreviewPesquisa('responder');
                        include("idiomas/".$config["idioma_padrao"]."/preview.php");
                        include("telas/".$config["tela_padrao"]."/preview.popup.php");
                        break;
                    case "filtros":
                        include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                        include("telas/".$config["tela_padrao"]."/formulario.php");
                        break;
                    case "json":
                        include("idiomas/".$config["idioma_padrao"]."/json.php");
                        include("telas/".$config["tela_padrao"]."/json.php");
                        break;
                    case "preview":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");
                        $linhaObj->Set("id",intval($url[3]));
                        $linha = $linhaObj->RetornarPreviewPesquisa();
                        include("idiomas/".$config["idioma_padrao"]."/preview.php");
                        include("telas/".$config["tela_padrao"]."/preview.php");
                        break;
                    case "previewpopup":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");
                        $linhaObj->Set("id",intval($url[3]));
                        $linha = $linhaObj->RetornarPreviewPesquisa();
                        include("idiomas/".$config["idioma_padrao"]."/preview.php");
                        include("telas/".$config["tela_padrao"]."/preview.popup.php");
                        break;
                    case "respostas":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");
                        $linhaObj->Set("id",intval($url[3]));
                        $linha = $linhaObj->RetornarPreviewPesquisaRespondida(intval($url[5]));
                        include("idiomas/".$config["idioma_padrao"]."/preview.php");
                        include("telas/".$config["tela_padrao"]."/preview.popup.php");
                        break;
                    case "pessoas_em_bloco":
                        include("../classes/empreendimentos.class.php");
                        $empObj = new Empreendimentos();
                        $empObj->Set("campos","e.*");
                        $empreendimentos = $empObj->ListarTodas();

                        include("idiomas/".$config["idioma_padrao"]."/pessoas_em_bloco.php");
                        include("telas/".$config["tela_padrao"]."/pessoas_em_bloco.php");
                        break;
                    case "alterar_situacao":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
                        include("idiomas/".$config["idioma_padrao"]."/alterar_situacao.php");
                        include("telas/".$config["tela_padrao"]."/alterar_situacao.php");
                        break;
                    case "resultado":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|18");
                        $perguntas = $linhaObj->ListarPerguntasResultado($url[3]);

                        include("idiomas/".$config["idioma_padrao"]."/resultado.php");
                        include("telas/".$config["tela_padrao"]."/resultado.php");
                        break;
                    case "reenviar":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|19");

                        include("idiomas/".$config["idioma_padrao"]."/reenviar.php");
                        include("telas/".$config["tela_padrao"]."/reenviar.php");
                        break;
                    default:
                       header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
                       exit();
                }

            } else {
               header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
               exit();
            }

        }

    } else {
        $linhaObj->Set("pagina",$_GET["pag"]);
        if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
        $linhaObj->Set("ordem",$_GET["ord"]);
        if(!$_GET["qtd"]) $_GET["qtd"] = 30;
        $linhaObj->Set("limite",intval($_GET["qtd"]));
        if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
        $linhaObj->Set("ordem_campo",$_GET["cmp"]);
        $linhaObj->Set("campos","*");
        $dadosArray = $linhaObj->ListarTodas();
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
    }

?>