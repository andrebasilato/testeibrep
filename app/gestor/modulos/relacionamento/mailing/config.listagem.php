<?php
  // Array de configuração para a listagem
  $config["listagem"] = array(
					  array("id" => "idemail",
							"variavel_lang" => "tabela_idemail",
							"tipo" => "php",
							"coluna_sql" => "idemail",
							"valor" => '
								$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
								if($diferenca > 24) {
									return "<span title=\"$diferenca\">".$linha["idemail"]."</span>";
								} else {
									return "<span title=\"$diferenca\">".$linha["idemail"]."</span> <i class=\"novo\"></i>";
								}
								',
							"busca" => true,
							"busca_class" => "inputPreenchimentoCompleto",
							"busca_metodo" => 1,
							"tamanho" => 80),

					  array("id" => "nome",
							"variavel_lang" => "tabela_nome",
							"tipo" => "banco",
							"evento" => "maxlength='100'",
							"coluna_sql" => "nome",
							"valor" => "nome",
							"busca" => true,
							"busca_class" => "inputPreenchimentoCompleto",
							"busca_metodo" => 2),


					  array("id" => "situacao",
							"variavel_lang" => "tabela_ativo_painel",
							"tipo" => "php",
							"coluna_sql" => "situacao",
							"valor" => 'return "<span class=\"label\" style=\"background-color:".$linha["situacao_legenda_cor"]."\">".$linha["situacao_legenda"]."</span>";',
							"busca" => true,
							"busca_tipo" => "select",
							"busca_class" => "inputPreenchimentoCompleto",
							"busca_array" => "situacao_mailing",
							"busca_metodo" => 1),

					  array("id" => "enviados",
							"variavel_lang" => "tabela_enviados",
							"coluna_sql" => "enviados",
							"tipo" => "php",
							"valor" => 'return $linha["enviados"];',
							"tamanho" => "80",
							"busca" => false,
							"nao_ordenar" => true
							),

					  array("id" => "total_reenvio",
							"variavel_lang" => "tabela_total_reenvio",
							"coluna_sql" => "total_reenvio",
							"tipo" => "php",
							"valor" => 'return $linha["total_reenvio"];',
							"tamanho" => "80",
							"busca" => false,
							"nao_ordenar" => true
							),

					  array("id" => "data_cad",
							"variavel_lang" => "tabela_datacad",
							"coluna_sql" => "data_cad",
							"tipo" => "php",
							"valor" => 'return formataData($linha["data_cad"],"br",1);',
							"tamanho" => "140"),

					  array("id" => "opcoes",
							"variavel_lang" => "tabela_opcoes",
							"tipo" => "php",
							"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idemail"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
							"busca_botao" => true,
							"tamanho" => "80")

);

