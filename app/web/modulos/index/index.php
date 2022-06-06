<?php
// echo "acao = "; var_export($_POST['acao']);
// echo "postttt = "; var_export($_POST);
// echo "<br>dados_escola = "; var_export($_SESSION['dados_escola']);
// echo "<br>lojaaaa = "; var_export($_SESSION['loja']);

$protocolo = "http";
if ($config["https"]) {
    $protocolo = "https";
}

if ($_GET['etapa']) {
    switch ($_GET['etapa']) {
        case 1:
            unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['dados_escola'], $_SESSION['loja_passo']);
            header('Location: ' . $config['urlSiteLoja']);
            exit;
            break;
        case 2:
            $slug =  $_SESSION['dados_escola']['slug'];
            unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['dados_escola'], $_SESSION['loja_passo']);

            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $slug);
            exit;
            break;
    }
}

if (
    (
        $url[3] == 'login'
        && $_POST['acao'] != 'cadastrar_logar'
        && $_POST['acao'] != 'modificar_logar'
    )
    || $_GET['opLogin'] == 'sair'
) {
    $_SESSION['loja_etapa_voltar'] = '2';

    if (
        ! empty($_SESSION['dados_escola']['detran_codigo'])
        && in_array($_SESSION['dados_escola']['idestado'], $GLOBALS['estadosDetran'])
    ) {
        // $mensagens[] = 'mensagem_detran';
    }

    require_once 'includes/login.php';
    exit;
}

