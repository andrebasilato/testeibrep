<?php
header("Content-type: application/save");
header("Content-Disposition: attachment; filename=".$linha[$url[5]."_nome"]." ");
header('Content-Disposition: attachment; filename="'. basename($linha[$url[5]."_nome"]).'"');
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/contas_retornos/".$linha[$url[5]."_servidor"]);