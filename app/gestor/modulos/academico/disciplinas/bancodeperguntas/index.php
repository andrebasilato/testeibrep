<?php
require '../classes/perguntas.class.php';
require 'config.php';
require 'config.formulario.php';
require 'config.listagem.php';
require 'idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php';

$linhaObj = new Perguntas;
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');
$podeEditar = ['ativo_painel', 'nome', 'critica'];

if ('salvarpergunta' == $_POST['acao']) {
    
    $linhaObj->verificaPermissao(
    	$perfil['permissoes'], 
    	$url[2] . '|2'
    );

    if($_FILES) {
        foreach($_FILES as $ind => $val) {
          $_POST[$ind] = $val;
        }
    }
   
    if (! $_POST['iddisciplina']) {
        $_POST['iddisciplina'] = $url[3];
    }
    
    $linhaObj->Set('post', $_POST);

    if ($_POST[$config['banco']['primaria']]) {
        $linhaObj->Set('id', $_POST[$config['banco']['primaria']]);
        $possueProva = $linhaObj->verificaUsoEmProva();
        if ($possueProva) {
            foreach($config["formulario"] as $indfildeset => $fildset){
                foreach($fildset["campos"] as $indcampo => $campo){
                    if (! in_array($campo['nome'], $podeEditar)) {
                        $campos_remover[$campo['nome']] = $config["formulario"][$indfildeset]["campos"][$indcampo]['nome'];
                    }
                }
            }
            $config["formulario"] = $linhaObj->alterarConfigFormulario($config["formulario"], $campos_remover);
        }
        $linhaObj->Set("config",$config);
        $salvar = $linhaObj->Modificar();
    } else {
        $salvar = $linhaObj->Cadastrar();
    }

    if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->Set('pro_mensagem_idioma', 'modificar_sucesso');
            $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[5] . '/cadastrar');
        } else {
            $linhaObj->Set('pro_mensagem_idioma', 'cadastrar_sucesso');
            $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/cadastrar');
        }
        
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "removerpergunta") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->Remover();
    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[5]."/cadastrar");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "cadastrar_opcao") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set('id', (int) $url[3]);
    $linhaObj->Set('post', $_POST);
    $salvar = $linhaObj->CadastrarOpcao();

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "cadastrar_opcao_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] ."/" . $url[5]);
        $linhaObj->Processando();
    }
} elseif ('remover_opcao' == $_POST['acao']) {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->removerOpcao();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_opcao_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] ."/" . $url[5]);
        $linhaObj->Processando();
    }
} elseif ('editar_opcoes' == $_POST['acao']) {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("id", (int) $url[3]);
    $linhaObj->Set("post", $_POST);
    $editar = $linhaObj->ModificarOpcoes();

    if ($editar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "editar_opcao_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] ."/" . $url[5]);
        $linhaObj->Processando();
    }
}//Adicionando função de associar disciplina------------------------
elseif ($_POST["acao"] == "associar_disciplinas") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $salvar = $linhaObj->AssociarDisciplinas(intval($url[3]), $_POST["disciplinas"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_associacao_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/disciplinas");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_disciplina") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->RemoverDisciplinas();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_associacao_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/disciplinas");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "clonar_perguntas") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");
    $linhaObj->Set("post", $_POST);
    $linhaObj->Set("idusuario", $usuario['idusuario']);
    $clonar = $linhaObj->salvarPerguntasClonar($url[3]);

    if ($clonar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "clonar_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] ."/" . $url[5]);
        $linhaObj->Processando();
    }
}