if ($url[1] && (int) $url[2] && $url[3]) {
    require_once '../classes/escolas.class.php';

    $escolaObj = new Escolas;
    $escola = $escolaObj->set('idpessoa', $usuario['idpessoa'])
        ->set('modulo', $url[0])
        ->set('siglaUf', $url[1])
        ->set('idcidade', (int) $url[2])
        ->set('slug', $url[3])
        ->set('ativoPainel', 'S')
        ->set('campos', 'p.idescola, p.nome_fantasia AS nome, p.avatar_servidor, p.slug, p.email,
            p.telefone, p.detran_codigo, e2.nome AS estado, i.idsindicato, p.pagseguro, e.idestado, p.fastconnect_client_key, p.fastconnect_client_code')
        ->retornar();

    if ($escola) {
        $_SESSION['loja_etapa'] = 'cursos';
        $_SESSION['loja']['idescola'] = $escola['idescola'];
    }
}

require_once '../classes/ofertas.class.php';
require_once '../classes/pessoas.class.php';
require_once '../classes/loja.pedidos.class.php';
require_once '../classes/cursos.class.php';
require_once 'config.formulario.php';


$ofertaObj = new Ofertas;
$ofertaObj->set('idpessoa', $usuario['idpessoa']);
$ofertaObj->set('modulo', $url[0]);

$lojaPedidoObj = new Loja_Pedidos;
$lojaPedidoObj->set('idpessoa', $usuario['idpessoa']);
$lojaPedidoObj->set('modulo', $url[0]);

if ($_POST['acao'] == 'matricular' && $_SESSION['loja_etapa'] == 'cursos') {
    
    $pessoaObj = new Pessoas;
    $pessoaObj->set('modulo', $url[0]);
    $pessoaObj->set('post', $_POST);
    $pessoaObj->set('nao_monitara',true);

    $config['formulario'] = $config['formulario_pagamentocurso'];

    $ofertaObj->set('idoferta_curso', $_POST['idoferta_curso']);
    $ofertaObj->set('idescola', $_SESSION['loja']['idescola']);
    $ofertaObj->set('ordem', 'ASC');
    $ofertaObj->set('limite', -1);
    $ofertaObj->set('ordem_campo', 'ocp.ordem, c.nome');
    $ofertaObj->set('campos', 'o.idoferta,
        oc.idoferta_curso,
        c.idcurso,
        ocp.idoferta_curso_escola,
        oc.possui_financeiro,
        c.nome,
        ocp.idescola,
        (
            SELECT
                idturma
            FROM
                ofertas_turmas
            WHERE
                idoferta = o.idoferta AND
                ativo = "S" AND
                ativo_painel = "S"
            ORDER BY
                RAND()
            LIMIT 1
        ) AS idturma');
    $ofertaCurso = $ofertaObj->listarTodasCursosMatriculas();//Retorna a oferta curso selecionada
    $ofertaCurso = array_shift($ofertaCurso);

    $cursoObj = new Cursos();
    $curso = $cursoObj->set('idpessoa', $usuario['idpessoa'])
        ->set('modulo', $url[0])
        ->set('id', $ofertaCurso['idcurso'])
        ->set('idcfc', $ofertaCurso['idescola'])
        ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
            COALESCE (svc.avista, cvc.avista) AS avista, COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	        COALESCE (svc.parcelas, cvc.parcelas) AS parcelas, cvc.idcfc')
        ->retornar(true);

    $estados = $pessoaObj->retornarEstados();
    // $paises = $pessoaObj->buscarPaises();

    $categorias = array("A", "B", "C", "D", "E", "AB", "AC", "AD", "AE");

    if ($ofertaCurso['idoferta_curso']) {
        $_SESSION['loja']['idoferta'] = $ofertaCurso['idoferta'];
        $_SESSION['loja']['idoferta_curso'] = $ofertaCurso['idoferta_curso'];
        $_SESSION['loja']['idcurso'] = $ofertaCurso['idcurso'];
        $_SESSION['loja']['idoferta_curso_escola'] = $ofertaCurso['idoferta_curso_escola'];
        $_SESSION['loja']['idturma'] = $ofertaCurso['idturma'];
        $_SESSION['loja']['idsindicato'] = $_SESSION['dados_escola']['idsindicato'];
        $_SESSION['loja']['idescola'] = $ofertaCurso['idescola'];
        $_SESSION['loja_etapa'] = 'cadastro';

        // header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/cadastro.html');
        require_once 'idiomas/' . $config['idioma_padrao'] . '/matricula.php';
        require_once 'telas/' . $config['tela_padrao'] . '/matricula.php';
        exit;

    } else {
        $_SESSION['loja_erros']['curso_sem_oferta'] = 'curso_sem_oferta';

        unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

        header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);

        exit;
    }
} elseif ($_POST['acao'] == 'cadastrar' || $_POST['acao'] == 'cadastrar_logar') {
    require_once 'config.formulario.php';

    $_POST['nome'] = $_POST['nome'] . ' ' . $_POST['sobrenome'];
    $_POST['senha'] = str_replace(array('.', '-', '/'), '', $_POST['documento']);
    $_POST['confirma_senha'] = str_replace(array('.', '-', '/'), '', $_POST['documento']);

    $pessoaObj = new Pessoas;
    $pessoaObj->set('modulo', $url[0]);
    $pessoaObj->set('post', $_POST);
    $pessoaObj->set('nao_monitara',true);

    $config['formulario'] = $config['formulario_pessoas'];
    $pessoaObj->set('config',$config);
    $pessoa = $pessoaObj->Cadastrar();

    if ($pessoa['sucesso']) {
        $pessoaObj->set('id',$pessoa['id']);
        $pessoaObj->set('campos','p.*');
        $pessoa = $pessoaObj->retornar();

        require '../classes/emailsautomaticos.class.php';
        $emailAutomaticoObj = new Emails_Automaticos();
        $emailAutomatico = $emailAutomaticoObj->retornaEmailPorTipo('cadal');

        if(! empty($emailAutomatico)){
            $coreObj = new Core();

            if(! empty($pessoaEmail)){
                $emailAutomaticoObj->enviarEmailAutomaticoPessoa($emailAutomatico, $pessoa, $linhaObj, $coreObj);
            }
        }

        $_SESSION['cliente_email'] = $pessoa['email'];
        $_SESSION['cliente_celular'] = $pessoa['celular'];
        $_SESSION['cliente_senha'] = $pessoa['senha'];
        $_SESSION['cliente_idpessoa'] = $pessoa['idpessoa'];
        $_SESSION['cliente_nome'] = $pessoa['nome'];
        $_SESSION['cliente_ultimoacesso'] = $pessoa['ultimo_acesso'];
        $_SESSION['cliente_avatar_servidor'] = $pessoa['avatar_servidor'];

        if ($_POST['acao'] != 'cadastrar_logar') {
            $cursoObj = new Cursos();
            $curso = $cursoObj->set('idpessoa', $pessoa['idpessoa'])
                ->set('modulo', $url[0])
                ->set('id', $_SESSION['loja']['idcurso'])
                ->set('idcfc', $_SESSION['dados_escola']['idescola'])
                ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
                    COALESCE (svc.avista, cvc.avista) AS avista,
	                COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	                COALESCE (svc.parcelas, cvc.parcelas) AS parcelas')
                ->retornar(true);

            if ($_SESSION['dados_escola']['pagseguro'] == 'S' && $curso['avista'] > 0) {
                $_SESSION['loja']['criar_pedido'] = true;
                $_SESSION['loja_etapa'] = 'pagamento';
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/pagamento.html');
            } else {
                $_SESSION['loja_etapa'] = 'finalizar';
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/finalizar.html');
            }

            exit;
        } else {
            $_SESSION['loja_etapa'] = 'cursos';

            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);
            exit;
        }
    } else {
        $_POST["opLogin"] = 'atualizar_cadastro';
        $_POST['acao'] = 'criar_novo';

        require_once 'includes/login.php';
        exit;
    }
} elseif (($_POST['acao'] == 'modificar' || $_POST['acao'] == 'modificar_logar') && $_SESSION['cliente_idpessoa']) {
    require_once 'config.formulario.php';

    $_POST['nome'] = $_POST['nome'] . ' ' . $_POST['sobrenome'];

    $pessoaObj = new Pessoas;
    $pessoaObj->set('modulo', $url[0]);
    $pessoaObj->set('post', $_POST);
    $pessoaObj->set('nao_monitara',true);

    $estados = $pessoaObj->retornarEstados();
    $paises = $pessoaObj->buscarPaises();

    $config['formulario'] = $config['formulario_pessoas'];
    $pessoaObj->set('config',$config);
    $pessoa = $pessoaObj->modificarAlunoLoja($_SESSION['cliente_idpessoa']);

    if ($pessoa['sucesso']) {
        $pessoaObj->set('id',$pessoa['id']);
        $pessoaObj->set('campos', 'p.*');
        $pessoa = $pessoaObj->retornar();

        $_SESSION['cliente_email'] = $pessoa['email'];
        $_SESSION['cliente_celular'] = $pessoa['celular'];
        $_SESSION['cliente_senha'] = $pessoa['senha'];
        $_SESSION['cliente_idpessoa'] = $pessoa['idpessoa'];
        $_SESSION['cliente_nome'] = $pessoa['nome'];
        $_SESSION['cliente_ultimoacesso'] = $pessoa['ultimo_acesso'];
        $_SESSION['cliente_avatar_servidor'] = $pessoa['avatar_servidor'];

        if ($_POST['acao'] != 'modificar_logar') {
            $cursoObj = new Cursos();
            $curso = $cursoObj->set('idpessoa', $usuario['idpessoa'])
                ->set('modulo', $url[0])
                ->set('id', $_SESSION['loja']['idcurso'])
                ->set('idcfc', $_SESSION['dados_escola']['idescola'])
                ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
                    COALESCE (svc.avista, cvc.avista) AS avista,
	                COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	                COALESCE (svc.parcelas, cvc.parcelas) AS parcelas')
                ->retornar(true);

            if ($_SESSION['dados_escola']['pagseguro'] == 'S' && $curso['avista'] > 0) {
                $_SESSION['loja']['criar_pedido'] = true;
                $_SESSION['loja_etapa'] = 'pagamento';
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/pagamento.html');
            } else {
                $_SESSION['loja_etapa'] = 'finalizar';
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/finalizar.html');
            }

            exit;
        } else {
            $_SESSION['loja_etapa'] = 'cursos';

            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);
            exit;
        }
    } else {
        $erros = $pessoa['erros'];//Colocado na variável $erros, porque no login sobescreve $pessoa

        $_POST['opLogin'] = 'atualizar_cadastro';
        $_POST['acao'] == 'atualizar_dados';

        require_once 'includes/login.php';
        exit;
    }
} elseif ($_POST['acao'] == 'finalizar' && ($_SESSION['loja_etapa'] == 'finalizar' || $_SESSION['loja_etapa'] == 'pagamento')) {
    require_once 'includes/login.php';
    require_once '../classes/matriculas.class.php';

    require_once 'idiomas/' . $config['idioma_padrao'] . '/pagamento.php';

    if (! $_SESSION['loja']['idoferta_curso']) {
        $erros[] = 'curso_nao_selecionado';
    }

    if (! $_SESSION['cliente_idpessoa']) {
        $erros[] = 'pessoa_nao_selecionada';
    }

    $matriculaObj = new Matriculas();
    $matriculaObj->set('idpessoa', $usuario['idpessoa']);
    $matriculaObj->set('modulo', $url[0]);
    $matriculaObj->set('idescola', $_SESSION['dados_escola']['idescola']);
    $verificaMatriculado = $matriculaObj->verificaMatriculado(
        (int) $_SESSION['cliente_idpessoa'],
        (int) $_SESSION['loja']['idoferta'],
        (int) $_SESSION['loja']['idcurso'],
        (int) $_SESSION['dados_escola']['idescola']
    );

    if ($verificaMatriculado['total'] > 0) {
        $erros[] = 'aluno_matriculado';
    }

    if (count($erros) == 0) {
        $_SESSION['loja']['idpessoa'] = $_SESSION['cliente_idpessoa'];

        if ($_SESSION['dados_escola']['pagseguro'] == 'S') {
            $_SESSION['loja']['pagamento'] = $_POST;;
        }

        $matriculaObj->set('post', $_SESSION['loja']);
        $matricula = $matriculaObj->cadastrar($_SESSION['loja']['idpedido']);

        $_SESSION['loja']['idmatricula'] = $matricula['idmatricula'];
        $_SESSION['loja_etapa'] = 'concluida';

        if ($idioma[$matricula['mensagem']]) {
            echo '<script>
                    alert("' . $idioma[$matricula['mensagem']] . '");
                    location.href="' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/concluida.html";
                </script>';
            exit;
        }

        header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/concluida.html');
        exit;
    } else {
        $slug =  $_SESSION['dados_escola']['slug'];
        unset($_SESSION['loja'], $_SESSION['dados_escola'], $_SESSION['loja_passo']);

        $_SESSION['loja_erros'] = $erros;

        header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $slug);
        exit;
    }
}

