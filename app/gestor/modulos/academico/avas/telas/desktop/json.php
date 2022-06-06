<?php
$linhaObj->Set("id",intval($url[5]));
$linhaObj->Set("get",$_GET);

if($url[7] == "associar_pergunta"){
  echo $linhaObj->BuscarPerguntaSimulado();
}elseif($url[10] == "curtir"){
  echo $linhaObj->BuscarPerguntaSimulado();
}

?>