<?php
require '../classes/chat_novo.class.php';
$chatsObj = new Chat(new Core);

if((int) $url[6]) {
	try {
		$chatsObj->setChatId( (int) $url[6] )->setId($usuario['idpessoa']);
		$chat = $chatsObj->infoAbout( (int) $url[6] );
		
		if (! $chat) {
			throw new NotFoundChatException();
		}
	
		if ('atualizarChat' == Request::post('acao')) {
			$messages = array();
			$chatsObj->getMoreThen(Request::post('lastId'));
			$messages = $chatsObj->getResult();
			exit( json_encode($messages));
		}
	
		if ('novamensagem' == Request::post( 'acao' )) {
			$chatsObj->setChatId( (int) $url[6] )->registerNewMessage($matricula['idmatricula']);
			## Cadastra historico do aluno
            $matriculaObj->cadastrarHistorioAluno($ava['idava'], 'respondeu', "chat", (int) $url[6]);
            $matriculaObj->contabilizarChat($matricula['idmatricula'], $ava['idava'], (int) $url[6]);
            ## /Cadastra historico do aluno
			
			$chat = $chatsObj->infoAbout( (int) $url[6] );
			if (! $chat) {
				throw new NotFoundChatException();
			}
		}
	
		if ($chatsObj->isValidPeriod()) {
			
			$hoje = strtotime(date('Y-m-d H:i'));
			$inicioEntradaAluno = strtotime($chat['dados']['inicio_entrada_aluno']);
			$fimEntradaAluno = strtotime($chat['dados']['fim_entrada_aluno']);
			
			$chatsObj->allMessages( (int) $url[6] );
			$mensagens = array_reverse( $chatsObj->getResult() );
			## Cadastra historico do aluno
            $matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', "chat", (int) $url[6]);
            ## /Cadastra historico do aluno
			require 'idiomas/'.$config['idioma_padrao'].'/chat.php';
			require 'telas/'.$config['tela_padrao'].'/chat.php';
			exit;
		}
    } catch (NotFoundChatException $error) {
		exit($error->getMessage());
    } catch (Exception $error) { }
} else {
	## Chats
	$_GET['cmp'] = 'aberto DESC, futuro DESC, inicio_entrada_aluno';
	$_GET['ord'] = 'ASC';
	$_GET['qtd'] = -1;
	$chats = $chatsObj->allConversation($ava['idava'])->getResult();
	unset($_GET['cmp']);
	unset($_GET['ord']);
	unset($_GET['qtd']);
	## /Chats
	
	require 'idiomas/'.$config['idioma_padrao'].'/chats.php';
	require 'telas/'.$config['tela_padrao'].'/chats.php';
	exit;
}