if ($url[1] == 'json') {
    $pessoaObj = new Pessoas;

    switch ($url[2]) {
        case 'buscarAlunoPorEmail':
            $pessoaObj->set(
                'campos',
                'p.idpessoa,
                p.nome,
                p.documento,
                p.celular,
                p.data_nasc,
                p.cep,
                p.idestado,
                p.idcidade,
                p.bairro,
                p.endereco,
                p.numero,
                p.rg,
                p.sexo,
                p.cnh,
                p.categoria,
                p.ato_punitivo,
                p.rg_orgao_emissor,
                p.complemento'
            );
            $aluno = $pessoaObj->retornarPorEmailCpf($_REQUEST['email'], null);
            $aluno['estado'] = $pessoaObj->retornarNomeEstado($aluno['idestado']);
            $aluno['cidade'] = $pessoaObj->retornarNomeCidade($aluno['idcidade']);

            echo json_encode($aluno);
            exit;
        break;
        
        case 'retornarCidades':

            if ($_REQUEST['idestado']) {
                $cidades = $pessoaObj->set('campos', 'ci.idcidade, ci.nome, LOWER(es.sigla) AS uf')
                    ->set('ordem_campo', 'ci.nome')
                    ->set('ordem', 'ASC')
                    ->set('limite', -1)
                    ->set('agruparPor', 'ci.idcidade')
                    ->retornarCidades($_REQUEST['idestado']);
                echo json_encode($cidades);
            }
            exit;
        break;
        
        case 'retornarEstados':
            $pessoaObj->set('modulo', $url[0]);
            $pessoaObj->set('post', $_POST);
            $pessoaObj->set('nao_monitara',true);

            $estados = $pessoaObj->retornarEstados();
            echo json_encode($estados);
        break;

        case 'retornarPaises':
            $pessoaObj->set('modulo', $url[0]);
            $pessoaObj->set('post', $_POST);
            $pessoaObj->set('nao_monitara',true);

            $paises = $pessoaObj->buscarPaises();
            echo json_encode($paises);
        break;

        case 'matricular': // trecho adaptado a partir do oráculo lite (loja)

            $config['formulario'] = $config['formulario_pagamentocurso'];

            $pessoaObj = new Pessoas;
            $pessoaObj->set('modulo', $url[0]);
            $pessoaObj->set('post', $_POST);
            $pessoaObj->Set('nao_monitara',true);

            $pessoa['novoCadastro'] = false;

            if (empty($_POST['idpessoa'])) {

                $pessoa = $pessoaObj->Cadastrar(); 

                if (! empty($pessoa['erros'])) {
                    switch ($pessoa['erros'][0]) {
                        case 'cpf_utilizado':
                        $erroCadastroPessoa['erro'] = "Já existe um cadastro com o CPF informado!";
                        break;
                    }

                    echo json_encode($erroCadastroPessoa);
                    exit;
                }

                if ($pessoa['sucesso']) {
                    $_POST['idpessoa'] = $pessoaObj->id = $pessoa['id'];
                    require '../classes/emailsautomaticos.class.php';
                    $emailAutomaticoObj = new Emails_Automaticos();
                    $emailAutomatico = $emailAutomaticoObj->retornaEmailPorTipo('cadal');

                    if(! empty($emailAutomatico)){
                        $coreObj = new Core();

                        $pessoaObj->set('id', $pessoa["id"]);
                        $pessoaObj->set('campos', 'p.*');
                        $pessoaEmail = $pessoaObj->retornar();

                        if(! empty($pessoa)){
                            $emailAutomaticoObj->enviarEmailAutomaticoPessoa($emailAutomatico, $pessoaEmail, $pessoaObj, $coreObj);
                        }
                    }
                    $pessoa['novoCadastro'] = true;
                    $pessoa['novaSenha'] = $pessoaObj->gerarSenhaLoja();
                }
            } 
            else {
                $pessoa = $pessoaObj->Modificar();
            }

            $pessoa['nome'] = $_POST['nome'];
            $pessoa['email'] = $_POST['email'];

            $ofertaObj->set('idoferta_curso', $_SESSION['loja']['idoferta_curso']);
            $ofertaObj->set('ordem', 'ASC');
            $ofertaObj->set('limite', -1);
            $ofertaObj->set('ordem_campo', 'ocp.ordem, c.nome');
            $ofertaObj->set('campos', 'o.idoferta,
                oc.idoferta_curso,
                c.idcurso,
                ocp.idoferta_curso_escola,
                oc.possui_financeiro,
                c.nome,
                ocp.idescola,
                (
                    SELECT
                        idturma
                    FROM
                        ofertas_turmas
                    WHERE
                        idoferta = o.idoferta AND
                        ativo = "S" AND
                        ativo_painel = "S"
                    ORDER BY
                        RAND()
                    LIMIT 1
                ) AS idturma');
            $ofertaCurso = $ofertaObj->listarTodasCursosMatriculas();//Retorna a oferta curso selecionada
            $ofertaCurso = array_shift($ofertaCurso);

            $cursoObj = new Cursos();
            $curso = $cursoObj->set('idpessoa', $usuario['idpessoa'])
                ->set('modulo', $url[0])
                ->set('id', $_SESSION['loja']['idcurso'])
                ->set('idcfc', $_SESSION['loja']['idescola'])
                ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
                    COALESCE (svc.avista, cvc.avista) AS avista,
	                COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	                COALESCE (svc.parcelas, cvc.parcelas) AS parcelas, cvc.idcfc')
                ->retornar(true);

            $lojaPedidoObj = new Loja_Pedidos;
            $lojaPedidoObj->set('idpessoa', $pessoa['id']);
            $lojaPedidoObj->set('modulo', $url[0]);
           
            $matriculaExistente = $lojaPedidoObj->retornarMatriculaExistente(
                $pessoa['id'],
                $_SESSION['loja']['idescola'],
                $_SESSION['loja']['idoferta'],
                $_SESSION['loja']['idcurso']
            );

            if (!empty($matriculaExistente)) { // A matrícula deve ser apenas uma vez por curso (apenas 1 ativa p/ curso)
                $erroMatricula['erro'] = "Já existe uma matrícula deste aluno para o curso: " . $matriculaExistente['curso'];
                echo json_encode($erroMatricula);
                exit;
            }
            
            $vl_final = ($_POST['forma_pagamento'] == 'B' || $_POST['input_parcela'] == 1) ? $curso['avista'] : $curso['aprazo'];


            $pedido = $lojaPedidoObj->cadastrar(
                $pessoa['id'],
                $_SESSION['loja']['idoferta'],
                $_SESSION['loja']['idcurso'],
                $_SESSION['loja']['idescola'],
                $_SESSION['loja']['idturma'],
                $vl_final
            );
            
            $pedido = $lojaPedidoObj->set('id', $pedido)
                ->set('campos', 'valor_final, idpedido, idpessoa')
                ->retornar();
            
            $fastconnect = null;

            if ($ofertaCurso['possui_financeiro'] == 'S'){

                if (!empty($_POST['forma_pagamento'])) {

                    $estado = $pessoaObj->retornarEstado($_POST['idestado']);
                    $cidade = $pessoaObj->retornarCidade($_POST['idcidade']);
                    
                    $arrDados = [
                        'nu_referencia' => 'PED_'.$pedido['idpedido'],
                        'nu_parcelas' => $_POST['input_parcela'],
                        'dt_validade' => $_POST['expiry'],
                        'nm_titular' => $_POST['name'],
                        'nu_cartao' => $_POST['number'],
                        'nm_bandeira' => $_POST['brand'],
                        'vl_total' => $pedido['valor_final'],
                        'tipo_venda' => 'PB', // Parcelado banco
                        'ds_softdescriptor' => 'FASTCONNECT',
                        'dia_cobranca' => 01,
                        'ds_email' => $_POST['email'],
                        'nm_cliente' => $_POST['nome'],
                        'nu_documento' => $_POST['documento'],
                        'nu_telefone' => $_POST['celular'],
                        'ds_cep' => $_POST['cep'],
                        'ds_endereco' => $_POST['endereco'],
                        'ds_complemento' => $_POST['complemento'],
                        'ds_numero' => $_POST['numero'],
                        'ds_bairro' => $_POST['bairro'],
                        'nm_cidade' => $cidade['nome'],
                        'nm_estado' => $estado['sigla'],
                        'idescola' => $_SESSION['loja']['idescola']
                    ];

                    require_once '../classes/escolas.class.php';
                    $escolasObj = new Escolas();
                    $escolasObj->set('campos', 'p.fastconnect_client_key, p.fastconnect_client_code');
                    $escolasObj->set('id', $_SESSION['loja']['idescola']);
                    $escola = $escolasObj->retornar(false);

                    $fastconnectObj = new FastConnect($escola['fastconnect_client_code'], $escola['fastconnect_client_key']);

                    if ($fastconnectObj::FORMA_PAGAMENTO_CARTAO == $_POST['forma_pagamento']) { 
                        $fastconnect = $fastconnectObj->fazerTransacaoCartao($arrDados);
                    } 
                    else if ($fastconnectObj::FORMA_PAGAMENTO_BOLETO == $_POST['forma_pagamento']) {
                        $fastconnect = $fastconnectObj->gerarBoleto($arrDados);
                    }

                    // Caso haja algum problema no pagamento, a matrícula não é realizada
                    if ($fastconnect['success'] == false){
                        $retorno = ['fastconnect' => $fastconnect];
                        echo json_encode($retorno);
                        exit;
                    }
                }
            }
            $lojaPedidoObj->set('post', $_POST);
            $cadastrarMatricula = $lojaPedidoObj->cadastrarMatricula($pedido['idpedido']);

            if (!empty($fastconnect)) {
                $fastconnectObj = new FastConnect();

                $fastconnectObj->adicionaIdConta($fastconnect['idfastconnect'],$cadastrarMatricula['idconta']);
            }

            $_SESSION['confirmacao_pedido'] = array(
                'acao' => 'concluida',
                'matricula' => $cadastrarMatricula['idmatricula'],
                'pedido' => $pedido,
                'pessoa' => $pessoa,
                'fastconnect' => $fastconnect,
            );

            //$lojaPedidoObj->enviarNotificacao($_SESSION['confirmacao_pedido']);

            echo json_encode($_SESSION['confirmacao_pedido']);
            exit;
        break;
    }

    exit;
}


if (isset($url[4]) and $url[4] == 'pedido-realizado'){
    if ($_SESSION['confirmacao_pedido']['matricula']) {
        
        $cursoObj = new Cursos();
        $curso = $cursoObj->set('idpessoa', $usuario['idpessoa'])
            ->set('modulo', $url[0])
            ->set('id', $_SESSION['loja']['idcurso'])
            ->set('idcfc', $_SESSION['loja']['idescola'])
            ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
                COALESCE (svc.avista, cvc.avista) AS avista,
	            COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	            COALESCE (svc.parcelas, cvc.parcelas) AS parcelas')
            ->retornar(true);

        $idmatricula = $_SESSION['confirmacao_pedido']['matricula'];
        $pessoa = $_SESSION['confirmacao_pedido']['pessoa'];

        unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

        $_SESSION['loja_passo'] = '4';
        $_SESSION['loja_etapa_voltar'] = '2';

        require_once 'idiomas/' . $config['idioma_padrao'] . '/concluida.php';
        require_once 'telas/' . $config['tela_padrao'] . '/concluida.php';
        exit;
    } 
    else {
        unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

        header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);
        exit;
    }
}

