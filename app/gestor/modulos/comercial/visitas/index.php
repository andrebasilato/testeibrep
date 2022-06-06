<?php
require '../classes/visitasvendedores.class.php';
require 'config.php';
require 'config.formulario.php';
require 'config.listagem.php';
require 'idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php';

$linhaObj = new VisitasVendedores();
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');

$urlBase = sprintf(
    '/%s/%s/%s',
    $url[0],
    $url[1],
    $url[2]
);

$urlComplement = '';

$linhaObj->Set('idusuario', $usuario['idusuario'])
    ->Set(
        'monitora_onde',
        $config['monitoramento']['onde']
    );

if ('salvar' == $_POST['acao']) {

    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
    $linhaObj->Set('post', $_POST);

    if($_POST[$config['banco']['primaria']]) {
		/*unset($config["formulario_editar"][0]["campos"][12]);
		unset($config["formulario_editar"][0]["campos"][13]);*/
        $linhaObj->config['formulario'] = $config['formulario_editar'];
        $action = 'Modificar';
    } else {
        $linhaObj->config['banco'] = $config['banco_pessoa'];
        $action = 'Cadastrar';
    }

    $salvar = $linhaObj->$action();

    if ($salvar['sucesso']) {

        $message = 'cadastrar_sucesso';

        if ($_POST[$config['banco']['primaria']]) {
            $message = 'modificar_sucesso';
            $urlComplement = "/{$url[3]}/{$url[4]}";
        }

        $linhaObj->Set('pro_mensagem_idioma', $message)
            ->Set('url', $urlBase . $urlComplement)
            ->Processando();
    }
}

if($_POST["acao"] == "salvar_mensagem") {
    if($_POST["mensagem"]) {
        $linhaObj->Set("post",$_POST);
        $linhaObj->Set('id',$url[3]);
        $salvar = $linhaObj->adicionarMensagem();
        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","mensagem_adicionada_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
            $linhaObj->Processando();
        } else {  
            $mensagem["erro"] = $salvar["mensagem"];
        }     
    } else {
        $salvar["sucesso"] = false;
        $salvar["erros"][] = "mensagem_vazia";
    }
} 

if($_POST["acao"] == "remover_mensagem") {
    if($_POST["idmensagem"]) {
        $linhaObj->Set('id',$url[3]);
        $remover = $linhaObj->removerMensagem((int) $_POST["idmensagem"]);
        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
            $linhaObj->Processando();
        } else {  
            $mensagem["erro"] = $remover["mensagem"];
        }
    } else {
        $mensagem["erro"] = "mensagem_remover_vazio";
    }
} elseif($_POST["acao"] == "adicionar_iteracao"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
	
	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("post",$_POST);
	$salvar = $linhaObj->adicionarIteracao();
	
	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visitas");
		$linhaObj->Processando();
	}	
} elseif($_POST["acao"] == "remover_iteracao"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
	
	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->RemoverIteracao();
	
	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visitas");
		$linhaObj->Processando();
	}
}

if ('remover' == $_POST['acao']){
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2]."|3");

    $remover = $linhaObj->Set('post', $_POST)
        ->Remover();

    if($remover['sucesso']){
        $linhaObj->Set('pro_mensagem_idioma', 'remover_sucesso')
            ->Set('url', $urlBase)
            ->Processando();
    }
}

