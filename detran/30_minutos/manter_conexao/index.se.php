<?php
header('Content-Type: text/xml');
header('Expires: 0');
header('Pragma: no-cache');
$arquivoWsdl = file_get_contents($config['detran']['SE']['urlWsl']);