$config["listagem_fila"] = array(
	array(
		"id" => "idemail_pessoa",
	  	"variavel_lang" => "tabela_idfila",
		"tipo" => "banco",
		"coluna_sql" => "idemail_pessoa",
		"valor" => "idemail_pessoa",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 60
	),
	array(
		"id" => "nome",
		"variavel_lang" => "tabela_nome",
		"coluna_sql" => "nome",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"valor" => 'nome',
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
		"valor" => 'email',
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
			       } else if($linha["tipo"] == "MA") {
					  return "<span data-original-title=\"".$idioma["tipo_matricula"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_matricula"]."</span>";
				   } else if($linha["tipo"] == "PR") {
					  return "<span data-original-title=\"".$idioma["tipo_professor"]."\" class=\"label label-warning\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_professor"]."</span>";
				   } else if($linha["tipo"] == "PE") {
					  return "<span data-original-title=\"".$idioma["tipo_pessoa"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_pessoa"]."</span>";
				   } else if($linha["tipo"] == "VE") {
					  return "<span data-original-title=\"".$idioma["tipo_vendedor"]."\" class=\"label label-info\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_vendedor"]."</span>";
				   } else if($linha["tipo"] == "VV") {
					  return "<span data-original-title=\"".$idioma["tipo_visita_vendedor"]."\" class=\"label label-primary\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_visita_vendedor"]."</span>";
				   }  else if($linha["tipo"] == "ES") {
					  return "<span data-original-title=\"".$idioma["tipo_escola"]."\" class=\"label label-orange\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_escola"]."</span>";
				   }  else if($linha["tipo"] == "SI") {
					  return "<span data-original-title=\"".$idioma["tipo_sindicato"]."\" class=\"label label-dark\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tipo_sindicato"]."</span>";
				   } ',
		"tamanho" => "100",
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_array" => "tipo_mailing",
		"busca_metodo" => 2
	),
	array(
		"id" => "informacao",
		"variavel_lang" => "tabela_informacao",
		"coluna_sql" => "informacao",
		"tipo" => "php",
		"valor" => 'if($linha["tipo"] == "UA") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/configuracoes/usuariosadm?q[1|u.idusuario]=".$linha["idusuario_adm"]."\"><span data-original-title=\"".$idioma["informacao_usuario_adm"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idusuario_adm"]."</span></a>";
			       } else if($linha["tipo"] == "MA") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/academico/matriculas?q[1|m.idmatricula]=".$linha["idmatricula"]."\"><span data-original-title=\"".$idioma["informacao_matricula"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idmatricula"]."</span></a>";
				   } else if($linha["tipo"] == "PR") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/cadastros/professores?q[1|p.idprofessor]=".$linha["idprofessor"]."\"><span data-original-title=\"".$idioma["informacao_professor"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idprofessor"]."</span></a>";
				   } else if($linha["tipo"] == "PE") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/cadastros/pessoas?q[1|p.idpessoa]=".$linha["idpessoa"]."\"><span data-original-title=\"".$idioma["informacao_pessoa"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idpessoa"]."</span></a>";
				   } else if($linha["tipo"] == "VV") {
					  return "<a onclick=\"return confirma_pagina_informacao()\" href=\"/gestor/comercial/visitas?q[1|idvisita]=".$linha["idvisita"]."\"><span data-original-title=\"".$idioma["informacao_visita"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["idvisita"]."</span></a>";
				   } ',
		"tamanho" => "100"
	),
	array(
		"id" => "data_cad",
		"variavel_lang" => "tabela_data_cad",
		"coluna_sql" => "data_cad",
		"tipo" => "php",
		"valor" => 'return formataData($linha["data_cad"],"br",1);',
		"tamanho" => "80"
	),
	array(
		"id" => "data_enviado",
		"variavel_lang" => "tabela_data_enviado",
		"coluna_sql" => "mf.data_envio",
		"tipo" => "php",
		"valor" => 'return formataData($linha["data_envio"],"br",1);',
		"tamanho" => "80"
	)

);

if($GLOBALS['config']['integrado_com_sms']){

	$config["listagem_fila"] [] = array(
		"id" => "paraemail",
		"variavel_lang" => "tabela_paraemail",
		"coluna_sql" => "mf.paraemail",
		"tipo" => "php",
		"valor" => '
		if($linha["paraemail"] == "S"){
			return "<span class=\"label label-success\">S</span>";
		}else{
			return "<span class=\"label label-important\">N</span>";
		}
		',
		"tamanho" => "80"
	);
	$config["listagem_fila"] [] = array(
		"id" => "parasms",
		"variavel_lang" => "tabela_parasms",
		"coluna_sql" => "mf.parasms",
		"tipo" => "php",
		"valor" => '
		if($linha["parasms"] == "S"){
			return "<span class=\"label label-success\">S</span>";
		}else{
			return "<span class=\"label label-important\">N</span>";
		}
		',
		"tamanho" => "80"
	);

}

$config["listagem_fila"] [] = array(
		"id" => "idfiltro",
	  	"variavel_lang" => "tabela_idfiltro",
		"tipo" => "php",
		"valor" => 'return "<a href=\"#busca".$linha["idfiltro"]."\">".$linha["idfiltro"]."</a>";',
		"tamanho" => 20
	);
$config["listagem_fila"] [] = array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_opcoes",
		"tipo" => "php",
		"valor" => 'return "<a href=\"javascript:void(0);\" class=\"btn btn-mini\" data-original-title=\"".$idioma["btn_remover"]."\" data-placement=\"left\" rel=\"tooltip\" onclick=\"remover(".$linha["idemail_pessoa"].")\"><i class=\"icon-remove\"></i></a>";',
		"busca_botao" => true,
		"tamanho" => "110"
	) ;

$config["listagem_add_fila"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"',
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
	),


);

if($GLOBALS['config']['integrado_com_sms']){
	$config["listagem_add_fila"][] = array(
		"id" => "enviar_sms",
		"variavel_lang" => "colunaenviar_sms",
		"tipo" => "php",
		"valor" => '
		if(!$linha["celular"]){
			return "<input disabled size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  /> <strong style=\"color:red;font-size:8px;\"> (NÃO POSSUI CELULAR CADASTRADO) </strong> "  ;
		}else{
			return "<input size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />";
		}',
	);
}

