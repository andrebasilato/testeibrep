<?php
// Array de configuração para a formulario			
$config["formulario"] = array(
							  array("fieldsetid" => "dadosdousuario", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosusuarios", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario
													  array(
															"id" => "form_upload",
															"nome" => "arquivo", 
															"nomeidioma" => "form_upload",
															"arquivoidioma" => "arquivo_enviado", 
															"arquivoexcluir" => "arquivo_excluir", 
															"tipo" => "file",
															"extensoes" => 'ret',
															"validacao" => array("file_required" => "arquivo_obrigatorio", "formato_arquivo" => "arquivo_invalido"),
															"class" => "span6",
															"pasta" => "contas_retornos", 
															"banco" => true, 
															"banco_campo" => "arquivo",
															"ignorarsevazio" => true,
															"ajudaidioma" => "form_ajuda_upload"
															)
													  )
									)
						);
						

?>