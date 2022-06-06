<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
//$sqlSindicato .= ' order by nome_abreviado';

// Array de configuração para a listagem
$config["listagem"] = array(
    array(
        "id" => "tabela_numero", // Id do atributo
        "variavel_lang" => "tabela_numero", // Referencia a variavel de idioma
        "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "coluna_sql" => "c.idconta", // Nome da coluna no banco de dados
        "valor" => 'idconta',
        "tamanho" => "60"
    ),
    array(
        "id" => "tabela_data_matricula",
        "variavel_lang" => "tabela_data_matricula",
        "tipo" => "php",
        "valor" => 'return formataData($linha["data_matricula"],"br",0);',
        "tamanho" => "120"
    ),
    array(
        "id" => "tabela_vencimento",
        "variavel_lang" => "tabela_vencimento",
        "tipo" => "php",
        "valor" => 'return formataData($linha["data_vencimento"],"br",0);',
        "tamanho" => "120"
    ),
    array(
        "id" => "tabela_valor",
        "variavel_lang" => "tabela_valor",
        "tipo" => "php",
        "valor" => 'if($linha["valor"] >= 0) {
                        $color = "green";
                    } else {
                        $color = "red";
                    }
                    return "<span style=\"color:gray; float:left\">R$</span> <span style=\"color:$color; float:right\"><strong>".number_format($linha["valor"],2,",",".")."</strong></span>";
                    ',
        "tamanho" => "120"
    ),
    array(
        "id" => "situacao",
        "variavel_lang" => "tabela_situacao",
        "tipo" => "php",
        "valor" => 'return "<span>".$linha["situacao_wf_nome"]."</span>";',
        "tamanho" => 100
    ),
    array(
        "id" => "tabela_descricao",
        "variavel_lang" => "tabela_descricao",
        "tipo" => "banco",
        "coluna_sql" => "c.nome",
        "valor" => "nome"
    ),
    array(
        "id" => "tabela_mantenedora",
        "variavel_lang" => "tabela_mantenedora",
        "tipo" => "banco",
        "coluna_sql" => "m.nome",
        "valor" => "mantenedora"
    ),
    array(
        "id" => "tabela_escola",
        "variavel_lang" => "tabela_escola",
        "tipo" => "banco",
        "coluna_sql" => "e.nome_fantasia",
        "valor" => "escola"
    ),
    array(
        "id" => "tabela_sindicato",
        "variavel_lang" => "tabela_sindicato",
        "tipo" => "banco",
        "coluna_sql" => "i.nome",
        "valor" => "sindicato"
    ),
    array(
        "id" => "tabela_codigo",
        "variavel_lang" => "tabela_codigo",
        "tipo" => "php",
        "valor" => 'if($linha["idmatricula"]) {
                        $codigo = "Mat.: ".$linha["idmatricula"]." - Aluno: ".$linha["idpessoa_matricula"];
                    } elseif($linha["idcliente"]) {
                        $codigo = $linha["idcliente"];
                    } elseif($linha["idfornecedor"]) {
                        $codigo = $linha["idfornecedor"];
                    } elseif($linha["idpessoa"]) {
                        $codigo = $linha["idpessoa"];
                    }
                    return $codigo;',
        "tamanho" => "120"
    ),
    array(
        "id" => "tabela_nome",
        "variavel_lang" => "tabela_nome",
        "tipo" => "php",
        "valor" => 'if($linha["idmatricula"]) {
                        $nome = $linha["aluno"];
                    } elseif($linha["idcliente"]) {
                        $nome = $linha["cliente"];
                    } elseif($linha["idfornecedor"]) {
                        $nome = $linha["fornecedor"];
                    } elseif($linha["idpessoa"]) {
                        $nome = $linha["pessoa"];
                    }
                    return $nome;',
        "tamanho" => "120"
    ),
    array(
        "id" => "tabela_produto",
        "variavel_lang" => "tabela_produto",
        "tipo" => "banco",
        "coluna_sql" => "p.nome",
        "valor" => "produto"
    ),
    array(
        "id" => "tabela_categoria",
        "variavel_lang" => "tabela_categoria",
        "tipo" => "banco",
        "coluna_sql" => "cat.nome",
        "valor" => "categoria"
    ),
    /*array(
        "id" => "tabela_cliente",
        "variavel_lang" => "tabela_cliente",
        "tipo" => "banco",
        "coluna_sql" => "cli.nome",
        "valor" => "cliente"
    ),*/
    array(
        "id" => "tabela_tipo",
        "variavel_lang" => "tabela_tipo",
        "tipo" => "banco",
        "coluna_sql" => "c.tipo",
        "valor" => "tipo"
    ),
    array(
        "id" => "tabela_datacad",
        "variavel_lang" => "tabela_datacad",
        "tipo" => "php",
        "valor" => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho" => "160"
    ),
    array(
        "id" => "tabela_conta_corrente",
        "variavel_lang" => "tabela_conta_corrente",
        "tipo" => "banco",
        "coluna_sql" => "cc.conta",
        "valor" => "conta_corrente"
    )
);


            // Array de configuração para a formulario
            $config["formulario"] = array(
                              array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
                                    "campos" => array( // Campos do formulario

                                                     array(
                                                        "id" => "form_tipo_data_filtro",
                                                        "nome" => "q[de_ate|tipo_data_filtro|c.data_vencimento]",
                                                        "nomeidioma" => "form_tipo_data_filtro",
                                                        "botao_hide" => true,
                                                        "iddivs" => array("de","ate"),
                                                        "tipo" => "select",
                                                        "iddiv" => "de",
                                                        "iddiv2" => "ate",
                                                        "iddiv_obr" => false,
                                                        "iddiv2_obr" => false,
                                                        "array" => "tipo_data_filtro", // Array que alimenta o select
                                                        "class" => "span3",
                                                        "valor" => "tipo_data_filtro",
                                                        "validacao" => array("required" => "tipo_data_filtro_vazio"),
                                                        "banco" => true,
                                                        "banco_string" => true,
                                                        "sql_filtro" => "array",
                                                        "sql_filtro_label" => "tipo_data_filtro"
                                                        ),
                                                      array(
                                                            "id" => "form_de",
                                                            "nome" => "de",
                                                            "nomeidioma" => "form_de",
                                                            "tipo" => "input",
                                                            "class" => "span2",
                                                            "datepicker" => true,
                                                            "input_hidden" => true,
                                                            ),
                                                      array(
                                                            "id" => "form_ate",
                                                            "nome" => "ate",
                                                            "nomeidioma" => "form_ate",
                                                            "tipo" => "input",
                                                            "class" => "span2",
                                                            "datepicker" => true,
                                                            "input_hidden" => true,
                                                            ),
                                                      /*array(
                                                            "id" => "idcliente",
                                                            "nome" => "q[1|c.idcliente]",
                                                            "nomeidioma" => "form_idcliente",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idcliente, nome FROM clientes WHERE ativo='S'", // SQL que alimenta o select
                                                            "sql_ordem" => "asc",
                                                            "sql_ordem_campo" => "nome",
                                                            "sql_valor" => "idcliente", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idcliente",
                                                            "sql_filtro" => "select * from clientes where idcliente=%",
                                                            "sql_filtro_label" => "nome",
                                                            ),*/
                                                      array(
                                                            "id" => "idmantenedora",
                                                            "nome" => "q[1|c.idmantenedora]",
                                                            "nomeidioma" => "form_idmantenedora",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idmantenedora, nome_fantasia FROM mantenedoras WHERE ativo='S'", // SQL que alimenta o select
                                                            "sql_ordem" => "asc",
                                                            "sql_ordem_campo" => "nome_fantasia",
                                                            "sql_valor" => "idmantenedora", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome_fantasia", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idmantenedora",
                                                            "sql_filtro" => "select * from mantenedora where idmantenedora=%",
                                                            "sql_filtro_label" => "nome_fantasia",
                                                            ),
                                                      array(
                                                            "id" => "idsindicato",
                                                            "nome" => "q[1|c.idsindicato]",
                                                            "nomeidioma" => "form_idsindicato",
                                                            "tipo" => "select",
                                                            "sql" => $sqlSindicato, // SQL que alimenta o select
                                                            "sql_ordem" => "asc",
                                                            "sql_ordem_campo" => "nome_abreviado",
                                                            "sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idsindicato",
                                                            "sql_filtro" => "select * from sindicatos where idsindicato=%",
                                                            "sql_filtro_label" => "nome_abreviado",
                                                            ),
                                                      array(
                                                            "id" => "idproduto",
                                                            "nome" => "q[1|c.idproduto]",
                                                            "nomeidioma" => "form_idproduto",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idproduto, nome FROM produtos WHERE ativo='S'", // SQL que alimenta o select
                                                            "sql_ordem" => "asc",
                                                            "sql_ordem_campo" => "nome",
                                                            "sql_valor" => "idproduto", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "sql_filtro" => "select * from produtos where idproduto=%",
                                                            "valor" => "idproduto",
                                                            "sql_filtro_label" => "nome",
                                                            ),

                                                      array(
                                                            "id" => "idcategoria",
                                                            "nome" => "q[1|c.idcategoria]",
                                                            "nomeidioma" => "form_idcategoria",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idcategoria, nome FROM categorias WHERE ativo='S'", // SQL que alimenta o select
                                                            "sql_ordem" => "asc",
                                                            "sql_ordem_campo" => "nome",
                                                            "sql_valor" => "idcategoria", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "sql_filtro" => "select * from categorias where idcategoria=%",
                                                            "valor" => "idcategoria",
                                                            "sql_filtro_label" => "nome",
                                                            ),

                                                      /*array(
                                                            "id" => "idpessoa",
                                                            "nome" => "q[1|c.idpessoa]",
                                                            "nomeidioma" => "form_idpessoa",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idpessoa, nome FROM pessoas WHERE ativo='S'", // SQL que alimenta o select
                                                            "sql_ordem" => "asc",
                                                            "sql_ordem_campo" => "nome",
                                                            "sql_valor" => "idpessoa", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "sql_filtro" => "select * from pessoas where idpessoa=%",
                                                            "valor" => "idpessoa",
                                                            "sql_filtro_label" => "nome",
                                                            ),*/

                                                      array(
                                                            "id" => "idsituacao",
                                                            "nome" => "situacao",
                                                            "nomeidioma" => "form_idsituacao",
                                                            "tipo" => "checkbox",
                                                            "sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo='S'", // SQL que alimenta o select
                                                            "sql_ordem" => "asc",
                                                            "sql_ordem_campo" => "nome",
                                                            "sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idsituacao",
                                                            "sql_filtro" => "select * from matriculas_workflow where idsituacao=%",
                                                            "sql_filtro_label" => "nome"
                                                            ),
                                                      )
                                    )
                        );

?>