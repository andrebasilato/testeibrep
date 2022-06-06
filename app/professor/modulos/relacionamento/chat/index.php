<?php 
require 'config.php';
require 'config.listagem.php';
require 'config.formulario.php';

require 'idiomas/pt_br/index.php';
require 'idiomas/pt_br/config.php';
require 'idiomas/pt_br/formulario.php';
require 'idiomas/pt_br/idiomapadrao.php';

$POG = $_GET['q'];
unset($_GET['q']);
$teacher = new Professores;
$myAvas = $teacher->set('id', $usu_professor['idprofessor'])
    ->set('ordem', 'desc')
    ->set('limite', '-1')
    ->set('ordem_campo', 'a.idava')
    ->set('campos', 'a.idava')
    ->ListarAvasAss();

$avasList = array();
foreach($myAvas as $ava) {
    $avasList[] = $ava['idava'];
}

$_GET['q'] = $POG;
$chat = new Chat(new Core);

try {

    $chat->setId( $usu_professor['idprofessor'] )
        ->setTypePerson(1);

    $dados = array(
        'url' => $url,
        'config' => $config,
        'idioma' => $idioma,
        'linhaObj' => $chat,
        'usu_professor' => $usu_professor
    );

    if ('novamensagem' == Request::post( 'acao' )) {
        $chat->setChatId( Request::url(4) )->registerNewMessage();
    }
	
    // Atualiza conversas do chat
    if ('atualizarChat' == Request::url(5)) {
        $messages = array();

        $chat->setChatId( Request::url(4) )->getMoreThen( Request::post('lastId') );
        $messages = $chat->getResult();
        exit( json_encode($messages));
    }

    if ( Request::get('chat(\?)?$') ) {
        $chat->allConversation( $avasList )
            ->renderTo( dirname(__FILE__).'/telas/desktop/index.php', $dados );
    }

    if ($resource = Request::get('chat/(\d+)')) {
		
			$info = $chat->infoAbout( $resource[1] );
	
			$chat->setChatId( $resource[1] )
				->allMessages()
				->renderTo(
					dirname(__FILE__).'/telas/desktop/chat.php',
					$dados + array('info' => $info)
				);
    }


} catch (NotFoundChatException $e) {
    exit( $e->getMessage() );
}