if (isset($url[4])) {

    if ('novapergunta' == $url[4]) {
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit;
    }
    if ('cadastrar' == $url[4]) {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

        $disciplinasClonar = $linhaObj->listarDisciplinasClonar($url[3]);

        if(!$_GET["cmp"]) $_GET["cmp"] = "idpergunta";
		$linhaObj->Set('ordem_campo', $_GET['cmp']);
		
		$linhaObj->Set("pagina",$_GET["pag"]);
		if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
		$linhaObj->Set("ordem",$_GET["ord"]);
		if(!$_GET["qtd"]) $_GET["qtd"] = 30;
		$linhaObj->Set("limite",intval($_GET["qtd"]));
		$linhaObj->Set("campos","*");	
		
        
        if ($linhaObj->verificaPermissao(
        		$perfil['permissoes'], 
        		$url[2]. "|7")
        ){
            $dadosArray = $linhaObj->listarPerguntasDaSecao($url[3]);
        }

        include("idiomas/" . $config["idioma_padrao"] . "/index.php");
        include("telas/" . $config["tela_padrao"] . "/index.php");

        exit;
    } else {

        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("campos", "*");
        $linha = $linhaObj->Retornar();

        if ($linha) {
            $possueProva = $linhaObj->verificaUsoEmProva();
            switch ($url[4]) {
                case 'editarpergunta':
                case 'editar':
                    //print_r2($config["formulario"],true);
                    if ($possueProva) {
                        foreach($config["formulario"] as $indfildeset => $fildset){
                          foreach($fildset["campos"] as $indcampo => $campo){
                              if (! in_array($campo['nome'], $podeEditar)) {//Condição para deixar os campos da ativo painel habilitado
                                  $config["formulario"][$indfildeset]["campos"][$indcampo]["evento"] = "disabled";
                              }
                            }
                        }
                    }
                    $linhaObj->Set("config",$config);
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');
                    include 'idiomas/' . $config['idioma_padrao'] . '/formulario.php';
                    include 'telas/' . $config['tela_padrao'] . '/formulario.php';
                    break;
                case 'removerpergunta':
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                    include("idiomas/" . $config["idioma_padrao"] . "/remover.php");
                    include("telas/" . $config["tela_padrao"] . "/remover.php");
                    break;
                case 'opcoes':
                    include 'idiomas/' . $config['idioma_padrao'] . '/opcoes.php';
                    include 'telas/' . $config['tela_padrao'] . '/opcoes.php';
                    exit;
                    break;
                case 'perguntaopcoes':
                    if ('O' == $linha['tipo']) {
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                        $linhaObj->Set("limite", "-1");
                        $linhaObj->Set("ordem", "asc");
                        $linhaObj->Set("ordem_campo", "ordem");
                        $linhaObj->Set("campos", "*");
                        $opcoes = $linhaObj->ListarTodasOpcoes();
                        include("idiomas/" . $config["idioma_padrao"] . "/formulario.opcoes.php");
                        include("telas/" . $config["tela_padrao"] . "/formulario.opcoes.php");
                    } else {
                        //header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
                        exit();
                    }
                    break;
                case "disciplinas"://Adicionando opções de associação da pergunta
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");

                    $linhaObj->Set('id', (int) $url[3]);
                    $linhaObj->Set('ordem', 'ASC');
                    $linhaObj->Set('limite', -1);
                    $linhaObj->Set('ordem_campo', 'nome');
                    $linhaObj->Set('campos', 'dp.iddisciplina_pergunta, dp.iddisciplina, d.iddisciplina, d.nome');
                    
                    $disciplinas = $linhaObj->ListarDisciplinasAssociadas();

                    include("idiomas/" . $config["idioma_padrao"] . "/disciplinas.php");
                    include("telas/" . $config["tela_padrao"] . "/disciplinas.php");
                    break;
                case 'json':
                    include 'idiomas/' . $config['idioma_padrao'] . '/json.php';
                    include 'telas/' . $config['tela_padrao'] . '/json.php';
                    break;
                default:
                    header("Location: /{$url[0]}/{$url[1]}/{$url[2]}");
                    exit();
            }
        } else {
            header("Location: /{$url[0]}/{$url[1]}/{$url[2]}");
            exit();
        }
    }
} else {
    
    $linhaObj->Set(
    	'pagina', 
    	$_GET['pag']
    );

    if (!$_GET['ordem']) {
        $_GET['ordem'] = 'DESC';
    }
    
    $linhaObj->Set('ordem', $_GET['ord']);
    
    if (!$_GET['qtd']) {
        $_GET['qtd'] = 30;
    }
    
    $linhaObj->Set('limite', (int) $_GET['qtd']);
    
    if (!$_GET['cmp']) {
        $_GET['cmp'] = $config['banco']['primaria'];
    }

    $linhaObj->Set('ordem_campo', $_GET['cmp']);
    $linhaObj->Set('campos', '*');
    
    $dadosArray = $linhaObj->ListarTodas();

    include 'idiomas/' . $config['idioma_padrao'] . '/index.php';
    include 'telas/' . $config['tela_padrao'] . '/index.php';
}