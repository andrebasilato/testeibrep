<?php
header("Content-type: application/save");
header("Content-Disposition: attachment; filename=".$linha[$url[4]."_nome"]." ");
header('Content-Disposition: attachment; filename="'. basename($linha[$url[4]."_nome"]).'"');
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/corretores_".$url[4]."/".$linha[$url[4]."_servidor"]);
?>