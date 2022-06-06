<?php
$avatar = $_SERVER["DOCUMENT_ROOT"]."/storage/professores_avatar/".$download["avatar_servidor"];
if(file_exists($avatar)) {
  header("Content-type: ".$download["avatar_tipo"]);
  header('Content-Disposition: attachment; filename="'. basename($download["avatar_nome"]).'"');
  header('Content-Length: '.$download["avatar_tamanho"]);
  header('Expires: 0');
  header('Pragma: no-cache');
  readfile($avatar);
} else {
  echo "Avatar não encontrado.";
}
?>