<?php
$tipo_mural["pt_br"] = array(
    "UA" => "Usuário Adm.",
    "PO" => "Professor",
    "VE" => "Atendente",
    "PL" => "CFC",
);

$config["listagem"] = array(
	array(
		"id" => "idmural",
	  	"variavel_lang" => "tabela_idmural",
		"tipo" => "php",
		"coluna_sql" => "idmural",
		"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idmural"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idmural"]."</span> <i class=\"novo\"></i>";
			}
			',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 80
	),
	array(
		"id" => "titulo",
		"variavel_lang" => "tabela_titulo",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "titulo",
		"valor" => "titulo",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "total_enviados",
		"variavel_lang" => "total_enviados",
		"tipo" => "banco",
		"coluna_sql" => "total_enviados",
		"valor" => "total_enviados",
		"tamanho" => 60,
		"nao_ordenar" => true
	),
	array(
		"id" => "total_lidos",
		"variavel_lang" => "total_lidos",
		"tipo" => "banco",
		"coluna_sql" => "total_lidos",
		"valor" => "total_lidos",
		"tamanho" => 60,
		"nao_ordenar" => true
	),
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_opcoes",
		"tipo" => "php",
		"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmural"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
		"busca_botao" => true,
		"tamanho" => "80"
	)
);

$config["listagem_fila"] = array(
	array(
		"id" => "idfila",
	  	"variavel_lang" => "tabela_idfila",
		"tipo" => "banco",
		"coluna_sql" => "idfila",
		"valor" => "idfila",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 60
	),
	array(
		"id" => "nome",
		"variavel_lang" => "tabela_nome",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "nome",
		"valor" => "nome",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "email",
		"variavel_lang" => "tabela_email",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "email",
		"valor" => "email",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "tipo",
		"variavel_lang" => "tabela_tipo",
		"coluna_sql" => "tipo",
		"tipo" => "php",
		"valor" => 'if($linha["tipo"] == "UA") {
					  return "<span data-original-title=\"".$idioma["tipo_usuario_adm"]."\" class=\"label\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_usuario_adm"]."</span>";
			       } else if($linha["tipo"] == "PO") {
					  return "<span data-original-title=\"".$idioma["tipo_professor"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_professor"]."</span>";
				   } else if($linha["tipo"] == "VE") {
					  return "<span data-original-title=\"".$idioma["tipo_vendedor"]."\" class=\"label label-warning\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_vendedor"]."</span>";
				   } else if($linha["tipo"] == "PE") {
					  return "<span data-original-title=\"".$idioma["tipo_pessoa"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_pessoa"]."</span>";
				   } else if($linha["tipo"] == "AT") {
					  return "<span data-original-title=\"".$idioma["tipo_atendimento"]."\" class=\"label label-info\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_atendimento"]."</span>";
				   } else if($linha["tipo"] == "MA") {
					  return "<span data-original-title=\"".$idioma["tipo_matricula"]."\" class=\"label label-inverse\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_matricula"]."</span>";
				   } else if($linha["tipo"] == "IN") {
					  return "<span data-original-title=\"".$idioma["tipo_sindicato"]."\" class=\"label label-inverse\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_sindicato"]."</span>";
				   } else if($linha["tipo"] == "PL") {
					  return "<span data-original-title=\"".$idioma["tipo_escola"]."\" class=\"label label-inverse\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_escola"]."</span>";
				   }',
		"tamanho" => "100",
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_array" => "tipo_mural",
		"busca_metodo" => 1
	),
	array(
		"id" => "data_cad",
		"variavel_lang" => "tabela_data_cad",
		"coluna_sql" => "mf.data_cad",
		"tipo" => "php",
		"valor" => 'return formataData($linha["data_cad"],"br",1);',
		"tamanho" => "140",
		"nao_ordenar" => true
	),
	array(
		"id" => "data_enviado",
		"variavel_lang" => "tabela_data_enviado",
		"coluna_sql" => "data_enviado",
		"tipo" => "php",
		"valor" => 'return formataData($linha["data_enviado"],"br",1);',
		"tamanho" => "140",
		"nao_ordenar" => true
	),
	array(
		"id" => "data_lido",
		"variavel_lang" => "tabela_data_lido",
		"coluna_sql" => "data_lido",
		"tipo" => "php",
		"valor" => 'return formataData($linha["data_lido"],"br",1);',
		"tamanho" => "140",
		"nao_ordenar" => true
	),
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_opcoes",
		"tipo" => "php",
		"valor" => 'return "<a href=\"javascript:void(0);\" class=\"btn btn-mini\" data-original-title=\"".$idioma["btn_remover"]."\" data-placement=\"left\" rel=\"tooltip\" onclick=\"remover(".$linha["idfila"].")\"><i class=\"icon-remove\"></i></a>"',
		"busca_botao" => true,
		"tamanho" => "80"
	)
);

$config["listagem_add_fila"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."\"  />"',
	),
	array(
		"id" => "nome",
		"variavel_lang" => "tabela_nome",
		//"tipo" => "banco",
		"tipo" => "php",
		"coluna_sql" => "nome",
		"valor" => 'if($linha["ativo_login"] == "N" || $linha["ativo_painel"] == "N") {
					  if ($linha["venda_bloqueada"] == "S")
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO e BLOQUEADO) </strong> ";
					  else
						return $linha["nome"]." <strong style=\"color:red;\"> (INATIVO) </strong> ";
			       } else if($linha["venda_bloqueada"] == "S") {
					  return $linha["nome"]." <strong style=\"color:red;\"> (BLOQUEADO) </strong> ";
			       } else {
					  return $linha["nome"];
				   }'
	),
	array(
		"id" => "email",
		"variavel_lang" => "tabela_email",
		"tipo" => "banco",
		"coluna_sql" => "email",
		"valor" => "email"
	)
);
?>