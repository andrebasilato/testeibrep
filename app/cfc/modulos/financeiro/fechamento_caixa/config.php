<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/financeiro_32.png";

$config["monitoramento"]["onde"] = "142";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela"             => "fechamentos_caixa_cfc",
                         "primaria"           => "idfechamento",
                         "campos_insert_fixo" => array("data_cad" => "now()",
                                                       "ativo"    => "'S'"
                         ),
                         "campos_unicos"      => array()
);

