<?php
//$data_matricula = new DateTime($matricula['data_cad']);
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/matriculas_arquivos/" . $download["arquivo_pasta"] . "/" . $download["idmatricula"]."/".$download["arquivo_servidor"];
if(file_exists($arquivo)) {
  header('Content-type: '.$download["arquivo_tipo"]);
  header('Content-length: '.filesize($arquivo));
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";
}