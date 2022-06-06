<?php
$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';

$sqlEscola = 'select idescola, razao_social from escolas where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlEscola .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
$sqlEscola .= ' order by razao_social';

$sqlCategoria = 'SELECT c.idcategoria, c.nome FROM categorias c
            INNER JOIN categorias_subcategorias cs ON ( c.idcategoria = cs.idcategoria )
            WHERE  c.ativo =  "S" AND  cs.ativo =  "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlCategoria .= ' AND
                (
                not exists (
                  SELECT
                    csss.idassociacao
                  FROM
                    categorias_subcategorias_sindicatos csss
                  WHERE
                    csss.ativo = "S" AND
                    csss.idsubcategoria = cs.idsubcategoria
                )
                OR
                exists (
                  SELECT
                    csss.idassociacao
                  FROM
                    categorias_subcategorias_sindicatos csss
                  WHERE
                    csss.ativo = "S" AND csss.idsindicato IN ('.$_SESSION['adm_sindicatos'].') AND
                    csss.idsubcategoria = cs.idsubcategoria
                )
            )';
$sqlCategoria .= '
            GROUP BY c.idcategoria
            ORDER BY c.nome ';

$config["formulario"] = array(
  array(
    "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
    "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
    "campos" => array( // Campos do formulario
     /*
      array(
        "id" => "form_idmantenedora",
        "nome" => "q[1|c.idmantenedora]",
        "nomeidioma" => "form_idmantenedora",
        "tipo" => "select",
        "sql" => "select idmantenedora, nome_fantasia from mantenedoras where ativo = 'S' order by nome_fantasia", // SQL que alimenta o select
        "sql_valor" => "idmantenedora", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome_fantasia", // Coluna da tabela que será usado como o label do options
        "valor" => "idmantenedora",
        "sql_filtro" => "select * from mantenedoras where idmantenedora = %",
        "sql_filtro_label" => "nome_fantasia",
        "class" => "span4",
      ),
      */
      array(
        "id" => "form_idsindicato",
        "nome" => "idsindicato",
        "nomeidioma" => "form_idsindicato",
        "tipo" => "checkbox",
        "sql" => $sqlSindicato,
        "sql_ordem_campo" => "nome_abreviado",
        "sql_ordem" => "asc",
        "sql_valor" => "idsindicato",
        "sql_label" => "nome_abreviado",
        "valor" => "idsindicato",
        "sql_filtro" => "select * from sindicatos where idsindicato = %",
        "sql_filtro_label" => "nome_abreviado"
      ),


      array(
        "id" => "form_filtro_data_vencimento",
        "nome" => "filtro_data_vencimento",
        "nomeidioma" => "form_filtro_data_vencimento",
        "botao_hide" => true,
        "tipo" => "select",
        "iddiv" => "de_data_vencimento",
        "iddiv2" => "ate_data_vencimento",
        "iddiv_obr" => true,
        "iddiv2_obr" => true,
        "array" => "tipo_data_filtro", // Array que alimenta o select
        "class" => "span3",
        "valor" => "tipo_data_filtro",
        "validacao" => array("required" => "filtro_data_vencimento_vazio"),
        "banco" => true,
        "banco_string" => true,
        "sql_filtro" => "array",
        "sql_filtro_label" => "tipo_data_filtro"
      ),
      array(
        "id" => "form_de_data_vencimento",
        "nome" => "de_data_vencimento",
        "nomeidioma" => "form_de_data_vencimento",
        "tipo" => "input",
        "class" => "span2",
        "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_vencimento\",\"form_ate_data_vencimento\")'",
        "validacao" => array("required" => "de_data_vencimento_vazio"),
        "datepicker" => true,
        "mascara" => "99/99/9999",
        "input_hidden" => true,
      ),
      array(
        "id" => "form_ate_data_vencimento",
        "nome" => "ate_data_vencimento",
        "nomeidioma" => "form_ate_data_vencimento",
        "tipo" => "input",
        "class" => "span2",
        "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_vencimento\",\"form_ate_data_vencimento\")'",
        "validacao" => array("required" => "ate_data_vencimento_vazio"),
        "datepicker" => true,
        "mascara" => "99/99/9999",
        "input_hidden" => true,
      ),
      array(
        "id" => "form_filtro_data_pagamento",
        "nome" => "filtro_data_pagamento",
        "nomeidioma" => "form_filtro_data_pagamento",
        "botao_hide" => true,
        "tipo" => "select",
        "iddiv" => "de_data_pagamento",
        "iddiv2" => "ate_data_pagamento",
        "iddiv_obr" => true,
        "iddiv2_obr" => true,
        "array" => "tipo_data_filtro", // Array que alimenta o select
        "class" => "span3",
        "valor" => "tipo_data_filtro",
        //"validacao" => array("required" => "filtro_data_pagamento_vazio"),
        "banco" => true,
        "banco_string" => true,
        "sql_filtro" => "array",
        "sql_filtro_label" => "tipo_data_filtro"
      ),
      array(
        "id" => "form_de_data_pagamento",
        "nome" => "de_data_pagamento",
        "nomeidioma" => "form_de_data_pagamento",
        "tipo" => "input",
        "class" => "span2",
        "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_pagamento\",\"form_ate_data_pagamento\")'",
        //"validacao" => array("required" => "de_data_pagamento_vazio"),
        "datepicker" => true,
        "mascara" => "99/99/9999",
        "input_hidden" => true,
      ),
      array(
        "id" => "form_ate_data_pagamento",
        "nome" => "ate_data_pagamento",
        "nomeidioma" => "form_ate_data_pagamento",
        "tipo" => "input",
        "class" => "span2",
        "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_pagamento\",\"form_ate_data_pagamento\")'",
        //"validacao" => array("required" => "ate_data_pagamento_vazio"),
        "datepicker" => true,
        "mascara" => "99/99/9999",
        "input_hidden" => true,
      ),
      array(
        "id" => "form_tipo",
        "nome" => "tipo",
        "nomeidioma" => "form_tipo",
        "botao_hide" => true,
        "iddivs" => array("idcliente","idfornecedor"),
        "iddiv" => "form_cliente",
        "iddiv2" => "form_fornecedor",
        "tipo" => "select",
        "array" => "tipo_contas", // Array que alimenta o select
        "class" => "span2",
        "valor" => "tipo",
        "ajudaidioma" => "form_tipo_ajuda",
        "banco" => true,
        "banco_string" => true
      ),


      array(
        "id" => "form_descricao",
        "nome" => "q[2|c.nome]",
        "nomeidioma" => "form_descricao",
        "tipo" => "input",
        "class" => "span4"
      ),

      array(
        "id" => "form_documento",
        "nome" => "q[2|c.documento]",
        "nomeidioma" => "form_documento",
        "tipo" => "input",
        "class" => "span4"
      ),
//      array(
//        "id" => "form_cliente",
//        "nome" => "q[2|e.nome]",
//        "nomeidioma" => "form_cliente",
//        "tipo" => "input",
//        "class" => "span4"
//      ),
      array(
        "id" => "form_fornecedor",
        "nome" => "q[2|f.nome]",
        "nomeidioma" => "form_fornecedor",
        "tipo" => "input",
        "class" => "span4"
      ),
      array(
        "id" => "form_idevento",
        "nome" => "q[1|c.idevento]",
        "nomeidioma" => "form_idevento",
        "tipo" => "select",
        "sql" => "select idevento, nome from eventos_financeiros where ativo = 'S' order by nome",
        "sql_valor" => "idevento",
        "sql_label" => "nome",
        "sql_filtro" => "select * from eventos_financeiros where idevento = %",
        "valor" => "idevento",
        "sql_filtro_label" => "nome",
        "class" => "span2",
      ),
      array(
        "id" => "form_forma_pagamento",
        "nome" => "q[1|c.forma_pagamento]",
        "nomeidioma" => "form_forma_pagamento",
        "botao_hide" => true,
        "tipo" => "select",
        "iddiv" => "idbandeira",
        "iddiv_obr" => false,
        "array" => "forma_pagamento_conta", // Array que alimenta o select
        "class" => "span3",
        "valor" => "forma_pagamento",
        "banco" => true,
        "banco_string" => true,
        "sql_filtro" => "array",
        "sql_filtro_label" => "forma_pagamento_conta"
      ),
      array(
        "id" => "form_idbandeira",
        "nome" => "q[1|c.idbandeira]",
        "nomeidioma" => "form_idbandeira",
        "tipo" => "select",
        "sql" => "select idbandeira, nome from bandeiras_cartoes where ativo = 'S' order by nome",
        "sql_valor" => "idbandeira",
        "sql_label" => "nome",
        "sql_filtro" => "select * from bandeiras_cartoes where idbandeira = %",
        "valor" => "idbandeira",
        "sql_filtro_label" => "nome",
        "class" => "span2",
        "select_hidden" => true,
      ),
      array(
        "id" => "form_idcurso",
        "nome" => "q[1|m.idcurso]",
        "nomeidioma" => "form_idcurso",
        "tipo" => "select",
        "sql" => "select idcurso, nome from cursos where ativo = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from cursos where idcurso = %",
        "valor" => "idcurso",
        "sql_filtro_label" => "nome",
      ),
      array(
        "id" => "idoferta",
        "nome" => "q[1|m.idoferta]",
        "nomeidioma" => "form_idoferta",
        "tipo" => "select",
        "sql" => "select idoferta, nome from ofertas where ativo = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from ofertas where idoferta = %",
        "valor" => "idoferta",
        "sql_filtro_label" => "nome",
        "class" => "span2",
      ),
      array(
        "id" => "form_idescola",
        "nome" => "q[1|po.idescola]",
        "nomeidioma" => "form_idescola",
        "tipo" => "select",
        "sql" => $sqlEscola,
        "sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "razao_social", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from escolas where idescola = %",
        "valor" => "idescola",
        "sql_filtro_label" => "razao_social",
      ),
      array(
        "id" => "form_idturma",
        "nome" => "q[1|m.idturma]",
        "nomeidioma" => "form_idturma",
        "json" => true,
        "json_idpai" => "idoferta",
        "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_turmas/",
        "json_input_pai_vazio" => "form_selecione_oferta",
        "json_input_vazio" => "form_selecione_turma",
        "json_campo_exibir" => "nome",
        "tipo" => "select",
        "valor" => "idturma",
        "sql_filtro" => "select * from ofertas_turmas where idturma = %",
        "sql_filtro_label" => "nome"
      ),
      array(
        "id" => "form_pessoa",
        "nome" => "q[2|mp.nome]",
        "nomeidioma" => "form_pessoa",
        "tipo" => "input",
        "class" => "span4"
      ),
      array(
        "id" => "form_idproduto",
        "nome" => "q[1|c.idproduto]",
        "nomeidioma" => "form_idproduto",
        "tipo" => "select",
        "sql" => "select idproduto, nome from produtos where ativo = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idproduto", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from produtos where idproduto = %",
        "valor" => "idproduto",
        "sql_filtro_label" => "nome",
      ),
      array(
        "id" => "form_idcentro_custo",
        "nome" => "idcentro_custo",
        "nomeidioma" => "form_idcentro_custo",
        "tipo" => "select",
        "sql" => "select idcentro_custo, nome from centros_custos where ativo = 'S' order by nome",
        "sql_valor" => "idcentro_custo",
        "sql_label" => "nome",
        "sql_filtro" => "select * from centros_custos where idcentro_custo = %",
        "valor" => "idcentro_custo",
        "sql_filtro_label" => "nome",
        "class" => "span2",
      ),
      array(
        "id" => "idcategoria",
        "nome" => "q[1|c.idcategoria]",
        "nomeidioma" => "form_idcategoria",
        "tipo" => "select",
        "sql" => $sqlCategoria,
        "sql_valor" => "idcategoria", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from categorias where idcategoria = %",
        "valor" => "idcategoria",
        "sql_filtro_label" => "nome",
        "class" => "span2",
      ),
      array(
        "id" => "idsubcategoria",
        "nome" => "q[1|c.idsubcategoria]",
        "nomeidioma" => "form_idsubcategoria",
        "json" => true,
        "json_idpai" => "idcategoria",
        "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_subcategorias/",
        "json_input_pai_vazio" => "form_selecione_categoria",
        "json_input_vazio" => "form_selecione_subcategoria",
        "json_campo_exibir" => "nome",
        "tipo" => "select",
        "valor" => "idsubcategoria",
        "sql_filtro" => "select * from categorias_subcategorias where idsubcategoria = %",
        "sql_filtro_label" => "nome"
      ),
      array(
        "id" => "form_idmatricula",
        "nome" => "q[1|m.idmatricula]",
        "nomeidioma" => "form_idmatricula",
        "tipo" => "input",
        "class" => "span2",
        "input_tipo" => "number"
      ),
      array(
        "id" => "form_ativo_painel",
        "nome" => "q[1|c.ativo_painel]",
        "nomeidioma" => "form_ativo_painel",
        "tipo" => "select",
        "array" => "ativo", // Array que alimenta o select
        "class" => "span1",
        "valor" => "ativo",
        "banco" => true,
        "banco_string" => true,
        "sql_filtro" => "array",
        "sql_filtro_label" => "ativo_painel"
      ) ,
      array(
        "id" => "form_idsituacao",
        "nome" => "idsituacao",
        "nomeidioma" => "form_idsituacao",
        "tipo" => "checkbox",
        "sql" => "select idsituacao, nome from contas_workflow where ativo = 'S'", // SQL que alimenta o select
        "sql_ordem_campo" => "nome",
        "sql_ordem" => "asc",
        "sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "valor" => "idsituacao",
        "sql_filtro" => "select * from contas_workflow where idsituacao = %",
        "sql_filtro_label" => "nome"
      ),
      /*array(
        "id" => "form_idgrupo",
        "nome" => "idgrupo",
        "nomeidioma" => "form_idgrupo",
        "tipo" => "select",
        "sql" => "select idgrupo, nome from grupos_vendedores where ativo = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idgrupo", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from grupos_vendedores where idgrupo = %",
        "valor" => "idgrupo",
        "sql_filtro_label" => "nome",
      ),*/
      array(
        "id" => "form_idvendedor",
        "nome" => "q[1|m.idvendedor]",
        "nomeidioma" => "form_idvendedor",
        "tipo" => "select",
        "sql" => "select idvendedor, nome from vendedores where ativo = 'S' order by nome", // SQL que alimenta o select
        "sql_valor" => "idvendedor", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from vendedores where idvendedor = %",
        "valor" => "idvendedor",
        "sql_filtro_label" => "nome",
      ),
      array(
        "id" => "form_idconta_corrente",
        "nome" => "q[1|c.idconta_corrente]",
        "nomeidioma" => "form_idconta_corrente",
        "tipo" => "select",
        "sql" => "select cc.idconta_corrente, cc.nome from contas_correntes cc, bancos b  where cc.idbanco = b.idbanco and cc.ativo = 'S' order by cc.nome", // SQL que alimenta o select
        "sql_valor" => "idconta_corrente", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "sql_filtro" => "select * from contas_correntes where idconta_corrente = %",
        "valor" => "idconta_corrente",
        "sql_filtro_label" => "nome",
      ),
      /*







      array(
        "id" => "form_tipo_conta",
        "nome" => "tipo",
        "nomeidioma" => "form_tipo_conta",
        "tipo" => "select",
        "array" => "tipo_contas", // Array que alimenta o select
        "class" => "span2",
        "valor" => "tipo_contas",
      ) ,
            array(
                "id" => "form_tipo_data_filtro",
                "nome" => "q[de_ate|tipo_data_filtro|con.data_vencimento]",
                "nomeidioma" => "form_tipo_data_filtro",
                "botao_hide" => true,
                "iddivs" => array(
                    "de",
                    "ate"
                ) ,
                "tipo" => "select",
                "iddiv" => "de",
                "iddiv2" => "ate",
                "iddiv_obr" => false,
                "iddiv2_obr" => false,
                "array" => "tipo_data_filtro", // Array que alimenta o select
                "class" => "span3",
                "valor" => "tipo_data_filtro",
                "validacao" => array(
                    "required" => "tipo_data_filtro_vazio"
                ) ,
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "tipo_data_filtro"
            ) ,
            array(
                "id" => "form_de",
                "nome" => "de",
                "nomeidioma" => "form_de",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,
            array(
                "id" => "form_ate",
                "nome" => "ate",
                "nomeidioma" => "form_ate",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,
            array(
                "id" => "form_tipo_data_matricula",
                "nome" => "q[de_ate|form_tipo_data_matricula|mat.data_cad]",
                "nomeidioma" => "form_tipo_data_matricula",
                "botao_hide" => true,
                "iddivs" => array(
                    "de_matricula",
                    "ate_matricula"
                ) ,
                "tipo" => "select",
                "iddiv" => "de_matricula",
                "iddiv2" => "ate_matricula",
                "iddiv_obr" => false,
                "iddiv2_obr" => false,
                "array" => "tipo_data_filtro", // Array que alimenta o select
                "class" => "span3",
                "valor" => "tipo_data_filtro",
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "tipo_data_filtro"
            ) ,
            array(
                "id" => "form_de_matricula",
                "nome" => "de_matricula",
                "nomeidioma" => "form_de",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,
            array(
                "id" => "form_ate_matricula",
                "nome" => "ate_matricula",
                "nomeidioma" => "form_ate",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,




            array(
                "id" => "form_tipo_data_registro",
                "nome" => "q[de_ate|form_tipo_data_matricula|mat.data_registro]",
                "nomeidioma" => "form_tipo_data_registro",
                "botao_hide" => true,
                "iddivs" => array(
                    "de_registro",
                    "ate_registro"
                ) ,
                "tipo" => "select",
                "iddiv" => "de_registro",
                "iddiv2" => "ate_registro",
                "iddiv_obr" => false,
                "iddiv2_obr" => false,
                "array" => "tipo_data_filtro", // Array que alimenta o select
                "class" => "span3",
                "valor" => "tipo_data_filtro",
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "tipo_data_filtro"
            ) ,
            array(
                "id" => "form_de_registro",
                "nome" => "de_registro",
                "nomeidioma" => "form_de",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,
            array(
                "id" => "form_ate_registro",
                "nome" => "ate_registro",
                "nomeidioma" => "form_ate",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,




            array(
                "id" => "form_tipo_data_pagamento",
                "nome" => "q[de_ate|form_tipo_data_pagamento|con.data_pagamento]",
                "nomeidioma" => "form_tipo_data_pagamento",
                "botao_hide" => true,
                "iddivs" => array(
                    "de_pagamento",
                    "ate_pagamento"
                ) ,
                "tipo" => "select",
                "iddiv" => "de_pagamento",
                "iddiv2" => "ate_pagamento",
                "iddiv_obr" => false,
                "iddiv2_obr" => false,
                "array" => "tipo_data_filtro", // Array que alimenta o select
                "class" => "span3",
                "valor" => "tipo_data_filtro",
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "tipo_data_filtro"
            ) ,
            array(
                "id" => "form_de_pagamento",
                "nome" => "de_pagamento",
                "nomeidioma" => "form_de",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,
            array(
                "id" => "form_ate_pagamento",
                "nome" => "ate_pagamento",
                "nomeidioma" => "form_ate",
                "tipo" => "input",
                "class" => "span2",
                "datepicker" => true,
                "input_hidden" => true,
            ) ,





            array(
                "id" => "form_forma_pagamento",
                "nome" => "q[de_ate|form_forma_pagamento|con.forma_pagamento]",
                "nomeidioma" => "form_forma_pagamento",
                "tipo" => "select",
                "array" => "forma_pagamento", // Array que alimenta o select
                "class" => "span2",
                "valor" => "forma_pagamento",
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "forma_pagamento"
            ),


            array(
                "id" => "form_bolsa",
                "nome" => "q[1|mat.bolsa]",
                "nomeidioma" => "form_bolsa",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span1",
                "valor" => "bolsa",
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "bolsa"
            ) ,



            array(
                "id" => "idevento",
                "nome" => "q[1|con.idevento]",
                "nomeidioma" => "form_idevento",
                "tipo" => "select",
                "sql" => "SELECT idevento, nome FROM eventos_financeiros WHERE ativo='S' ORDER BY nome",
                "sql_valor" => "idevento",
                "sql_label" => "nome",
                "sql_filtro" => "select * from eventos_financeiros where idevento=%",
                "valor" => "idevento",
                "sql_filtro_label" => "nome",
                "class" => "span2",
            ) ,


            array(
                "id" => "idbandeira",
                "nome" => "q[1|con.idbandeira]",
                "nomeidioma" => "form_idbandeira",
                "tipo" => "select",
                "sql" => "SELECT idbandeira, nome FROM bandeiras_cartoes WHERE ativo='S' ORDER BY nome",
                "sql_valor" => "idbandeira",
                "sql_label" => "nome",
                "sql_filtro" => "select * from bandeiras_cartoes where idbandeira=%",
                "valor" => "idbandeira",
                "sql_filtro_label" => "nome",
                "class" => "span2",
            ) ,





            array(
                "id" => "idcliente",
                "nome" => "q[1|con.idcliente]",
                "nomeidioma" => "form_idcliente",
                "tipo" => "select",
                "sql" => "SELECT idcliente, nome FROM clientes WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idcliente", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idcliente",
                "sql_filtro" => "select * from clientes where idcliente=%",
                "sql_filtro_label" => "nome",
            ) ,


            array(
                "id" => "idfornecedor",
                "nome" => "q[1|con.idfornecedor]",
                "nomeidioma" => "form_idfornecedor",
                "tipo" => "select",
                "sql" => "SELECT idfornecedor, nome FROM fornecedores WHERE ativo='S' ORDER BY nome",
                "sql_valor" => "idfornecedor",
                "sql_label" => "nome",
                "valor" => "idfornecedor",
                "sql_filtro" => "select * from fornecedores where idfornecedor=%",
                "sql_filtro_label" => "nome",
            ) ,




            array(
                "id" => "idproduto",
                "nome" => "q[1|con.idproduto]",
                "nomeidioma" => "form_idproduto",
                "tipo" => "select",
                "sql" => "SELECT idproduto, nome FROM produtos WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idproduto", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "sql_filtro" => "select * from produtos where idproduto=%",
                "valor" => "idproduto",
                "sql_filtro_label" => "nome",
            ) ,

            array(
                "id" => "idcategoria",
                "nome" => "q[1|con.idcategoria]",
                "nomeidioma" => "form_idcategoria",
                "tipo" => "select",
                "sql" => "SELECT idcategoria, nome FROM categorias WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idcategoria", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "sql_filtro" => "select * from categorias where idcategoria=%",
                "valor" => "idcategoria",
                "sql_filtro_label" => "nome",
            ) ,
            array(
                "id" => "idoferta",
                "nome" => "q[1|mat.idoferta]",
                "nomeidioma" => "form_idoferta",
                "tipo" => "select",
                "sql" => "SELECT idoferta, nome FROM ofertas WHERE ativo='S' ORDER BY idoferta desc", // SQL que alimenta o select
                "sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "sql_filtro" => "select * from ofertas where idoferta=%",
                "valor" => "idoferta",
                "sql_filtro_label" => "nome",
                "class" => "span2",
            ) ,


           array(
                  "id" => "idturma",
                  "nome" => "q[1|mat.idturma]",
                  "nomeidioma" => "form_idturma",
                  "json" => true,
                  "json_idpai" => "idoferta",
                  "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_turmas/",
                  "json_input_pai_vazio" => "form_selecione_oferta",
                  "json_input_vazio" => "form_selecione_turma",
                  "json_campo_exibir" => "nome",
                  "tipo" => "select",
                  "valor" => "idturma",
                  "sql_filtro" => "select * from ofertas_turmas where idoferta=%",
                  "sql_filtro_label" => "nome"
                  ),


            array(
                "id" => "idcurso",
                "nome" => "q[1|mat.idcurso]",
                "nomeidioma" => "form_idcurso",
                "tipo" => "select",
                "sql" => "SELECT idcurso, nome FROM cursos WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "sql_filtro" => "select * from cursos where idcurso=%",
                "valor" => "idcurso",
                "sql_filtro_label" => "nome",
            ) ,
            array(
                "id" => "idescola",
                "nome" => "q[1|mat.idescola]",
                "nomeidioma" => "form_idescola",
                "tipo" => "select",
                "sql" => "SELECT p.idescola, CONCAT(i.nome, ' -> ', p.nome_fantasia) as nome FROM escolas p, sindicatos i WHERE p.idsindicato=i.idsindicato and p.ativo='S' ORDER BY i.nome, p.nome_fantasia", // SQL que alimenta o select
                "sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "sql_filtro" => "select * from escolas where idescola=%",
                "valor" => "idescola",
                "sql_filtro_label" => "nome",
            ) ,
            array(
                "id" => "idpessoa",
                "nome" => "q[1|mat.idpessoa]",
                "nomeidioma" => "form_idpessoa",
                "tipo" => "input",
                "class" => "span1"
            ) ,


            array(
                "id" => "nomepessoa",
                "nome" => "q[1|pes.nome]",
                "nomeidioma" => "form_nomepessoa",
                "tipo" => "input",
                "class" => "span5"
            ) ,

            array(
                "id" => "idmatricula",
                "nome" => "q[1|con.idmatricula]",
                "nomeidioma" => "form_idmatricula",
                "tipo" => "input",
                "class" => "span1"
            ) ,
            array(
                "id" => "idsituacao",
                "nome" => "situacao",
                "nomeidioma" => "form_idsituacao",
                "tipo" => "checkbox",
                "sql" => "SELECT idsituacao, nome FROM contas_workflow WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idsituacao",
                "sql_filtro" => "select * from contas_workflow where idsituacao=%",
                "sql_filtro_label" => "nome"
            ) ,


            array(
                "id" => "form_situacao_registro",
                "nome" => "q[1|con.ativo_painel]",
                "nomeidioma" => "form_situacao_registro",
                "tipo" => "select",
                "array" => "ativo", // Array que alimenta o select
                "class" => "span1",
                "valor" => "ativo",
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "ativo_painel"
            ) ,

            array(
                "id" => "form_tipo_documento",
                "nome" => "q[1|con.tipo_documento]",
                "nomeidioma" => "form_tipo_documento",
                "tipo" => "select",
                "array" => "contas_tipo_documento", // Array que alimenta o select
                "class" => "span2",
                "valor" => "tipo_documento",
                "banco" => true,
                "banco_string" => true,
                "sql_filtro" => "array",
                "sql_filtro_label" => "tipo_documento"
            ) ,*/

        )
    )
);

