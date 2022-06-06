<?php
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/professores_arquivos/".$download["idprofessor"]."/".$download["arquivo_servidor"];
if(file_exists($arquivo)) {
  header('Content-type: '.$download["arquivo_tipo"]);
  header('Content-length: '.filesize($arquivo));
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";
}