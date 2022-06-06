<?php
$config['formulario_rotas_aprendizagem'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
        'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
        'campos' => array( // Campos do formulario
            array(
                'id' => 'form_porcentagem_rota',
                'nome' => 'porcentagem_rota',
                'nomeidioma' => 'form_porcentagem_rota',
                'tipo' => 'input',
                'valor' => 'porcentagem_rota',
                'validacao' => array('required' => 'porcentagem_rota_vazio'),
                'decimal' => true,
                'evento' => 'maxlength="5"',
                'class' => 'span2',
                'banco' => true,
                'banco_string' => true,
            ),
            array(
                'id' => 'form_form_porcentagem_chat',
                'nome' => 'porcentagem_chat',
                'nomeidioma' => 'form_form_porcentagem_chat',
                'tipo' => 'input',
                'valor' => 'porcentagem_chat',
                'validacao' => array('required' => 'porcentagem_chat_vazio'),
                'decimal' => true,
                'evento' => 'maxlength="5"',
                'class' => 'span2',
                'banco' => true,
                'banco_string' => true,
            ),
            array(
                'id' => 'form_porcentagem_forum',
                'nome' => 'porcentagem_forum',
                'nomeidioma' => 'form_porcentagem_forum',
                'tipo' => 'input',
                'valor' => 'porcentagem_forum',
                'validacao' => array('required' => 'porcentagem_forum_vazio'),
                'decimal' => true,
                'class' => 'span2',
                'banco' => true,
                'banco_string' => true,
            ),
            array(
                'id' => 'form_porcentagem_tira_duvida',
                'nome' => 'porcentagem_tira_duvida',
                'nomeidioma' => 'form_porcentagem_tira_duvida',
                'tipo' => 'input',
                'valor' => 'porcentagem_tira_duvida',
                'validacao' => array('required' => 'porcentagem_tira_duvida_vazio'),
                'decimal' => true,
                'evento' => 'maxlength="5"',
                'class' => 'span2',
                'banco' => true,
                'banco_string' => true,
            ),
            array(
                'id' => 'form_porcentagem_biblioteca',
                'nome' => 'porcentagem_biblioteca',
                'nomeidioma' => 'form_porcentagem_biblioteca',
                'tipo' => 'input',
                'valor' => 'porcentagem_biblioteca',
                'validacao' => array('required' => 'porcentagem_biblioteca_vazio'),
                'decimal' => true,
                'evento' => 'maxlength="5"',
                'class' => 'span2',
                'banco' => true,
                'banco_string' => true,
            ),
            array(
                'id' => 'form_porcentagem_simulado',
                'nome' => 'porcentagem_simulado',
                'nomeidioma' => 'form_porcentagem_simulado',
                'tipo' => 'input',
                'valor' => 'porcentagem_simulado',
                'validacao' => array('required' => 'porcentagem_simulado_vazio'),
                'decimal' => true,
                'evento' => 'maxlength="5"',
                'class' => 'span2',
                'banco' => true,
                'banco_string' => true,
            ),
        )
    )
);

$linhaObj->Set("config",$config);
include("../classes/avas.rotasdeaprendizagem.class.php");

$linhaObj = new Rotas_Aprendizagem();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_rotas_aprendizagem"]);
$linhaObj->Set("idava",intval($url[3]));

$linhaObj->config["banco"] = $config["banco_rotas_aprendizagem"];
$linhaObj->config["formulario"] = $config["formulario_rotas_aprendizagem"];

if($_POST["acao"] == "salvar_rota_aprendizagem"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");

    $linhaObjAva = new Ava();
    $linhaObjAva->Set("idusuario",$usuario["idusuario"]);
    $linhaObjAva->Set("monitora_onde",$config["monitoramento"]["onde"]);

    $linhaObjAva->config["formulario"] = $config["formulario_rotas_aprendizagem"];

    $_POST[$config["banco"]["primaria"]] = $url[3];

    $linhaObjAva->Set("post",$_POST);
    $salvar = $linhaObjAva->Modificar();

    if($salvar["sucesso"]){
        $linhaObjAva->Set("pro_mensagem_idioma","modificar_sucesso");
        $linhaObjAva->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObjAva->Processando();
    }
} elseif($_POST["acao"] == "cadastrar_objeto"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    $linhaObj->Set("id",intval($url[5]));
    $linhaObj->Set("post",$_POST);
    $salvar = $linhaObj->CadastrarObjetos();

    if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","cadastrar_objeto_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
        $linhaObj->Processando();
    }
} elseif($_POST["acao"] == "remover_objeto"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->RemoverObjeto();

    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_objeto_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
        $linhaObj->Processando();
    }
} elseif($_POST["acao"] == "editar_objeto"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    $linhaObj->Set("id",intval($url[5]));
    $linhaObj->Set("post",$_POST);
    $editar = $linhaObj->ModificarObjetos();

    if($editar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","editar_objeto_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
        $linhaObj->Processando();
    }
}

