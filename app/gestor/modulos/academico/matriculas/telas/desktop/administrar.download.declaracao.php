<?php
//$data_matricula = new DateTime($matricula['data_cad']);
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/matriculas_declaracoes/" . $declaracao["arquivo_pasta"] . "/" . $declaracao["idmatricula"]."/".$declaracao["arquivo_servidor"];
if(file_exists($arquivo)) {
  header("Content-type: ".$declaracao["arquivo_tipo"]);
  header('Content-Disposition: attachment; filename="'. basename($declaracao["arquivo"]).'"');
  header('Content-Length: '.$declaracao["arquivo_tamanho"]);
  header('Expires: 0');
  header('Pragma: no-cache');
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";
}
?>