if(isset($url[3])){

    if ('cadastrar' == $url[3]) {

        if ('json' == $url[4]) {
            include 'idiomas/'.$config['idioma_padrao'].'/json.php';
            include 'telas/'.$config['tela_padrao'].'/json.php';
            exit;
        }

        $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
        include 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
        include 'telas/'.$config['tela_padrao'].'/formulario.php';
        exit;
    } else {

        $linhaObj->Set(
            'id',  (int) $url[3]
        )->Set(
            'campos',

            'vv.*,
            pe.idpessoa,
            pe.nome as nome_pessoa,
            pe.documento as documento_pessoa,
            pe.data_nasc as data_nasc_pessoa,
            pe.email as email_pessoa,
            pe.telefone as telefone_pessoa,
            c.nome as cidade,
            e.nome as estado,
            v.nome as vendedor'
        );

        $linha = $linhaObj->Retornar();

        if ($linha['documento_pessoa']) {
            $linha['documento'] = $linha['documento_pessoa'];
        }

        if ($linha['nome_pessoa']) {
            $linha['nome'] = $linha['nome_pessoa'];
        }

        if ($linha['email_pessoa']) {
            $linha['email'] = $linha['email_pessoa'];
        }

        if ($linha['telefone_pessoa']) {
            $linha['telefone'] = $linha['telefone_pessoa'];
        }

        if ($linha['data_nasc_pessoa']) {
            $linha['data_nasc'] = $linha['data_nasc_pessoa'];
        }

        if ($linha) {

            switch ($url[4]) {
                case 'editar':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
                    $cursos_associados = $linhaObj->retornarCursosVisita($url[3]);
                    include 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
                    include 'telas/'.$config['tela_padrao'].'/formulario.php';
                    break;
                case 'mensagens':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|4');
                    $linhaObj->Set("campos","vm.*,
                            ua.nome as usuario,
                            ua.idusuario,
                            v.nome as vendedor,
                            v.idvendedor");
                    $linhaObj->Set('ordem','desc');
                    $linhaObj->Set('groupby','vm.idmensagem');
                    $linhaObj->Set('ordem_campo','vm.idmensagem');
                    $linhaObj->Set('limite',-1);
                    $mensagensVisita = $linhaObj->retornarMensagensVisita();
                    include 'idiomas/'.$config['idioma_padrao'].'/mensagens.php';
                    include 'telas/'.$config['tela_padrao'].'/mensagens.php';
                    break;
                case 'remover':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|3');
                    include 'idiomas/'.$config['idioma_padrao'].'/remover.php';
                    include 'telas/'.$config['tela_padrao'].'/remover.php';
                    break;
                case 'geolocalizacao':
                    include 'idiomas/'.$config['idioma_padrao'].'/geolocalizacao.php';
                    include 'telas/'.$config['tela_padrao'].'/geolocalizacao.php';
                    break;
                case 'opcoes':
                    include 'idiomas/'.$config['idioma_padrao'].'/opcoes.php';
                    include 'telas/'.$config['tela_padrao'].'/opcoes.php';
                    break;
				case "visitas":			
					$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
					$linhaObj->Set("id",intval($url[3]));
					$linhaObj->Set("ordem","asc");
					$linhaObj->Set("limite",-1);
					$linhaObj->Set("ordem_campo","vvi.numero");
					$linhaObj->Set("campos","vvi.*");
					$associacoesArray = $linhaObj->ListarIteracoes();
					include("idiomas/".$config["idioma_padrao"]."/visitas.php");
					include("telas/".$config["tela_padrao"]."/visitas.php");
					break;
                case 'json':
                    include 'idiomas/'.$config['idioma_padrao'].'/json.php';
                    include 'telas/'.$config['tela_padrao'].'/json.php';
                break;
                default:
                   header('Location: /'.$urlBase);
                   exit;
            }

        } else {
           header('Location: /'. $urlBase);
           exit;
        }
    }

} else {

    if (! $_GET['ordem'])
        $_GET['ordem'] = 'desc';

    if (! $_GET['qtd'])
        $_GET['qtd'] = 30;

    if (! $_GET['cmp'])
        $_GET['cmp'] = $config['banco']['primaria'];

    $dadosArray = $linhaObj->Set('pagina', $_GET['pag'])
        ->Set('ordem', $_GET['ord'])
        ->Set('limite', (int) $_GET['qtd'])
        ->Set('ordem_campo', $_GET['cmp'])
        ->Set('campos',
            'vv.*,
            e.nome as estado,
            c.nome as cidade,
            pe.idpessoa,
            pe.nome as nome_pessoa,
            pe.documento as documento_pessoa,
            pe.data_nasc as data_nasc_pessoa,
            pe.email as email_pessoa,
            pe.telefone as telefone_pessoa,
            v.nome as vendedor,
            mid_v.nome as midia'
        )->ListarTodas();

    include 'idiomas/'.$config['idioma_padrao'].'/index.php';
    include 'telas/'.$config['tela_padrao'].'/index.php';
}