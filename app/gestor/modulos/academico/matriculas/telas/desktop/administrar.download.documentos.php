<?php
//$data_cadastro = new DateTime($matricula['data_cad']);
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/matriculas_documentos/".$download["arquivo_pasta"]."/".$download["idmatricula"]."/".$download["arquivo_servidor"];
if(file_exists($arquivo)) {
  header("Content-type: ".$download["arquivo_tipo"]);
  header('Content-Disposition: attachment; filename="'. basename($download["arquivo_nome"]).'"');
  header('Content-Length: '.$download["arquivo_tamanho"]);
  header('Expires: 0');
  header('Pragma: no-cache');
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";	
}
?>