<?php
header("Content-type: ".$arquivo["arquivo_professor_tipo"]);
header('Content-Disposition: attachment; filename="'.basename($arquivo["arquivo_professor"]).'"');
header('Content-Length: '.$arquivo["arquivo_professor_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/provas_professores_anexos/".$arquivo["arquivo_professor_servidor"]);