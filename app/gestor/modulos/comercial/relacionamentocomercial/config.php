<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "cadastrar_remover_mensagem";

$config["monitoramento"]["onde"] = "156";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "relacionamentos_comerciais",
  "primaria" => "idrelacionamento",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array("campo_banco" => "email_pessoa", 
          "campo_form" => "email_pessoa", 
          "erro_idioma" => "email_utilizado"
    )
  ),
											 
);
?>