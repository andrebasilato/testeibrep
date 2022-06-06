<?php
if ($url[5] == "participantes_mensagem_instantanea") {
    require_once "../classes/avas.mensagem_instantanea.class.php";
    $participantesObj = new MensagemInstantanea();
    $participantesObj->set("idava", $url[6]);
    $participantesObj->set("idprofessor",$url[7]);
    echo $participantesObj->buscarParticipantes();
}
exit;