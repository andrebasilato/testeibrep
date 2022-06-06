<?php
require_once $caminhoApp . '/app/includes/config.php';
require_once $caminhoApp . '/app/gestor/includes/config.php';
require_once $caminhoApp . '/app/classes/perguntas.class.php';
$perguntasObj = new Perguntas();
$config['banco'] = array(
    'tabela' => 'perguntas',
    'primaria' => 'idpergunta'
);
$perguntasObj->set('config', $config);

$perguntas = $perguntasObj->retornarPerguntasPendentesClonar();
foreach ($perguntas as $pergunta) {
    $perguntasObj->set('idusuario', $pergunta['idusuario']);
    $perguntasObj->clonarPergunta($pergunta);
}