$config["listagem_add_fila_pessoa"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"',
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
	),

);
if($GLOBALS['config']['integrado_com_sms']){
	$config["listagem_add_fila_pessoa"][] = array(
		"id" => "enviar_sms",
		"variavel_lang" => "colunaenviar_sms",
		"tipo" => "php",
		"valor" => '
		if(!$linha["celular"]){
			return "<input disabled size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  /> <strong style=\"color:red;font-size:8px;\"> (NÃO POSSUI CELULAR CADASTRADO) </strong> "  ;
		}else{
			return "<input size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />" ;
		}',
	);
}

$config["listagem_add_fila_vendedores"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"',
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
	),

);

$config["listagem_add_fila_visita_vendedores"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"',
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
	),

);

if($GLOBALS['config']['integrado_com_sms']){
	$config["listagem_add_fila_vendedores"][] = array(
		"id" => "enviar_sms",
		"variavel_lang" => "colunaenviar_sms",
		"tipo" => "php",
		"valor" => '
		if(!$linha["celular"]){
			return "<input disabled size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  /> <strong style=\"color:red;font-size:8px;\"> (NÃO POSSUI CELULAR CADASTRADO) </strong> "  ;
		}else{
			return "<input size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />" ;
		}',
	);
        $config["listagem_add_fila_visita_vendedores"][] = array(
		"id" => "enviar_sms",
		"variavel_lang" => "colunaenviar_sms",
		"tipo" => "php",
		"valor" => '
		if(!$linha["celular"]){
			return "<input disabled size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  /> <strong style=\"color:red;font-size:8px;\"> (NÃO POSSUI CELULAR CADASTRADO) </strong> "  ;
		}else{
			return "<input size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />" ;
		}',
	);
}

$config["listagem_add_fila_cfc"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"',
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
if($GLOBALS['config']['integrado_com_sms']){
	$config["listagem_add_fila_cfc"][] = array(
			"id" => "enviar_sms",
			"variavel_lang" => "colunaenviar_sms",
			"tipo" => "php",
			"valor" => '
			if(!$linha["celular"]){
				return "<input disabled size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  /> <strong style=\"color:red;font-size:8px;\"> (NÃO POSSUI CELULAR CADASTRADO) </strong> "  ;
			}else{
				return "<input size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"  ;
			}
			'
		) ;
}

$config["listagem_add_fila_sindicato"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"',
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
if($GLOBALS['config']['integrado_com_sms']){
	$config["listagem_add_fila_sindicato"][] = array(
			"id" => "enviar_sms",
			"variavel_lang" => "colunaenviar_sms",
			"tipo" => "php",
			"valor" => '
			if(!$linha["celular"]){
				return "<input disabled size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  /> <strong style=\"color:red;font-size:8px;\"> (NÃO POSSUI CELULAR CADASTRADO) </strong> "  ;
			}else{
				return "<input size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"  ;
			}
			'
		) ;
}

$config["listagem_add_fila_matricula"] = array(
	array(
		"id" => "opcoes",
		"variavel_lang" => "tabela_add",
		"tipo" => "php",
		"valor" => 'return "<input size=\"1\" type=\"checkbox\" name=\"".id."[".$linha["id"]."]\" id=\"".id."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"',
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
	),
	array(
		"id" => "matricula",
		"variavel_lang" => "tabela_matricula",
		"tipo" => "banco",
		"coluna_sql" => "idmatricula",
		"valor" => "idmatricula"
	),
);
if($GLOBALS['config']['integrado_com_sms']){
	$config["listagem_add_fila_matricula"][] = array(
			"id" => "enviar_sms",
			"variavel_lang" => "colunaenviar_sms",
			"tipo" => "php",
			"valor" => '
			if(!$linha["celular"]){
				return "<input disabled size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  /> <strong style=\"color:red;font-size:8px;\"> (NÃO POSSUI CELULAR CADASTRADO) </strong> "  ;
			}else{
				return "<input size=\"2\" type=\"checkbox\" name=\"".idsms."[".$linha["id"]."]\" id=\"".idsms."[".$linha["id"]."]\" value=\"".$linha["nome"]."|".$linha["email"]."|".$linha["celular"]."\"  />"  ;
			}
			'
		) ;
}

