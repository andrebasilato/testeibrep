<?php

// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id"            => "idfechamento",
        "variavel_lang" => "tabela_idfechamento",
        "tipo"          => "php",
        "coluna_sql"    => "idfechamento",
        "valor"         => '$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
					if($diferenca > 24) {
						return "<span title=\"$diferenca\">".$linha["idfechamento"]."</span>";
					} else {
						return "<span title=\"$diferenca\">".$linha["idfechamento"]."</span> <i class=\"novo\"></i>";
					}',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "data_cad",
        "variavel_lang" => "tabela_datacad",
        "coluna_sql"    => "fc.data_cad",
        "tipo"          => "php",
        "valor"         => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho"       => "140",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 3
    ),

    array(
        "id"            => "responsavel",
        "variavel_lang" => "tabela_responsavel",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "ua.razao_social",
        "valor"         => "responsavel",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "credito_valor",
        "variavel_lang" => "tabela_credito_valor",
        "tipo"          => "php",
        "coluna_sql"    => "fc.credito_valor",
        "valor"         => 'return "<span style=\"color:gray; float:left\">R$</span><span style=\"color:green; float:right\"><strong>".number_format($linha["credito_valor"],2,",",".")."</strong></span>";',
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 100
    ),

    array("id"            => "credito_quantidade",
          "variavel_lang" => "tabela_credito_quantidade",
          "tipo"          => "banco",
          "coluna_sql"    => "fc.credito_quantidade",
          "valor"         => "credito_quantidade",
          "busca"         => true,
          "busca_class"   => "inputPreenchimentoCompleto",
          "busca_metodo"  => 1,
          "tamanho"       => 60),

    array(
        "id"            => "debito_valor",
        "variavel_lang" => "tabela_debito_valor",
        "tipo"          => "php",
        "coluna_sql"    => "fc.debito_valor",
        "valor"         => 'return "<span style=\"color:gray; float:left\">R$</span><span style=\"color:red; float:right\"><strong>".number_format($linha["debito_valor"],2,",",".")."</strong></span>";',
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 100
    ),

    array("id"            => "debito_quantidade",
          "variavel_lang" => "tabela_debito_quantidade",
          "tipo"          => "banco",
          "coluna_sql"    => "fc.debito_quantidade",
          "valor"         => "debito_quantidade",
          "busca"         => true,
          "busca_class"   => "inputPreenchimentoCompleto",
          "busca_metodo"  => 1,
          "tamanho"       => 60),

    array(
        "id"            => "saldo",
        "variavel_lang" => "tabela_saldo",
        "tipo"          => "php",
        "coluna_sql"    => "fc.credito_valor",
        "valor"         => '$saldo = $linha["credito_valor"]-$linha["debito_valor"];
											if($saldo >= 0) {
												$color = "green";											
											} else {
												$color = "red";		
											}
											return "<span style=\"color:gray; float:left\">R$</span> <span style=\"color:$color; float:right\"><strong>".number_format(abs($saldo),2,",",".")."</strong></span>";
											',
        "tamanho"       => 100
    ),

    array("id"            => "opcoes",
          "variavel_lang" => "tabela_opcoes",
          "tipo"          => "php",
          "valor"         => 'return "<a href=\"/".$this->url["0"]."/financeiro/fechamento_caixa/".$linha["idfechamento"]."/xml\" target=\"_blank\" class=\"btn dropdown-toggle btn-mini\">XML</a>"',
          "busca_botao"   => true,
          "tamanho"       => "160")

);

?>