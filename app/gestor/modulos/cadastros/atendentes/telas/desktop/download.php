<?php
header("Content-type: " . $linha[$url[5] . "_tipo"]);
header('Content-Disposition: attachment; filename="' . basename($linha[$url[5] . "_nome"]) . '"');
header('Content-Length: ' . $linha[$url[5] . "_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"] . "/storage/" . $url[2] . "_" . $url[5] . "/" . $linha[$url[5] . "_servidor"]);