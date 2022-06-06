<?php 
header("Content-type: ".$linha[$url[7]."_tipo"]);
header('Content-Disposition: attachment; filename="'. basename($linha[$url[7]."_nome"]).'"');
header('Content-Length: '.$linha[$url[7]."_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/".$url[2]."_".$url[4]."_".$url[7]."/".$linha[$url[7]."_servidor"]);
?>