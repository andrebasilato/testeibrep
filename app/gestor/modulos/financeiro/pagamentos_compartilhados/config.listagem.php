<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id"            => "idpagamento",
        "variavel_lang" => "tabela_idpagamento",
        "tipo"          => "php",
        "coluna_sql"    => "pc.idpagamento",
        "valor"         => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idpagamento"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idpagamento"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "nome",
        "variavel_lang" => "tabela_nome",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "pc.nome",
        "valor"         => "nome",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
        "tamanho"       => '700'
    ),
    array(
        "id"            => "valor",
        "variavel_lang" => "tabela_valor",
        "tipo"          => "php",
        "coluna_sql"    => "pc.valor",
        "valor"         => '
				$rs = "<span style=\"color:gray; float:left\">R$</span>";
				if($linha["valor"] < 0)
					return "$rs <span style=\"color:red; float:right\"><strong>".number_format(($linha["valor"]*-1),2,",",".")."</strong></span>";
				else
					return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["valor"],2,",",".")."</strong></span>";
				',
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 150
    ),
    array(
        "id"            => "data_cad",
        "variavel_lang" => "tabela_datacad",
        "tipo"          => "php",
        "coluna_sql"    => "data_cad",
        "valor"         => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho"       => "140"
    ),
    array(
        "id"            => "ativo_painel",
        "variavel_lang" => "tabela_ativo_painel",
        "tipo"          => "php",
        "coluna_sql"    => "ativo_painel",
        "valor"         => 'if($linha["ativo_painel"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">Ativo</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">Inativo</span>";
				}',
        "busca"         => true,
        "busca_tipo"    => "select",
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_array"   => "ativo",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'if($linha["ativo_painel"] == "S") {
				  return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_contas_tooltip"]."\" href=\"/".$this->url["0"]."/relatorios/contas_relatorio/html?q[1|c.idpagamento_compartilhado]=".$linha["idpagamento"]."\" data-placement=\"left\" rel=\"tooltip \" target=\"_blank\">".$idioma["tabela_contas"]."</a>
				  &nbsp; <a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idpagamento"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
				} else {
				  return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_contas_tooltip"]."\" href=\"/".$this->url["0"]."/relatorios/contas_relatorio/html?q[1|c.idpagamento_compartilhado]=".$linha["idpagamento"]."\" data-placement=\"left\" rel=\"tooltip \" target=\"_blank\">".$idioma["tabela_contas"]."</a>";
				}',
        "busca_botao"   => true,
        "tamanho"       => "160"
    )
);
?>