if(isset($url[5])){
    $linhaObj->Set("id",intval($url[5]));
    $linhaObj->Set("campos","r.*, a.nome as ava");
    $linha = $linhaObj->RetornarRotaAprendizagem();

    if($linha) {
        switch($url[6]) {
            case "editar":
                $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
                include("idiomas/".$config["idioma_padrao"]."/formulario.rotasdeaprendizagem.php");
                include("telas/".$config["tela_padrao"]."/formulario.rotasdeaprendizagem.php");
                break;
            case "opcoes":
                include("idiomas/".$config["idioma_padrao"]."/opcoes.rotasdeaprendizagem.php");
                include("telas/".$config["tela_padrao"]."/opcoes.rotasdeaprendizagem.php");
                break;
            case "objetos":
                $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

                require '../classes/dataaccess/db.php';
                require '../classes/dataaccess/mysql.php';

                include("../classes/avas.audios.class.php");
                $linhaObjAudio = new Audios();
                $linhaObjAudio->Set("idava",intval($url[3]));
                $linhaObjAudio->Set("limite",-1);
                $linhaObjAudio->Set("ordem_campo","aa.nome");
                $linhaObjAudio->Set("ordem","asc");
                $linhaObjAudio->Set("campos","aa.*, a.nome as ava");
                $_GET['q']['1|aa.exibir_ava'] = 'S';
                $audios = $linhaObjAudio->ListarTodasAudio();
                unset($_GET['q']['1|aa.exibir_ava']);

                include("../classes/avas.conteudos.class.php");
                $linhaObjConteudo = new Conteudos();
                $linhaObjConteudo->Set("idava",intval($url[3]));
                $linhaObjConteudo->Set("limite",-1);
                $linhaObjConteudo->Set("ordem_campo","c.nome");
                $linhaObjConteudo->Set("ordem","asc");
                $linhaObjConteudo->Set("campos","c.*, a.nome as ava");
                $_GET['q']['1|c.exibir_ava'] = 'S';
                $conteudos = $linhaObjConteudo->ListarTodasConteudo();
                unset($_GET['q']['1|c.exibir_ava']);

                include("../classes/avas.objetosdivisores.class.php");
                $linhaObjObjetoDivisor = new ObjetosDivisores();
                $linhaObjObjetoDivisor->Set("idava",(int)$url[3]);
                $linhaObjObjetoDivisor->Set("limite",-1);
                $linhaObjObjetoDivisor->Set("ordem_campo","od.nome");
                $linhaObjObjetoDivisor->Set("ordem","asc");
                $linhaObjObjetoDivisor->Set("campos","od.*, a.nome as ava");
                $_GET['q']['1|od.exibir_ava'] = 'S';
                $objetosdivisores = $linhaObjObjetoDivisor->ListarTodasObjetosDivisores();
                unset($_GET['q']['1|od.exibir_ava']);

                include("../classes/avas.downloads.class.php");
                $linhaObjDownload = new Downloads();
                $linhaObjDownload->Set("idava",intval($url[3]));
                $linhaObjDownload->Set("limite",-1);
                $linhaObjDownload->Set("ordem_campo","d.nome");
                $linhaObjDownload->Set("ordem","asc");
                $linhaObjDownload->Set("campos","d.*, a.nome as ava");
                $_GET['q']['1|d.exibir_ava'] = 'S';
                $downloads = $linhaObjDownload->ListarTodasDownload();
                unset($_GET['q']['1|d.exibir_ava']);

                include("../classes/avas.links.class.php");
                $linhaObjLink = new Links();
                $linhaObjLink->Set("idava",intval($url[3]));
                $linhaObjLink->Set("limite",-1);
                $linhaObjLink->Set("ordem_campo","l.nome");
                $linhaObjLink->Set("ordem","asc");
                $linhaObjLink->Set("campos","l.*, a.nome as ava");
                $_GET['q']['1|l.exibir_ava'] = 'S';
                $links = $linhaObjLink->ListarTodasLink();
                unset($_GET['q']['1|l.exibir_ava']);

                include("../classes/avas.perguntas.class.php");
                $linhaObjPergunta = new Perguntas();
                $linhaObjPergunta->Set("idava",intval($url[3]));
                $linhaObjPergunta->Set("limite",-1);
                $linhaObjPergunta->Set("ordem_campo","p.nome");
                $linhaObjPergunta->Set("ordem","asc");
                $linhaObjPergunta->Set("campos","p.*, a.nome as ava");
                $_GET['q']['1|p.exibir_ava'] = 'S';
                $perguntas = $linhaObjPergunta->ListarTodasPergunta();
                unset($_GET['q']['1|p.exibir_ava']);

                include("../classes/avas.enquetes.class.php");
                $linhaObjEnquete = new Enquetes();
                $linhaObjEnquete->Set("idava",intval($url[3]));
                $linhaObjEnquete->Set("limite",-1);
                $linhaObjEnquete->Set("ordem_campo","e.pergunta");
                $linhaObjEnquete->Set("ordem","asc");
                $linhaObjEnquete->Set("campos","e.*, a.nome as ava");
                $_GET['q']['1|e.exibir_ava'] = 'S';
                $enquetes = $linhaObjEnquete->ListarTodosEnquetes();
                unset($_GET['q']['1|e.exibir_ava']);

                include("../classes/avas.videos.class.php");
                $linhaObjVideo = new Videos(new Core, new Zend_Db_Select(new Zend_Db_MySql()));
                $linhaObjVideo->Set("idava",intval($url[3]));
                $linhaObjVideo->Set("limite",-1);
                $linhaObjVideo->Set("ordem_campo","v.idvideoteca");
                $linhaObjVideo->Set("ordem","asc");
                $linhaObjVideo->Set("campos","v.*, a.nome as ava");
                $videos = $linhaObjVideo->ListarTodasVideo();

                include("../classes/avas.exercicios.class.php");
                $linhaObjExercicio = new Exercicios();
                $linhaObjExercicio->Set("idava",intval($url[3]));
                $linhaObjExercicio->Set("limite",-1);
                $linhaObjExercicio->Set("ordem_campo","ae.nome");
                $linhaObjExercicio->Set("ordem","asc");
                $linhaObjExercicio->Set("campos","ae.*, a.nome as ava");
                $_GET['q']['1|ae.exibir_ava'] = 'S';
                $exercicios = $linhaObjExercicio->ListarTodasExercicio();
                unset($_GET['q']['1|ae.exibir_ava']);

                include("../classes/aulasonline.class.php");
                $linhaObjAulasOnLine = new AulasOnLine();
                $linhaObjAulasOnLine->Set("idava",intval($url[3]));
                $linhaObjAulasOnLine->Set("limite","-1");
                $linhaObjAulasOnLine->Set("ordem","asc");
                $linhaObjAulasOnLine->Set("ordem_campo","ao.nome");
                $linhaObjAulasOnLine->Set("campos","ao.*, aao.idavas_aulas_online, aao.idava, d.nome as disciplina");
                $aulasonline = $linhaObjAulasOnLine->listarTodasAulasOnLine();
                unset($_GET['q']['1|ae.exibir_ava']);

                $linhaObj->Set("limite","-1");
                $linhaObj->Set("ordem","asc");
                $linhaObj->Set("ordem_campo","arab.ordem asc, arab.data_cad");
                $linhaObj->Set("campos","arab.*,aa.nome as nome_audio, 
            ac.nome as nome_conteudo, ad.nome as nome_download, 
            al.nome as nome_link, ap.nome as nome_pergunta, 
            m.titulo as nome_video, m.*, asi.nome as nome_simulado, 
            ae.pergunta as nome_enquete, od.nome as nome_objeto_divisor, aex.nome as nome_exercicio, ao.nome as nome_aulaonline");
                $objetos = $linhaObj->ListarTodasObjetos();

                include("idiomas/".$config["idioma_padrao"]."/formulario.rotasdeaprendizagem.objetos.php");
                include("telas/".$config["tela_padrao"]."/formulario.rotasdeaprendizagem.objetos.php");
                break;
            default:
                header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
                exit();
        }
    } else {
        header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        exit();
    }
} else {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");

    $linhaObj->Set("campos","r.*, a.nome as ava, a.porcentagem_rota, a.porcentagem_chat, a.porcentagem_forum, a.porcentagem_tira_duvida, a.porcentagem_biblioteca, a.porcentagem_simulado");
    $linha = $linhaObj->RetornarRotaAprendizagemAva();

    include("idiomas/".$config["idioma_padrao"]."/formulario.rotasdeaprendizagem.php");
    include("telas/".$config["tela_padrao"]."/formulario.rotasdeaprendizagem.php");
}
?>