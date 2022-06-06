<?php 
//$data_matricula = new DateTime($contrato['data_matricula']);
$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/matriculas_contratos/" . $contrato['arquivo_pasta'] . "/" . $contrato["idmatricula"]."/".$contrato["arquivo_servidor"];
if(file_exists($arquivo)) {
  header("Content-type: ".$contrato["arquivo_tipo"]);
  header('Content-Disposition: attachment; filename="'. basename($contrato["arquivo"]).'"');
  header('Content-Length: '.$contrato["arquivo_tamanho"]);
  header('Expires: 0');
  header('Pragma: no-cache');
  readfile($arquivo);
} else {
  echo "Arquivo não encontrado.";	
}
?>