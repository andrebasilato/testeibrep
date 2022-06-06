<?php
header("Content-type: " . $linha[$url[4] . "_tipo"]);
header('Content-Disposition: attachment; filename="' . basename($linha[$url[4] . "_nome"]) . '"');
header('Content-Length: ' . $linha[$url[4] . "_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"] . "/storage/usuariosadm_avatar/" . $linha[$url[4] . "_servidor"]);