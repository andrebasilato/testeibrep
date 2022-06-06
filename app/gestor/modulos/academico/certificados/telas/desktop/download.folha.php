<?php
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/certificados/".$page["idcertificado"]."/".$page["arquivo_servidor"];

if(file_exists($arquivo)) {
	//print_r2($page);exit;
  header("Content-type: ".$page["arquivo_tipo"]);
  header('Content-Disposition: attachment; filename="'. basename($page["arquivo_nome"]).'"');
  header('Content-Length: '.$page["arquivo_tamanho"]);
  header('Expires: 0');
  header('Pragma: no-cache');
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";	
}
?>