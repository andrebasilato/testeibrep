<?
$arquivo = 'http://'.$_SERVER["SERVER_NAME"]."/storage/".Historicos::PASTA."/".$page["idhistorico_escolar"]."/".$page["arquivo"];
//$arquivo = $_SERVER["DOCUMENT_ROOT"]."/storage/certificados/".$page["idcertificado"]."/".$page["arquivo"];
//if(file_exists($arquivo)) {
 header("Content-type: text/html; charset=utf-8");
	 ?> <iframe src="<?php echo $arquivo; ?>" width="850" height="600"></iframe> <?php exit;
 /* header("Content-type: ".$contrato["arquivo_tipo"]);
  header('Content-Disposition: attachment; filename="'. basename($contrato["arquivo"]).'"');
  header('Content-Length: '.$contrato["arquivo_tamanho"]);
  header('Expires: 0');
  header('Pragma: no-cache');*/
  //readfile($arquivo);;
//} else {
  //echo "Arquivo não encontrado.";	
//}
?>