switch ($_SESSION['loja_etapa']) {
    case 'cursos':
        if (! $url[1] || ! $url[2] || ! $url[3] || ! $escola) {
            unset($_SESSION['loja'], $_SESSION['loja_etapa'], $_SESSION['dados_escola'], $_SESSION['loja_passo']);
            header('Location: ' . $config['urlSiteLoja']);
            exit;
        }

        unset($_SESSION['loja'], $_SESSION['loja_passo']);
        $_SESSION['dados_escola'] = $escola;
        $_SESSION['loja_passo'] = '1';
        $_SESSION['loja_etapa_voltar'] = '1';

        //Colocado para caso passe o idoferta_curso pela URL já selecione o curso indo para o próximo passo
        if ((int) $url[4]) {
            $_POST['acao'] = 'matricular';
            $_POST['idoferta_curso'] = (int) $url[4];
            $informacoes['url'] = '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/';
            incluirLib('processar_post', $config, $informacoes);
            exit;
        }

        //Busca a quantidade de CFCs que tem nessa cidade e estado
        $qntCfcs = $ofertaObj->set('siglaUfVinculo', $url[1])
            ->set('idcidadeVinculo', $url[2])
            ->set('campos', 'COUNT(DISTINCT(e.idescola)) AS total')
            ->set('ordem_campo', 'e.nome_fantasia')
            ->set('ordem', 'ASC')
            ->set('limite', -1)
            ->set('naoAgrupar', true)
            ->listarTodasCursosMatriculas();

        $escolher_outro = 'escolher_outro';
        $etapa = 2;
        if ($qntCfcs[0]['total'] == 1) {
            $escolher_outro = 'escolher_outra_cidade';
            $etapa = 1;
        }

        $ofertaCursos = $ofertaObj->set('idescola', $_SESSION['dados_escola']['idescola'])
            ->set('campos', 'oc.idoferta_curso, o.nome AS oferta, c.nome AS curso, c.imagem_exibicao_servidor,
                COALESCE (svc.avista, cvc.avista) AS avista,
	            COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	            COALESCE (svc.parcelas, cvc.parcelas) AS parcelas')
            ->set('ordem_campo', 'ocp.ordem, c.nome')
            ->set('ordem', 'ASC')
            ->set('limite', -1)
            ->listarTodasCursosMatriculas();

        require_once 'idiomas/' . $config['idioma_padrao'] . '/cursos.php';
        require_once 'telas/' . $config['tela_padrao'] . '/cursos.php';
        break;
    case 'cadastro':
        if ($_SESSION['loja']['idoferta_curso']) {
            if ($url[3] != 'cadastro.html') {
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/cadastro.html');
                exit;
            }

            $cursoObj = new Cursos();
            $curso = $cursoObj->set('idpessoa', $usuario['idpessoa'])
                ->set('modulo', $url[0])
                ->set('id', $_SESSION['loja']['idcurso'])
                ->set('idcfc', $_SESSION['dados_escola']['idescola'])
                ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
                    COALESCE (svc.avista, cvc.avista) AS avista,
	                COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	                COALESCE (svc.parcelas, cvc.parcelas) AS parcelas')
                ->retornar(true);

            $_SESSION['loja_etapa_voltar'] = '2';

            if (
                ! empty($_SESSION['dados_escola']['detran_codigo'])
                && in_array($_SESSION['dados_escola']['idestado'], $GLOBALS['estadosDetran'])
            ) {
                // $mensagens[] = 'mensagem_detran';
            }

            require_once 'includes/login.php';

            $_SESSION['loja_passo'] = '2';

            if ($_SESSION['dados_escola']['pagseguro'] == 'S' && $curso['avista'] > 0) {
                $_SESSION['loja']['criar_pedido'] = true;

                $_SESSION['loja_etapa'] = 'pagamento';
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/pagamento.html');
            } else {
                $_SESSION['loja_etapa'] = 'finalizar';
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/finalizar.html');
            }

            exit;
        } else {
            unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);
            exit;
        }
        break;
    case 'pagamento':
        if ($_SESSION['loja']['idoferta_curso']) {
            //Se tiver o transactionCode é porque retornou do PagSeguro, assim já submete o formulário
            if (! empty($_GET['transactionCode'])) {
                $_POST['acao'] = 'finalizar';
                $_POST['tipo_pagamento'] = 'PS';
                $_POST['codigo_transacao_pagseguro'] = $_GET['transactionCode'];
                $informacoes['url'] = '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3];
                incluirLib('processar_post', $config, $informacoes);
                exit;
            }

            if ($url[3] != 'pagamento.html') {
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/pagamento.html');
                exit;
            }

            $_SESSION['loja_passo'] = '3';
            $_SESSION['loja_etapa_voltar'] = '2';

            if (
                ! empty($_SESSION['dados_escola']['detran_codigo'])
                && in_array($_SESSION['dados_escola']['idestado'], $GLOBALS['estadosDetran'])
            ) {
                // $mensagens[] = 'mensagem_detran';
            }

            require_once 'includes/login.php';

            $cursoObj = new Cursos();
            $curso = $cursoObj->set('idpessoa', $usuario['idpessoa'])
                ->set('modulo', $url[0])
                ->set('id', $_SESSION['loja']['idcurso'])
                ->set('idcfc', $_SESSION['dados_escola']['idescola'])
                ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
                    COALESCE (svc.avista, cvc.avista) AS avista,
	                COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	                COALESCE (svc.parcelas, cvc.parcelas) AS parcelas')
                ->retornar(true);

            if (! empty($_SESSION['loja']['criar_pedido'])) {
                $pedido = $lojaPedidoObj->cadastrar(
                    $_SESSION['cliente_idpessoa'],
                    $_SESSION['loja']['idoferta'],
                    $_SESSION['loja']['idcurso'],
                    $_SESSION['loja']['idescola'],
                    $_SESSION['loja']['idturma'],
                    $curso['avista']
                );

                if ($pedido['erro']) {
                    $slug =  $_SESSION['dados_escola']['slug'];
                    unset($_SESSION['loja'], $_SESSION['dados_escola'], $_SESSION['loja_passo']);

                    $_SESSION['loja_erros'] = $pedido['erros'];

                    header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $slug);
                    exit;
                }

                $_SESSION['loja']['idpedido'] = $pedido;
                unset($_SESSION['loja']['criar_pedido']);
            }

            $pessoaObj = new Pessoas;
            $pessoaObj->set('modulo', $url[0]);
            $pessoaObj->Set('id', $_SESSION['cliente_idpessoa']);
            $pessoaObj->Set('campos', 'p.nome, p.documento, p.email, CONCAT_WS(" ", l.nome, p.endereco) AS endereco,
                p.numero, p.complemento, p.bairro, cid.nome AS cidade, est.sigla AS uf,
                p.cep, p.celular, p.telefone, p.data_nasc');
            $pessoa = $pessoaObj->retornar();

            $pedido = $lojaPedidoObj->set('id', $_SESSION['loja']['idpedido'])
                ->set('campos', 'valor_final')
                ->retornar();

            $pagSeguroObj = new PagSeguro($_SESSION['loja']['idescola']);
            $pagSeguro = $pagSeguroObj->set('idpessoa', $_SESSION['cliente_idpessoa'])
                ->set('modulo', $url[0])
                ->set('pessoa', $pessoa)
                ->set('reference', 'PED_' . $_SESSION['loja']['idpedido'])
                ->set('idItem', 'CURSO_' . $curso['idcurso'])
                ->set('descricaoItem', $curso['nome'])
                ->set('valorItem', $pedido['valor_final'])
                ->criarCode();

            // $fastconnectObj = new FastConnect();
            // $fastconnect = $fastconnectObj->set('pessoa', $pessoa)
            //     // ->set('nm_cliente', $pessoa['nome'])
            //     // ->set('nu_documento', $pessoa['documento'])
            //     // ->set('ds_email', $pessoa['email'])
            //     // ->set('nu_telefone', $pessoa['telefone'])
            //     ->set('nu_referencia', 'PED_' . $_SESSION['loja']['idpedido'])
            //     ->set('vl_total', $pedido['valor_final'])
            //     ->fazerTransacao();

            require_once 'idiomas/' . $config['idioma_padrao'] . '/pagamento.php';
            require_once 'telas/' . $config['tela_padrao'] . '/pagamento.php';
        } else {
            unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);
            exit;
        }

        break;
    case 'finalizar':
        if ($_SESSION['loja']['idoferta_curso']) {
            require_once 'telas/' . $config['tela_padrao'] . '/finalizar.php';
            exit;
        } else {
            unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);
            exit;
        }
        break;
    case 'concluida':
        if ($_SESSION['loja']['idmatricula']) {
            if ($url[3] != 'concluida.html') {
                header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/concluida.html');
                exit;
            }

            require_once 'includes/login.php';

            $cursoObj = new Cursos();
            $curso = $cursoObj->set('idpessoa', $usuario['idpessoa'])
                ->set('modulo', $url[0])
                ->set('id', $_SESSION['loja']['idcurso'])
                ->set('idcfc', $_SESSION['dados_escola']['idescola'])
                ->set('campos', 'c.idcurso, c.nome, c.imagem_exibicao_servidor,
                    COALESCE (svc.avista, cvc.avista) AS avista,
	                COALESCE (svc.aprazo, cvc.aprazo) AS aprazo,
	                COALESCE (svc.parcelas, cvc.parcelas) AS parcelas')
                ->retornar(true);

            $idmatricula = $_SESSION['loja']['idmatricula'];

            unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

            $_SESSION['loja_passo'] = '4';
            $_SESSION['loja_etapa_voltar'] = '2';

            require_once 'idiomas/' . $config['idioma_padrao'] . '/concluida.php';
            require_once 'telas/' . $config['tela_padrao'] . '/concluida.php';
        } else {
            unset($_SESSION['loja_etapa'], $_SESSION['loja'], $_SESSION['loja_passo']);

            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $_SESSION['dados_escola']['slug']);
            exit;
        }
        break;
    default:
        $slug =  $_SESSION['dados_escola']['slug'];
        unset($_SESSION['loja'], $_SESSION['dados_escola'], $_SESSION['loja_passo']);

        if ($slug) {
            header('Location: ' . $protocolo . '://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $slug);
            exit;
        }
        header('Location: ' . $config['urlSiteLoja']);
        break;
}
