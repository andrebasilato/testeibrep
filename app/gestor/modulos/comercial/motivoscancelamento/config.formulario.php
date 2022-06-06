<?php			   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_nome",
		"nome" => "nome", 
		"nomeidioma" => "form_nome",
		"tipo" => "input",
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"), 
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_retirar_comissao",
		"nome" => "retirar_comissao",
		"nomeidioma" => "form_retirar_comissao",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "retirar_comissao",
		"validacao" => array("required" => "retirar_comissao_vazio"),
		"ajudaidioma" => "form_retirar_comissao_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_anular_parcelas",
		"nome" => "anular_parcelas",
		"nomeidioma" => "form_anular_parcelas",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "anular_parcelas",
		"validacao" => array("required" => "anular_parcelas_vazio"),
		"ajudaidioma" => "form_anular_parcelas_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_cancelamento",
		"nome" => "cancela_automatico",
		"nomeidioma" => "form_cancelamento",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "cancela_automatico",
		"validacao" => array("required" => "cancelamento_vazio"),
		"ajudaidioma" => "form_cancelamento_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_ativo_painel",
		"nome" => "ativo_painel",
		"nomeidioma" => "form_ativo_painel",
		"tipo" => "select",
		"array" => "ativo", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "ativo_painel",
		"validacao" => array("required" => "ativo_vazio"),
		"ajudaidioma" => "form_ativo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
        array(
            "id" => "form_padrao",
            "nome" => "padrao",
            "nomeidioma" => "form_padrao",
            "tipo" => "select",
            "array" => "sim_nao", // Array que alimenta o select em "\app\includes\config.php"
            "class" => "span2",
            "valor" => "padrao",
            "validacao" => array("required" => "padrao_vazio"),
            "ajudaidioma" => "form_padrao_ajuda",
            "banco" => true,
            "banco_string" => true
        ),
	)
  )								  
);
?>