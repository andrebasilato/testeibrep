<?php
// Array de configuração para a formulario			
$config["formulario"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto",
        "legendaidioma" => "legendadadosdados",
        "campos"        => array(
            array(
                "id"           => "form_nome",
                "nome"         => "nome",
                "nomeidioma"   => "form_nome",
                "tipo"         => "input",
                "valor"        => "nome",
                "validacao"    => array("required" => "nome_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            /*array(
                "id"           => "subcategoria_obrigatoria",
                "nome"         => "subcategoria_obrigatoria",
                "nomeidioma"   => "form_subcategoria_obrigatoria",
                "tipo"         => "select",
                "array"        => "sim_nao",
                "class"        => "span2",
                "valor"        => "subcategoria_obrigatoria",
                "validacao"    => array("required" => "subcategoria_obrigatoria_vazio"),
                "ajudaidioma"  => "form_subcategoria_obrigatoria_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),*/
            array(
                "id"           => "form_ativo_painel",
                "nome"         => "ativo_painel",
                "nomeidioma"   => "form_ativo_painel",
                "tipo"         => "select",
                "array"        => "ativo",
                "class"        => "span2",
                "valor"        => "ativo_painel",
                "validacao"    => array("required" => "ativo_vazio"),
                "ajudaidioma"  => "form_ativo_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),
        )
    )
);

// Array de configuração para a formulario
$config["formulario_subcategoria"] = array(
    array("fieldsetid"    => "dadosdoobjeto",
          "legendaidioma" => "legendadadosdados",
          "campos"        => array(
              array(
                  "id"           => "form_nome",
                  "nome"         => "nome",
                  "nomeidioma"   => "form_nome",
                  "tipo"         => "input",
                  "valor"        => "nome",
                  "validacao"    => array("required" => "nome_vazio"),
                  "class"        => "span6",
                  "banco"        => true,
                  "banco_string" => true,
              ),
              array(
                  "id"         => "idcategoria",
                  "nome"       => "idcategoria",
                  "nomeidioma" => "form_categoria",
                  "tipo"       => "select",
                  "sql"        => "SELECT idcategoria, nome FROM categorias WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                  "sql_valor"  => "idcategoria",
                  "sql_label"  => "nome",
                  "valor"      => "idcategoria",
                  "validacao"  => array("required" => "categoria_vazio"),
                  "banco"      => true
              ),
              array(
                  "id"           => "form_ativo_painel",
                  "nome"         => "ativo_painel",
                  "nomeidioma"   => "form_ativo_painel",
                  "tipo"         => "select",
                  "array"        => "ativo",
                  "class"        => "span2",
                  "valor"        => "ativo_painel",
                  "validacao"    => array("required" => "ativo_vazio"),
                  "ajudaidioma"  => "form_ativo_ajuda",
                  "banco"        => true,
                  "banco_string" => true
              ),
          )
    )
);