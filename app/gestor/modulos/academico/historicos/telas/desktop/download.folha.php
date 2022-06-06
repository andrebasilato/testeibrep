<?
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/".Historicos::PASTA."/".$page["idhistorico_escolar"]."/".$page["arquivo"];
if(file_exists($arquivo)) {
  header("Content-type: ".$page["arquivo_tipo"]);
  header('Content-Disposition: attachment; filename="'. basename($page["arquivo"]).'"');
  header('Content-Length: '.$page["arquivo_tamanho"]);
  header('Expires: 0');
  header('Pragma: no-cache');
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";	
}
?>