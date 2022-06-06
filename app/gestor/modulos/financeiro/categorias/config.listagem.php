<?php

// Array de configuração para a listagem
$config["listagem"] = array(
    array("id"            => "idsubcategoria",
          "variavel_lang" => "tabela_idcategoria",
          "tipo"          => "php",
          "coluna_sql"    => "idsubcategoria",
          "valor"         => '
            $diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
            if($diferenca > 24) {
                return "<span title=\"$diferenca\">".$linha["idsubcategoria"]."</span>";
            } else {
                return "<span title=\"$diferenca\">".$linha["idsubcategoria"]."</span> <i class=\"novo\"></i>";
            }
        ',
          "busca"         => true,
          "busca_class"   => "inputPreenchimentoCompleto",
          "busca_metodo"  => 1,
          "tamanho"       => 80),

    array("id"            => "categoria",
          "variavel_lang" => "tabela_categoria",
          "tipo"          => "banco",
          "evento"        => "maxlength='100'",
          "coluna_sql"    => "categoria",
          "valor"         => "categoria",
          "busca"         => true,
          "busca_class"   => "inputPreenchimentoCompleto",
          "busca_metodo"  => 2),

    array("id"            => "subcategoria",
          "variavel_lang" => "tabela_subcategoria",
          "tipo"          => "banco",
          "evento"        => "maxlength='100'",
          "coluna_sql"    => "subcategoria",
          "valor"         => "subcategoria",
          "busca"         => true,
          "busca_class"   => "inputPreenchimentoCompleto",
          "busca_metodo"  => 2),

    array("id"            => "ativo_painel",
          "variavel_lang" => "tabela_ativo_painel",
          "tipo"          => "php",
          "coluna_sql"    => "ativo_painel",
          "valor"         => 'if($linha["ativo_painel"] == "S") {
                                                return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
                                              } else {
                                                return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
                                              }',
          "busca"         => true,
          "busca_tipo"    => "select",
          "busca_class"   => "inputPreenchimentoCompleto",
          "busca_array"   => "ativo",
          "busca_metodo"  => 1,
          "tamanho"       => 60),

    array("id"            => "data_cad",
          "variavel_lang" => "tabela_datacad",
          "coluna_sql"    => "data_cad",
          "tipo"          => "php",
          "valor"         => 'return formataData($linha["data_cad"],"br",1);',
          "tamanho"       => "140"),

    array("id"            => "opcoes",
          "variavel_lang" => "tabela_opcoes",
          "tipo"          => "php",
          "valor"         => 'if($linha["tipo"] == "C") {
                                                  return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsubcategoria"]."/opcoescategoria\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
                                              } else {
                                                  return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsubcategoria"]."/opcoessubcategoria\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
                                              }',

          "busca_botao"   => true,
          "tamanho"       => "80")

);