$colunas = array(
  1 => "mantenedora",
  2 => "sindicato",
  45 => "escola",  
  47 => "razao_social",
  48 => "cnpj_cfc",
  3 => "idconta",
  4 => "codigo",
  5 => "nome",
  44 => "empresa",
  6 => "telefone",
  7 => "email",
  8 => "descricao",
  9 => "documento",
  10 => "cpf_cnpj",
  11 => "valor",
  12 => "valor_juros",
  13 => "valor_multa",
  14 => "valor_desconto",
  15 => "valor_liquido",
  16 => "parcela",
  46 => "valor_contrato",
  17 => "data_vencimento",
  18 => "data_pagamento",
  41 => "numero_documento",
  19 => "adimplente",
  20 => "situacao",
  21 => "forma_pagamento",
  22 => "bandeira_cartao",
  23 => "produto",
  24 => "curso",
  42 => 'vendedor',
  25 => "centro_custo",
  26 => "categoria",
  27 => "subcategoria",
  28 => "banco",
  29 => "agencia",
  30 => "conta_corrente",
  31 => "numero_cheque",
  32 => "emitente_cheque",
  33 => "data1_cheque_alinea",
  34 => "devolvido_motivo_1",
  35 => "data2_cheque_alinea",
  36 => "devolvido_motivo_2",
  37 => "data3_cheque_alinea",
  38 => "devolvido_motivo_3",
  39 => "motivo_cancelamento",
  40 => "arquivos_conta",
  43 => "classificacao_despesa",
);

$colunasPadrao = array(2,3,4,5,8,9,11,12,13,14,15,16,17,18,20,21,22,25,27,26,45,46,47);