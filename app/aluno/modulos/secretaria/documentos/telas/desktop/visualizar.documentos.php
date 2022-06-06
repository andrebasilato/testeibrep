<?php
//$data_matricula = new DateTime($download['data_matricula']);
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/matriculas_documentos/" . $download['arquivo_pasta'] . "/" . $download["idmatricula"]."/".$download["arquivo_servidor"];
if(file_exists($arquivo)) {
  header('Content-type: '.$download["arquivo_tipo"]);
  header('Content-length: '.filesize($arquivo));
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";
}
?>