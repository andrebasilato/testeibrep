<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id" => "idusuario",
        "variavel_lang" => "tabela_idusuario",
        "tipo" => "php",
        "coluna_sql" => "u.idusuario",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idusuario"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idusuario"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
    array(
        "id" => "nome", // Id do atributo
        "variavel_lang" => "tabela_nome", // Referencia a variavel de idioma
        "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "coluna_sql" => "u.nome", // Nome da coluna no banco de dados
        "valor" => "nome", // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ), //Se for 1 a busca é feita usando o '=', se for 2 a busca é feita usando o 'LIKE'
    array(
        "id" => "email",
        "variavel_lang" => "tabela_email",
        "tipo" => "php",
        "coluna_sql" => "u.email",
        "valor" => ' return "<a data-original-title=\"".$linha["email"]."\" data-placement=\"top\" rel=\"tooltip\" href=\"mailto:".$linha["email"]."\">".$linha["email"]."</a>";',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2,
        "tamanho" => "120",
        "overflow" => true
    ),
    array(
        "id" => "documento",
        "variavel_lang" => "tabela_cpf",
        "tipo" => "php",
        "coluna_sql" => "u.documento",
        "valor" => ' return "<span data-original-title=\"".$linha["documento"]."\" data-placement=\"top\" rel=\"tooltip\">".$linha["documento"]."</span>";',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2,
        "tamanho" => "80",
        "overflow" => true
    ),
    array(
        "id" => "perfil",
        "variavel_lang" => "tabela_perfil",
        "tipo" => "banco",
        "coluna_sql" => "p.idperfil",
        "valor" => "perfil",
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_sql" => "SELECT idperfil, nome FROM usuarios_adm_perfis where ativo = 'S'", // SQL que alimenta o select
        "busca_sql_valor" => "idperfil", // Coluna da tabela que será usado como o valor do options
        "busca_sql_label" => "nome",
        "busca_metodo" => 1,
        "tamanho" => "100",
        "overflow" => true
    ), // Coluna da tabela que será usado como o label do options),
    array(
        "id" => "ativo_login", // Id do atributo
        "variavel_lang" => "tabela_situacao", // Referencia a variavel de idioma
        "tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "coluna_sql" => "u.ativo_login",
        "valor" => 'if($linha["ativo_login"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "ativo",
        "busca_metodo" => 1
    ), // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
    array(
        "id" => "ultimo_acesso",
        "variavel_lang" => "tabela_ultimoacesso",
        "tipo" => "php",
        "coluna_sql" => "u.ultimo_acesso",
        "valor" => 'return formataData($linha["ultimo_acesso"],"br",1);',
        "tamanho" => "140"
    ),
    array(
        "id" => "validade", // Id do atributo
        "variavel_lang" => "tabela_validade", // Referencia a variavel de idioma
        "tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "coluna_sql" => "u.validade",
        "valor" => 'if($linha["validade"]) {
				  $dias = dataDiferenca(date("Y-m-d"), $linha["validade"], "D");
				  if($dias > 0) {
					if($dias > 7) {
					  return $dias." Dias"; 
					} else {
					  return "<strong style=\"color:#FF0000\">".$dias." Dias</strong>";  
					}
				  } else {
					return "<strong style=\"color:#FF0000\">Expirado</strong>"; 
				  }
				} else {
				  return " - - ";
				}',
        "busca" => false
    ), // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idusuario"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "60"
    )
);
?>